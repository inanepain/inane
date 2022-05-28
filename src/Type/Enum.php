<?php

/**
 * Enum Abstract Class
 *
 * PHP version 8.1
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Type
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 *
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

declare(strict_types=1);

namespace Inane\Type;

use JsonSerializable;
use ReflectionClass;

use function array_key_exists;
use function array_keys;
use function array_search;
use function get_class;
use function in_array;

use Inane\Exception\{
    BadMethodCallException,
    UnexpectedValueException
};

/**
 * Base Enum class
 *
 * Create an enum by implementing this class and adding class constants.
 *
 * @package Inane\Type
 * @version 0.5.0
 */
abstract class Enum implements JsonSerializable {
    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * Cache of instances of the Enum class
     *
     * @var array
     */
    protected static $instances = [];

    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Enum key, the constant name
     *
     * @var string
     */
    private $key;

    /**
     * Enum description
     *
     * @var mixed
     */
    protected $description = '';

    /**
     * User friendly status description
     *
     * @var array
     */
    protected static array $descriptions = [];

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
    protected static array $defaults = [];

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     *
     * @throws UnexpectedValueException if incompatible type is given.
     */
    public function __construct($value) {
        if ($value instanceof static) $value = $value->getValue();

        $this->key = static::assertValidValueReturningKey($value);
        $this->description = static::$descriptions[$value] ?? null;
        $this->default = static::$defaults[$value] ?? null;
        $this->value = $value;
    }

    /**
     * This method exists only for the compatibility reason when deserializing a previously serialized version
     * that didn't had the key property
     */
    public function __wakeup() {
        if ($this->key === null) $this->key = static::search($this->value);
    }

    /**
     * create from value
     *
     * @param mixed $value
     *
     * @return static
     */
    public static function from($value): self {
        $key = static::assertValidValueReturningKey($value);

        return self::__callStatic($key, []);
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
     * @return string
     */
    public function getKey(): string {
        return $this->key;
    }

    /**
     * Magic toString function
     *
     * @return string
     */
    public function __toString(): string {
        return (string) $this->value;
    }

    /**
     * equals
     *
     * Determines if Enum should be considered equal with the variable passed as a parameter.
     * Returns false if an argument is an object of different class or not an object.
     *
     * @param mixed $variable
     * @return bool
     */
    final public function equals(mixed $variable = null): bool {
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
     * Check if is valid enum value
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isValid(mixed $value): bool {
        return in_array($value, static::toArray(), true);
    }

    /**
     * Returns all possible values as an array
     *
     * @return array Constant name in key, constant value in value
     */
    public static function toArray(): array {
        $class = static::class;

        if (!isset(static::$cache[$class])) {
            $reflection            = new ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }

        return static::$cache[$class];
    }

    /**
     * Asserts valid enum value
     *
     * @param mixed $value
     */
    public static function assertValidValue($value): void {
        self::assertValidValueReturningKey($value);
    }

    /**
     * Asserts valid enum value
     *
     * @param mixed $value
     * @return string
     * @throws UnexpectedValueException
     */
    private static function assertValidValueReturningKey($value): string {
        if (false === ($key = static::search($value))) throw new UnexpectedValueException("Value '$value' is not part of the enum " . static::class);

        return $key;
    }

    /**
     * Check if is valid enum key
     *
     * @param string $key
     *
     * @return bool
     */
    public static function isValidKey(string $key): bool {
        $array = static::toArray();

        return isset($array[$key]) || array_key_exists($key, $array);
    }

    /**
     * Return key for value
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function search(mixed $value) {
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
    public static function __callStatic($name, $arguments): static {
        if (!isset(self::$instances[static::class][$name])) {
            $array = static::toArray();
            if (!isset($array[$name]) && !array_key_exists($name, $array)) {
                $message = "No static method or enum constant '$name' in class " . static::class;
                throw new BadMethodCallException($message);
            }
            return self::$instances[static::class][$name] = new static($array[$name]);
        }
        return clone self::$instances[static::class][$name];
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @return mixed
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     */
    public function jsonSerialize(): mixed {
        return $this->getValue();
    }
}
