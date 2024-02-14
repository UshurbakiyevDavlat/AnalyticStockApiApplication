<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TnFiltersEnum;
use App\Models\Isin;
use App\Models\Ticker;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TnService
{
    /** @const string instrument code type title **/
    public const INSTRUMENT_CODE_TYPE_TITLE = 'instr_type_c';

    /** @const string instrument code type operator **/
    public const INSTRUMENT_CODE_TYPE_OPERATOR = 'in';

    /** @const string instrument code type value shares **/
    public const INSTRUMENT_CODE_TYPE_VALUE_SHARES = 1;

    /** @const string instrument code type value obligations **/
    public const INSTRUMENT_CODE_TYPE_VALUE_OBLIGATIONS = 2;

    /** @const int take amount **/
    public const TAKE = 1000;

    /**
     * Get tickers from the TN API
     *
     * @return Response
     */
    public function getTickers(): Response
    {
        return Http::get(config('services.tn.url'), [
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
        return Http::get(config('services.tn.url'), [
            TnFiltersEnum::TAKE->value => self::TAKE,
            TnFiltersEnum::FIELD->value => self::INSTRUMENT_CODE_TYPE_TITLE,
            TnFiltersEnum::OPERATOR->value => self::INSTRUMENT_CODE_TYPE_OPERATOR,
            TnFiltersEnum::VALUE->value => self::INSTRUMENT_CODE_TYPE_VALUE_OBLIGATIONS,
        ]);
    }

    /**
     * Get the dictionary data from TN API
     *
     * @return array
     */
    public function getDictionaryData(): array
    {
        $tickers = $this->getTickers()->json();
        $isins = $this->getIsins()->json();

        return $this->prepareData($tickers, $isins);
    }

    /**
     * Prepare data for the database
     *
     * @param $tickers array tickers
     * @param $isins array isins
     * @return array
     */
    private function prepareData(array $tickers, array $isins): array
    {
        $data = [];

        foreach ($tickers['securities'] as $ticker) {
            $data[] = [
                'ticker_short_name' => $ticker['ticker'],
                'ticker_full_name' => $ticker['name'],
                'type' => TnFiltersEnum::SHARES->value,
            ];
        }

        foreach ($isins['securities'] as $isin) {
            $data[] = [
                'isin_code' => $isin['ticker'],
                'type' => TnFiltersEnum::OBLIGATIONS->value,
            ];
        }

        return $data;
    }

    /**
     * Set data to the database
     *
     * @param array $data data
     * @return void
     */
    public function setDataToDb(array $data): void
    {
        foreach ($data as $item) {
            if ($item['type'] === TnFiltersEnum::SHARES->value) {
                Ticker::updateOrCreate(
                    [
                        'short_name' => $item['ticker_short_name'],
                    ],
                    [
                        'full_name' => $item['ticker_full_name'] ?? $item['ticker_short_name'] ,
                        'is_active' => true,
                    ],
                );
            } else {
                Isin::updateOrCreate(
                    [
                        'code' => $item['isin_code'],
                    ],
                    [
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
