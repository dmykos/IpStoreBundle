<?php

namespace Dmykos\IpStoreBundle\Repository;

use Dmykos\IpStoreBundle\Entity\IpStore;
use Dmykos\IpStoreBundle\Entity\IpModel;
use Dmykos\IpStoreBundle\StoreDriverInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\RetryableException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IpStore|null find($id, $lockMode = null, $lockVersion = null)
 * @method IpStore|null findOneBy(array $criteria, array $orderBy = null)
 * @method IpStore[]    findAll()
 * @method IpStore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IpStoreRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IpStore::class);
    }

    public function add( IpModel $ipModel ): int {

        $conn = $this->getEntityManager()
                     ->getConnection();

        $retries = 0;
        do {
            $conn->beginTransaction();
            try {
                $sql = "SELECT * FROM ip_store WHERE ip = :ip FOR UPDATE;";
                $stmt = $conn->prepare($sql);
                $stmt->bindValue('ip', $ipModel->getIp());
                $stmt->execute();
                $selectResult = $stmt->fetch();

                if(!$selectResult){
                    // Ip not added yet
                    $count = 1;
                    $sql = "INSERT INTO `ip_store` (`ip`, `count`) VALUES (:ip, :count);";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue('ip', $ipModel->getIp());
                    $stmt->bindValue('count', $count);
                    $stmt->execute();
                }else{
                    // Ip exist
                    $count = $selectResult['count']+1;
                    $sql =  "UPDATE `ip_store` SET `count` = `count` + 1 WHERE `ip_store`.`ip` = :ip ;";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindValue('ip', $ipModel->getIp());
                    $stmt->execute();
                }

                $conn->commit();

                return $count;
            } catch (RetryableException $e) {
                $conn->rollback();


                ++$retries;
            } catch (\Exception $e) {
                $conn->rollback();
                throw $e;
            }
        } while ($retries < 10);
        throw $e;
    }

    // /**
    //  * @return IpStore[] Returns an array of IpStore objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IpStore
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
