<?php

namespace Dmykos\IpStoreBundle\Repository;

use Dmykos\IpStoreBundle\Entity\IpModel;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Exception\RetryableException;
use Throwable;


class DatabaseStoreRepository
{
    private $connection;
    private $tableName;
    private $idColumnName;
    private $idColumnValue;
    private $keyColumnName;

    public function __construct(
        Connection $connection,
        $tableName,
        $idColumnName,
        $idColumnValue,
        $keyColumnName
    ) {

        $this->connection = $connection;
        $this->tableName = $tableName;
        $this->idColumnName = $idColumnName;
        $this->idColumnValue = $idColumnValue;
        $this->keyColumnName = $keyColumnName;
    }

    public function query( IpModel $ipModel ): int {
        $selectResult = $this->select();

        if(!$selectResult || $selectResult[$this->keyColumnName] == null){
            return 0;
        }

        // Ip Store exists
        $keyColumnValue = json_decode($selectResult[$this->keyColumnName], true);
        if(array_key_exists($ipModel->getIp(), $keyColumnValue)){
            return $keyColumnValue[$ipModel->getIp()];
        }

        return 0;
    }

    public function add( IpModel $ipModel ): int {

        $conn = $this->connection;

        $retries = 0;
        do {
            $conn->beginTransaction();
            try {
                $selectResult = $this->selectForUpdate();

                if(!$selectResult){
                    // Store with $this->idColumnValue don't exist
                    $count = 1;
                    $keyColumnValue = [
                        $ipModel->getIp() => $count
                    ];
                    $keyColumnValue = json_encode($keyColumnValue);

                    $this->insert($keyColumnValue);
                } else if($selectResult[$this->keyColumnName] == null){
                    // No Ip was added yet
                    $count = 1;
                    $keyColumnValue = [
                        $ipModel->getIp() => $count
                    ];
                    $keyColumnValue = json_encode($keyColumnValue);

                    $this->update($keyColumnValue);
                }else{
                    // Ip Store exists

                    $keyColumnValue = json_decode($selectResult[$this->keyColumnName], true);

                    if(array_key_exists($ipModel->getIp(), $keyColumnValue)){
                        $count = $keyColumnValue[$ipModel->getIp()]+1;
                    }else{
                        $count = 1;
                    }

                    $keyColumnValue[$ipModel->getIp()] = $count;

                    $keyColumnValue = json_encode($keyColumnValue);
                    $this->update($keyColumnValue);
                }

                $conn->commit();

                return $count;
            } catch (Throwable $e) {
                $conn->rollback();

                ++$retries;
            }
        } while ($retries < 10);

        throw $e;
    }

    private function selectForUpdate(){
        $conn = $this->connection;
        $sql = "SELECT {$this->keyColumnName} FROM {$this->tableName} WHERE {$this->idColumnName} = :idColumnValue; FOR UPDATE";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('idColumnValue', $this->idColumnValue);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function select(){
        $conn = $this->connection;
        $sql = "SELECT {$this->keyColumnName} FROM {$this->tableName} WHERE {$this->idColumnName} = :idColumnValue;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('idColumnValue', $this->idColumnValue);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function insert($keyColumnValue){
        $conn = $this->connection;
        $sql = "INSERT INTO {$this->tableName} (`{$this->idColumnName}`, `$this->keyColumnName`) VALUES (:idColumnValue, :keyColumnValue);";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('idColumnValue', $this->idColumnValue);
        $stmt->bindValue('keyColumnValue', $keyColumnValue);
        $stmt->execute();
    }

    private function update($keyColumnValue){
        $conn = $this->connection;
        $sql =  "UPDATE `{$this->tableName}` SET `{$this->keyColumnName}` = :keyColumnValue WHERE `{$this->idColumnName}` = :idColumnValue;";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('keyColumnValue', $keyColumnValue);
        $stmt->bindValue('idColumnValue', $this->idColumnValue);
        $stmt->execute();
    }
}
