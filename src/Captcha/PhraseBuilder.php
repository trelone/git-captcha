<?php

namespace T2A\Captcha;

use T2A\Captcha\Tool\MathHelper;

class PhraseBuilder implements PhraseBuilderInterface
{
    /**
     * @var int
     */
    private $length;

    /**
     * @var string
     */
    private $charset;

    /**
     * @var MathHelper
     */
    private $mathHelper;

    /**
     * @param int $length
     * @param string $charset
     */
    public function __construct(int $length = 6, string $charset = '123456789abcdefghijklmnpqrstuvwxyz')
    {
        $this->length = $length;
        $this->charset = $charset;

        $this->mathHelper = new MathHelper();
    }

    /**
     * @param int|null $length
     * @param string|null $charset
     *
     * @return string
     */
    public function build(int $length = null, string $charset = null): string
    {
        if ($length) {
            $this->length = $length;
        }

        if ($charset) {
            $this->charset = $charset;
        }

        $phrase = '';

        $lookupTable = str_split($this->charset);
        $lookupTableMax = count($lookupTable) - 1;

        for ($i = 0; $i < $this->length; $i++) {
            $phrase .= $lookupTable[$this->mathHelper->rand(0, $lookupTableMax)];
        }

        return $phrase;
    }
}