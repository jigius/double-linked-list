<?php

namespace DoubleLinkedList;

/**
 * Contract for instances that can calculate hash from theirs state. It's used for uniquence checking
 */
interface HashableInterface
{
    /**
     * Returns a hash
     * @return string
     */
    public function hash(): string;
}
