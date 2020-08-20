<?php
/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\File
 *
 * @license MIT
 * @license http://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\File;

use Inane\String\Capitalisation;

use function strtoupper;
use function strtolower;
use function md5_file;
use function file_exists;
use function floor;
use function pow;
use function sprintf;
use function rtrim;
use function glob;
use function array_pop;

use const FILEINFO_MIME_TYPE;

/**
 * File metadata
 *
 * @package Inane\File
 * @version 0.5.0
 */
class FileInfo extends \SplFileInfo
{
    /**
     * Get the file extension
     *
     * @param Capitalisation    $case Optional: Capitalisation only UPPERCASE and lowercase have any effect
     * {@inheritDoc}
     * @see \SplFileInfo::getExtension()
     */
    public function getExtension(Capitalisation $case = null)
    {
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
     * @return string|null
     */
    public function getHumanSize($decimals = 2)
    {
        return self::humanSize(parent::getSize(), $decimals);
    }

    /**
     * Return md5 hash
     * @return string|null
     */
    public function getMd5()
    {
        return md5_file(parent::getPathname());
    }

    /**
     * Return the mime type
     *
     * @return string|null
     */
    public function getMimetype()
    {
        return (new \finfo())->file(parent::getPathname(), FILEINFO_MIME_TYPE);
    }

    /**
     * True if file exists
     *
     * @return bool
     */
    public function isValid()
    {
        return file_exists(parent::getPathname());
    }

    /**
     * Convert bites to human readable size
     *
     * @param number $size
     * @param number $decimals
     * @return string
     */
    protected function humanSize($size,int $decimals = 2): string
    {
        $sizes = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($size) - 1) / 3);
        $formatedSize = sprintf("%.{$decimals}f", $size / pow(1024, $factor));

        return rtrim($formatedSize, '0.').' '.@$sizes[$factor];
    }

    /**
     * Get files in dir
     *
     * @param string $filter
     * @return array|null
     */
    public function getFiles(string $filter = '*'): ?array {
        return glob(parent::getPathname().'/'.$filter) ?? null;
    }

    /**
     * Ges file in dir
     *
     * @param string $file the file to get
     * @return string|null
     */
    public function getFile(string $file): ?string {
        $file = array_pop(glob(parent::getPathname().'/'.$file));
        return $file;
    }
}
