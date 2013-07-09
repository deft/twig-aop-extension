<?php

namespace Deft\Twig\AopExtension\NodeProvider;

/**
 * Provides a weavable twig node by rendering a template and fetching the body node.
 */
class TemplateNodeProvider implements NodeProvider
{
    /**
     * Necessary for rendering the template.
     *
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Name of the template that can be loaded by the registered loader.
     *
     * @var string
     */
    private $templateName;

    /**
     * @param \Twig_Environment $twig
     * @param string            $templateName
     */
    public function __construct(\Twig_Environment $twig, $templateName)
    {
        $this->twig = $twig;
        $this->templateName = $templateName;
    }

    /**
     * {@inheritdoc}
     */
    public function getNode()
    {
        $adviceModuleNode = $this->twig->parse($this->twig->tokenize(
            $this->twig->getLoader()->getSource($this->templateName)
        ));

        return $adviceModuleNode->getNode('body');
    }
}
