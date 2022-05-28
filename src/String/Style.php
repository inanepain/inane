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
 * @package Inane\String
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\String;

use Inane\Type\Enum;

/**
 * Highlight Styles
 *
 * @package Inane\String
 * @version 0.2.0
 *
 * @method static Style DEFAULT()
 * @method static Style PHP2()
 * @method static Style HTML()
 */
class Style extends Enum {
    const DEFAULT = 'php';
    const PHP2 = 'php2';
    const HTML = 'html';

    /**
     * @var string[] the descriptions
     */
    protected static array $descriptions = [
        Style::DEFAULT => 'The colours straight out the box.',
        Style::PHP2 => 'Somebody\'s idea of what the default should.',
        Style::HTML => 'An html styled colour theme.',
    ];

    /**
     * @var array style values
     */
    protected static array $defaults = [
        Style::DEFAULT => [
            'highlight.comment' => '#FF8000',
            'highlight.default' => '#0000BB',
            'highlight.html'    => '#000000',
            'highlight.keyword' => '#007700',
            'highlight.string'  => '#DD0000'
        ],
        Style::PHP2 => [
            'highlight.comment' => '#008000',
            'highlight.default' => '#000000',
            'highlight.html'    => '#808080',
            'highlight.keyword' => '#0000BB; font-weight: bold',
            'highlight.string'  => '#DD0000'
        ],
        Style::HTML => [
            'highlight.comment' => '#008000',
            'highlight.default' => '#CC0000',
            'highlight.html'    => '#000000',
            'highlight.keyword' => '#000000; font-weight: bold',
            'highlight.string'  => '#0000FF'
        ],
    ];

    /**
     * Apply this style
     *
     * @return void
     */
    public function apply(): void {
        static::applyStyle($this);
    }

    /**
     * Apply $style
     *
     * @param Style $style
     *
     * @return void
     */
    public static function applyStyle(Style $style): void {
        foreach ($style->getDefault() as $key => $val) ini_set($key, $val);
    }
}
