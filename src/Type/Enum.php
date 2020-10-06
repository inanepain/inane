<?php

/**
 * Enum Abstract Class
 * 
 * PHP version 7
 * 
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Type
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 *
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

namespace Inane\Type;

use Inane\Exception\UnexpectedValueException;
use Inane\Exception\BadMethodCallException;

use JsonSerializable;

use function get_class;
use function array_keys;
use function in_array;
use function array_key_exists;
use function array_search;

/**
 * Base Enum class
 *
 * Create an enum by implementing this class and adding class constants.
 *
 * @package Inane\Type
 * @version 0.4.0
 */
abstract class Enum implements JsonSerializable {
    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Store existing constants in a static cache per object.
     *
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Enum description
     *
     * @var mixed
     */
    protected $description = 'Enum';

    /**
     * User friendly status description
     *
     * @var array
     */
    protected static $descriptions = [];

    /**
     * Enum default
     *
     * @var mixed
     */
    protected $default = '';

    /**
     * Default value
     * 
     * If enum of properties this could store their default values
     *
     * @var array
     */
    protected static $defaults = [];

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     *
     * @throws UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value) {
        if ($value instanceof static) $value = $value->getValue();

        if (!$this->isValid($value)) {
            throw new UnexpectedValueException("Value '$value' is not part of the enum " . static::class);
        }

        $this->value = $value;
        $this->description = static::$descriptions[$value] ?? null;
        $this->default = static::$defaults[$value] ?? null;
    }

    /**
     * Get value
     * 
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Get description
     * 
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Get default
     * 
     * @return mixed
     */
    public function getDefault() {
        return $this->default;
    }

    /**
     * Returns the enum key (i.e. the constant name).
     *
     * @return mixed
     */
    public function getKey() {
        return static::search($this->value);
    }

    /**
     * Magic toString function
     * 
     * @return string
     */
    public function __toString() {
        return (string) $this->value;
    }

    /**
     * Determines if Enum should be considered equal with the variable passed as a parameter.
     * Returns false if an argument is an object of different class or not an object.
     *
     * This method is final, for more information read https://github.com/myclabs/php-enum/issues/4
     *
     * @return bool
     */
    final public function equals($variable = null): bool {
        return $variable instanceof self
            && $this->getValue() === $variable->getValue()
            && static::class === get_class($variable);
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return string[]
     */
    public static function keys(): array {
        return array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return static[] Constant name in key, Enum instance in value
     */
    public static function values(): array {
        $values = [];

        foreach (static::toArray() as $key => $value) $values[$key] = new static($value);

        return $values;
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray(): array {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            $reflection            = new \ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     * 
     * @return bool
     */
    public static function isValid($value): bool {
        return in_array($value, static::toArray(), true);
    }

    /**
     * Check if is valid enum key
     *
     * @param $key
     * 
     * @return bool
     */
    public static function isValidKey($key): bool {
        $array = static::toArray();

        return isset($array[$key]) || array_key_exists($key, $array);
    }

    /**
     * Return key for value
     *
     * @param $value
     * 
     * @return mixed
     */
    public static function search($value) {
        return array_search($value, static::toArray(), true);
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return static
     * 
     * @throws BadMethodCallException
     */
    public static function __callStatic($name, $arguments) {
        $array = static::toArray();
        if (isset($array[$name]) || array_key_exists($name, $array)) return new static($array[$name]);

        throw new BadMethodCallException("No static method or enum constant '$name' in class " . static::class);
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize() {
        return $this->getValue();
    }
}
