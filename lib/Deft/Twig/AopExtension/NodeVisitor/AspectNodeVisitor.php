<?php

namespace Deft\Twig\AopExtension\NodeVisitor;

use Deft\Twig\AopExtension\Aop\Weaver;
use Deft\Twig\AopExtension\Aop\Advice;

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
     * @param array $aspects
     */
    public function __construct(array $aspects, Weaver $weaver)
    {
        $this->aspects = $aspects;
        $this->weaver = $weaver;
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
        foreach ($this->aspects as $aspect) {
            foreach ($aspect->getAdvice() as $advice) {
                if ($advice->matches($node)) {
                    $adviceNode = $env->parse($env->tokenize(
                            $env->getLoader()->getSource($advice->getTemplateName())
                        ))->getNode('body');

                    switch ($advice->getPosition())
                    {
                        case Advice::POSITION_BEFORE: return $this->weaver->before($node, $adviceNode);
                        case Advice::POSITION_AFTER: return $this->weaver->after($node, $adviceNode);
                        case Advice::POSITION_AROUND: return $this->weaver->around($node, $adviceNode);
                        default: throw new \UnexpectedValueException(
                            sprintf("%s is not supported as position", $advice->getPosition())
                        );
                    }
                }
            }
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
