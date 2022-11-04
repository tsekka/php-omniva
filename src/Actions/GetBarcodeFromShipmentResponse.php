<?php

namespace Tsekka\Omniva\Actions;

use SimpleXMLElement;
use Tsekka\Omniva\Exceptions\ShipmentBarcodeException;

class GetBarcodeFromShipmentResponse
{
    /**
     * @throws \Tsekka\Omniva\Exceptions\ShipmentBarcodeException
     */
    public function handle(string $responseXmlString): string
    {
        $xmlString = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $responseXmlString);
        $xmlString = str_replace('SOAP-ENV:', '', $xmlString);
        $xmlString = str_replace('soapenv:', '', $xmlString);
        $xmlObj = new SimpleXMLElement($xmlString);
        $xmlArray = json_decode(json_encode((array) $xmlObj), true);
        $barcode = $xmlArray['Body']['businessToClientMsgResponse']['savedPacketInfo']['barcodeInfo']['barcode'] ?? null;

        if (! $barcode) {
            throw new ShipmentBarcodeException("Can't get barcode. Response xml: ".$responseXmlString, 1);
        }

        return $barcode;
    }
}
