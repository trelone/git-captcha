<?php

namespace TRELONE\Captcha\Effect;

interface EffectInterface
{
    /**
     * @param array $params
     *
     * @return resource
     */
    public function apply(array $params = []);
}
