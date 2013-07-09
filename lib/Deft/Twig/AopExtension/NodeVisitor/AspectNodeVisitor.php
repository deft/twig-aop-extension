<?php

namespace Deft\Twig\AopExtension\NodeVisitor;

use Deft\Twig\AopExtension\Aop\Matcher;
use Deft\Twig\AopExtension\Aop\Weaver;
use Deft\Twig\AopExtension\Aop\Advice;
use Deft\Twig\AopExtension\Aop\Aspect;

class AspectNodeVisitor implements \Twig_NodeVisitorInterface
{
    /**
     * The list of aspects that will be weaved.
     *
     * @var Aspect[]
     */
    protected $aspects;
    public function getAspects() { return $this->aspects; }

    /**
     * Takes care of the actual weaving of advice into the twig node structure.
     *
     * @var Weaver
     */
    protected $weaver;

    /**
     * @param Aspect[] $aspects
     * @param Weaver   $weaver
     * @param Matcher  $matcher
     */
    public function __construct(array $aspects, Weaver $weaver, Matcher $matcher)
    {
        $this->aspects = $aspects;
        $this->weaver = $weaver;
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
            $this->matcher->findAdvice($node, $this->aspects),
            [$this->weaver, 'weave'],
            $node
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
