<?php

namespace Deft\Twig\AopExtension\NodeProvider;

interface NodeProvider
{
    /**
     * Must return a node that is suitable for weaving into the targeted join points.
     *
     * @return \Twig_Node
     */
    public function getNode();
}
