<?php

namespace Deft\Twig\AopExtension\Aop;

use Deft\Twig\AopExtension\Proceed\ProceedNode;
use Deft\Twig\Iterator\NodeIterator;

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
        $adviceSource = $this->twig->getLoader()->getSource($advice->getTemplateName());
        $adviceNode = $this->twig->parse($this->twig->tokenize($adviceSource))->getNode('body');

        return $advice->getWeavingStrategy()->weave($originalNode, $adviceNode);
    }
}
