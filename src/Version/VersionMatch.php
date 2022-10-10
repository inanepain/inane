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
 * @version $Id$
 * $Date$
 */

declare(strict_types=1);

namespace Inane\Version;

/**
 * VersionMatch
 *
 * @version 1.0.0
 *
 * @package Inane\Tools
 */
enum VersionMatch: int {
    case LOWER = -1;
    case EQUAL = 0;
    case HIGHER = 1;
}
