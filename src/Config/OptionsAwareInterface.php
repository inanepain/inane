<?php
/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * PHP version 8
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Config
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2021 Michael Raab <peep@inane.co.za>
 */
namespace Inane\Config;

/**
 * OptionsAwareInterface
 *
 * @package Inane\Helpers
 * @version 1.0.0
 */
interface OptionsAwareInterface {

	/**
     * set: options
     * 
	 * @param Options $data options
     * @return mixed $this
	 */
	public function setOptions(Options $data): mixed;
}
