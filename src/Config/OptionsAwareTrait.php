<?php

/**
 * This file is part of the InaneTools package.
 * 
 * PHP version 8
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Config
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\Config;

use ReflectionObject;

/**
 * OptionsAwareTrait
 * 
 * @uses OptionsAwareInterface::setOptions Implements interface methods
 *
 * @package Inane\Helpers
 * @version 1.0.0
 */
trait OptionsAwareTrait {
    /**
     * @example OptionsAwareAttribute property for options storage
     */
    // #[OptionsAwareAttribute]
    // protected Options $data;

    /**
     * _OptionsAwareTraitProperty
     */
    private string $_OptionsAwareTraitProperty;

    /**
     * {@inheritDoc}
     * @see \Inane\Config\OptionsAwareInterface::setOptions()
     */
    public function setOptions(Options $data): mixed {
        if (!isset($this->_OptionsAwareTraitProperty)) {
            $reflection = new ReflectionObject($this);
            foreach ($reflection->getProperties() as $property) {
                $attributes = $property->getAttributes(OptionsAwareAttribute::class);
                if (count($attributes) > 0)
                    $this->_OptionsAwareTraitProperty = $property->getName();
            }
        }

        if (!isset($this->{$this->_OptionsAwareTraitProperty})) $this->{$this->_OptionsAwareTraitProperty} = $data;
        return $this->{$this->_OptionsAwareTraitProperty};
    }
}
