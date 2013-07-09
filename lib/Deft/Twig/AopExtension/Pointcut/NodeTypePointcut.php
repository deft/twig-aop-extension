<?php

namespace Deft\Twig\AopExtension\Pointcut;

/**
 * Checks whether the given node is an instance of the configured type.
 */
class NodeTypePointcut implements Pointcut
{
    /**
     * The class name of the node that should be matched. Can also be the name
     * of a super class or interface.
     *
     * @var string
     */
    private $nodeType;

    public function __construct($nodeType)
    {
        $this->nodeType = $nodeType;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(\Twig_Node $node)
    {
        return $node instanceof $this->nodeType;
    }
}
