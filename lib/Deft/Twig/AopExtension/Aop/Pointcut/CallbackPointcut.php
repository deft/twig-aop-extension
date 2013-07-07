<?php

namespace Deft\Twig\AopExtension\Aop\Pointcut;

use Deft\Twig\AopExtension\Aop\Pointcut;

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
