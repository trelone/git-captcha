<?php

namespace TRL\CaptchaEffect;

interface EffectInterface
{
    /**
     * @param array $params
     *
     * @return resource
     */
    public function apply(array $params = []);
}
