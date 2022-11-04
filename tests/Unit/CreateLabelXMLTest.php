<?php

namespace Tsekka\Omniva\Unit\Feature;

use Tsekka\Omniva\Actions\CreateLabelXML;
use Tsekka\Omniva\Tests\TestCase;

class CreateLabelXMLTest extends TestCase
{
    /** @test */
    public function canCreateLabelXmlWithEmail()
    {
        $xml = (new CreateLabelXML)->handle('12345', 'CE376918992EE', 'example@pintek.ee');

        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../files/sendLabelRequestBody.xml',
            $xml
        );
    }

    /** @test */
    public function canCreateLabelXml()
    {
        $xml = (new CreateLabelXML)->handle('12345', 'CE376918992EE');

        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/../files/getLabelRequestBody.xml',
            $xml
        );
    }
}
