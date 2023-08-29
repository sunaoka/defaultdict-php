<?php

declare(strict_types=1);

namespace Sunaoka\DefaultDict;

/**
 * @template TKey as array-key
 * @template TValue
 *
 * @implements \ArrayAccess<TKey, TValue>
 */
class DefaultDict implements \ArrayAccess
{
    /**
     * @var array<TKey, TValue>
     */
    private $dictionary = [];

    /**
     * @var TValue
     */
    private $defaultValue;

    /**
     * @param TValue $defaultValue
     */
    public function __construct($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }

    /**
     * @param TKey $offset
     *
     * @return true
     */
    public function offsetExists($offset): bool
    {
        return true;
    }

    /**
     * @param TKey $offset
     *
     * @return TValue
     */
    #[\ReturnTypeWillChange]
    public function &offsetGet($offset)
    {
        if (array_key_exists($offset, $this->dictionary) === false) {
            if (is_callable($this->defaultValue)) {
                $this->dictionary[$offset] = ($this->defaultValue)($offset);
            } else {
                $this->dictionary[$offset] = $this->defaultValue;
            }
        }

        return $this->dictionary[$offset];
    }

    /**
     * @param TKey   $offset
     * @param TValue $value
     *
     * @return void
     *
     * @phpstan-param ?TKey $offset
     */
    public function offsetSet($offset, $value): void
    {
        $this->dictionary[$offset] = $value;
    }

    /**
     * @param TKey $offset
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->dictionary[$offset]);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        return $this->dictionary;
    }
}
