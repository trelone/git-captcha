<?php

namespace Captcha\Adapter;

interface AdapterInterface
{
    public function save(string $key, array $data): bool;

    public function fetch(string $key): array;

    public function delete(string $key): bool;
}
