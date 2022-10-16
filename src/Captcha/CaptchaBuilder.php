<?php

namespace TRELONE\Captcha;

use TRELONE\Captcha\Model\LayerStack;
use TRELONE\Captcha\PhraseEffect\PhraseEffectInterface;
use TRELONE\Captcha\PhraseEffect\SimplePhraseEffect;
use TRELONE\Captcha\Tool\ImageHelper;

class CaptchaBuilder implements CaptchaBuilderInterface
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
     * @var string
     */
    protected $phrase;

    /**
     * @var int
     */
    protected $firstColor;
    /**
     * @var int
     */
    protected $secondColor;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var PhraseEffectInterface|null
     */
    protected $phraseEffect;

    /**
     * @param int $width
     * @param int $height
     * @param string $phrase
     * @param PhraseEffectInterface|null $phraseEffect
     */
    public function __construct(
        int $width,
        int $height,
        string $phrase = '',
        ?PhraseEffectInterface $phraseEffect = null
    )
    {
        $this->width = $width;
        $this->height = $height;
        $this->phrase = $phrase;

        $this->firstColor = 0x000000;
        $this->secondColor = 0xffffff;

        $this->imageHelper = new ImageHelper();

        $this->phraseEffect = $phraseEffect;
    }

    /**
     * @param string $phrase
     *
     * @return CaptchaBuilder
     */
    public function setPhrase(string $phrase): self
    {
        $this->phrase = $phrase;

        return $this;
    }

    /**
     * @param int $width
     *
     * @return CaptchaBuilder
     */
    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param int $height
     *
     * @return CaptchaBuilder
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @param int $firstColor
     *
     * @return CaptchaBuilder
     */
    public function setFirstColor(int $firstColor): self
    {
        $this->firstColor = $firstColor;

        return $this;
    }

    /**
     * @param int $secondColor
     *
     * @return CaptchaBuilder
     */
    public function setSecondColor(int $secondColor): self
    {
        $this->secondColor = $secondColor;

        return $this;
    }

    /**
     * @return string
     */
    public function build(): string
    {
        $layerStack = new LayerStack();

        $backgroundLayer = $this->imageHelper->createLayer(
            $this->width,
            $this->height,
            $this->imageHelper->createColor($this->secondColor)
        );

        if ($backgroundLayer) {
            $layerStack->push($backgroundLayer);
        }

        if (!is_object($this->phraseEffect) || !($this->phraseEffect instanceof PhraseEffectInterface)) {
            $this->phraseEffect = new SimplePhraseEffect(
                $this->width,
                $this->height,
                $this->imageHelper->createColor($this->firstColor)
            );
        }

        $phraseLayer = $this->phraseEffect->create($this->phrase);

        if ($phraseLayer) {
            $layerStack->push($phraseLayer);
        }

        $image = $this->imageHelper->mergeLayers($layerStack->getStack(), $this->width, $this->width, true);
        $captcha = $this->imageHelper->createImage($image);

        return $captcha;
    }
}