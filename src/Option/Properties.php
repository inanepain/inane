<?php

namespace Inane\Option;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Iterator;

use function reset;
use function count;
use function next;
use function current;
use function key;
use function is_null;

/**
 * Properties
 * 
 * @package Inane\Option
 * @version 0.7.0
 */
class Properties extends ArrayIterator implements ArrayAccess, Iterator, Countable {

    /**
     * Variables
     * 
     * @var array
     */
    private $_data = [];

    private $allowModifications = true;

    /**
     * get value
     * @param mixed $key key
     * @return mixed|Properties value
     */
    public function &__get($key) {
        return $this->_data[$key];
    }

    /**
     * Assigns a value to the specified data
     * 
     * @param string The data key to assign the value to
     * @param mixed  The value to set
     * @access public 
     */
    public function __set($key, $value) {
        $this->_data[$key] = $value;
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
     * Unset data by key
     *
     * @param string The key to unset
     * @access public
     */
    public function __unset($key) {
        unset($this->_data[$key]);
    }

    /**
     * HtmlModel
     * 
     * @return void 
     */
    public function __construct(array $data, bool $allowModifications = true) {
        foreach ($data as $key => $value) if (is_array($value)) $this->_data[$key] = new static($value);
        else $this->_data[$key] = $value;
    }

    /**
     * Current
     * 
     * @return mixed|Properties
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
        return !is_null(key($this->_data));
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
    public function count() {
        return count($this->_data);
    }

    /**
     * Key exists
     * 
     * @param string $offset key
     * @return bool exists
     */
    public function offsetExists($offset) {
        return isset($this->_data[$offset]);
    }

    /**
     * get key
     * @param string $offset key
     * @return mixed|Properties value
     */
    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->_data[$offset] : null;
    }

    /**
     * set key
     * @param string $offset key
     * @param mixed $value value
     * @return void 
     */
    public function offsetSet($offset, $value) {
        if (is_null($offset)) $this->_data[] = $value;
        else $this->_data[$offset] = $value;
    }

    /**
     * delete key
     * 
     * @param string $offset key
     * @return void 
     */
    public function offsetUnset($offset) {
        if ($this->offsetExists($offset))
            unset($this->_data[$offset]);
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray() {
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
     * @param Properties ...$models
     * @return Properties
     */
    public function defaults(Properties ...$models): Properties {
        $replaceable = ['', null, false];

        while ($model = array_pop($models)) {
            foreach ($model as $key => $value) {
                if ($value instanceof self && isset($this[$key]) && $this[$key] instanceof self) {
                    $this[$key]->defaults($value);
                } else {
                    if (!$this->offsetExists($key) || in_array($this[$key], $replaceable))
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
    public function merge(Properties $merge): self {
        /** @var Properties $value */
        foreach ($merge as $key => $value) {
            if (array_key_exists($key, $this->_data)) {
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
}
