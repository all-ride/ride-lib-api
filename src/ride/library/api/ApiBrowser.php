<?php

namespace ride\library\api;

use ride\library\api\doc\DocParser;
use ride\library\api\reflection\ReflectionClass;
use ride\library\system\file\FileSystem;
use ride\library\system\file\File;

/**
 * Zibo live API browser using PHP's reflection interface
 */
class ApiBrowser {

    /**
     * Separator between namespace parts
     * @var string
     */
    const NAMESPACE_SEPARATOR = '/';

    /**
     * Parser for the doc comments
     * @var \ride\library\api\doc\DocParser
     */
    private $docParser;

    /**
     * Instance of the file system
     * @var \ride\library\system\file\FileSystem
     */
    private $fileSystem;

    /**
     * The include paths to read
     * @var array
     */
    private $includePaths;

    /**
     * Extensions for the files considered as PHP sources
     * @var array
     */
    private $sourceExtensions = array('php', 'inc');

    /**
     * Constructs a new API browser
     * @param array $includePaths The paths to read
     * @return null
     */
    public function __construct(DocParser $docParser, FileSystem $fileSystem, array $includePaths) {
        $this->docParser = $docParser;
        $this->fileSystem = $fileSystem;
        $this->includePaths = $includePaths;
    }

    /**
     * Gets a list of namespaces
     * @param string $namespace Filter namespaces with the provided namespace
     * @return array Ordered array with the namespace as key and value
     */
    public function getNamespaces($namespace = null) {
        $namespaces = array();

        foreach ($this->includePaths as $path) {
            $path = $this->fileSystem->getFile($path);
            if ($namespace) {
                $path = $path->getChild($namespace);

                $namespaces = $this->readNamespacesFromPath($path, $namespaces, $namespace . self::NAMESPACE_SEPARATOR);
            } else {
                $namespaces = $this->readNamespacesFromPath($path, $namespaces);
            }
        }

        ksort($namespaces);

        return $namespaces;
    }

    /**
     * Gets the classes of a namespace
     * @param string $namespace Namespace of the classes
     * @param boolean $recursive Look in subnamespaces
     * @param string $query Case insensitive, file name based search query
     * @return array Ordered array with namespace and class name as key and the class name as value
     */
    public function getClassesForNamespace($namespace, $recursive = false, $query = null) {
        $namespace = str_replace('\\', self::NAMESPACE_SEPARATOR, $namespace);
        $classes = array();

        foreach ($this->includePaths as $includePath) {
            $path = $this->fileSystem->getFile($includePath);

            if ($namespace) {
                $path = $path->getChild($namespace);
                if ($namespace[strlen($namespace) - 1] != self::NAMESPACE_SEPARATOR) {
                    $namespace .= self::NAMESPACE_SEPARATOR;
                }
            }

            $classes = $this->readClassesFromPath($path, $classes, $namespace, $recursive, $query);
        }

        ksort($classes);

        return $classes;
    }

    /**
     * Get the reflection class object of a class
     * @param string $namespace namespace of the class
     * @param string $class name of the class
     * @return \ride\library\api\ReflectionClass
     */
    public function getClass($namespace, $class) {
        $className = str_replace(self::NAMESPACE_SEPARATOR, '\\', $namespace) . '\\' . $class;

        $reflectionClass = new ReflectionClass($className);
        $reflectionClass->setDocParser($this->docParser);

        return $reflectionClass;
    }

    /**
     * Read all the classes in a path
     * @param \ride\library\system\file\File $path Path to read
     * @param array $classes Already found classes
     * @param string $namespace Look for classes in the provided namespace
     * @param boolean $recursive Look in subdirectories
     * @param string $query Only return class names which match this query
     * @return array Array with namespace and class name as key and the class
     * name as value
     */
    private function readClassesFromPath(File $path, array $classes, $namespace, $recursive, $query = null) {
        if (!$path->exists()) {
            return $classes;
        }

        if ($path->isDirectory()) {
            $files = $path->read();
            foreach ($files as $file) {
                if ($file->isDirectory()) {
                    if ($recursive) {
                        $classes = $this->readClassesFromPath($file, $classes, $namespace . $file->getName() . self::NAMESPACE_SEPARATOR, $recursive, $query);
                    }

                    continue;
                }

                $classes = $this->readClassesFromPath($file, $classes, $namespace, $recursive, $query);
            }
        } else {
            $extension = $path->getExtension();
            if (in_array($extension, $this->sourceExtensions) && !($query && stripos($path->getName(), $query) === false)) {
                $name = substr($path->getName(), 0, (strlen($extension) + 1) * -1);

                $classes[$namespace . $name] = $name;
            }
        }

        return $classes;
    }

    /**
     * Read all the namespaces in a path
     * @param \ride\library\system\file\File $path Path to read
     * @param array $namespaces Already found namespaces
     * @param string $prefix Namespace prefix for the results
     * @return array Array with namespaces as key and as value
     */
    private function readNamespacesFromPath(File $path, array $namespaces, $prefix = null) {
        if (!$path->exists() || !$path->isDirectory()) {
            return $namespaces;
        }

        $hasFiles = false;

        $files = $path->read();
        foreach ($files as $file) {
            if (!$file->isDirectory()) {
                $hasFiles = true;

                continue;
            }

            $name = $prefix . $file->getName();

            $namespaces[$name] = $name;
            $namespaces = $this->readNamespacesFromPath($file, $namespaces, $name . self::NAMESPACE_SEPARATOR);
        }

        if (!$hasFiles && $prefix) {
            $prefix = substr($prefix, 0, -1);

            unset($namespaces[$prefix]);
        }

        return $namespaces;
    }

}
