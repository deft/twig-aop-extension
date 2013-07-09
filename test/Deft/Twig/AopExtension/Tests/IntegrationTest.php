<?php

namespace Deft\Twig\AopExtension\Tests;

use Deft\Twig\AopExtension\Advice;
use Deft\Twig\AopExtension\Aspect;
use Deft\Twig\AopExtension\AspectWeaver;
use Deft\Twig\AopExtension\AdviceMatching\DefaultAdviceMatcher;
use Deft\Twig\AopExtension\NodeProvider\DirectNodeProvider;
use Deft\Twig\AopExtension\NodeProvider\TemplateNodeProvider;
use Deft\Twig\AopExtension\Pointcut\CallbackPointcut;
use Deft\Twig\AopExtension\Pointcut\NodeTypePointcut;
use Deft\Twig\AopExtension\Weaving\After;
use Deft\Twig\AopExtension\Weaving\Around;
use Deft\Twig\AopExtension\Weaving\Before;
use Deft\Twig\AopExtension\Weaving\WeavingStrategy;
use Deft\Twig\AopExtension\Extension\Aop;

/**
 * @runTestsInSeparateProcesses
 */
class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $template;

    public function setUp()
    {
        $loader = new \Twig_Loader_String();
        $this->twig = new \Twig_Environment($loader);
        $this->template = "Hello! {% block foobar %}This is awesome!{% endblock %}";
    }

    public function testBeforeAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new Before())
        ]));

        $output = $this->twig->render($this->template);

        $this->assertContains('42This', $output);
    }

    public function testAfterAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new After())
        ]));

        $output = $this->twig->render($this->template);

        $this->assertContains('awesome!42', $output);
    }

    public function testMultipleAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new Before()),
            new SimpleAspect(new After())
        ]));

        $output = $this->twig->render($this->template);

        $this->assertContains('42This', $output);
        $this->assertContains('awesome!42', $output);
    }

    public function testNoMatchingAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new SimpleAspect(new Before())
        ]));

        $output = $this->twig->render("Hello! This is awesome");

        $this->assertNotContains('42', $output);
    }

    public function testAroundAdvice()
    {
        $this->twig->addExtension($this->createExtension([
            new AroundAspect($this->twig)
        ]));

        $output = $this->twig->render($this->template);
        $this->assertNotContains('This is awesome!', $output);
    }

    private function createExtension(array $aspects)
    {
        return new Aop(new AspectWeaver(
            $aspects,
            new DefaultAdviceMatcher()
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
            new Advice(
                $pointcut,
                new DirectNodeProvider(new \Twig_Node_Text('42', 1)),
                $this->weavingStrategy
            )
        ];
    }
}

class AroundAspect implements Aspect
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Returns a list of advice associated with this aspect.
     *
     * @return Advice[]
     */
    public function getAdvice()
    {
        $template = "{% if false %}{% proceed %}{% endif %}";
        $pointcut = new NodeTypePointcut('Twig_Node_BlockReference');

        return [
            new Advice(
                $pointcut,
                new TemplateNodeProvider($this->twig, $template),
                new Around()
            )
        ];
    }
}
