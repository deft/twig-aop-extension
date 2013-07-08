<?php

namespace Deft\Twig\AopExtension\Aop;

interface Pointcut
{
    /**
     * Determines whether a given join point (Twig node) should be picked out.
     *
     * @param \Twig_Node $node
     *
     * @return bool
     */
    public function matches(\Twig_Node $node);
}
