Proposal
========

Introduction
------------

This document provides a proposal as to how AOP can be implemented in a Twig extension.

Nomenclature
------------

Below is a summary of the most important concepts in AOP and how they're mapped to the domain of Twig.

**Aspect**
Module that encapsulates a concern; collection of *pointcuts* and *advice* bodies.

**Pointcut**
A predicate that matches one or more *join points*.

**Join point**
Point in the execution flow. In Twig, every node is a join point (1-1 relation) and pointcuts are therefore defined by whether they match a given
  node or not.

**Advice**
Code that is associated with a pointcut. Types of advice:

 * Before: The advice runs before the join point, no further interaction with join point.
 * After: The advice runs after the join point, no further interaction with join point.
 * Around: The advice runs *instead* of the join point. The advice does get a reference to the original join point.

**Aspect weaver**
The processor that actually adds the advice to the compiled code at the
associated join points. This means modifying the node tree just before it is
compiled using node visitors.

Aspect example:
---------------

Basic API:

```php
interface Aspect
{
    /**
     * @return Advice[]
     */
    public function getAdvice();
}

interface Advice
{
    /**
     * Receives a Twig node and should return whether to associate with it or
     * not. Essentially the pointcut of this advice.
     *
     * @param \Twig_Node $node
     * @return bool
     */
    public function matches(\Twig_Node $node);

    /**
     * Performs (or delegates) the steps necessary to associate with the given
     * node. The AspectWeaver will replace the passed node with the node that
     * this method returns, which of course can be the same node.
     *
     * However, in most cases it will be necessary to wrap the passed node in
     * a new node that handles adding the code before/after/around the original
     * node.
     *
     * @param \Twig_Node $node
     * @return \Twig_Node
     */
    public function associate(\Twig_Node $node);
}
```

Usage example:

```php
class LoggingAspect implements Aspect
{
    public function getAdvice()
    {
        return [
            'block' => [new LogBlockNodeAdvice()],
            'text' => [new LogTextNodeAdvice()]
        ];
    }
}

class LogBlockNodeAdvice implements Advice
{
    public function matches(\Twig_Node $node)
    {
        return
            $node instanceof \Twig_Node_Block
            && $node->getAttribute('name') == 'foobar'
        ;
    }

    public function associate(\Twig_Node $node)
    {
        /** todo **/
    }
}

/**
 * @todo
 */
class LogTextNodeAdvice implements Advice
{
}
```

Weaving example:
----------------

Given the following template:

    Hello!
    {% block foobar %}
        This is awesome!
    {% endblock %}

With the following aspect:

    LoggingAspect:
        - Advice:
            * Around "Nodes instanceof \Twig_Node_Block with name 'foobar'":
                - log "Entering block"
                - original node
                - log "Leaving block"
            * Before 'Nodes instencof \Twig_Node_Text':
                - log "Printing some text"

Then this will be the node structure *before* weaving:

    Module node:
        Text node: ...
        Block node:
            Text node: ...

And this should be the node structure *after* weaving:

    Module node:
        Before advice node:
            -- log "Printing some text"
            Text node: ...
        Around advice node:
            -- log "Entering block"
            Block node:
                Before advice node:
                Text node: ...
            -- log: "Leaving block"

References
----------

http://en.wikipedia.org/wiki/Aspect-oriented_programming  
http://www.eclipse.org/aspectj/doc/next/progguide/semantics.html  
http://static.springsource.org/spring/docs/2.0.8/reference/aop.html  
