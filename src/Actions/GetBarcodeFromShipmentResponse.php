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
        try {
            return $this->updatedMethod($responseXmlString);
        } catch (\Throwable $th) {
            //
        }

        $xmlString = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $responseXmlString);
        $xmlString = str_replace('SOAP-ENV:', '', $xmlString);
        $xmlString = str_replace('soapenv:', '', $xmlString);
        $xmlObj = new SimpleXMLElement($xmlString);
        $xmlArray = json_decode(json_encode((array) $xmlObj), true);
        $barcode = $xmlArray['Body']['businessToClientMsgResponse']['savedPacketInfo']['barcodeInfo']['barcode'] ?? null;

        if (!$barcode) {
            throw new ShipmentBarcodeException("Can't get barcode. Response xml: " . $responseXmlString, 1);
        }

        return $barcode;
    }

    private function updatedMethod(string $responseXmlString): string
    {
        $xmlString = preg_replace('/<(\/)?(\w+):/i', '<$1', $responseXmlString);
        $xmlString = preg_replace('/<\/(\w+):/i', '</', $xmlString);
        $xmlObj = new SimpleXMLElement($xmlString);
        $barcodeNodes = $xmlObj->xpath('//businessToClientMsgResponse/savedPacketInfo/barcodeInfo/barcode');
        if ($barcodeNodes && count($barcodeNodes) > 0) {
            return (string) $barcodeNodes[0];
        } else {
            throw new \Exception("Can't get barcode. Response xml: " . $responseXmlString, 1);
        }
    }
}
