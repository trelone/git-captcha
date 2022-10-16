<?php

namespace TRL\Captcha\Adapter;

use Exception;

class ArrayAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    private $tempDir;

    /**
     * @param string $tempDir
     *
     * @throws Exception
     */
    public function __construct(string $tempDir)
    {
        $this->tempDir = $tempDir;

        if (!$this->isExistsDirectory($this->tempDir)) {
            throw new Exception('Captcha temp directory is not exists. Create directory "'
                . $this->tempDir . '".');
        }

        if (!is_writable($this->tempDir)) {
            throw new Exception('Captcha temp directory is not writable. Change permissions for a directory "'
                . $this->tempDir . '".');
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    protected function isExistsDirectory(string $path): bool
    {
        if (!file_exists($path) || !is_dir($path)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $tempDir
     *
     * @return ArrayAdapter
     */
    public function setTempDir(string $tempDir): self
    {
        $this->tempDir = $tempDir;

        return $this;
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
        $file = $this->tempDir . '/' . $key;
        $data = serialize($data);

        if (!@file_put_contents($file, $data)) {
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
        $file = $this->tempDir . '/' . $key;

        if (!file_exists($file)) {
            return [];
        }

        return unserialize(file_get_contents($file));
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        $file = $this->tempDir . '/' . $key;

        if (!file_exists($file)) {
            return true;
        }

        return unlink($file);
    }
}
