<?php

namespace Deft\Twig\AopExtension\Weaving;

use Deft\Twig\Iterator\RecursiveNodeIterator;

/**
 * This strategy replaces the original node with the advice node. It's still
 * possible to execute the original code by using the 'proceed'-tag, which will
 * be replaced by the original node during weaving. See the example below:
 *
 * <code>
 * {% if security_check() %}
 *   {% proceed %}
 * {% endif %}
 * </code>
 */
class Around implements WeavingStrategy
{
    public function weave(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
        $recursiveNodeIterator = new \RecursiveIteratorIterator(new RecursiveNodeIterator($adviceNode), 1);

        foreach ($recursiveNodeIterator as $node)
        {
            if (!$node instanceof \Twig_Node) continue;
            foreach ($node as $name => $subNode)
            {
                if ($subNode instanceof ProceedNode) {
                    $node->setNode($name, $originalNode);
                }
            }
        }

        return $adviceNode;
    }
}
