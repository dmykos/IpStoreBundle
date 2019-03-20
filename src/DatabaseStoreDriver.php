<?php


namespace Dmykos\IpStoreBundle;


use Dmykos\IpStoreBundle\Entity\IpModel;
use Dmykos\IpStoreBundle\Repository\IpStoreRepository;

class DatabaseStoreDriver implements StoreDriverInterface
{
    /**
     * @var IpStoreRepository
     */
    private $ipStoreRepository;

    public function __construct(IpStoreRepository $ipStoreRepository) {

        $this->ipStoreRepository = $ipStoreRepository;
    }

    public function add( IpModel $ipModel ): int {
        return $this->ipStoreRepository->add($ipModel);
    }

    public function query( IpModel $ipModel ): int {
        $ipStore = $this->ipStoreRepository->findOneBy(['ip'=> $ipModel->getIp()]);

        if (!$ipStore){
            $count = 0;
        } else{
            $count = $ipStore->getCount();
        }

        return $count;
    }

}