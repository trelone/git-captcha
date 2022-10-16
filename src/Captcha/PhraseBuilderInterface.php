<?php

namespace TRL\Captcha;

interface PhraseBuilderInterface
{
    public function build(int $length, string $charset): string;
}