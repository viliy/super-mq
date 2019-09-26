# Supervisor - XmlRpc


* PHP >= 7.1

## install 

```shell

composer require zhaqq/xml-rpc

```

##  Example

```php

use Zhaqq\XmlRpc\Client;

require __DIR__ . '/vendor/autoload.php';

$config = [
    'dns' => '192.168.10.10:9001',
    'username' => 'www',
    'password' => '123'
];

$processName = 'swoole:swoole_0';
$processName = 'swoole1';

try {
    $supervisorMonitor = new Client($config['dns'], $config['username'], $config['password']);
    $data1 = $supervisorMonitor->getProcessInfo($processName);
    var_dump($data1);
} catch (\Exception $exception) {
    var_dump($exception->getMessage());
}

```

## License MIT