<?php

namespace Deft\Twig\AopExtension;

use Deft\Twig\AopExtension\NodeVisitor\AspectNodeVisitor;
use Deft\Twig\AopExtension\Proceed\TokenParser;

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

    public function getTokenParsers()
    {
        return [new TokenParser()];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        'deft_aop';
    }
}
