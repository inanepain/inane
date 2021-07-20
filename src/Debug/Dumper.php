<?php
/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Debug
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2021 Philip Michael Raab <philip@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Debug;

use function array_key_exists;
use function highlight_string;
use function implode;
use function is_null;
use function str_replace;
use function var_export;
use function count;

/**
 * Dumper
 * 
 * @version 1.0.0
 *
 * @package Inane\Debug
 */
class Dumper {
    /**
     * Single instance of Dumper
     */
    private static Dumper $instance;

    /**
     * Disable Dumper's output
     */
    public static bool $enabled = true;

    /**
     * The collected dumps
     */
    protected static array $dumps = [];

    /**
     * Private constructor
     */
    protected function __construct() {
    }

    /**
     * When destroyed the dumps get written to page
     */
    public function __destruct() {
        static::dump();
    }

    /**
     * With Args: Add a dump to the collection
     * OR
     * Without Args: Write current dumps to page
     *
     * @param mixed $data item to dump
     * @param null|string $header
     * @param array $options
     * 
     * @return void
     */
    public function __invoke(mixed $data = null, ?string $header = null, array $options = []): void {
        static::dump($data, $header, $options);
    }

    /**
     * Add a dump to the collection
     *
     * @param mixed $data item to dump
     * @param null|string $header
     * @param array $options
     * 
     * @return void
     */
    protected function addDump(mixed $data, ?string $header = null, array $options = []): void {
        $code = var_export($data, true);
        $code = highlight_string("<?php\n" . $code, true);
        $code = str_replace("&lt;?php<br />", '', $code);

        $open = '';
        if (array_key_exists('open', $options) && $options['open'] === true) $open = ' open';

        static::$dumps[] = <<<DEBUG
<div class="ierror">
<details class="iheader"{$open}>
<summary>{$header}</summary>
{$code}
</details>
</div>
DEBUG;
    }

    /**
     * Prepare the string for writing to page
     *
     * @return string
     */
    protected function render(): string {
        $code = implode("\n", static::$dumps);

        static::$dumps = [];

        return <<<CODE
<style>.idebug{position:absolute;bottom:0px;left:0px;z-index:999999999999999;background:#fff;width:100vw;border-top:1px silver solid;font-size:14px}.idebug .idebug-box{background:#f0f8ff;border-bottom:3px gray groove;font-weight:700;color:#8a2be2}.idebug summary:focus{outline:none}.idebug .ierror{border-bottom:1px #000 solid}.idebug .ierror summary{border-bottom:1px gray solid;background:#a9a9a9;padding-left:.5rem}.idebug .ierror summary .iheader{min-width:150px;display:inline-block}.idebug .ierror summary .iheader::after{content:" :";float:right}.idebug .ierror pre{padding-left:1rem}</style>
<div class="idebug">
<details>
<summary class="idebug-box">idebug</summary>
{$code}
</details>
</div>
CODE;
    }

    /**
     * Create a header for the dump with relevant information
     *
     * @param string|null $header
     * 
     * @return string
     */
    protected function buildHeader(?string $header = null): string {
        $data = [];
        $a = debug_backtrace()[1];
        $data['file'] = $a['file'];
        $data['line'] = $a['line'];
        $b = debug_backtrace()[2];
        $data['class'] = $b['class'];
        $data['function'] = $b['function'];

        $title = isset($header) ? "<strong class=\"iheader\">${header}</strong> " : '';
        return "{$title}{$data['class']}::<strong>{$data['function']}</strong> => {$data['file']}::<strong>{$data['line']}</strong>";
    }

    /**
     * With Args: Add a dump to the collection
     * OR
     * Without Args: Write current dumps to page
     * 
     * options:
     *  - (bool) open: true creates dumps open (main panel not effect)
     *
     * @param mixed $data item to dump
     * @param null|string $header
     * @param array $options
     * 
     * @return Dumper
     */
    public static function dump(mixed $data = null, ?string $header = null, array $options = []): static {
        if (!isset(static::$instance)) static::$instance = new static();

        if (!is_null($data)) {
            $header = static::$instance->buildHeader($header);
            static::$instance->addDump($data, $header, $options);
        } else if (static::$enabled && count(static::$dumps) > 0) {
            echo static::$instance->render();
        }

        return static::$instance;
    }
}
