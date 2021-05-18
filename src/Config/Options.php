<?php

/**
 * Options
 * 
 * Replaces Inane\Config\Config
 * @since 0.22.0
 * 
 * PHP version 8
 */

namespace Inane\Config;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Inane\Exception\InvalidArgumentException;
use Inane\Exception\RuntimeException;
use Iterator;

use function array_pop;
use function count;
use function current;
use function in_array;
use function is_array;
use function is_int;
use function key;
use function next;
use function reset;

/**
 * Options
 * 
 * Provides a property based interface to an array.
 * The data are read-only unless $allowModifications is set to true
 * on construction.
 *
 * Implements Countable, Iterator and ArrayAccess
 * to facilitate easy access to the data.
 * 
 * @package Inane\Config
 * @version 0.8.1
 */
class Options extends ArrayIterator implements ArrayAccess, Iterator, Countable {

    /**
     * Variables
     * 
     * @var array
     */
    private $_data = [];

    /**
     * Whether modifications to configuration data are allowed
     * 
     * @var bool
     */
    private $allowModifications;

    /**
     * get value
     * 
     * public function &__get($key) {
     * 
     * @param mixed $key key
     * @return mixed|Options value
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * Assigns a value to the specified data
     * 
     * @param string The data key to assign the value to
     * @param mixed  The value to set
     * @access public 
     */
    public function __set($key, $value) {
        if ($this->allowModifications) {
            if (is_array($value)) $value = new static($value);

            if (null === $key) $this->data[] = $value;
            else $this->data[$key] = $value;
        } else {
            throw new RuntimeException('Option is read only');
        }
    }

    /**
     * Whether or not an data exists by key
     *
     * @param string An data key to check for
     * @access public
     * @return boolean
     * @abstracting ArrayAccess
     */
    public function __isset($key) {
        return isset($this->_data[$key]);
    }

    /**
     * Unsets an data by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key) {
        if (!$this->allowModifications) {
            throw new InvalidArgumentException('Option is read only');
        } elseif ($this->__isset($key)) unset($this->_data[$key]);
    }

    /**
     * HtmlModel
     * 
     * @return void 
     */
    public function __construct(array $data, bool $allowModifications = true) {
        $this->allowModifications = (bool) $allowModifications;

        foreach ($data as $key => $value) if (is_array($value)) $this->_data[$key] = new static($value, $this->allowModifications);
        else $this->_data[$key] = $value;
    }

    /**
     * Current
     * 
     * @return mixed|Options
     */
    public function current() {
        return current($this->_data);
    }

    /**
     * next
     *
     * @return void
     */
    public function next() {
        next($this->_data);
    }

    /**
     * key
     * 
     * @return string|float|int|bool|null key
     */
    public function key() {
        return key($this->_data);
    }

    /**
     * valid
     * 
     * @return bool valid
     */
    public function valid() {
        return ($this->key() !== null);
    }

    /**
     * rewind to first item
     * 
     * @return void 
     */
    public function rewind() {
        reset($this->_data);
    }

    /**
     * count
     * 
     * @return int item count
     */
    public function count(): int {
        return count($this->_data);
    }

    /**
     * Key exists
     * 
     * @param string $offset key
     * @return bool exists
     */
    public function offsetExists($offset): bool {
        return $this->__isset($offset);
    }

    /**
     * get offset
     * @param string $offset offset
     * @return mixed|Options value
     */
    public function offsetGet($offset) {
        return $this->get($offset);
    }

    /**
     * get key
     * @param string $key key
     * @param mixed $default value
     * 
     * @return mixed|Options value
     */
    public function get($key, $default = null) {
        return $this->offsetExists($key) ? $this->_data[$key] : $default;
    }

    /**
     * set offset
     * @param string $offset offset
     * @param mixed $value value
     * @return void 
     */
    public function offsetSet($offset, $value) {
        $this->__set($offset, $value);
    }

    /**
     * set key
     * @param string $key key
     * @param mixed $value value
     * @return void 
     */
    public function set($key, $value): Options {
        $this->offsetSet($key, $value);
        return $this;
    }

    /**
     * delete key
     * 
     * @param string $offset key
     * @return void 
     */
    public function offsetUnset($offset) {
        $this->__unset($offset);
    }

    /**
     * delete key
     * 
     * @param string $offset key
     * @return Options 
     */
    public function unset($key): Options {
        $this->offsetUnset($key);
        return $this;
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray(): array {
        $array = [];
        $data = $this->_data;

        /** @var self $value */
        foreach ($data as $key => $value) if ($value instanceof self) $array[$key] = $value->toArray();
        else $array[$key] = $value;

        return $array;
    }

    /**
     * updates properties 2+ into first array with decreasing importance
     * so only unset keys are assigned values
     *
     * 1 array in = same array out
     * 0 array in = empty array out
     * 
     * @todo: check for allowModifications
     *
     * @param Options ...$modles
     * @return Options
     */
    public function defaults(Options ...$models): self {
        // $replacable = ['', null, false];
        $replacable = ['', null];

        while ($model = array_pop($models)) {
            foreach ($model as $key => $value) {
                if ($value instanceof self && $this->offsetExists($key) && $this[$key] instanceof self) {
                    $this[$key]->defaults($value);
                } else {
                    if (!$this->offsetExists($key) || in_array($this[$key], $replacable))
                        $this[$key] = $value;
                }
            }
        }
        return $this;
    }

    /**
     * Merge another Config with this one.
     *
     * For duplicate keys, the following will be performed:
     * - Nested Configs will be recursively merged.
     * - Items in $merge with INTEGER keys will be appended.
     * - Items in $merge with STRING keys will overwrite current values.
     *
     * @param Config $merge
     * @return self
     */
    public function merge(Options $merge): self {
        /** @var Options $value */
        foreach ($merge as $key => $value) {
            if ($this->offsetExists($key)) {
                if (is_int($key)) $this->_data[] = $value;
                elseif ($value instanceof self && $this->_data[$key] instanceof self) $this->_data[$key]->merge($value);
                else {
                    if ($value instanceof self) $this->_data[$key] = new static($value->toArray(), $this->allowModifications);
                    else $this->_data[$key] = $value;
                }
            } else {
                if ($value instanceof self) $this->_data[$key] = new static($value->toArray(), $this->allowModifications);
                else $this->_data[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * Prevent any more modifications being made to this instance.
     *
     * Useful after merge() has been used to merge multiple Config objects
     * into one object which should then not be modified again.
     *
     * @return Options
     */
    public function lock(): self {
        $this->allowModifications = false;

        /** @var Options $value */
        foreach ($this->data as $value) if ($value instanceof self) $value->lock();

        return $this;
    }

    /**
     * Returns whether this Config object is locked or not.
     *
     * @return bool
     */
    public function isLocked(): bool {
        return !$this->allowModifications;
    }
}
