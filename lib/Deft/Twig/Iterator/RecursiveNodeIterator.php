<?php


namespace Deft\Twig\Iterator;

use RecursiveIterator;

class RecursiveNodeIterator implements \RecursiveIterator
{
    /**
     * @var \Twig_Node
     */
    private $node;

    /**
     * @var \ArrayIterator
     */
    private $iterator;

    public function __construct(\Twig_Node $node)
    {
        $this->node = $node;
        $this->iterator = $node->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function hasChildren()
    {
        $current = $this->current();
        return method_exists($current, 'count') && $current->count() > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {
        return new self($this->current());
    }

    public function current() { return $this->iterator->current(); }
    public function next() { return $this->iterator->next(); }
    public function key() { return $this->iterator->key(); }
    public function valid() { return $this->iterator->valid(); }
    public function rewind() { return $this->iterator->rewind(); }
}
