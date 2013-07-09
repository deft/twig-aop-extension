<?php

namespace Deft\Twig\AopExtension;

use Deft\Twig\AopExtension\NodeProvider\NodeProvider;
use Deft\Twig\AopExtension\Pointcut\Pointcut;
use Deft\Twig\AopExtension\Weaving\WeavingStrategy;

class Advice
{
    /**
     * The pointcut that determines to which join points this advice should be added.
     *
     * @var Pointcut
     */
    private $pointcut;

    /**
     * Object that takes care of providing an advice body as a weavable node.
     *
     * @var NodeProvider
     */
    private $nodeProvider;

    /**
     * Returns the strategy for weaving the advice node, e.g. a BeforeStrategy.
     *
     * @var WeavingStrategy
     */
    private $weavingStrategy;

    /**
     * @param Pointcut        $pointcut
     * @param NodeProvider    $nodeProvider
     * @param WeavingStrategy $weavingStrategy
     */
    public function __construct(Pointcut $pointcut, NodeProvider $nodeProvider, WeavingStrategy $weavingStrategy)
    {
        $this->pointcut = $pointcut;
        $this->nodeProvider = $nodeProvider;
        $this->weavingStrategy = $weavingStrategy;
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

    /**
     * Weaves the node provided by the configured NodeProvider into
     * $originalNode, and returns the resulting Twig node.
     *
     * @param $originalNode
     * @return \Twig_Node
     */
    public function weave($originalNode)
    {
        return $this->weavingStrategy->weave(
            $originalNode,
            $this->nodeProvider->getNode()
        );
    }
}
