<?php

namespace Tsekka\Omniva\Actions;

use Tsekka\Omniva\Parcel;
use XMLWriter;

class CreateShipmentXML
{
    public function handle(string $username, Parcel $parcel): string
    {
        $writer = new XMLWriter;
        $writer->openMemory();

        $writer->startElement('ns1:businessToClientMsgRequest');

        $writer->writeElement('partner', $username);

        $writer->startElement('interchange');
        $writer->writeAttribute('msg_type', 'elsinfov1');

        $writer->startElement('header');
        $writer->writeAttribute('file_id', $parcel->fileId);
        $writer->writeAttribute('sender_cd', $username);
        $writer->endElement();

        $writer->startElement('item_list');

        $writer->startElement('item');
        $receiver = $parcel->receiver;

        $hasPickupPoint = $receiver->pickupPoint !== null;
        $pickupPoint = $receiver->pickupPoint;

        $writer->writeAttribute('service', $parcel->deliveryService);

        if ($parcel->hasAdditionalServices()) {
            $writer->startElement('add_service');
            foreach ($parcel->getAdditionalServices() as $service) {
                $writer->startElement('option');
                $writer->writeAttribute('code', $service);
                $writer->endElement();
            }
            $writer->endElement();
        }

        if ($parcel->weight) {
            $writer->startElement('measures');
            $writer->writeAttribute('weight', $parcel->weight);
            $writer->endElement();
        }

        if ($parcel->codAmount) {
            $writer->startElement('monetary_values');

            $writer->startElement('values');
            $writer->writeAttribute('code', 'item_value');
            $writer->writeAttribute('amount', $parcel->codAmount);
            $writer->endElement();

            $writer->endElement();

            $writer->writeElement('account', $parcel->bankAccount);
        }

        if ($parcel->comment) {
            $writer->writeElement('comment', $parcel->comment);
        }

        if ($parcel->partnerId) {
            $writer->writeElement('partnerId', $parcel->partnerId);
        }

        $writer->startElement('receiverAddressee');
        $writer->writeElement('person_name', $receiver->name);
        $writer->writeElement('mobile', $receiver->mobile);
        if ($receiver->email) {
            $writer->writeElement('email', $receiver->email);
        }
        if ($receiver->phone) {
            $writer->writeElement('email', $receiver->phone);
        }

        $writer->startElement('address');

        if ($hasPickupPoint) {
            $writer->writeAttribute('offloadPostcode', $pickupPoint->offloadPostcode);
        } else {
            $writer->writeAttribute('postcode', $receiver->postcode);
            $writer->writeAttribute('deliverypoint', $receiver->deliverypoint);
            $writer->writeAttribute('street', $receiver->street);
        }

        $writer->writeAttribute('country', $receiver->country);
        $writer->endElement();

        $writer->endElement();

        $writer->startElement('returnAddressee');
        $returnAddress = $parcel->returnee;
        $writer->writeElement('person_name', $returnAddress->name);
        if ($returnAddress->phone) {
            $writer->writeElement('phone', $returnAddress->phone);
        }
        if ($returnAddress->mobile) {
            $writer->writeElement('mobile', $returnAddress->mobile);
        }
        if ($returnAddress->email) {
            $writer->writeElement('email', $returnAddress->email);
        }

        $writer->startElement('address');
        $writer->writeAttribute('postcode', $returnAddress->postcode);
        $writer->writeAttribute('deliverypoint', $returnAddress->deliverypoint);
        $writer->writeAttribute('street', $returnAddress->street);
        $writer->writeAttribute('country', $returnAddress->country);
        $writer->endElement();
        $writer->endElement();

        if ($parcel->sender) {
            $writer->startElement('onloadAddressee');
            $sender = $parcel->sender;
            $writer->writeElement('person_name', $sender->name);
            $writer->writeElement('phone', $sender->phone);
            if ($sender->email) {
                $writer->writeElement('email', $sender->email);
            }

            $writer->startElement('address');
            $writer->writeAttribute('postcode', $sender->postcode);
            $writer->writeAttribute('deliverypoint', $sender->deliverypoint);
            $writer->writeAttribute('street', $sender->street);
            $writer->writeAttribute('country', $sender->country);
            $writer->endElement();
            $writer->endElement();
        }
        $writer->endElement();

        $writer->endElement();

        $writer->endElement();
        $writer->endElement();

        return $writer->outputMemory();
    }
}
