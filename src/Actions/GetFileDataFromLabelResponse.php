<?php

namespace Tsekka\Omniva\Actions;

use SimpleXMLElement;
use Tsekka\Omniva\Exceptions\LabelFileDataException;

class GetFileDataFromLabelResponse
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
        $barcode = $xmlArray['Body']['addrcardMsgResponse']['successAddressCards']['addressCardData']['fileData'] ?? null;

        if (!$barcode) {
            throw new LabelFileDataException("Can't get fileData. Response xml: " . $responseXmlString, 1);
        }

        return $barcode;
    }
}
