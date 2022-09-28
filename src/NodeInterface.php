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

/**
 * Node contract that is used by List's items
 */
interface NodeInterface
{
    /**
     * Returns a next node the current is linked with
     * @return NodeInterface|null
     */
    public function prev(): ?NodeInterface;
    
    /**
     * Returns a previous node the current is linked with
     * @return NodeInterface|null
     */
    public function next(): ?NodeInterface;
    
    /**
     * Returns a value data
     * @return HashableInterface
     */
    public function payload(): HashableInterface;
    
    /**
     * Mutates the state of the instance! Defines a value data
     * @param HashableInterface $data
     */
    public function mutatePayload(HashableInterface $data): void;
    
    /**
     * Mutates the state of the instance! Links the instance with a next node
     * @param NodeInterface|null $next
     */
    public function mutateNext(?NodeInterface $next = null): void;
    
    /**
     * Mutates the state of the instance! Links the instance with a previous node
     * @param NodeInterface|null $next
     */
    public function mutatePrev(?NodeInterface $next = null): void;
    
    /**
     * Clones the instance
     * @return NodeInterface
     */
    public function blueprinted(): NodeInterface;
}
