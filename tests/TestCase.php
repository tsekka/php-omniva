<?php

namespace Tsekka\Omniva\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Tsekka\Omniva\Address;
use Tsekka\Omniva\Parcel;
use Tsekka\Omniva\PickupPoint;

class TestCase extends Orchestra
{
    protected function parcel(): Parcel
    {
        $pickupPoint = new PickupPoint(offloadPostcode: 96094, type: 0);

        $receiver = new Address();
        $receiver->country = 'EE';
        $receiver->name = 'Jane Doe';
        $receiver->mobile = '+3725511223';
        $receiver->email = 'client@example.com';
        $receiver->pickupPoint = $pickupPoint;

        $returnee = new Address();
        $returnee->country = 'EE';
        $returnee->name = 'John Row';
        $returnee->mobile = '+3725566778';
        $returnee->email = 'returnee@example.com';
        $returnee->postcode = '80040';
        $returnee->deliverypoint = 'PARNU';
        $returnee->street = 'Savi 20';

        $parcel = new Parcel(deliveryService: 'PA');
        $parcel->fileId = '6364cbc9e75c5';
        $parcel->receiver = $receiver;
        $parcel->returnee = $returnee;
        $parcel
            ->addAdditionalService('ST')
            ->addAdditionalService('SF');

        return $parcel;
    }
}
