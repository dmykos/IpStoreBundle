<?php


namespace Dmykos\IpStoreBundle;


use Dmykos\IpStoreBundle\Entity\IpModel;
use Dmykos\IpStoreBundle\Repository\DatabaseStoreRepository;

class DatabaseStoreDriver implements StoreDriverInterface
{
    /**
     * @var DatabaseStoreRepository
     */
    private $databaseStoreRepository;

    public function __construct(DatabaseStoreRepository $databaseStoreRepository) {

        $this->databaseStoreRepository = $databaseStoreRepository;
    }

    public function add( IpModel $ipModel ): int {
        return $this->databaseStoreRepository->add($ipModel);
    }

    public function query( IpModel $ipModel ): int {
        $count = $this->databaseStoreRepository->query($ipModel);

        return $count;
    }

}