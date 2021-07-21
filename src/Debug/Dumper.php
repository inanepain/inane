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
use function basename;
use function count;
use function file_get_contents;
use function highlight_string;
use function implode;
use function in_array;
use function is_null;
use function str_replace;
use function var_export;

/**
 * Dumper
 * 
 * A simple dump tool that neatly stacks its collapsed dumps on the bottom of the page.
 * 
 * @version 1.0.2
 *
 * @package Inane\Debug
 */
class Dumper {
    /**
     * Single instance of Dumper
     */
    private static Dumper $instance;

    /**
     * Set to false to stop dumper writing to page. instant quiet.
     * PS: this effect manual calls to write dumps as well.
     */
    public static bool $enabled = true;

    /**
     * Stops Dumper automatically writing dumps to page when it is destroyed
     * Calling dump with no arguments will write the dumps collected thus far at that point.
     */
    public static bool $autoDump = true;

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
        if (static::$autoDump) static::dump();
    }

    /**
     * With Args: Add a dump to the collection
     * OR
     * Without Args: Write current dumps to page
     *
     * @param mixed $data item to dump
     * @param null|string $label
     * @param array $options
     * 
     * @return Dumper
     */
    public function __invoke(mixed $data = null, ?string $label = null, array $options = []): static {
        return static::dump($data, $label, $options);
    }

    /**
     * Add a dump to the collection
     *
     * @param mixed $data item to dump
     * @param null|string $label
     * @param array $options
     * 
     * @return void
     */
    protected function addDump(mixed $data, ?string $label = null, array $options = []): void {
        $code = var_export($data, true);
        $code = highlight_string("<?php\n" . $code, true);
        $code = str_replace("&lt;?php<br />", '', $code);

        $open = '';
        if (array_key_exists('open', $options) && $options['open'] === true) $open = ' open';

        static::$dumps[] = <<<DEBUG
<div class="dump">
<details class="dump-window"{$open}>
<summary>{$label}</summary>
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

        $style = file_get_contents(__DIR__ . '/dumper.css');

        return <<<CODE
<style>{$style}</style>
<div class="dumper">
<details class="dumper-window">
<summary class="dumper-title">dumper</summary>
<div class="dumper-body">
{$code}
</div>
</details>
</div>
CODE;
    }

    /**
     * Create a label for the dump with relevant information
     *
     * @param string|null $label
     * 
     * @return string
     */
    protected function buildLabel(?string $label = null): string {
        $i = -1;
        foreach(debug_backtrace() as $trace) {
            $i++;
            if (!in_array( basename($trace['file']), ['Dumper.php', 'index.php'])) break;
        }

        $data = [];
        $a = debug_backtrace()[$i];
        $data['file'] = $a['file'];
        $data['line'] = $a['line'];
        $b = debug_backtrace()[++$i];
        $data['class'] = $b['class'];
        $data['function'] = $b['function'];

        $title = isset($label) ? "<strong class=\"dump-label\">${label}</strong> " : '';
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
     * Chaining: You only need bracket your arguments for repeated dumps.
     * Dumper::dump('one')('two', 'Label')
     *
     * @param mixed $data item to dump
     * @param null|string $label
     * @param array $options
     * 
     * @return Dumper
     */
    public static function dump(mixed $data = null, ?string $label = null, array $options = []): static {
        if (!isset(static::$instance)) static::$instance = new static();

        if (!is_null($data)  && !is_null($label)) {
            $label = static::$instance->buildLabel($label);
            static::$instance->addDump($data, $label, $options);
        } else if (static::$enabled && count(static::$dumps) > 0) {
            echo static::$instance->render();
        }

        return static::$instance;
    }
}
