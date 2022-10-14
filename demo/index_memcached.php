<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use T2A\Captcha\Adapter\MemcachedAdapter;
use T2A\Captcha\CaptchaBuilder;
use T2A\Captcha\CaptchaStorage;
use T2A\Captcha\PhraseBuilder;
use T2A\Captcha\PhraseEffect\LinePhraseEffect;
use T2A\Captcha\Tool\ImageHelper;

$phraseBuilder = new PhraseBuilder(6);

$width = 180;
$height = 40;

$color = [0xff7435, 0x00abff, 0x1dc79e];

$firstColor = $color[rand(0, count($color) - 1)];
$secondColor = 0xffffff;

$imageHelper = new ImageHelper();
$phraseEffect = new LinePhraseEffect(
    $width,
    $height,
    $imageHelper->createColor($firstColor),
    $imageHelper->createColor($secondColor)
);

$phrase = $phraseBuilder->build();

$captchaBuilder = new CaptchaBuilder($width, $height, $phrase, $phraseEffect);

$captchaBuilder->setFirstColor($firstColor);
$captchaBuilder->setSecondColor($secondColor);

try {
    $adapter = new MemcachedAdapter('127.0.0.1', 11211, 60);
} catch (Exception $e) {
    die($e->getMessage());
}

$storage = new CaptchaStorage(md5($_SERVER['REMOTE_ADDR']), $adapter);
$storage->setPhrase($phrase);

try {
    $storage->save();
} catch (Exception $e) {
    die($e->getMessage());
}

$response = $captchaBuilder->build();

header("Content-type: image/png");
echo $response;
