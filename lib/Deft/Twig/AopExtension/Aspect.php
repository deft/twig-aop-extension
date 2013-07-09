<?php

namespace Deft\Twig\AopExtension;

interface Aspect
{
    /**
     * Returns a list of advice associated with this aspect.
     *
     * @return Advice[]
     */
    public function getAdvice();
}
