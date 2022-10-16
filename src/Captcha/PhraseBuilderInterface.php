<?php

namespace Captcha;

interface PhraseBuilderInterface
{
    public function build(int $length, string $charset): string;
}