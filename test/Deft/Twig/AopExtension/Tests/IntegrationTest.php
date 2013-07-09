<?php

namespace Deft\Twig\AopExtension\Tests;

use Deft\Twig\AopExtension\Aop\Advice;
use Deft\Twig\AopExtension\Aop\Aspect;
use Deft\Twig\AopExtension\Aop\Matcher;
use Deft\Twig\AopExtension\Aop\Pointcut\CallbackPointcut;
use Deft\Twig\AopExtension\Aop\Weaver;
use Deft\Twig\AopExtension\Aop\Weaving\AfterStrategy;
use Deft\Twig\AopExtension\Aop\Weaving\BeforeStrategy;
use Deft\Twig\AopExtension\Aop\Weaving\WeavingStrategy;
use Deft\Twig\AopExtension\AopExtension;
use Deft\Twig\AopExtension\NodeVisitor\AspectNodeVisitor;
use Deft\Twig\AopExtension\AspectWeaver;

/**
 * @runTestsInSeparateProcesses
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $template = "Hello! {% block foobar %}This is awesome!{% endblock %}";

    public function setUp()
    {
        $loader = new \Twig_Loader_String();
        $this->twig = new \Twig_Environment($loader);
    }

    public function testBeforeAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new BeforeStrategy())
        ]));

        $output = $this->twig->render($this->template);

        $this->assertContains('42This', $output);
    }

    public function testAfterAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new AfterStrategy())
        ]));

        $output = $this->twig->render($this->template);

        $this->assertContains('awesome!42', $output);
    }

    public function testMultipleAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new BeforeStrategy()),
            new SimpleAspect(new AfterStrategy())
        ]));

        $output = $this->twig->render($this->template);

        $this->assertContains('42This', $output);
        $this->assertContains('awesome!42', $output);
    }

    public function testNoMatchingAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new BeforeStrategy())
        ]));

        $output = $this->twig->render("Hello! This is awesome");

        $this->assertNotContains('42', $output);
    }

    private function createExtension(array $aspects)
    {
        return new AopExtension(new AspectNodeVisitor(
            $aspects,
            new Weaver($this->twig),
            new Matcher()
        ));
    }
}

class SimpleAspect implements Aspect
{
    private $weavingStrategy;

    public function __construct(WeavingStrategy $weavingStrategy)
    {
        $this->weavingStrategy = $weavingStrategy;
    }

    public function getAdvice()
    {
        $pointcut = new CallbackPointcut(function (\Twig_Node $node) {
            return $node instanceof \Twig_Node_BlockReference;
        });

        return [
            new Advice('{{ 42 }}', $this->weavingStrategy, $pointcut)
        ];
    }
}
