<?php

namespace Deft\Twig\AopExtension\Weaving;

/**
 * This strategy will prepend the advice code to the original node code.
 */
class Before implements WeavingStrategy
{
    public function weave(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
        return new \Twig_Node([$adviceNode, $originalNode]);
    }
}
