# IpStoreBundle!

IpStoreBundle is used for storing and querying IP addresses (IPv4 and IPv6). 

Application is built to support different store drivers.
The supported store drivers are:

1) Database store – this driver runs with PDO and able to store data in a defined row of a defined table.

The application can be called on different interfaces so it can be integrated into different systems

Supported interfaces:

1) REST interface based on json

Install the package with:

```console
composer require dmykos/ip-store-bundle
```

If you're *not* using Symfony Flex, you'll also
need to enable the `Dmykos\IpStoreBundle\DmykosIpStoreBundle`
in your `bundles.php` file.

## Usage

To add ip address: 

example.com/ip/add/255.255.255.0 

To show haw many times ip added:

example.com/ip/query/255.255.255.0 

## Configuration

To use bundle with Database store you should configure database connection with Doctrine and configure IpStoreBundle directly by
creating a new `config/packages/ip_store.yaml` file. The
example values are:

```yaml
# config/packages/ip_store.yaml
dmykos_ip_store:
    store_driver: dmykos_ip_store.database_store_driver
    database:
      table_name: stores
      id_column_name: store_name
      id_column_value: ip
      key_column_name: store_value
```

Specified above table name with id_column_name and key_column_name should already exist in database. 

Example:
```php
// src/Entity/Stores.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StoresRepository")
 */
class Stores
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $store_name;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $store_value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStoreName(): ?string
    {
        return $this->store_name;
    }

    public function setStoreName(string $store_name): self
    {
        $this->store_name = $store_name;

        return $this;
    }

    public function getStoreValue(): ?string
    {
        return $this->store_value;
    }

    public function setStoreValue(string $store_value): self
    {
        $this->store_value = $store_value;

        return $this;
    }
}


```

Also create new `config/routes/ip_store.yaml` file, to configure Api Controller routes:

```yaml
_ip_store:
  resource: '@DmykosIpStoreBundle/Resources/config/routes.xml'
  prefix: '/ip'
```

## Extending the IpStoreBundle

Instead of using DatabaseStoreDriver, you can create your own StoreDriver implementation:

```yaml
# config/packages/ip_store.yaml
dmykos_ip_store:
    store_driver: App\Service\DummyStoreDriver
```



```php
//src/Service/DummyStoreDriver.php


namespace App\Service;


use Dmykos\IpStoreBundle\Entity\IpModel;
use Dmykos\IpStoreBundle\StoreDriverInterface;

class DummyStoreDriver implements StoreDriverInterface
{

    public function add( IpModel $ipModel ): int {
        return 1000;
    }

    public function query( IpModel $ipModel ): int {
        return 100;
    }
}
```
