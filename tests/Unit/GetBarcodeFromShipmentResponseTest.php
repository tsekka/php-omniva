<?php

namespace Tsekka\Omniva\Unit\Feature;

use Tsekka\Omniva\Actions\GetBarcodeFromShipmentResponse;
use Tsekka\Omniva\Tests\TestCase;

class GetBarcodeFromShipmentResponseTest extends TestCase
{
    /** @test */
    public function canGetBarcode()
    {
        $file = __DIR__ . '/../files/shipmentResponse.xml';

        $shipmentResponse = file_get_contents($file);

        $barcode = (new GetBarcodeFromShipmentResponse)->handle($shipmentResponse);

        $this->assertSame('CE387408425EE', $barcode);
    }
}
