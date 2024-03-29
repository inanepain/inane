<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Version
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Tools;

use Laminas\Http;

use function file_get_contents;
use function ini_get;
use function preg_replace;
use function sprintf;
use function strtolower;
use function trigger_error;
use function version_compare;
use const E_USER_WARNING;

/**
 * InaneClasses Version
 *
 * @package Inane\Version
 * @version 0.1.2
 */
final class Version {
	/**
	 * Inane Classes version identification - see compareVersion()
	 */
	const VERSION = '0.30.0';

	/**
	 * Inane (inane.co.za) Service Identifier for version information is retrieved from
	 */
	const VERSION_SERVICE_INANE = 'INANE';

	/**
	 * Local (inane.local) Service Identifier for version information is retrieved from
	 */
	const VERSION_SERVICE_LOCAL = 'LOCAL';

	/**
	 * The latest stable version Inane Classes available
	 *
	 * @var string
	 */
	protected static $latestVersion;

	/**
	 * Compare the specified Inane Classes version string $version
	 * with the current Inane\Version\Version::VERSION of Inane Classes.
	 *
	 * @param string $version A version string (e.g. "0.7.1").
	 * @return int -1 if the $version is older,
	 *         0 if they are the same,
	 *         and +1 if $version is newer.
	 *
	 */
	public static function compareVersion($version) {
		$version = strtolower($version);
		$version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);

		return version_compare($version, strtolower(self::VERSION));
	}

	/**
	 * Fetches the version of the latest stable release.
	 *
	 * By default, this uses the API provided by inane.co.za for version
	 * retrieval.
	 *
	 * @api
	 *
	 * @param string $service Version service with which to retrieve the version
	 * @param Http\Client $httpClient HTTP client with which to retrieve the version
	 *
	 * @return string the latest version
	 */
	public static function getLatest($service = self::VERSION_SERVICE_INANE, Http\Client $httpClient = null) {
		if (null !== self::$latestVersion) {
			return self::$latestVersion;
		}

		self::$latestVersion = 'not available';

		if (null === $httpClient && !ini_get('allow_url_fopen')) {
			trigger_error(sprintf('allow_url_fopen is not set, and no Laminas\Http\Client ' . 'was passed. You must either set allow_url_fopen in ' . 'your PHP configuration or pass a configured ' . 'Laminas\Http\Client as the second argument to %s.', __METHOD__), E_USER_WARNING);

			return self::$latestVersion;
		}

		$response = false;
		if ($service === self::VERSION_SERVICE_INANE) {
			$response = self::getLatestFromUrl($httpClient);
		} elseif ($service === self::VERSION_SERVICE_LOCAL) {
			$response = self::getLatestFromUrl($httpClient, 'http://inane.local/project/version/inaneclasses');
		} else {
			trigger_error(sprintf('Unknown version service: %s', $service), E_USER_WARNING);
		}

		if ($response) {
			self::$latestVersion = $response;
		}

		return self::$latestVersion;
	}

	/**
	 * Returns true if the running version of Inane Classes is
	 * the latest (or newer??) than the latest returned by self::getLatest().
	 *
	 * @api
	 *
	 * @return bool true if latest
	 */
	public static function isLatest() {
		return self::compareVersion(self::getLatest()) < 1;
	}

	/**
	 * Get the API response to a call from a configured HTTP client
	 *
	 * @param Http\Client $httpClient Configured HTTP client
	 * @return string|false API response or false on error
	 */
	protected static function getApiResponse(Http\Client $httpClient) {
		try {
			$response = $httpClient->send();
		} catch (Http\Exception\RuntimeException $e) {
			return false;
		}

		if (!$response->isSuccess()) {
			return false;
		}

		return $response->getBody();
	}

	/**
	 * Get the latest version from inane.co.za
	 *
	 * @param Http\Client $httpClient Configured HTTP client
	 * @param string $url the url used to get the latest version
	 *
	 * @return boolean|string API response or false on error
	 */
	protected static function getLatestFromUrl(Http\Client $httpClient = null, $url = null) {
		if ($url === null)
			$url = 'https://inane.co.za/ts/v/inaneclasses';

		if ($httpClient === null) {
			$apiResponse = file_get_contents($url);
		} else {
			$request = new Http\Request();
			$request->setUri($url);
			$httpClient->setRequest($request);
			$apiResponse = self::getApiResponse($httpClient);
		}

		if (!$apiResponse) {
			return false;
		}

		return $apiResponse;
	}
}
