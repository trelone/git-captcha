<?php

namespace Captcha\Effect;

use Captcha\Model\Color;
use Captcha\Tool\ImageHelper;
use Captcha\Tool\MathHelper;

class CircleEffect implements EffectInterface
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

        for ($i = 0; $i < $this->count; $i++) {
            $circleColor = $this->imageHelper->allocateColor(
                $this->image,
                $this->color
            );

            $diameter = $this->mathHelper->rand(10, 24);

            \imagefilledellipse(
                $this->image,
                $this->mathHelper->rand(0, $this->width),
                $this->mathHelper->rand(0, $this->height),
                $diameter,
                $diameter,
                $circleColor
            );
        }

        \imagealphablending($this->image, true);

        return $this->image;
    }
}