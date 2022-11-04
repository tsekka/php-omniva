# Omniva for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tsekka/omniva.svg?style=flat-square)](https://packagist.org/packages/tsekka/omniva)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/tsekka/omniva/run-tests?label=tests)](https://github.com/tsekka/omniva/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/tsekka/omniva/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/tsekka/omniva/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/tsekka/omniva.svg?style=flat-square)](https://packagist.org/packages/tsekka/omniva)

Omniva is a shipping carrier serving Baltic countries Estonia, Latvia and Lithuania. With this package, you'll be able to generate parcels and request shipping labels.

## Features
- [x] Generate shipments (type of request responds to [businessToClientMsgRequest](https://www.omniva.ee/public/files/failid/manual_xml_dataexchange_eng.pdf)) and get the shipment's barcode.
- [x] Get the shipment's barcode (can be used to show tracking code or to get shipping label).
- [x] Request shipping label to be sent via email from Omniva's server.
- [x] Get parcel label as file.

## Installation

You can install the package via composer:

```bash
composer require tsekka/php-omniva
```

## Usage examples
### Generating the shipment
*This example assumes you'll use the Omniva's parcel machine as the destination and that you know its zip code.* 

*The list of parcel machines is easily available in json, xml and csv formats. Please see the [Omniva's manual](https://www.omniva.ee/public/files/failid/manual_xml_dataexchange_eng.pdf) to get the list of destination points and service codes used in this example.*
```php
    use Tsekka\Omniva\Client;
    use Tsekka\Omniva\Parcel;
    use Tsekka\Omniva\Address;
    use Tsekka\Omniva\PickupPoint;

    /**
     * Set your authentication details.
     */
    $client = new Client(
        username: 'your Omniva web service username',
        password: 'your Omniva web service password'
    );

    /**
     * Set & define delivery service, 
     * pickup point's information 
     * and additional services. 
     */
    $parcel = new Parcel(
        deliveryService: 'PA'
    );
    $pickupPoint = new PickupPoint(
        offloadPostcode: 96094
    );
    $parcel
        ->addAdditionalService('ST')
        ->addAdditionalService('SF');
    $receiver->pickupPoint = $pickupPoint;

    /**
     * Set & define receiver and returnee.
     */
    $receiver = new Address();
    $receiver->name = 'Jane Doe';
    $receiver->mobile = '+3725511223';
    $receiver->email = 'client@example.com';
    $returnee = new Address();
    $returnee->country = 'EE';
    $returnee->name = 'John Roe';
    $returnee->mobile = '+3725566778';
    $returnee->email = 'returnee@example.com';
    $returnee->postcode = '80040';
    $returnee->deliverypoint = 'PARNU';
    $returnee->street = 'Savi 20';
    $returnee->country = 'EE';
    $parcel->receiver = $receiver;
    $parcel->returnee = $returnee;

    /**
     * Generate the shipment & get the barcode
     */
    $barcode = $client->createShipment($parcel);
```

### Getting the shipping label
```php
    /**
     * Request the label to be emailed to you from Omniva's server
     */
    $client->sendLabel($barcode, 'business@example.com');

    /**
     * Or get the content of pdf file & save or output it
     */
    $fileData = $client->getLabel($barcode);
    $fileName = "label_{$barcode}.pdf";
    file_put_contents(storage_path() . "/{$fileName}", base64_decode($fileData));
    header('Content-type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    echo base64_decode($fileData);
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome and will be credited.

## Security Vulnerabilities

Please report security vulnerabilities by email `pintek@pintek.ee`.

## Credits

- [Kristjan Käärma](https://github.com/tsekka)
- [Aurimas Baubkus](https://github.com/nebijokit/omniva)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
