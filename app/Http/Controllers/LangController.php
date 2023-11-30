<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\LangStrEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LangController extends Controller
{
    /**
     * Set language group
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setGroup(Request $request): JsonResponse
    {
        $group = $request->group;

        // Process $group using your logic
        $params = LangStrEnum::getParamsForTranslation($group);

        // Return a response if needed
        return response()->json(['params' => $params]);
    }
}
