<?php

declare(strict_types=1);

namespace App\Repositories;

class ShippingRepository
{
    /*
    * Obtiene los metodos de envío disponibles
    *
    * @param string $data
    * @return array
    */
    public function fetchShipingOptions(): array
    {
        $shippingOptions = '[{"name":"Option 4","type":"Delivery","cost":10,"estimated_days":3},
                {"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5},
                {"name":"Option 2","type":"Custom","cost":5,"estimated_days":4},
                {"name":"Option 3","type":"Pickup","cost":7,"estimated_days":1}]';

        return json_decode($shippingOptions, true);
    }
}
