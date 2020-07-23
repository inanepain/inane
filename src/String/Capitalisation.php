<?php
 /**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\String
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\String;

use Inane\Type\Enum;

/**
 * String Capitalisation
 *
 * @package Inane\String
 * @namespace \Inane\String
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
	protected static $descriptions = [
		'Ignore' => 'Don\'t change case of string.',
		'UPPERCASE' => 'CHANGE STRING TO UPPRCASE',
		'lowercase' => 'change string to lowercase',
		'StudlyCaps' => 'Change String To Studlycaps',
		'camelCase' => 'change String To Camelcase',
		'RaNDom' => 'chANGe StRInG to rAnDOm CApITaliSAtIOn',
	];
}
