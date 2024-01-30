<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\SortShippingOptionsAction;
use PHPUnit\Framework\TestCase;

class ShortShippingOptionsActionTest extends TestCase
{

    /*
    * Ayudante para covertir los datos JSON en ARRAY
    *
    * @param string $data
    * @return array
    */
    private function jsonToArray(string $data): array
    {
        return json_decode($data, true) ?? [];
    }

    /*
    * Método que realiza la verificaciones de las pruebas recibiendo
    * como paramentros los datos a evaluar y los datos
    * que se esperan que sean devueltos.
    *
    * @param string $data (Entrada Desordenada)
    * @param string $expectedSortedData (Salida Ordenada)
    * @return void
    */
    private function assertSortedShippingOptions(string $data, string $expectedSortedData): void
    {
        $shippingOptionsSource = $this->jsonToArray($data);

        $sortedShippingOptions = SortShippingOptionsAction::exec($shippingOptionsSource);

        $this->assertNotEquals($sortedShippingOptions, $shippingOptionsSource);
        $this->assertEquals($sortedShippingOptions, $this->jsonToArray($expectedSortedData));
    }

    /*
    * Prueba ordenar las opciones de envío con: estimación y costo diferentes
    */
    public function test_sort_shipping_options_different_cost_and_estimated(): void
    {
        $initData = '[{"name":"Option 4","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5},
        {"name":"Option 2","type":"Custom","cost":5,"estimated_days":4},
        {"name":"Option 3","type":"Pickup","cost":7,"estimated_days":1}]';

        $sortedData = '[{"name":"Option 2","type":"Custom","cost":5,"estimated_days":4},
        {"name":"Option 3","type":"Pickup","cost":7,"estimated_days":1},
        {"name":"Option 4","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5}]';

        $this->assertSortedShippingOptions($initData, $sortedData);
    }

    /*
    * Prueba ordenar las opciones de envío con: estimación diferente y mismo costo
    */
    public function test_sort_shipping_options_different_estimated_same_cost(): void
    {
        $initData = '[{"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5},
        {"name":"Option 2","type":"Custom","cost":10,"estimated_days":2},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $sortedData = '[{"name":"Option 2","type":"Custom","cost":10,"estimated_days":2},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5}]';

        $this->assertSortedShippingOptions($initData, $sortedData);
    }

    /*
    * Prueba ordenar las opciones de envío con: misma estimación y costo diferente
    */
    public function test_sort_shipping_options_different_cost_same_estimated(): void
    {
        $initData = '[{"name":"Option 1","type":"Delivery","cost":6,"estimated_days":3},
        {"name":"Option 2","type":"Custom","cost":5,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $sortedData = '[{"name":"Option 2","type":"Custom","cost":5,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":6,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $this->assertSortedShippingOptions($initData, $sortedData);
    }

    /*
    * Prueba ordenar las opciones de envío con: estimación y costo similar
    */
    public function test_sort_shipping_options_same_estimated_same_cost(): void
    {
        $initData = '[{"name":"Option 1","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 2","type":"Custom","cost":10,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $sortedData = '[{"name":"Option 1","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 2","type":"Custom","cost":10,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $shippingOptionsSourse = $this->jsonToArray($initData);

        $sortShippingOptions = SortShippingOptionsAction::exec($shippingOptionsSourse);

        $this->assertEquals($sortShippingOptions, $this->jsonToArray($sortedData));
    }

    /*
    * Prueba ordenar las opciones de envío con: estimación y costo similar
    */
    public function test_sort_shipping_options_data_empty(): void
    {
        $initData = '[]';

        $sortedData = '';

        $shippingOptionsSourse = $this->jsonToArray($initData);

        $sortShippingOptions = SortShippingOptionsAction::exec($shippingOptionsSourse);

        $this->assertEquals($sortShippingOptions, $this->jsonToArray($sortedData));
    }


}
