<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 8.0
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\String
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\String;

use Inane\Type\Enum;

/**
 * String Capitalisation
 *
 * @package Inane\String
 * @version 0.2.0
 *
 * @method static Capitalisation Ignore()
 * @method static Capitalisation UPPERCASE()
 * @method static Capitalisation lowercase()
 * @method static Capitalisation StudlyCaps()
 * @method static Capitalisation camelCase()
 * @method static Capitalisation RaNDom()
 */
class Capitalisation extends Enum {
	const Ignore = 'Ignore';
	const UPPERCASE = 'UPPERCASE';
	const lowercase = 'lowercase';
	const StudlyCaps = 'StudlyCaps';
	const camelCase = 'camelCase';
	const RaNDom = 'RaNDom';

	/**
	 * @var string[] the descriptions
	 */
	protected static array $descriptions = [
		self::Ignore => 'Don\'t change case of string.',
		self::UPPERCASE => 'CHANGE STRING TO UPPERCASE',
		self::lowercase => 'change string to lowercase',
		self::StudlyCaps => 'Change String To Studlycaps',
		self::camelCase => 'change String To Camelcase',
		self::RaNDom => 'chANGe StRInG to rAnDOm CApITaliSAtIOn',
	];
}
