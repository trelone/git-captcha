<?php

namespace TRELONE\Captcha\PhraseEffect;

use TRELONE\Captcha\Effect\EffectInterface;
use TRELONE\Captcha\Model\Color;
use TRELONE\Captcha\Tool\ImageHelper;
use TRELONE\Captcha\Tool\MathHelper;
use TRELONE\Captcha\Tool\TextHelper;

class MaskPhraseEffect implements PhraseEffectInterface
{
    /**
     * @var EffectInterface
     */
    private $effect;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

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
     * @var Color
     */
    private $firstColor;

    /**
     * @var Color
     */
    private $secondColor;

    /**
     * @var int
     */
    private $count;

    /**
     * @param string $effect
     * @param int $width
     * @param int $height
     * @param Color $firstColor
     * @param Color $secondColor
     */
    public function __construct(string $effect, int $width, int $height, Color $firstColor, Color $secondColor)
    {
        $this->effect = $effect;
        $this->width = $width;
        $this->height = $height;
        $this->firstColor = $firstColor;
        $this->secondColor = $secondColor;
        $this->count = 4;

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

        $firstImage = $this->imageHelper->createTransparentLayer($this->width, $this->width);
        $secondImage = $this->imageHelper->createLayer($this->width, $this->width, $this->firstColor);

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
                $firstImage,
                $this->firstColor
            );

            \imagettftext($firstImage, $fontSize, $angle, $x, $y, $textColor, $font, $symbol);

            $textColor = $this->imageHelper->allocateColor(
                $secondImage,
                $this->secondColor
            );

            \imagettftext($secondImage, $fontSize, $angle, $x, $y, $textColor, $font, $symbol);

            $x += $blockWidth;
        }

        $color = clone $this->secondColor;

        /**
         * @var EffectInterface $effect
         */
        $effect = new $this->effect(
            $secondImage,
            $color->setAlpha(127),
            $this->width,
            $this->height,
            $this->count
        );

        $secondImage = $effect->apply();

        $image = $this->imageHelper->mergeLayers([$firstImage, $secondImage], $this->width, $this->height, true);

        return $image;
    }

    public function setCountEffectElements(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
