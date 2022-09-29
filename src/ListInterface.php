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
 * List contract
 */
interface ListInterface
{
    /**
     * Appends a value data into the list
     * @param HashableInterface $payload
     */
    public function with(HashableInterface $payload): void;
    
    /**
     * Removes a node from the list
     * @param string $hash
     */
    public function without(string $hash): void;
    
    /**
     * Does the exchanging places
     * @param string $hashOne
     * @param string $hashTwo
     */
    public function exchange(string $hashOne, string $hashTwo);
    
    /**
     * Returns the first node of the list
     * @return NodeInterface
     * @throw OutOfBoundException
     */
    public function head(): NodeInterface;
    
    /**
     * Returns the last node of the list
     * @return NodeInterface
     * @throw OutOfBoundException
     */
    public function tail(): NodeInterface;
    
    /**
     * Returns a sign if the list is empty
     * @return bool
     */
    public function empty(): bool;
    
    /**
     * Reorders nodes in reverse order
     * @return void
     */
    public function reverse(): void;
}
