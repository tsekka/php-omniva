<?php

namespace Tsekka\Omniva\Actions;

use XMLWriter;

class CreateLabelXML
{
    public function handle(
        string $username,
        string $barcode,
        string|null $email = null
    ): string {
        $writer = new XMLWriter();
        $writer->openMemory();

        $writer->startElement('ns1:addrcardMsgRequest');
        $writer->writeElement('partner', $username);
        if ($email) {
            $writer->writeElement('sendAddressCardTo', 'email');
            $writer->writeElement('cardReceiverEmail', $email);
        } else {
            $writer->writeElement('sendAddressCardTo', 'response');
        }
        $writer->startElement('barcodes');
        $writer->writeElement('barcode', $barcode);
        $writer->endElement();
        $writer->endElement();

        return $writer->outputMemory();
    }
}
