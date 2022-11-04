<?php

namespace Tsekka\Omniva;

use ArrayIterator;

class Parcel
{
    public string $deliveryService;

    public Address $receiver;

    public Address $returnee;

    public ?Address $sender = null;

    public ArrayIterator $additionalServices;

    public ?float $codAmount = null;

    public ?string $bankAccount = null;

    public ?string $comment = null;

    public string|int|null $partnerId = null;

    public ?float $weight = null;

    public string $fileId;

    public function __construct(string $deliveryService)
    {
        $this->deliveryService = $deliveryService;
        $this->additionalServices = new ArrayIterator();
        $this->fileId = uniqid();
    }

    public function hasAdditionalServices(): bool
    {
        return $this->additionalServices->count() > 0;
    }

    public function addAdditionalService(string $service): self
    {
        $this->additionalServices->append($service);

        return $this;
    }

    public function getAdditionalServices(): ArrayIterator
    {
        return $this->additionalServices;
    }
}
