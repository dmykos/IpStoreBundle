<?php

namespace Dmykos\IpStoreBundle\Tests;


use Dmykos\IpStoreBundle\Entity\IpModel;
use PHPUnit\Framework\TestCase;

class DatabaseStoreDriverTest extends TestCase
{
    public function testGetWords()
    {
        $ipModel = new IpModel();
        $ipModel->setIp('255.255.255.0');
        $this->assertCount(2, explode(' ', 'dgd words'));
    }
}