<?php

namespace Deft\Twig\AopExtension\Weaving;

/**
 * This strategy will make the advice body run after the original code has run.
 */
class After implements WeavingStrategy
{
    public function weave(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
        return new \Twig_Node([$originalNode, $adviceNode]);
    }
}
