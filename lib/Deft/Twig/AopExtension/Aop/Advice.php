<?php

namespace Deft\Twig\AopExtension\Aop;

use Deft\Twig\AopExtension\Aop\Weaving\WeavingStrategy;

class Advice
{
    /**
     * The name of the template containing the advice body.
     *
     * @var string
     */
    private $templateName;
    public function getTemplateName() { return $this->templateName; }

    /**
     * Returns the strategy for weaving the advice, e.g. a BeforeStrategy or AfterStrategy
     *
     * @var WeavingStrategy
     */
    private $weavingStrategy;
    public function getWeavingStrategy() { return $this->weavingStrategy; }

    /**
     * The point cut that determines to which join points this advice should be added.
     *
     * @var Pointcut
     */
    private $pointcut;

    /**
     * @param          $templateName
     * @param          $position
     * @param Pointcut $pointcut
     */
    public function __construct($templateName, WeavingStrategy $weavingStrategy, Pointcut $pointcut)
    {
        $this->templateName = $templateName;
        $this->weavingStrategy = $weavingStrategy;
        $this->pointcut = $pointcut;
    }

    /**
     * Determines whether this advice should be added to the given Twig node.
     *
     * @param \Twig_Node $node
     *
     * @return bool
     */
    public function matches(\Twig_Node $node)
    {
        return $this->pointcut->matches($node);
    }
}
