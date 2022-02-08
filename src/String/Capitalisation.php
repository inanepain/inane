<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 8.1
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

/**
 * String Capitalisation
 *
 * @package Inane\String
 * @version 0.3.0
 */
enum Capitalisation: string {
	case Ignore     = 'Ignore';
	case UPPERCASE  = 'UPPERCASE';
	case lowercase  = 'lowercase';
	case StudlyCaps = 'StudlyCaps';
	case camelCase  = 'camelCase';
	case RaNDom     = 'RaNDom';

	/**
	 * Case Description
	 *
	 * @return string
	 */
	public function description(): string {
		return match ($this) {
			static::Ignore => 'Don\'t change case of string.',
			static::UPPERCASE => 'CHANGE STRING TO UPPERCASE',
			static::lowercase => 'change string to lowercase',
			static::StudlyCaps => 'Change String To Studlycaps',
			static::camelCase => 'change String To Camelcase',
			static::RaNDom => 'chANGe StRInG to rAnDOm CApITaliSAtIOn',
		};
	}
}
