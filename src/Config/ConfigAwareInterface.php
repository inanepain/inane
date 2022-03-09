<?php

/**
 * Inane Tools
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 8.1
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Config
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2022 Philip Michael Raab <philip@inane.co.za>
 */

/**
 * Examples (controller & Service)
 * Using a config key: myconfig
 *
 * config/autoload/myconfig.global.php
 *
 * return array(
 *    'myconfig' => ['test' => true],
 *);
 *
 * Module.php
 *
 * use Inane\Config\ConfigAwareInterface;
 *
 * public function getControllerConfig() {
 *		return array(
 *			'initializers' => array(
 *				function ($instance, $sm) {
 *					if ($instance instanceof ConfigAwareInterface) {
 *						$locator = $sm->getServiceLocator();
 *						$config = $locator->get('Config');
 *						$instance->setConfig($config['myconfig']);
 *					}
 *				}));
 *	}
 *
 * public function getServiceConfig() {
 * 		return array(
 * 			'initializers' => array(
 * 				function ($instance, $sm) {
 * 					if ($instance instanceof ConfigAwareInterface) {
 * 						$config = $sm->get('Config');
 * 						$instance->setConfig($config['myconfig']);
 * 					}
 * 				}));
 * 	}
 *
 * IndexController.php
 *
 * use Inane\Config\ConfigAwareInterface;
 *
 * class IndexController extends AbstractActionController implements ConfigAwareInterface {
 *
 * protected $config;
 *
 * 	public function setConfig($config) {
 * 		$this->config = $config;
 * 	}
 */

declare(strict_types=1);

namespace Inane\Config;

/**
 * ConfigAwareInterface
 *
 * @package Inane\Config
 *
 * @version 1.0.0
 */
interface ConfigAwareInterface {

	/**
	 * configuration
	 *
	 * @param array|Options $config configuration
	 *
	 * @return void
	 */
	public function setConfig(array|Options $config): void;
}
