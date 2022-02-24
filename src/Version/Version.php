<?php

/**
 * Version
 *
 * This file is part of the InaneTools package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 8.1
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Tools
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2022 Philip Michael Raab <philip@inane.co.za>
 */
declare(strict_types=1);

namespace Inane\Version;

use Stringable;

use function is_null;
use function version_compare;

/**
 * Version
 *
 * A target version others get compared against
 *
 * @package Inane\Tools
 *
 * @version 1.1.0
 */
class Version implements Stringable {
    /**
     * Clean the comparing version before comparing to this version
     *
     * @var bool
     */
    public static bool $cleanBeforeCompare = true;

    /**
     * Version stripped of everything but digits and periods
     *
     * @var string
     */
    private readonly string $cleanVersion;

    /**
     * Version
     *
     * @param string $version
     */
    public function __construct(
        /**
         * This version
         *
         * @var string
         */
        private readonly string $version,
    ) {
        $this->cleanVersion = static::parseVersion($version);
    }

    /**
     * Invoke dual actions
     *
     * - with parameter $version => compare
     * - without parameter => this version
     *
     * @param null|string $version to compare or null to retrieve this version
     *
     * @return string|\Inane\Version\VersionMatch comparison or version
     */
    public function __invoke(?string $version = null): string|VersionMatch {
        if (is_null($version)) return "{$this}";
        return $this->compare($version);
    }

    /**
     * This version
     *
     * @return string version
     */
    public function __toString(): string {
        return $this->version;
    }

    /**
     * Parse $version for easy comparisons
     *
     * @param string $version to clean
     *
     * @return string clean version
     */
    public static function parseVersion(string $version): string {
        preg_match_all('/[0-9]+/', $version, $match);
        return implode('.', $match[0]);
    }

    /**
     * Is $version higher, equal or lower than this
     *
     * @param string $version requiring validation
     *
     * @return null|\Inane\Version\VersionMatch $version match or null if incomparable
     */
    public function compare(string $version): ?VersionMatch {
        if (static::$cleanBeforeCompare) $version = static::parseVersion($version);

        return VersionMatch::tryFrom(version_compare($version, $this->cleanVersion));
    }

    /**
     * Require $version to be higher than this
     *
     * @param string $version to check
     *
     * @return bool is $version higher
     */
    public function higher(string $version): bool {
        return VersionMatch::HIGHER == $this->compare($version);
    }

    /**
     * Require $version to be higher or equal to this
     *
     * @param string $version to check
     *
     * @return bool is $version higher or equal
     */
    public function higherOrEqual(string $version): bool {
        return $this->compare($version)->value >= VersionMatch::EQUAL->value;
    }

    /**
     * Require $version to be equal to this
     *
     * @param string $version to check
     *
     * @return bool is $version equal
     */
    public function equal(string $version): bool {
        return VersionMatch::EQUAL == $this->compare($version);
    }

    /**
     * Require $version to be lower or equal to this
     *
     * @param string $version to check
     *
     * @return bool is $version lower or equal
     */
    public function lowerOrEqual(string $version): bool {
        return $this->compare($version)->value <= VersionMatch::EQUAL->value;
        // return match ($this->compare($version)->value) {
        //     -1, 0 => true,
        //     default => false
        // };
    }

    /**
     * Require $version to be lower than this
     *
     * @param string $version to check
     *
     * @return bool is $version lower
     */
    public function lower(string $version): bool {
        return VersionMatch::LOWER == $this->compare($version);
    }
}
