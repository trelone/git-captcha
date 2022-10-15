<?php

namespace CAPTCHA\Captcha\Effect;

use CAPTCHA\Captcha\Model\Color;
use CAPTCHA\Captcha\Tool\ImageHelper;
use CAPTCHA\Captcha\Tool\MathHelper;

class LineEffect implements EffectInterface
{
    /**
     * @var
     */
    protected $image;

    /**
     * @var Color
     */
    protected $color;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var MathHelper
     */
    protected $mathHelper;

    /**
     * @param $image
     * @param Color $color
     * @param int $width
     * @param int $height
     * @param int $count
     */
    public function __construct($image, Color $color, int $width, int $height, int $count = 4)
    {
        $this->image = $image;
        $this->color = $color;
        $this->width = $width;
        $this->height = $height;
        $this->count = $count;

        $this->imageHelper = new ImageHelper();
        $this->mathHelper = new MathHelper();
    }

    /**
     * @param array $params
     *
     * @return resource
     */
    public function apply(array $params = [])
    {
        \imagealphablending($this->image, false);

        $imageColor = $this->imageHelper->allocateColor(
            $this->image,
            $this->color
        );

        $thickness = $params['thickness'] ?? ceil($this->width / ($this->count - 1) / 2);
        $space = $params['space'] ?? $thickness;

        $offset = $this->height - $thickness;
        $x1 = 0 - $offset - $this->mathHelper->rand(0, $thickness);
        $y1 = 0;
        $x2 = $this->height + $x1;
        $y2 = $this->height;

        for ($i = 0; $i < $this->count + 1; $i++) {
            \imageline($this->image, $x1, $y1, $x2, $y2, $imageColor);
            \imageline($this->image, $x1 + $thickness, $y1, $x2 + $thickness, $y2, $imageColor);

            \imagefilledpolygon(
                $this->image,
                [
                    $x1, $y1,
                    $x1 + $thickness, $y1,
                    $x2 + $thickness, $y2,
                    $x2, $y2
                ],
                4,
                $imageColor
            );

            $x1 += $thickness + $space;
            $x2 += $thickness + $space;
        }

        \imagealphablending($this->image, true);

        return $this->image;
    }
}