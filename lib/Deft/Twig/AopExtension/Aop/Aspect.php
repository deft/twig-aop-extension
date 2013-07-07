<?php

namespace Deft\Twig\AopExtension\Aop;

interface Aspect
{
    /**
     * Returns a list of advice associated with this aspect.
     *
     * @return Advice[]
     */
    public function getAdvice();
}
