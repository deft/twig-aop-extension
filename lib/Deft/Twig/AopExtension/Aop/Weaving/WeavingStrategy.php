<?php

namespace Deft\Twig\AopExtension\Aop\Weaving;

interface WeavingStrategy
{
    public function weave(\Twig_Node $originalNode, \Twig_Node $adviceNode);
}
