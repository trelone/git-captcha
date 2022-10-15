<?php


namespace CAPTCHA\Captcha\Tool;


class MathHelper
{
    /**
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public function rand(int $min, int $max): int
    {
        return rand($min, $max);
    }
}
