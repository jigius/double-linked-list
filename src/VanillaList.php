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

use Exception;
use OutOfBoundsException;

final class VanillaList implements ListInterface
{
    /**
     * @var array
     */
    private array $i;
    /**
     * @var NodeInterface
     */
    private NodeInterface $node;
    
    /**
     * Cntr
     * @param NodeInterface|null $node
     * @throws Exception
     */
    public function __construct(?NodeInterface $node = null)
    {
        $this->node = $node ?? new Node();
        $this->i = [
            'hashes' => []
        ];
    }
    
    /**
     * Appends to the tail of the list
     * @inheritDoc
     */
    public function with(HashableInterface $payload): void
    {
        $hash = $payload->hash();
        if (isset($this->i['hashes'][$hash])) {
            /* updates an existed */
            $this->i['hashes'][$hash]->mutatePayload($payload);
        } else {
            /* appends a new to the tail */
            $node = $this->node->blueprinted();
            $node->mutatePayload($payload);
            if ($this->empty()) {
                $this->i['head'] = $this->i['tail'] = $node;
            } else {
                $node->mutatePrev($this->i['tail']);
                $this->i['tail']->mutateNext($node);
                $this->i['tail'] = $node;
            }
            $this->i['hashes'][$hash] = $node;
        }
    }
    
    /**
     * @inheritDoc
     */
    public function without(string $hash): void
    {
        if (isset($this->i['hashes'][$hash])) {
            $node = $this->i['hashes'][$hash];
            /**
             * @var NodeInterface $node
             */
            if ($node->next() && $node->prev()) {
                $node->prev()->mutateNext($node->next());
                $node->next()->mutatePrev($node->prev());
            } elseif ($node->next()) {
                $node->next()->mutatePrev();
                $this->i['head'] = $node->next();
            } elseif ($node->prev()) {
                $node->prev()->mutateNext();
                $this->i['tail'] = $node->prev();
            } else {
                $this->i['head'] = $this->i['tail'] = null;
            }
            unset($this->i['hashes'][$hash]);
        }
    }
    
    /**
     * @inheritDoc
     */
    public function known(string $hash): bool
    {
        return isset($this->i['hashes'][$hash]);
    }
    
    /**
     * @param string $hash
     * @return HashableInterface
     */
    public function fetch(string $hash): HashableInterface
    {
        if (!$this->known($hash)) {
            throw new OutOfBoundsException("there is no value for a defined hash");
        }
        return $this->i['hashes'][$hash]->payload();
    }
    
    /**
     * @inheritDoc
     * @throw OutOfBoundsException
     */
    public function exchange(string $hashOne, string $hashTwo): void
    {
        if ($hashOne === $hashTwo) {
            return;
        }
        if (!$this->known($hashOne) || !$this->known($hashTwo)) {
            throw new OutOfBoundsException();
        }
        $t = $this->fetch($hashOne);
        $this->i['hashes'][$hashOne]->mutatePayload($this->i['hashes'][$hashTwo]->payload());
        $this->i['hashes'][$hashTwo]->mutatePayload($t);
        $nodeOne = $this->i['hashes'][$hashOne];
        $this->i['hashes'][$hashOne] = $this->i['hashes'][$hashTwo];
        $this->i['hashes'][$hashTwo] = $nodeOne;
    }
    
    /**
     * @inheritDoc
     */
    public function head(): NodeInterface
    {
        if (!isset($this->i['head'])) {
            throw new OutOfBoundsException();
        }
        return $this->i['head'];
    }
    
    /**
     * @inheritDoc
     */
    public function tail(): NodeInterface
    {
        if (!isset($this->i['tail'])) {
            throw new OutOfBoundsException();
        }
        return $this->i['tail'];
    }
    
    /**
     * @inheritDoc
     */
    public function empty(): bool
    {
        return count($this->i['hashes']) === 0;
    }
    
    /**
     * @inheritDoc
     */
    public function reverse(): void
    {
        if (!$this->empty() && $this->head()->next()) {
            $node = $this->head();
            do {
                $next = $node->next();
                $node->mutateNext($node->prev());
                $node->mutatePrev($next);
            } while ($node = $node->prev());
            $head = $this->i['head'];
            $this->i['head'] = $this->i['tail'];
            $this->i['tail'] = $head;
        }
    }
}
