<?php

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
 * @package Inane\Version
 *
 * @version 1.0.0
 */
class Version implements Stringable {
    /**
     * Version stripped of everything but digits and periods
     *
     * @var string
     */
    private readonly string $cleanVersion;

    public function __construct(private readonly string $version) {
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
     * @return string clean
     */
    public static function parseVersion(string $version): string {
        preg_match_all('/[0-9]+/', $version, $match);
        return implode('.', $match[0]);
    }

    /**
     * Is $version higher, equal or lower than this
     *
     * @param string $version requiring validation
     * @return \Inane\Version\VersionMatch $version match
     */
    public function compare(string $version): VersionMatch {
        return VersionMatch::from(version_compare($version, $this->cleanVersion));
        // return match (version_compare($version, $this->version)) {
        //     -1 => VersionMatch::LOWER,
        //     0 => VersionMatch::EQUAL,
        //     1 => VersionMatch::HIGHER,
        // };
    }

    /**
     * Require $version to be higher than this
     *
     * @param string $version to check
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
        // return match ($this->compare($version)->value) {
        //     1, 0 => true,
        //     default => false
        // };
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
