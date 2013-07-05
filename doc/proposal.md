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

The problem
-----------

Suppose you want to wrap all 'foobar' blocks in your application in an if
statement. Then a template like this...

```twig
Hello!
{% block foobar %}
  This is awesome!
{% endblock %}
```

would become...

```twig
Hello!
{% if true %}
  {% block foobar %}
    This is awesome!
  {% endblock %}
{% endif
```

For one block this is not so problematic, but if you would want to apply this
rule to a lot of blocks in many templates, this one rule soon becomes many
extra LOC in your templates. Even if you wrap the expression in 1 function, you
would still have to add the if statements.

Proposed usage
--------------

The root of the problem described above is the fact that we're trying to add a
certain aspect to our code that is in fact a cross-cutting concern: it applies
to many pieces of the application.

However, the piece of code your adding is always the same, so intuitively you'd
want to define this in one place. This is where AOP comes to the rescue.

The idea is to create separate templates that contain your cross-cutting
concern, take for example the simple security check in this template:

```twig
{% if access_granted() %}
  {% aspect thisJoinPoint %}
{% endblock %}
```

This template is the 'advice': the actual code that has to be weaved. In
this case, we wrapped new code around an existing join point.

(todo: how to define aspects)

Proposed implementation
-----------------------

The best way to achieve this is probably by modifying the node structure,
using a NodeVisitor.

Considering the example above, this would be the node structure *before* weaving:

    Module node:
        Body node:
            Text node: ...
            BlockReference node:
                Text node: ...
        

And this should be the node structure *after* weaving:

    Module node:
        Body node:
            Text node: ...
            If node:
                Expression node
                BlockReference node:
                    Text node: ...

References
----------

http://en.wikipedia.org/wiki/Aspect-oriented_programming  
http://www.eclipse.org/aspectj/doc/next/progguide/semantics.html  
http://static.springsource.org/spring/docs/2.0.8/reference/aop.html  
