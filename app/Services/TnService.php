<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TnFiltersEnum;
use App\Models\Isin;
use App\Models\Ticker;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonMachine\Exception\InvalidArgumentException;
use JsonMachine\Items;
use Storage;
use Symfony\Component\DomCrawler\Crawler;
use ZipArchive;

class TnService
{
    /** @const string instrument code type title * */
    public const INSTRUMENT_CODE_TYPE_TITLE = 'instr_type_c';

    /** @const string instrument code type operator * */
    public const INSTRUMENT_CODE_TYPE_OPERATOR = 'in';

    /** @const string instrument code type value shares * */
    public const INSTRUMENT_CODE_TYPE_VALUE_SHARES = 1;

    /** @const string instrument code type value obligations * */
    public const INSTRUMENT_CODE_TYPE_VALUE_OBLIGATIONS = 2;

    /** @const int take amount * */
    public const TAKE = 1000;

    /** @const string path of ajax security endpoint * */
    public const SECURITIES_ENDPOINT = '/securities/ajax-get-all-securities/';

    /** @const string endpoint of full securities json archives data  * */
    public const FULL_DATA_ENDPOINT = '/refbooks/';

    /** @const string format of archive * */
    public const ARCHIVE_FOLDER = 'zip';

    /** @const string folder path * */
    public const JSON_FOLDER_PATH = 'app/json';

    /** @const string extension pattern * */
    public const JSON_EXTENSION_PATTERN = "'/*.json'";

    /** @const string attribute name * */
    public const ATTRIBUTE_HREF = 'href';

    /** @const string app folder name */
    public const APP_FOLDER = 'app/';

    /** @const string link html tag */
    public const HTML_LINK_TAG = 'a';

    /** @const int request timeout */
    public const REQ_TIMEOUT = 60000;

    /** @const int chunk size */
    public const CHUNK_SIZE = 1000;

    /** @const int chunk size for slicing by bytes */
    public const CHUNK_SIZE_BYTE = 4096;

    /** @const string last actual data date */
    public const LAST_ACTUAL_DATE = '2024-02-08/';

    /** @const string json folder name */
    public const JSON_FOLDER_NAME = 'json';

    /** @const string app folder name */
    public const APP_FOLDER_NAME = 'app/';

    /** @const string date format*/
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    /** @const string file opening mode */
    public const OPENING_MODE = 'wb';

    /**
     * Get tickers from the TN API
     *
     * @return Response
     */
    public function getTickers(): Response
    {
        return Http::get(config('services.tn.url' . self::SECURITIES_ENDPOINT), [
            TnFiltersEnum::TAKE->value => self::TAKE,
            TnFiltersEnum::FIELD->value => self::INSTRUMENT_CODE_TYPE_TITLE,
            TnFiltersEnum::OPERATOR->value => self::INSTRUMENT_CODE_TYPE_OPERATOR,
            TnFiltersEnum::VALUE->value => self::INSTRUMENT_CODE_TYPE_VALUE_SHARES,
        ]);
    }

    /**
     * Get ISINs from the TN API
     *
     * @return Response
     */
    public function getIsins(): Response
    {
        return Http::get(config('services.tn.url' . self::SECURITIES_ENDPOINT), [
            TnFiltersEnum::TAKE->value => self::TAKE,
            TnFiltersEnum::FIELD->value => self::INSTRUMENT_CODE_TYPE_TITLE,
            TnFiltersEnum::OPERATOR->value => self::INSTRUMENT_CODE_TYPE_OPERATOR,
            TnFiltersEnum::VALUE->value => self::INSTRUMENT_CODE_TYPE_VALUE_OBLIGATIONS,
        ]);
    }

