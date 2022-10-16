<?php

namespace TRELONE\Captcha;

interface PhraseBuilderInterface
{
    public function build(int $length, string $charset): string;
}