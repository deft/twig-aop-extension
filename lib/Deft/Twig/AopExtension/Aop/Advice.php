<?php

namespace Deft\Twig\AopExtension\Aop;

class Advice
{
    /**
     * The advice will be run before the original code.
     */
    const POSITION_BEFORE = 'before';

    /**
     * The advice will run after the original code.
     */
    const POSITION_AFTER = 'after';

    /**
     * The advice will run instead of the original code. You do have access to
     * the original node inside your advice, which can be run using the
     * 'proceed'-tag. For example:
     * <code>
     * {% if security_check() %}
     *   {% proceed %}
     * {% endif %}
     * </code>
     */
    const POSITION_AROUND = 'around';

    /**
     * The name of the template containing the advice body.
     *
     * @var string
     */
    private $templateName;
    public function getTemplateName() { return $this->templateName; }

    /**
     * Returns the position relative to the original code where the advice code
     * should be added. Must be one of the POSITION_ constants.
     *
     * @var string
     */
    private $position;
    public function getPosition() { return $this->position; }

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
    public function __construct($templateName, $position, Pointcut $pointcut)
    {
        $this->templateName = $templateName;
        $this->position = $position;
        $this->pointcut = $pointcut;
    }

    /**
     * Determines whether this advice should be added to the given Twig node.
     *
     * @param \Twig_Node $node
     * @return bool
     */
    public function matches(\Twig_Node $node)
    {
        return $this->pointcut->matches($node);
    }
}
