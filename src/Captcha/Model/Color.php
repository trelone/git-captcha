<?php

namespace TRL\Captcha\Model;

class Color
{
    /**
     * @var int
     */
    private $red;
    /**
     * @var int
     */
    private $green;
    /**
     * @var int
     */
    private $blue;

    /**
     * @var int
     */
    private $alpha;

    public function __construct()
    {
        $this->alpha = 0;
    }

    /**
     * @return int
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * @param int $red
     *
     * @return Color
     */
    public function setRed(int $red): self
    {
        $this->red = $red;

        return $this;
    }

    /**
     * @return int
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * @param int $green
     *
     * @return Color
     */
    public function setGreen(int $green): self
    {
        $this->green = $green;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlue(): int
    {
        return $this->blue;
    }

    /**
     * @param mixed $blue
     *
     * @return Color
     */
    public function setBlue(int $blue): self
    {
        $this->blue = $blue;

        return $this;
    }

    /**
     * @return int
     */
    public function getAlpha(): int
    {
        return $this->alpha;
    }

    /**
     * @param int $alpha
     *
     * @return Color
     */
    public function setAlpha(int $alpha): self
    {
        $this->alpha = $alpha;

        return $this;
    }
}
