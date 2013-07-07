<?php

namespace Deft\Twig\AopExtension;

use Deft\Twig\AopExtension\NodeVisitor\AspectNodeVisitor;

class AopExtension extends \Twig_Extension
{
    /**
     * @var \Twig_NodeVisitorInterface
     */
    protected $aspectWeaver;

    public function __construct(AspectNodeVisitor $aspectWeaver)
    {
        $this->aspectWeaver = $aspectWeaver;
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return [$this->aspectWeaver];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        'deft_aop';
    }
}
