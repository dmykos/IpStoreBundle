<?php


namespace Dmykos\IpStoreBundle;



use Dmykos\IpStoreBundle\Entity\IpModel;

interface StoreDriverInterface {

    public function add(IpModel $ipModel) :int ;

    public function query(IpModel $ipModel): int;

}