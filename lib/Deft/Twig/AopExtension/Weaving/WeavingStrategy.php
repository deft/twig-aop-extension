<?php

namespace Deft\Twig\AopExtension\Weaving;

interface WeavingStrategy
{
    /**
     * Weaves an advice body (c.q. $adviceNode) with $originalNode and returns
     * the resulting node.
     *
     * @param \Twig_Node $originalNode
     * @param \Twig_Node $adviceNode
     * @return \Twig_Node
     */
    public function weave(\Twig_Node $originalNode, \Twig_Node $adviceNode);
}
