<?php

namespace Deft\Twig\AopExtension\Pointcut;

/**
 * Performs a callback that determines whether the given node should be matched.
 */
class CallbackPointcut implements Pointcut
{
    /**
     * @var callable
     */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function matches(\Twig_Node $node)
    {
        return call_user_func($this->callback, $node);
    }
}
