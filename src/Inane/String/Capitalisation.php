<?php
 /**
 * This file is part of the InaneClasses package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\String
 *
 * @license MIT
 * @license http://www.inane.co.za/license/MIT
 *
 * @copyright 2015-2016 Philip Michael Raab <philip@inane.co.za>
 */
 
namespace Inane\String;

use Inane\Type\Enum;

/**
 * String Capitalisation
 * 
 * @package Inane\String
 * @version 0.1.0
 * 
 * @method static Capitalisation Ignore()
 * @method static Capitalisation UPPERCASE()
 * @method static Capitalisation lowercase()
 * @method static Capitalisation UpperCamelCase()
 * @method static Capitalisation lowerCamelCase()
 * @method static Capitalisation RaNDom()
 */
class Capitalisation extends Enum {
	const Ignore = 'Ignore';
	const UPPERCASE = 'UPPERCASE';
	const lowercase = 'lowercase';
	const UpperCamelCase = 'UpperCamelCase';
	const lowerCamelCase = 'lowerCamelCase';
	const RaNDom = 'RaNDom';
}
