<?php

namespace Tsekka\Omniva;

use SoapClient;
use SoapVar;
use Tsekka\Omniva\Actions\CreateLabelXML;
use Tsekka\Omniva\Actions\CreateShipmentXML;
use Tsekka\Omniva\Actions\GetBarcodeFromShipmentResponse;
use Tsekka\Omniva\Actions\GetFileDataFromLabelResponse;
use Tsekka\Omniva\Exceptions\CreateShipmentException;
use Tsekka\Omniva\Exceptions\GetLabelException;
use Tsekka\Omniva\Exceptions\SendLabelException;

class Client
{
    public string $wsdlUrl = 'https://edixml.post.ee/epmx/services/messagesService.wsdl';

    public ?SoapClient $soapClient = null;

    private string $username;

    private string $password;

    public function __construct(string|int $username, string $password)
    {
        $this->username = (string) $username;
        $this->password = $password;
    }

    /**
     * @throws \Tsekka\Omniva\Exceptions\CreateShipmentException
     * @throws \Tsekka\Omniva\Exceptions\ShipmentBarcodeException
     */
    public function createShipment(Parcel $parcel): string
    {
        try {
            $this->getSoapClient()->businessToClientMsg(
                new SoapVar($this->shipmentXML($parcel), XSD_ANYXML)
            );
        } catch (\SoapFault $th) {
            throw new CreateShipmentException($this->soapExceptionMessage($th));
        }

        $barcode = $this->getBarcodeFromShipmentResponse($this->soapClient->__getLastResponse());

        return $barcode;
    }

    /**
     * @throws \Tsekka\Omniva\Exceptions\SendLabelException
     */
    public function sendLabel(string $parcelBarcode, string $email): bool
    {
        try {
            $this->getSoapClient()->addrcardMsg(
                new SoapVar($this->labelXML($parcelBarcode, $email), XSD_ANYXML)
            );
        } catch (\SoapFault $th) {
            throw new SendLabelException($this->soapExceptionMessage($th));
        }

        return true;
    }

    public function getLabel(string $parcelBarcode): string
    {
        $file = __DIR__ . '/../tests/files/getLabelResponse.xml';

        $shipmentResponse = file_get_contents($file);

        $fileData = (new GetFileDataFromLabelResponse)->handle($shipmentResponse);

        return $fileData;

        try {
            $response = $this->getSoapClient()->addrcardMsg(
                new SoapVar($this->labelXML($parcelBarcode, null), XSD_ANYXML)
            );

            return $response;
        } catch (\SoapFault $th) {
            throw new GetLabelException($this->soapExceptionMessage($th));
        }

        $fileData = $this->getFileDataFromLabelResponse($this->soapClient->__getLastResponse());

        return $fileData;
    }

    private function getSoapClient(): SoapClient
    {
        if ($this->soapClient) {
            return $this->soapClient;
        }

        $soapClientOptions = [
            'cache_wsdl' => false,
            'login' => $this->username,
            'password' => $this->password,
            'trace' => true,
            'exceptions' => true,
        ];

        $this->soapClient = new SoapClient(
            $this->wsdlUrl,
            $soapClientOptions
        );

        return $this->soapClient;
    }

    private function getBarcodeFromShipmentResponse(string $responseXmlString): string
    {
        return (new GetBarcodeFromShipmentResponse)->handle($responseXmlString);
    }

    private function getFileDataFromLabelResponse(string $responseXmlString): string
    {
        return (new GetFileDataFromLabelResponse)->handle($responseXmlString);
    }

    private function shipmentXML(Parcel $parcel): string
    {
        return (new CreateShipmentXML)->handle($this->username, $parcel);
    }

    private function labelXML(string $parcelBarcode, string|null $email): string
    {
        return (new CreateLabelXML)->handle($this->username, $parcelBarcode, $email);
    }

    private function soapExceptionMessage(\SoapFault $th)
    {
        $request = $this->soapClient->__getLastRequest() ?? '-';

        return  'Soap error: ' . $th->faultstring . ".\n"
            . 'Faultcode: ' . $th->faultcode . ".\n"
            . 'Request: ' . $request;
    }
}
