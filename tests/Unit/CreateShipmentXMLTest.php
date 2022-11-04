<?php

namespace Tsekka\Omniva\Unit\Feature;

use Tsekka\Omniva\Actions\CreateShipmentXML;
use Tsekka\Omniva\Tests\TestCase;

class CreateShipmentXMLTest extends TestCase
{
    /** @test */
    public function canCreateShipmentXml()
    {
        $xml = (new CreateShipmentXML)->handle('12345', $this->parcel());

        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../files/shipmentRequestBody.xml',
            $xml
        );
    }
}