    /**
     * Store full data from the TN API and extract JSON files
     *
     * @return void
     */
    public function storeFullData(): void
    {
        $url = config('services.tn.url') . self::FULL_DATA_ENDPOINT . self::LAST_ACTUAL_DATE;

        try {
            // Get the HTML content from the URL
            $response = Http::get($url);
            $html = $response->body();

            // Create a Crawler instance
            $crawler = new Crawler($html);

            // Initialize an array to store downloaded file paths
            $downloadedJsonFiles = [];

            // Find all anchor tags
            $links = $crawler->filter(self::HTML_LINK_TAG);

            // Iterate over each anchor tag and download the linked file
            foreach ($links as $link) {
                $href = $link->getAttribute(self::ATTRIBUTE_HREF);

                // Only download zip files
                if (pathinfo($href, PATHINFO_EXTENSION) === self::ARCHIVE_FOLDER) {
                    $fileName = basename($href);
                    $zipPath = storage_path(self::APP_FOLDER . $fileName);

                    $this->screenRequestDetails($fileName);

                    // Download the file
                    $response = Http::timeout(self::REQ_TIMEOUT)->get($url . $href);

                    // Check if the response is successful
                    if ($response->successful()) {
                        // Save the file to storage

                        $fileHandle = fopen($zipPath, self::OPENING_MODE);

                        // Get the response body stream
                        $bodyStream = $response->getBody();

                        // Read the response body in chunks and write to the file handle
                        while (!$bodyStream->eof()) {
                            fwrite($fileHandle, $bodyStream->read(self::CHUNK_SIZE_BYTE)); // Read 4KB at a time
                        }

                        // Close the file handle
                        fclose($fileHandle);

                        // Unzip the file
                        $zip = new ZipArchive();
                        if ($zip->open($zipPath) === true) {
                            // Extract the JSON files to storage
                            $extractPath = storage_path(self::JSON_FOLDER_PATH);
                            $zip->extractTo($extractPath);

                            // Close the archive
                            $zip->close();

                            // Collect the paths of extracted JSON files
                            $jsonFiles = glob($extractPath . self::JSON_EXTENSION_PATTERN);
                            $downloadedJsonFiles = array_merge($downloadedJsonFiles, $jsonFiles);
                        }

                        // Delete the original ZIP file
                        unlink($zipPath);
                    } else {
                        throw new RequestException($response);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::channel('tn')->error('An error occurred: ' . $e->getMessage());
            echo "An error occurred: " . $e->getMessage();
        }
    }

    /**
     * Get the dictionary data from TN API
     *
     * @return array
     */
    public function handleDictionaryData(): array
    {
        $this->storeFullData();
        return $this->prepareData();
    }

    /**
     * Prepare data for the database
     *
     * @return array
     */
    private function prepareData(): array
    {
        $data = [];

        $files = Storage::files(self::JSON_FOLDER_NAME);

        echo 'Time started: ' . date(self::DATE_FORMAT) . "\n";

        foreach ($files as $file) {
            try {
                echo '----------------------------------------' . "\n";
                echo 'Processing file: ' . $file . "\n";
                $json = Items::fromFile(storage_path(self::APP_FOLDER_NAME . $file));
                foreach ($json as $item) {
                    $item = (array) $item;
                    $this->processItem($item, $data);
                }
            } catch (InvalidArgumentException $e) {
                Log::channel('tn')->error('An error occurred: ' . $e->getMessage());
                echo "An error occurred: " . $e->getMessage();
            }

            echo 'Memory usage: ' . memory_get_usage() . "\n";
            echo 'Time taken: ' . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . ' seconds' . "\n";
        }

        return $data;
    }

    /**
     * Process data of securities into the database
     *
     * @param array $item item
     * @param array $data data
     * @return void
     */
    private function processItem(array $item, array &$data): void
    {
        if (isset($item['instr_type_c'])) {
            if ($item['instr_type_c'] == self::INSTRUMENT_CODE_TYPE_VALUE_SHARES) {
                $data[] = [
                    'ticker_short_name' => $item['ticker'] ?? '',
                    'ticker_full_name' => $item['name'] ?? '',
                    'type' => TnFiltersEnum::SHARES->value,
                ];
            } elseif ($item['instr_type_c'] == self::INSTRUMENT_CODE_TYPE_VALUE_OBLIGATIONS) {
                $data[] = [
                    'isin_code' => $item['ticker'] ?? '',
                    'type' => TnFiltersEnum::OBLIGATIONS->value,
                ];
            }
        }

        if (count($data) >= self::CHUNK_SIZE) {
            $this->setDataToDb($data);
            $data = [];
        }
    }

    /**
     * Set data to the database
     *
     * @param array $data data
     * @return void
     */
    public function setDataToDb(
        array $data,
    ): void {
        foreach ($data as $item) {
            if ($item['type'] === TnFiltersEnum::SHARES->value) {
                Ticker::updateOrCreate(
                    [
                        'short_name' => $item['ticker_short_name'],
                    ],
                    [
                        'full_name' => $item['ticker_full_name'] ?? $item['ticker_short_name'],
                        'is_active' => 0,
                    ],
                );
            } else {
                Isin::updateOrCreate(
                    [
                        'code' => $item['isin_code'],
                    ],
                    [
                        'is_active' => 0,
                    ],
                );
            }
        }
    }

    /**
     * Screen request details
     *
     * @param $fileName string file name
     * @return void
     */
    private function screenRequestDetails(
        string $fileName,
    ): void {
        echo '----------------------------------------' . "\n";
        echo 'Current memory usage: ' . memory_get_usage() . ' bytes' . "\n";
        echo 'Peak memory usage: ' . memory_get_peak_usage() . ' bytes' . "\n";
        echo 'Currently file: ' . $fileName . "\n";
        echo 'Time taken: ' . (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) . ' seconds' . "\n";
        echo '----------------------------------------' . "\n";
    }
}
