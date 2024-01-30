<?php

declare(strict_types=1);

namespace App\Actions;

final class SortShippingOptionsAction
{
    private function __construct() {}

    /**
     * Recibe una lista de opciones de envio,
     * ordena la lista segun por mejor consto y tiempo
     * y retorna el resultado
     *
     * @param array $shippingOptions
     * @return array
     */
    public static function exec(array $shippingOptions): array
    {
        $collection = collect($shippingOptions);

        $criteriaSort = [
            ['cost', 'asc'],
            ['estimated_days', 'asc']
        ];

        $sorted = $collection->sortBy($criteriaSort);

        return $sorted->values()->all();
    }
}

