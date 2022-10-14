<?php

namespace T2A\Captcha\Tool;

class TextHelper
{
    private $mathHelper;

    public function __construct()
    {
        $this->mathHelper = new MathHelper();
    }

    public function getFontSize(int $width, int $height): int
    {
        $fontSize = $height > $width ? $width : $height / 1.6;
        $shakeFontSize = $this->mathHelper->rand(0, round($fontSize / 3));
        $fontSize -= $this->mathHelper->rand(0, $shakeFontSize);

        return $fontSize;
    }

    public function getPosition($rangeSize, $blockSize, $shake = false) {
        $position = round($rangeSize / 2 + $blockSize / 2);

        if ($shake) {
            $shakeSize = round($rangeSize / 2 - $blockSize);
            $position += $this->mathHelper->rand(0 - $shakeSize, $shakeSize);
        }

        return $position;
    }
}
