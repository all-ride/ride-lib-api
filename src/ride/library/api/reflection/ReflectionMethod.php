<?php

namespace ride\library\api\reflection;

use ride\library\api\doc\DocParser;

use \ReflectionMethod as PhpReflectionMethod;

/**
 * Overriden the ReflectionMethod to get more usefull output for API documentation
 */
class ReflectionMethod extends PhpReflectionMethod {

    /**
     * Instance of the doc parser
     * @var ride\library\api\doc\DocParser
     */
    private $docParser;

    /**
     * Name of the class which holds this method
     * @var string
     */
    private $className;

    /**
     * API documentation object for this method
     * @var ride\library\api\doc\Doc
     */
    private $doc;

    /**
     * The source code of this method
     * @var string
     */
    private $code;

    /**
     * Construct a new reflection method object
     * @param mixed $classOrName
     * @param string $name name of the class
     * @return null
     */
    public function __construct ($classOrName = null, $name = null) {
        parent::__construct($classOrName, $name);
        if ($name) {
            $this->className = $classOrName;
        }

        $this->code = null;
    }

    /**
     * Sets the doc parser to this instance
     * @param ride\library\api\doc\DocParser $docParser
     * @return null
     */
    public function setDocParser(DocParser $docParser) {
        $this->docParser = $docParser;
    }

    /**
     * Get the API documentation object for this method
     * @return ride\library\api\doc\Doc
     */
    public function getDoc() {
        if ($this->doc) {
            return $this->doc;
        }

        $doc = $this->getDocComment();
        $this->doc = $this->docParser->parse($doc);

        return $this->doc;
    }

    /**
     * Gets the source code of this method
     * @return string
     */
    public function getCode() {
        if ($this->code !== null) {
            return $this->code;
        }

        $this->code = '';

        $export = self::export($this->class, $this->name, true);
        $export = explode("\n", $export);

        foreach ($export as $line) {
            $line = trim($line);

            if (strpos($line, '@@') !== 0) {
                continue;
            }

            $tokens = explode(' ', $line);
            $file = $tokens[1];
            $start = $tokens[2];
            $end = $tokens[4];

            if ($start != $end) {
                $length = $end - $start - 1;

                $content = file_get_contents($file);
                $lines = explode("\n", $content);

                $lines = array_slice($lines, $start, $length);

                $trimSize = null;
                foreach ($lines as $line) {
                    if (!trim($line)) {
                        continue;
                    }

                    if ($trimSize === null) {
                        $trimSize = strlen($line) - strlen(ltrim($line));
                    } else {
                        $trimSize = min($trimSize, strlen($line) - strlen(ltrim($line)));
                    }
                }

                if ($trimSize) {
                    foreach ($lines as $line) {
                        $this->code .= substr($line, $trimSize) . "\n";
                    }

                    $this->code = rtrim($this->code);
                } else {
                    $this->code = implode("\n", $lines);
                }
            }

            break;
        }

        return $this->code;
    }

    /**
     * Get the type of this method as a string
     * @return string
     */
    public function getTypeString() {
        $string = '';
        if ($this->isFinal()) {
            $string = 'final ';
        } else if ($this->isAbstract()) {
            $string = 'abstract ';
        }

        if ($this->isPublic()) {
            $string .= 'public';
        } elseif ($this->isProtected()) {
            $string .= 'protected';
        } elseif ($this->isPrivate()) {
            $string .= 'private';
        }

        if ($this->isStatic()) {
            $string .= ' static';
        }

        return $string;
    }

    /**
     * Check whether this method is inherited
     * @param string $className Checks if this method is inherited from the
     * provided class instead of generally inherited
     * @return boolean True if this method is inherited
     */
    public function isInherited($className = null) {
        if ($className === null) {
            $className = $this->className;
        }

        $declaringClass = $this->getDeclaringClass();
        $declaringClassName = $declaringClass->getName();

        return $declaringClassName === $className;
    }

}