<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SortShippingOptionsAction;
use App\Repositories\ShippingRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    /**
     * Retorna una lista de opciones de envío disponibles
     * ordenada por la mejor combinación
     * entre consto y tiempo
     *
     * @param ShippingRepository $shippingRepository
     * @return JsonResponse
     */
    public function options(ShippingRepository $shippingRepository): JsonResponse
    {
        $shippingOptions = $shippingRepository->fetchShipingOptions();

        $response = SortShippingOptionsAction::exec($shippingOptions);

        return response()->json($response);
    }
}
