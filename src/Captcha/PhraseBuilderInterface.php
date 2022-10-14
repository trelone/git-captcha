<?php

namespace T2A\Captcha;

interface PhraseBuilderInterface
{
    public function build(int $length, string $charset): string;
}