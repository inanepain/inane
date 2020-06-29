<?php

/**
 * This file is part of InaneTools.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Option
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <peep@inane.co.za>
 */

namespace Inane\Option;

use Inane\Option\Exception\MethodException;
use Inane\Option\Exception\PropertyException;

use function get_class_methods;
use function in_array;
use function lcfirst;
use function property_exists;
use function str_replace;
use function ucwords;

/**
 * MagicPropertyTrait
 * 
 * Adds Getters / Setters via magic get / get methods
 *
 * @package Inane\Option\Property
 * @version 0.1.0
 */
trait MagicPropertyTrait {
    /**
     * Getter method indentifier
     */
    protected static string $MAGIC_PROPERTY_GET = 'get';

    /**
     * Setter method indentifier
     */
    protected static string $MAGIC_PROPERTY_SET = 'set';

    /**
     * If property does not exist an exception is thrown
     * 
     * @var bool
     */
    protected static bool $verify = true;

    /**
     * Gets the method name based on the property name
     *
     * @param string $property - propert name
     * @param string $prepend - string identifying method (get/set/store/fetch/put/...)
     * 
     * @return string - the method name
     * 
     * @throws MethodException
     */
    protected function parseMethodName(string $property, string $prepend = ''): string {
        $methodName = $prepend . str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));
        if (!$prepend) $methodName = lcfirst($methodName);

        if (!in_array($methodName, get_class_methods(__CLASS__))) throw new MethodException($methodName);

        return $methodName;
    }

    /**
     * magic method: __get
     *
     * @param string $property - propert name
     * 
     * @return mixed
     * 
     * @throws PropertyException
     * @throws MethodException
     */
    public function __get(string $property) {
        if (static::$verify && property_exists(__CLASS__, 'magic_property_properties')) {
            if (!in_array($property, $this->magic_property_properties)) throw new PropertyException("Property not in array: {$property}", 13, new PropertyException());
        } else if (static::$verify && !property_exists(__CLASS__, $property)) throw new PropertyException($property, 11);

        $method = $this->parseMethodName($property, static::$MAGIC_PROPERTY_GET);
        return $this->$method();
    }

    /**
     * magic method: __set
     * 
     * @param string $property - propert name
     * @param mixed $value - new property value
     * 
     * @return $this 
     * 
     * @throws PropertyException
     * @throws MethodException
     */
    public function __set(string $property, $value) {
        if (static::$verify && property_exists(__CLASS__, 'magic_property_properties')) {
            if (!in_array($property, $this->magic_property_properties)) throw new PropertyException("Property not in array: {$property}", 14, new PropertyException());
        } else if (static::$verify && !property_exists(__CLASS__, $property)) throw new PropertyException($property, 12);

        $method = $this->parseMethodName($property, static::$MAGIC_PROPERTY_SET);
        return $this->$method($value);
    }
}
