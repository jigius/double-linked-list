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
use InvalidArgumentException;

/**
 * Abstract implementation of List contract
 */
abstract class AbstractList implements ListInterface
{
    /**
     * @var array
     */
    protected array $i;
    /**
     * @var NodeInterface
     */
    protected NodeInterface $node;
    
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
     * @inheritDoc
     * @throws InvalidArgumentException|OutOfBoundsException
     */
    public function with(HashableInterface $payload, ?string $after = null): void
    {
        $hash = $payload->hash();
        if ($hash === $after) {
            throw new InvalidArgumentException("`after` value equals to the hash for passed `payload`");
        }
        if ($this->known($hash) && !$after) {
            $this->fetch($hash)->mutatePayload($payload);
            return;
        }
        if ($this->known($hash)) {
            /* updates an existed */
            $node = $this->fetch($hash);
            $this->without($hash);
        } else {
            /* appends a new */
            $node = $this->node->blueprinted();
        }
        $node->mutatePayload($payload);
        if ($this->empty()) {
            $this->i['head'] = $this->i['tail'] = $node;
        } else {
            if ($after === null) {
                $sibling = $this->i['tail'];
            } else {
                if (!$this->known($after)) {
                    throw new OutOfBoundsException("node with hash=`$after` is unknown");
                }
                $sibling = $this->i['hashes'][$after];
            }
            $node->mutatePrev($sibling);
            $node->mutateNext($sibling->next());
            if ($sibling->next()) {
                $sibling->next()->mutatePrev($node);
            } else {
                $this->i['tail'] = $node;
            }
            $sibling->mutateNext($node);
    
        }
        $this->i['hashes'][$hash] = $node;
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
     * @inheritDoc
     */
    abstract public function fetch(string $hash): NodeInterface;
    
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
        $t = $this->fetch($hashOne)->payload();
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
