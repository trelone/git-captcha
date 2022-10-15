<?php

namespace CAPTCHA\Captcha\PhraseEffect;

use CAPTCHA\Captcha\Model\Color;
use CAPTCHA\Captcha\Tool\ImageHelper;
use CAPTCHA\Captcha\Tool\MathHelper;
use CAPTCHA\Captcha\Tool\TextHelper;

class SimplePhraseEffect implements PhraseEffectInterface
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var Color
     */
    protected $color;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var MathHelper
     */
    protected $mathHelper;

    /**
     * @var TextHelper
     */
    protected $textHelper;

    /**
     * @param int $width
     * @param int $height
     * @param Color $color
     */
    public function __construct(int $width, int $height, Color $color)
    {
        $this->width = $width;
        $this->height = $height;
        $this->color = $color;

        $this->imageHelper = new ImageHelper();
        $this->mathHelper = new MathHelper();
        $this->textHelper = new TextHelper();
    }

    /**
     * @param string $phrase
     *
     * @return false|resource
     */
    public function create(string $phrase)
    {
        $length = mb_strlen($phrase);

        if ($length === 0) {
            return false;
        }

        $image = $this->imageHelper->createTransparentLayer($this->width, $this->width);

        $font = dirname(__DIR__) . '/Font/captcha.ttf';

        $indent = round($this->width * 5 / 100);
        $width = $this->width - $indent * 2;
        $blockWidth = round($width / $length);
        $symbols = str_split($phrase);

        $x = round($indent * 1.5);

        foreach ($symbols as $symbol) {
            $fontSize = $this->textHelper->getFontSize($blockWidth, $this->height);

            $angle = $this->mathHelper->rand(-45, 45);

            $y = $this->textHelper->getPosition($this->height, $fontSize, true);

            $textColor = $this->imageHelper->allocateColor(
                $image,
                $this->color->setAlpha($this->mathHelper->rand(20, 100))
            );

            \imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $font, $symbol);

            $x += $blockWidth;
        }

        return $image;
    }
}