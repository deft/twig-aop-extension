<?php

namespace Deft\Twig\AopExtension\AdviceMatching;

use Deft\Twig\AopExtension\Advice;
use Deft\Twig\AopExtension\Aspect;

class DefaultAdviceMatcher implements AdviceMatcher
{
    /**
     * {@inheritdoc}
     */
    public function findMatchingAdvice(\Twig_Node $node, array $aspects)
    {
        $adviceFilter = function (Advice $advice) use ($node) {
            return $advice->matches($node);
        };

        $reducer = function (array &$advice, Aspect $aspect) use ($adviceFilter) {
            return array_merge(
                $advice,
                array_filter($aspect->getAdvice(), $adviceFilter)
            );
        };

        return array_reduce($aspects, $reducer, []);
    }
}
