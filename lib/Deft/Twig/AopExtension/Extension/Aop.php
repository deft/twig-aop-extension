<?php

namespace Deft\Twig\AopExtension\Extension;

use Deft\Twig\AopExtension\AspectWeaver;
use Deft\Twig\AopExtension\Proceed\TokenParser as ProceedTokenParser;

class Aop extends \Twig_Extension
{
    /**
     * @var AspectWeaver
     */
    protected $aspectWeaver;

    /**
     * @param AspectWeaver $aspectWeaver
     */
    public function __construct(AspectWeaver $aspectWeaver)
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
    public function getTokenParsers()
    {
        return [new ProceedTokenParser()];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        'deft_aop';
    }
}
