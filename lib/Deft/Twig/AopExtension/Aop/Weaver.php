<?php

namespace Deft\Twig\AopExtension\Aop;

class Weaver
{
    public function before(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
            return new \Twig_Node([$adviceNode, $originalNode]);
    }

    public function after(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
        return new \Twig_Node([$originalNode, $adviceNode]);
    }

    public function around(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
        throw new \Exception("Not implemented yet");
    }
}
