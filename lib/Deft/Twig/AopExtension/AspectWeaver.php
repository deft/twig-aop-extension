<?php

namespace Deft\Twig\AopExtension;

use Deft\Twig\AopExtension\AdviceMatching\AdviceMatcher;

class AspectWeaver implements \Twig_NodeVisitorInterface
{
    /**
     * The list of aspects that will be weaved.
     *
     * @var Aspect[]
     */
    protected $aspects;
    public function getAspects() { return $this->aspects; }

    /**
     * @param array         $aspects
     * @param AdviceMatcher $matcher
     */
    public function __construct(array $aspects, AdviceMatcher $matcher)
    {
        $this->aspects = $aspects;
        $this->matcher = $matcher;
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return array_reduce(
            $this->matcher->findMatchingAdvice($node, $this->aspects),
            [$this, 'weave'],
            $node
        );
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
        return $advice->weave($originalNode);
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
