<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Repositories\ShippingRepository;
use Mockery\MockInterface;
use Tests\TestCase;

class ShippingControllerTest extends TestCase
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
    * Método que realiza la verificaciones de las pruebas recibiendo como paramentros
    * los datos a mokear que seran ordenados en la API y los datos
    * que se esperan en la respuesta.
    *
    * @param string $data (Entrada Desordenada)
    * @param string $expectedSortedData (Salida Ordenada)
    * @return void
    */
    private function assertFetchedAndSortedShippingOptions(string $data, string $expectedResponse): void
    {
        $this->mock(ShippingRepository::class, function (MockInterface $mock) use ($data)  {
            $mock->shouldReceive('fetchShipingOptions')
            ->andReturn($this->jsonToArray($data));
        });

        $response = $this->get('/api/shipping-options');

        $response->assertStatus(200);
        $response->assertExactJson($this->jsonToArray($expectedResponse));
    }

    /*
    * Prueba obtener las opciones de envío con: estimación y costo diferentes
    */
    public function testGetApiShippingOptionsDifferentCostAndEstimated(): void
    {
        $initData = '[{"name":"Option 4","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5},
        {"name":"Option 2","type":"Custom","cost":5,"estimated_days":4},
        {"name":"Option 3","type":"Pickup","cost":7,"estimated_days":1}]';

        $responseData = '[{"name":"Option 2","type":"Custom","cost":5,"estimated_days":4},
        {"name":"Option 3","type":"Pickup","cost":7,"estimated_days":1},
        {"name":"Option 4","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5}]';

        $this->assertFetchedAndSortedShippingOptions($initData, $responseData);
    }

    /*
    * Prueba obtener las opciones de envío con: estimación diferente y mismo costo
    */
    public function testGetApiShippingOptionsDifferentEstimatedSameCost(): void
    {
        $initData = '[{"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5},
        {"name":"Option 2","type":"Custom","cost":10,"estimated_days":2},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $responseData = '[{"name":"Option 2","type":"Custom","cost":10,"estimated_days":2},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":10,"estimated_days":5}]';

        $this->assertFetchedAndSortedShippingOptions($initData, $responseData);
    }

    /*
    * Prueba obtener las opciones de envío con: misma estimación y costo diferente
    */
    public function testGetApiShippingOptionsDifferentCostSameEstimated(): void
    {
        $initData = '[{"name":"Option 1","type":"Delivery","cost":6,"estimated_days":3},
        {"name":"Option 2","type":"Custom","cost":5,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $responseData = '[{"name":"Option 2","type":"Custom","cost":5,"estimated_days":3},
        {"name":"Option 1","type":"Delivery","cost":6,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $this->assertFetchedAndSortedShippingOptions($initData, $responseData);
    }

    /*
    * Prueba obtener las opciones de envío con: estimación y costo similar
    */
    public function testGetApiShippingOptionsSameEstimatedSameCost(): void
    {
        $initData = '[{"name":"Option 1","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 2","type":"Custom","cost":10,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $responseData = '[{"name":"Option 1","type":"Delivery","cost":10,"estimated_days":3},
        {"name":"Option 2","type":"Custom","cost":10,"estimated_days":3},
        {"name":"Option 3","type":"Pickup","cost":10,"estimated_days":3}]';

        $this->assertFetchedAndSortedShippingOptions($initData, $responseData);
    }

    /*
    * Prueba obtener las opciones de envío con: sin datos
    */
    public function testGetApiShippingOptionsEmptyResponse(): void
    {
        $initData = '';

        $responseData = '';

        $this->assertFetchedAndSortedShippingOptions($initData, $responseData);
    }
}
