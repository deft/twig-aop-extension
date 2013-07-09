<?php

namespace Deft\Twig\AopExtension\NodeProvider;

/**
 * Receives a twig node that can be directly used by the weaver.
 */
class DirectNodeProvider implements NodeProvider
{
    /**
     * @var \Twig_Node
     */
    private $node;

    public function __construct(\Twig_Node $node)
    {
        $this->node = $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getNode()
    {
        return $this->node;
    }
}
