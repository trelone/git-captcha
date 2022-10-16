<?php

namespace TRL\Captcha\Effect;

use TRL\Captcha\Model\Color;
use TRL\Captcha\Tool\ImageHelper;
use TRL\Captcha\Tool\MathHelper;

class HexagonEffect implements EffectInterface
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

        \imageantialias($this->image, true);

        $imageColor = $this->imageHelper->allocateColor(
            $this->image,
            $this->color
        );

        $blockWidth = round($this->width / $this->count);
        $prevDiameter = 0;

        $zoneId = 0;

        for ($i = 0; $i < $this->count; $i++) {
            $diameter = $this->mathHelper->rand(round($blockWidth / 2), round($blockWidth));

            $x = $this->mathHelper->rand($blockWidth * $i + $prevDiameter / 2, $blockWidth + $blockWidth * $i - $diameter / 2);

            $zones = [
                1 => $this->mathHelper->rand(0, $this->height / 3),
                2 => $this->mathHelper->rand($this->height / 3, $this->height / 3 * 2),
                3 => $this->mathHelper->rand($this->height / 3 * 2, $this->height)
            ];

            unset($zones[$zoneId]);

            $zoneId = array_rand($zones);
            $y = $zones[$zoneId];

            $this->imageHelper->drawHexagon($this->image, $x, $y, $diameter, $imageColor);

            $prevDiameter = $diameter;
        }

        \imagealphablending($this->image, true);

        return $this->image;
    }
}