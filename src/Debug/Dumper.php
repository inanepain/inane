<?php
/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * PHP version 8
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

use Inane\String\Style;

use const PHP_EOL;

use function basename;
use function count;
use function file_get_contents;
use function highlight_string;
use function implode;
use function in_array;
use function ob_start;
use function php_sapi_name;
use function str_replace;
use function str_starts_with;
use function var_export;

/**
 * Dumper
 * 
 * A simple dump tool that neatly stacks its collapsed dumps on the bottom of the page.
 * 
 * @version 1.3.0
 *
 * @package Inane\Debug
 */
class Dumper {
    /**
     * Single instance of Dumper
     */
    private static Dumper $instance;

    /**
     * Colour codes for console
     */
    private static array $colour = [
        'B' => "\033[30m",
        'r' => "\033[31m",
        'g' => "\033[32m",
        'y' => "\033[33m",
        'b' => "\033[34m",
        'm' => "\033[35m",
        'c' => "\033[36m",
        'LG' => "\033[37m",
        'dg' => "\033[90m",
        'lr' => "\033[91m",
        'lg' => "\033[92m",
        'ly' => "\033[93m",
        'lb' => "\033[94m",
        'lm' => "\033[95m",
        'lc' => "\033[96m",
        'w' => "\033[97m",
        'e' => "\033[0m",
    ];

    /**
     * Set to false to stop dumper writing to page. instant quiet.
     * PS: this effect manual calls to write dumps as well.
     */
    public static bool $enabled = true;

    /**
     * Code style theme for dumper
     */
    public static Style $style;

    /**
     * The collected dumps
     */
    protected static array $dumps = [];

    /**
     * Running in console
     *
     * @return bool
     */
    protected static function isCli(): bool {
        return (str_starts_with(php_sapi_name(), 'cli') || php_sapi_name() === 'cli-server');
    }

    /**
     * Private constructor
     */
    protected function __construct() {
        static::$style = Style::DEFAULT();
    }

    /**
     * When destroyed the dumps get written to page
     */
    public function __destruct() {
        if (static::$enabled && count(static::$dumps) > 0) {
            ob_start();
            // echo static::$instance->render();
            echo $this->render();
        }
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
     * Prepare the string for writing to page
     *
     * @return string
     */
    protected function render(): string {
        if (static::isCli()) {
            $c = (object) static::$colour;

            $code = implode("{$c->y}==========================================================================={$c->e} \n", static::$dumps);
            static::$dumps = [];
            return "{$c->m}DUMPER{$c->e}\n{$code}";
        }

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
    protected function formatLabel(?string $label = null): string {
        $i = -1;
        foreach (debug_backtrace() as $trace) {
            $i++;
            if (!in_array(basename($trace['file']), ['Dumper.php', 'index.php'])) break;
        }

        $backtrace = debug_backtrace();

        $data = [];
        $a = debug_backtrace()[$i];
        $data['file'] = $a['file'] ?? '';
        $data['line'] = $a['line'] ?? '';

        if (count($backtrace) <= ($i)) {
            $b = debug_backtrace()[++$i];
            $data['class'] = $b['class'] ?? false;
            $data['function'] = $b['function'] ?? '';
        } else {
            $data['class'] = false;
        }

        // CHECK CONSOLE
        if (static::isCli()) {
            $c = (object) static::$colour;

            $title = isset($label) ? "{$c->b} ${label}:{$c->e} " : '';
            $file = "{$c->w}{$data['file']}{$c->e}::{$c->r}{$data['line']}{$c->e}";
            $class = $data['class'] ? " => {$c->y}{$data['class']}::{$data['function']}{$c->e}" : '';
        } else {
            // HTML
            $title = isset($label) ? "<strong class=\"dump-label\">${label}</strong> " : '';
            $file = "{$data['file']}::<strong>{$data['line']}</strong>";
            $class = $data['class'] ? " => {$data['class']}::<strong>{$data['function']}</strong>" : '';
        }
        return "{$title}{$file}{$class}" . PHP_EOL;
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
        // CHECK CONSOLE
        if (static::isCli()) {
            $code = var_export($data, true);
            static::$dumps[] = "{$label}{$code}" . PHP_EOL;
            return;
        }

        // HTML
        $style = $options['style'] ?? static::$style;
        $style->apply();

        $code = var_export($data, true);
        $code = highlight_string("<?php\n" . $code, true);
        $code = str_replace("&lt;?php<br />", '', $code);

        $text = trim($code);
        $text = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", '', $text, 1);  // remove prefix
        $text = preg_replace("|\\</code\\>\$|", '', $text, 1);  // remove suffix 1
        $text = trim($text);  // remove line breaks
        $text = preg_replace("|\\</span\\>\$|", '', $text, 1);  // remove suffix 2
        $text = trim($text);  // remove line breaks
        $code = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $text);  // remove custom added "<?php "

        $open = ($options['open'] ?? false) ? 'open' : '';

        static::$dumps[] = <<<DEBUG
<div class="dump">
<details class="dump-window"{$open}>
<summary>{$label}</summary>
<code>
{$code}
</code>
</details>
</div>
DEBUG;
    }

    /**
     * Add a dump to the collection
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

        $label = static::$instance->formatLabel($label);
        static::$instance->addDump($data, $label, $options);

        return static::$instance;
    }
}
