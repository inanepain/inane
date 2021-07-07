<?php

/**
 * File Info
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * PHP version 7
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\File
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\File;

use Inane\String\Capitalisation;

use SplFileInfo;

use function array_pop;
use function file_exists;
use function file_get_contents;
use function floor;
use function glob;
use function md5_file;
use function pow;
use function rtrim;
use function sprintf;
use function strtolower;
use function strtoupper;
use function unserialize;

/**
 * File metadata
 *
 * @method FileInfo getFileInfo()
 * 
 * @package Inane\File
 * @version 0.6.0
 */
class FileInfo extends SplFileInfo {
    public function __construct(string $file_name) {
        parent::__construct($file_name);
        $this->setInfoClass(static::class);
    }
    /**
     * Get the file extension
     *
     * @param Capitalisation    $case Optional: Capitalisation only UPPERCASE and lowercase have any effect
     * {@inheritDoc}
     * @see \SplFileInfo::getExtension()
     */
    public function getExtension(Capitalisation $case = null) {
        $ext = parent::getExtension();

        switch ($case) {
            case Capitalisation::UPPERCASE:
                $ext = strtoupper($ext);
                break;

            case Capitalisation::lowercase:
                $ext = strtolower($ext);
                break;

            default:

                break;
        }

        return $ext;
    }

    /**
     * Return human readable size (Kb, Mb, ...)
     *
     * @param int $decimals
     * @return string|null
     */
    public function getHumanSize($decimals = 2): ?string {
        return $this->humanSize(parent::getSize(), $decimals);
    }

    /**
     * Return md5 hash
     * @return string|bool
     */
    public function getMd5(): string|bool {
        return md5_file(parent::getPathname());
    }

    /**
     * Return the mime type
     *
     * @param string|null $default if not matched
     * 
     * @return null|string
     */
    public function getMimetype(?string $default = null): ?string {
        $mimes = unserialize(file_get_contents(__DIR__.'/../../mimeic.blast'));
        return $mimes['mimes'][$this->getExtension(Capitalisation::lowercase())] ?? $default;
        // return (new finfo())->file(parent::getPathname(), FILEINFO_MIME_TYPE);
    }

    /**
     * True if file exists
     *
     * @return bool
     */
    public function isValid(): bool {
        return file_exists(parent::getPathname());
    }

    /**
     * Convert bites to human readable size
     *
     * @param int $size
     * @param int $decimals
     * @return string
     */
    protected function humanSize(int $size, int $decimals = 2): string {
        $sizes = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($size) - 1) / 3);
        $formattedSize = sprintf("%.{$decimals}f", $size / pow(1024, $factor));

        return rtrim($formattedSize, '0.') . ' ' . @$sizes[$factor];
    }

    /**
     * Get files in dir
     *
     * @param string $filter
     * @return array|null
     */
    public function getFiles(string $filter = '*'): ?array {
        return glob(parent::getPathname() . '/' . $filter) ?? null;
    }

    /**
     * Ges file in dir
     *
     * @param string $file the file to get
     * @return string|null
     */
    public function getFile(string $file): ?string {
        $file = array_pop(glob(parent::getPathname() . '/' . $file));
        return $file;
    }
}
