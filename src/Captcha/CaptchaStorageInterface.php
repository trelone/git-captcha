<?php

namespace TRL\Captcha;

use TRL\Captcha\Adapter\AdapterInterface;

interface CaptchaStorageInterface
{
    public function __construct(string $key, AdapterInterface $adapter);

    public function getPhrase(): string;

    public function setPhrase(string $phrase): CaptchaStorage;

    public function getAttempts(): int;

    public function setAttempts(int $attempts): CaptchaStorage;

    public function save(): bool;

    public function delete(): bool;
}