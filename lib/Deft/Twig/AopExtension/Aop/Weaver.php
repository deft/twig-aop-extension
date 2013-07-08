<?php

namespace Deft\Twig\AopExtension\Aop;

class Weaver
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Requires the twig environment to render advice bodies.
     *
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Takes an advice and weaves it into a given twig node.
     *
     * @param \Twig_Node $originalNode
     * @param Advice     $advice
     *
     * @return \Twig_Node
     * 
     * @throws \UnexpectedValueException
     */
    public function weave(\Twig_Node $originalNode, Advice $advice)
    {
        $adviceNode = $this->twig->parse($this->twig->tokenize(
                $this->twig->getLoader()->getSource($advice->getTemplateName())
            ))->getNode('body');

        switch ($advice->getPosition())
        {
            case Advice::POSITION_BEFORE: return $this->before($originalNode, $adviceNode);
            case Advice::POSITION_AFTER: return $this->after($originalNode, $adviceNode);
            case Advice::POSITION_AROUND: return $this->around($originalNode, $adviceNode);
            default: throw new \UnexpectedValueException(
                sprintf("%s is not supported as position", $advice->getPosition())
            );
        }
    }

    protected function before(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
            return new \Twig_Node([$adviceNode, $originalNode]);
    }

    protected function after(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
        return new \Twig_Node([$originalNode, $adviceNode]);
    }

    protected function around(\Twig_Node $originalNode, \Twig_Node $adviceNode)
    {
        throw new \Exception("Not implemented yet");
    }
}
