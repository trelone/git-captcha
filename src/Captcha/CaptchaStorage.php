<?php

namespace Captcha;

use Captcha\Adapter\AdapterInterface;

class CaptchaStorage implements CaptchaStorageInterface
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $data;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param string $key
     * @param AdapterInterface $adapter
     */
    public function __construct(string $key, AdapterInterface $adapter)
    {
        $this->key = $key;
        $this->adapter = $adapter;

        $this->data = $this->adapter->fetch($this->key);
    }

    /**
     * @return string
     */
    public function getPhrase(): string
    {
        return $this->data['phrase'] ?? '';
    }

    /**
     * @param string $phrase
     *
     * @return $this
     */
    public function setPhrase(string $phrase): self
    {
        $this->data['phrase'] = $phrase;

        return $this;
    }

    /**
     * @return int
     */
    public function getAttempts(): int
    {
        return $this->data['attempts'] ?? 0;
    }

    /**
     * @param int $attempts
     *
     * @return $this
     */
    public function setAttempts(int $attempts): self
    {
        $this->data['attempts'] = $attempts;

        return $this;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        return $this->adapter->save($this->key, $this->data);
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        return $this->adapter->delete($this->key);
    }
}
