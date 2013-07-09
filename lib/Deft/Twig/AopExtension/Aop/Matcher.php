<?php

namespace Deft\Twig\AopExtension\Aop;

class Matcher
{
    /**
     * Find the advice within $aspects which's pointcuts match the given node.
     *
     * @param \Twig_Node $node
     * @param array      $aspects
     *
     * @return Advice[]
     */
    public function findAdvice(\Twig_Node $node, array $aspects)
    {
        $adviceFilter = function (Advice $advice) use ($node) {
            return $advice->matches($node);
        };

        $reducer = function (&$advice, $aspect) use ($adviceFilter) {
            return array_merge($advice, array_filter($aspect->getAdvice(), $adviceFilter));
        };

        return array_reduce($aspects, $reducer, []);
    }
}
