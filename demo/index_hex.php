<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';

use T2A\Captcha\Adapter\ArrayAdapter;
use T2A\Captcha\CaptchaBuilder;
use T2A\Captcha\CaptchaStorage;
use T2A\Captcha\Effect\HexagonEffect;
use T2A\Captcha\PhraseBuilder;
use T2A\Captcha\PhraseEffect\MaskPhraseEffect;
use T2A\Captcha\Tool\ImageHelper;

$phraseBuilder = new PhraseBuilder(6);

$width = 180;
$height = 60;

$color = [0x45b9b0, 0x56da98];

$firstColor = 0xffffff;
$secondColor = $color[rand(0, count($color) - 1)];

$imageHelper = new ImageHelper();
$phraseEffect = new MaskPhraseEffect(
    HexagonEffect::class,
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
    $adapter = new ArrayAdapter(__DIR__ . '/../temp/captcha');
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
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo $response;
