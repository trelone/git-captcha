<?php

namespace TRL\Captcha\PhraseEffect;

interface PhraseEffectInterface
{
    public function create(string $phrase);
}