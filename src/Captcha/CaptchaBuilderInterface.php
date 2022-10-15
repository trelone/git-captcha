<?php

namespace CAPTCHA\Captcha;

interface CaptchaBuilderInterface
{
    public function __construct(int $width, int $height, string $phrase = '');

    public function build(): string;

    public function setPhrase(string $phrase): CaptchaBuilder;

    public function setWidth(int $width): CaptchaBuilder;

    public function setHeight(int $height): CaptchaBuilder;

    public function setFirstColor(int $firstColor): CaptchaBuilder;

    public function setSecondColor(int $secondColor): CaptchaBuilder;
}
