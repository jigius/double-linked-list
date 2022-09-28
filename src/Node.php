<?php
/**
 * This file is part of the jigius/double-linked-list library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) 2022 Jigius <jigius@gmail.com>
 * @link https://github.com/jigius/double-linked-list GitHub
 */
namespace DoubleLinkedList;

use LogicException;

/**
 * Implementation of Node contract
 */
final class Node implements NodeInterface
{
    /**
     * @var array
     */
    private array $i;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->i = [
            'prev' => null,
            'next' => null
        ];
    }
    
    /**
     * @inheritDoc
     */
    public function prev(): ?NodeInterface
    {
        return $this->i['prev'];
    }
    
    /**
     * @inheritDoc
     */
    public function next(): ?NodeInterface
    {
        return $this->i['next'];
    }
    
    /**
     * @inheritDoc
     * @throw LogicException
     */
    public function payload(): HashableInterface
    {
        if (!$this->i['payload']) {
            throw new LogicException("payload's data is not defined yet :(");
        }
        return $this->i['payload'];
    }
    
    /**
     * @inheritDoc
     */
    public function mutatePayload(HashableInterface $data): void
    {
       $this->i['payload'] = $data;
    }
    
    /**
     * @inheritDoc
     */
    public function mutateNext(?NodeInterface $next = null): void
    {
        $this->i['next'] = $next;
    }
    
    /**
     * @inheritDoc
     */
    public function mutatePrev(?NodeInterface $next = null): void
    {
        $this->i['prev'] = $next;
    }
    
    /**
     * @inheritDoc
     */
    public function blueprinted(): self
    {
        $that = new self();
        $that->i = $this->i;
        return $that;
    }
}
