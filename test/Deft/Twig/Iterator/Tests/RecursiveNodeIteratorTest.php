<?php

namespace Deft\Twig\Iterator\Tests;

use Deft\Twig\Iterator\RecursiveNodeIterator;

class RecursiveNodeIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testHasChildren_false()
    {
        $node = new \Twig_Node([
            new \Twig_Node()
        ]);
        $nodeIterator = new RecursiveNodeIterator($node);

        $this->assertEquals(false, $nodeIterator->hasChildren());
    }

    public function testHasChildren_true()
    {
        $node = new \Twig_Node([
            new \Twig_Node([
                new \Twig_Node()
            ])
        ]);
        $nodeIterator = new RecursiveNodeIterator($node);

        $this->assertEquals(true, $nodeIterator->hasChildren());
    }

    public function testGetChildren()
    {
        $node = new \Twig_Node([
            new \Twig_Node([
                new \Twig_Node_Text('test', 1)
            ])
        ]);
        $nodeIterator = new RecursiveNodeIterator($node);

        $children = $nodeIterator->getChildren();
        $this->assertInstanceOf('Twig_Node_Text', $children->current());
    }
}
