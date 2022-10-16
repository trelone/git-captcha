<?php

namespace TRELONE\Captcha\Model;

class LayerStack
{
    /**
     * @var array
     */
    private $stack;

    /**
     * @param array $stack
     */
    public function __construct(array $stack = [])
    {
        $this->stack = $stack;
    }

    /**
     * @return resource
     */
    public function pop()
    {
        return array_pop($this->stack);
    }

    /**
     * @param resource $layer
     */
    public function push($layer): void
    {
        array_push($this->stack, $layer);
    }

    /**
     * @param int $index
     *
     * @return resource
     */
    public function getItem(int $index)
    {
        return $this->stack[$index];
    }

    /**
     * @return array
     */
    public function getStack(): array
    {
        return $this->stack;
    }

    /**
     * @param array $stack
     *
     * @return LayerStack
     */
    public function setStack(array $stack): self
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->stack);
    }
}
