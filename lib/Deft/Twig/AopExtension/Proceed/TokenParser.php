<?php

namespace Deft\Twig\AopExtension\Proceed;

/**
 * Handles the 'proceed'-tag in advice bodies.
 */
class TokenParser extends \Twig_TokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(\Twig_Token $token)
    {
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        return new ProceedNode();
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'proceed';
    }
}
