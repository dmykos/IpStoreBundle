<?php


namespace Dmykos\IpStoreBundle;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\PDOConnection;

class PDOContainer {
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var PDOConnection
     */
    private $pdoConnection;


    public function __construct(Connection $connection) {
        $this->connection = $connection;
        $this->pdoConnection = $connection->getWrappedConnection();
    }


    /**
     * @return PDOConnection
     */
    public function getPdoConnection() {

        return $this->pdoConnection;
    }
}