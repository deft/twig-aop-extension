<?php

namespace Deft\Twig\AopExtension\AdviceMatching;

use Deft\Twig\AopExtension\Advice;

interface AdviceMatcher
{
    /**
     * Find the advice within $aspects which's pointcuts match the given node.
     *
     * @param \Twig_Node $node
     * @param array      $aspects
     *
     * @return Advice[]
     */
    public function findMatchingAdvice(\Twig_Node $node, array $aspects);
}
