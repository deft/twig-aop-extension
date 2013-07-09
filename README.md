AOP for Twig Extension
======================

Introduction
------------

This extension adds Aspect-oriented programming (AOP) features to the Twig
templating language. AOP makes it possible to encapsulate cross-cutting
concerns (e.g. logging, security, etc.) into distinct modules, which makes it
possible to reduce code duplication and allows a better separation of concerns.

For more information on AOP in general, see http://en.wikipedia.org/wiki/Aspect-oriented_programming

Installation
------------

The recommended way of installing this extension is by adding
```"deft/twig-aop-extension": "~0.1"``` to the ```require``` section of your composer.json.

Important concepts
------------------

Below is a summary of the most important concepts in AOP and how they're mapped to the domain of Twig.

**Aspect**
Module that encapsulates a concern; collection of *pointcuts* and *advice* bodies.

**Pointcut**
A predicate that matches one or more *join points*.

**Join point**
Point in the execution flow. In Twig, every node is a join point (1-1 relation)
 and pointcuts are therefore defined by whether they match a given node or not.

**Advice**
Code that is associated with a pointcut. Types of advice:

 * Before: The advice runs before the join point;
 * After: The advice runs after the join point;
 * Around: The advice runs *instead* of the join point. The advice stil does have the
possibility to execute the original join point, but has to explicitly make this call.

**Aspect weaver**
The processor that actually adds the advice to the compiled result at the
associated join points. In Twig, this can be accomplished by modifying the
node tree just using node visitors, just before it is compiled.

Usage
-----

This section is still under construction, but you can have a look at
test/Deft/Twig/AopExtension/IntegrationTestCase which should provide some
guidelines on usage.
