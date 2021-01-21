<?php

namespace Dmykos\IpStoreBundle\Tests;

use Dmykos\IpStoreBundle\DatabaseStoreDriver;
use Dmykos\IpStoreBundle\Entity\IpModel;
use PHPUnit\Framework\TestCase;


class DatabaseStoreDriverTest extends TestCase
{

    public function testDatabaseStoreDriverAdd()
    {
        $ipModel = new IpModel();
        $ipModel->setIp('255.255.255.0');

        $ipStoreRepository = $this->getMockBuilder(\Dmykos\IpStoreBundle\Repository\DatabaseStoreRepository::class)
             ->disableOriginalConstructor()
             ->getMock();

        $ipStoreRepository->expects($this->once())->method('add')
                          ->with(
                              $ipModel
                          );

        $databaseStoreDriver = new DatabaseStoreDriver($ipStoreRepository);
        $databaseStoreDriver->add($ipModel);


    }

    public function testDatabaseStoreDriverQuery()
    {
        $ipModel = new IpModel();
        $ipModel->setIp('255.255.255.0');

        $ipStoreRepository = $this->getMockBuilder(\Dmykos\IpStoreBundle\Repository\DatabaseStoreRepository::class)
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $ipStoreRepository->expects($this->once())->method('query')
                          ->with(
                              $ipModel
                          );
        $databaseStoreDriver = new DatabaseStoreDriver($ipStoreRepository);
        $databaseStoreDriver->query($ipModel);
    }

}