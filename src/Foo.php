<?php
namespace DoubleLinkedList;

use LogicException;

/**
 * For test purpose only
 */
final class Foo implements HashableInterface
{
    /**
     * @var array
     */
    protected array $payload;
    
    /**
     * Cntr
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
    
    /**
     * @inheritDoc
     * @throw LogicException
     */
    public function hash(): string
    {
        if (!isset($this->payload['id'])) {
            throw new LogicException("data is corrupted");
        }
        return (string)$this->payload['id'];
    }
    
    /**
     * @return array
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
