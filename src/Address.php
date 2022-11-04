<?php

namespace Tsekka\Omniva;

class Address
{
    public string $name;

    public ?string $mobile = null;

    public ?string $email = null;

    public ?string $phone = null;

    public ?PickupPoint $pickupPoint = null;

    public ?string $deliverypoint = null;

    public ?string $street = null;

    public ?string $postcode = null;

    public string $country = 'EE';
}
