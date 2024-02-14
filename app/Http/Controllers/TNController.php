<?php

namespace App\Http\Controllers;

use App\Contracts\TNInterface;
use App\Services\TnService;

class TNController extends Controller implements TNInterface
{
    /**
     * @constructor TNController
     * @param TnService $tnService tn service
     */
    public function __construct(
        private readonly TnService $tnService,
    ) {}

    /** @inheritDoc */
    public function getSharesAndObligationsData(): array
    {
        return $this->tnService->getDictionaryData();
    }
}
