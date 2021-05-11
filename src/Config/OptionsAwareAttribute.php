<?php
/**
 * Options Aware Attribute
 * 
 * PHP version 8
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Config
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2021 Michael Raab <peep@inane.co.za>
 */
namespace Inane\Config;

use Attribute;

/**
 * Options Aware Attribute
 * 
 * Used with OptionsAwareTrait to set the storage property for the options
 *
 * @package Inane\Helpers
 * @version 1.0.0
 */
#[Attribute (Attribute::TARGET_PROPERTY)]
class OptionsAwareAttribute {
}
