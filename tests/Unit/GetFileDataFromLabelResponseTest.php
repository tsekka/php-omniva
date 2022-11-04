<?php

namespace Tsekka\Omniva\Unit\Feature;

use Tsekka\Omniva\Actions\GetFileDataFromLabelResponse;
use Tsekka\Omniva\Tests\TestCase;

class GetFileDataFromLabelResponseTest extends TestCase
{
    /** @test */
    public function canGetFileData()
    {
        $file = __DIR__ . '/../files/getLabelResponse.xml';

        $shipmentResponse = file_get_contents($file);

        $fileData = (new GetFileDataFromLabelResponse)->handle($shipmentResponse);

        $this->assertStringStartsWith('JVBERi0xLjQKJeL', $fileData);
    }
}
