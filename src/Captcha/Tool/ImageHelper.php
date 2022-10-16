<?php

namespace Captcha\Tool;

use Captcha\Model\Color;

class ImageHelper
{
    /**
     * @param int $color
     * @param int $alpha
     *
     * @return Color
     */
    public function createColor(int $color, int $alpha = 0): Color
    {
        $colorModel = new Color();

        $colorModel->setRed(0xff & ($color >> 0x10));
        $colorModel->setGreen(0xff & ($color >> 0x8));
        $colorModel->setBlue(0xff & $color);
        $colorModel->setAlpha($alpha);

        return $colorModel;
    }

    /**
     * @param resource $image
     * @param Color $color
     *
     * @return int
     */
    public function allocateColor($image, Color $color): int
    {
        return \imagecolorallocatealpha(
            $image,
            $color->getRed(),
            $color->getGreen(),
            $color->getBlue(),
            $color->getAlpha()
        );
    }

    /**
     * @param int $width
     * @param int $height
     * @param Color $color
     *
     * @return false|resource
     */
    public function createLayer(int $width, int $height, Color $color)
    {
        $image = \imagecreatetruecolor($width, $height);

        $fillColor = $this->allocateColor(
            $image,
            $color
        );

        \imagefill($image, 0, 0, $fillColor);

        imagesavealpha($image, true);
        \imageantialias($image, true);

        \imagealphablending($image, true);

        return $image;
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return false|resource
     */
    public function createTransparentLayer(int $width, int $height)
    {
        $image = \imagecreatetruecolor($width, $height);

        $transparent = $this->allocateColor(
            $image,
            $this->createColor(0xffffff, 127)
        );

        \imagefill($image, 0, 0, $transparent);

        imagesavealpha($image, true);
        \imageantialias($image, true);

        \imagealphablending($image, true);

        return $image;
    }

    /**
     * @param array $layers
     * @param integer $width
     * @param integer $height
     * @param bool $destroy
     *
     * @return resource|false
     */
    public function mergeLayers(array $layers, int $width, int $height, bool $destroy = false)
    {
        $image = $layers[0];

        \imagealphablending($image, true);

        for ($i = 1; $i < count($layers); $i++) {
            \imagecopy($image, $layers[$i], 0, 0, 0, 0, $width, $height);

            if ($destroy) {
                \imagedestroy($layers[$i]);
            }
        }

        return $image;
    }

    /**
     * @param $image
     *
     * @return false|string
     */
    public function createImage($image): string
    {
        ob_start();
        \imagepng($image);
        $imageData = ob_get_contents();
        ob_end_clean();

        \imagedestroy($image);

        return $imageData;
    }

    public function drawHexagon($image, $x, $y, $diameter, $color)
    {
        $radius = round($diameter / 2);

        $fx = 0.5;
        $fy = 0.86;

        $px1 = $radius + $x;
        $py1 = $y;
        $px2 = $radius * $fx + $x;
        $py2 = $radius * $fy + $y;
        $px3 = 0 - $radius * $fx + $x;
        $py3 = $radius * $fy + $y;
        $px4 = 0 - $radius + $x;
        $py4 = $y;
        $px5 = 0 - $radius * $fx + $x;
        $py5 = 0 - $radius * $fy + $y;
        $px6 = $radius * $fx + $x;
        $py6 = 0 - $radius * $fy + $y;

        \imagefilledpolygon(
            $image,
            [
                $px1, $py1,
                $px2, $py2,
                $px3, $py3,
                $px4, $py4,
                $px5, $py5,
                $px6, $py6,
            ],
            6,
            $color
        );

        \imageline($image, $px1, $py1, $px2, $py2, $color);
        \imageline($image, $px2, $py2, $px3, $py3, $color);
        \imageline($image, $px3, $py3, $px4, $py4, $color);
        \imageline($image, $px4, $py4, $px5, $py5, $color);
        \imageline($image, $px5, $py5, $px6, $py6, $color);
        \imageline($image, $px6, $py6, $px1, $py1, $color);

        return $image;
    }
}