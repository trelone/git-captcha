<?php

namespace Captcha\Adapter;

use Exception;
use Memcached;

class MemcachedAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var int
     */
    private $expiration;

    /**
     * @var Memcached
     */
    private $memcached;

    /**
     * @param string $host
     * @param int $port
     * @param int $expiration
     */
    public function __construct(string $host, int $port, int $expiration = 0)
    {
        $this->host = $host;
        $this->port = $port;
        $this->expiration = $expiration;

        $this->memcached = new Memcached();
        $this->memcached->addServer($this->host, $this->port);
    }

    /**
     * @param string $key
     * @param array $data
     *
     * @return bool
     * @throws Exception
     */
    public function save(string $key, array $data): bool
    {
        $data = serialize($data);

        if (!$this->memcached->setByKey('captcha', $key, $data, $this->expiration)) {
            throw new Exception('Unable to save captcha data.');
        }

        return true;
    }

    /**
     * @param string $key
     *
     * @return array
     */
    public function fetch(string $key): array
    {
        $data = $this->memcached->getByKey('captcha', $key);

        if (!$data) {
            return [];
        }

        return unserialize($data);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        return $this->memcached->deleteByKey('captcha', $key);
    }
}
