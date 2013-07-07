<?php


namespace Deft\Twig\Iterator;

use RecursiveIterator;

class NodeIterator implements \RecursiveIterator
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
        $cur = $this->current();

        return new self($cur);
    }

    public function current() {
        return $this->iterator->current();
    }
    public function next() {
        $next = $this->iterator->next();
        return $next;
    }
    public function key() { return $this->iterator->key(); }
    public function valid() { return $this->iterator->valid(); }
    public function rewind() { return $this->iterator->rewind(); }
}
