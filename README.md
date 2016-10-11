# Ride: API Library

Library to browse the API of your PHP code through reflection.

## What's In This Library

### DocParser

The _DocParser_ class parses a doc comment string into a _Doc_ instance.

An example of a doc comment string:

```
    /**
     * Gets a list of namespaces
     * @param string $namespace Filter namespaces with the provided namespace
     * @return array Ordered array with the namespace as key and value
     */
```

### Doc

The _Doc_ class is a data container for the doc comment information as parsed by the _DocParser_.

### DocParameter

The _DocParameter_ class is a data container for the doc comment information of an argument of a function or method.

### TagParser

The _TagParser_ class is used by the _DocParser_ to process the different tags (eg @param, @return, ...) of a doc comment.

### Tag

The _Tag_ interface is used to implement a specific doc comment tag.

### ReflectionClass

The _ReflectionClass_ extends from the PHP core class and adds methods to access the parsed _Doc_ instances and other usefull things to generate an API doc.

### ReflectionMethod

The _ReflectionClass_ class extends from the PHP core class and adds methods to access the parsed _Doc_ instances and the source code.

### ReflectionProperty

The _ReflectionProperty_ class extends from the PHP core class and adds methods to access the parsed _Doc_ instances.

### ApiBrowser

The _ApiBrowser_ class is the facade to this library.
Use this instance to check the available namespaces, the classes which reside therein or the full detail of an individual class.

## Code Sample

Check the following code sample to see some of the possibilities of this library.

```php
<?php

use ride\library\api\doc\DocParser;
use ride\library\api\ApiBrowser;
use ride\library\system\file\FileSystem;

function createApiBrowser(FileSystem $fileSystem) {
    $includePaths = array(
        '/path/to/source1',
        '/path/to/source2',
    );
    
    $tagParser = new TagParser();
    $docParser = new DocParser($tagParser);
    
    $apiBrowser = new ApiBrowser($docParser, $fileSystem, $includePaths);
    
    return $apiBrowser;
}

function useApiBrowser(ApiBrowser $apiBrowser) {
    // get all available namespaces
    $namespaces = $apiBrowser->getNamespaces();
    
    // get all namespaces in a specific namespace
    $subNamespaces = $apiBrowser->getNamespaces('ride\\library\\api');
    // array(
    //     'ride\\library\\api' => 'ride\\library\\api', 
    //     'ride\\library\\api\\doc' => 'ride\\library\\api\\doc', 
    //     'ride\\library\\api\\doc\\tag' => 'ride\\library\\api\\doc\\tag', 
    //     'ride\\library\\api\\io' => 'ride\\library\\api\\io', 
    //     'ride\\library\\api\\reflection' => 'ride\\library\\api\\reflection', 
    // );
    
    $classes = $apiBrowser->getClassesForNamespace('ride\\library\\api');
    // array(
    //     'ride\\library\\api\\ApiBrowser' => 'ApiBrowser',
    // );
    
    $class = $apiBrowser->getClass('ride\\library\\api\\ApiBrowser');
    
    $doc = $class->getDoc();
    $type = $class->getTypeAsString(); // abstract class, interface
    
    // an array with for each class, the methods it overrides or implements 
    $inheritance = $class->getInheritance();
    // array(
    //     'className' => array(
    //          'methodName' => 'ReflectionMethod',
    //     ), 
    // );
    
    // an array with all classes it extends or implements
    $parents = $class->getParentArray();
    // array(
    // );
    
    $methods = $class->getMethods();
    foreach ($methods as $methodName => $method) {
        $doc = $method->getDoc();
        $type = $method->getTypeAsString(); // abstract protected, ...
        $source = $method->getSource();
        
        $forInterface = $class->getMethodInterface($methodName);
        $false = $method->isInherited('ride\\library\\api\\ApiBrowser');
    }
    
    $properties = $class->getProperties();
    foreach ($properties as $propertyName => $property) {
        $doc = $property->getDoc();
        $type = $property->getTypeAsString();
    }
    
    $constants = $class->getConstants();
    foreach ($constants as $name => $value) {
        
    }
}

```

## Related Modules

- [ride/app-api](https://github.com/all-ride/ride-app-api)
- [ride/lib-system](https://github.com/all-ride/ride-lib-system)
- [ride/web-api](https://github.com/all-ride/ride-web-api)

## Installation

You can use [Composer](http://getcomposer.org) to install this library.

```
composer require ride/lib-api
```
