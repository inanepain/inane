<?php

/**
 * This file is part of the InaneTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\String
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2018 Philip Michael Raab <philip@inane.co.za>
 */
/* vscode: vscode-fold=2 */

namespace Inane\String;

use Inane\Option\MagicPropertyTrait as OptionMagicPropertyTrait;
use Inane\String\Capitalisation;

use function array_merge;
use function count;
use function in_array;
use function lcfirst;
use function mt_rand;
use function rand;
use function str_replace;
use function strlen;
use function strrpos;
use function strstr;
use function strtolower;
use function strtoupper;
use function substr_replace;
use function trim;
use function ucwords;

/**
 * Str
 * 
 * @package Inane\String\Str
 * @property-read public length
 * @property public string
 * @version 0.2.0
 */
class Str {
    use OptionMagicPropertyTrait;

    /**
     * Capitalisation
     */
    protected $_case = Capitalisation::Ignore;

    /**
     * String
     */
    protected string $_str = '';

    /**
     * Creates instance of Str object
     *
     * @param string $string
     */
    public function __construct(string $string = '') {
        if ($string) $this->_str = $string;
    }

    /**
     * magic method: _get
     *
     * @param string $property
     * 
     * @return mixed
     */
    public function __get($property) {
        if (!in_array($property, ['length', 'string']))
            throw new \Exception("Invalid Property:\n\tStr has no property: {$property}");

        $methods = [
            'length' => 'length',
            'string' => 'getString'
        ];

        return $this->{$methods[$property]}();
    }

    /**
     * magic method: _set
     *
     * @param string $property
     * @param mixed $value
     * 
     * @return mixed
     */
    public function __set($property, $value) {
        if (!in_array($property, ['string']))
            throw new \Exception("Invalid Property:\n\tStr has no property: {$property}");

        $methods = [
            'length' => 'length',
            'string' => 'setString'
        ];

        $this->{$methods[$property]}($value);

        return $this;

        $method = $this->parseMethodName($property, 'set');
        $this->$method($value);
    }

    /**
     * Echoing the Str object print out the string
     *
     * @return string
     */
    public function __toString(): string {
        return $this->_str;
    }

    /**
     * Append str to Str
     *
     * @param string $str
     * 
     * @return Str
     */
    public function append(string $str): Str {
        $this->_str .= $str;

        return $this;
    }

    /**
     * Check if Str contains needle
     *
     * @param string $needle
     * 
     * @return bool
     */
    public function contains(string $needle): bool {
        return self::str_contains($needle, $this->_str);
    }

    /**
     * getString
     * 
     * @return string
     */
    public function getString(): string {
        return $this->_str;
    }

    /**
     * length of str
     *
     * @return int
     */
    public function length(): int {
        return strlen($this->_str);
    }

    /**
     * Prepend str to Str
     *
     * @param string $str
     * 
     * @return Str
     */
    public function prepend(string $str): Str {
        $this->_str = "{$str}{$this->_str}";

        return $this;
    }

    /**
     * Replaces last match of search with replace
     *
     * @param string $search
     * @param string $replace
     * 
     * @return Str
     */
    public function replaceLast(string $search, string $replace): Str {
        $this->_str = self::str_replace_last($search, $replace, $this->_str);

        return $this;
    }

    /**
     * Replaces last match of search with replace
     *
     * @param string $search
     * @param string $replace
     * 
     * @return Str
     */
    public function replace(string $search, string $replace): Str {
        $this->_str = str_replace($search, $replace, $this->_str);

        return $this;
    }

    /**
     * @param string $string
     *
     * @return Str
     */
    public function setString(string $string): Str {
        $this->_str = $string;

        return $this;
    }

    /**
     * Check if haystack contains needle
     *
     * @param string $needle
     * @param string $haystack
     * 
     * @return bool
     */
    public static function str_contains(string $needle, string $haystack): bool {
        return strstr($haystack, $needle);
    }

    /**
     * Replaces last match of search with replace in str
     *
     * @param string $search
     * @param string $replace
     * @param string $str
     * 
     * @return string
     */
    public static function str_replace_last(string $search, string $replace, string $str): string {
        if (($pos = strrpos($str, $search)) !== false) {
            $search_length = strlen($search);
            $str = substr_replace($str, $replace, $pos, $search_length);
        }

        return $str;
    }

    /**
     * Changes the case of $string to $case and optionally removes spaces
     *
     * @param String $string
     * @param Capitalisation $case
     * @param bool $removeSpaces
     *
     * @return string
     */
    public static function str_to_case(String $string, Capitalisation $case, bool $removeSpaces = false): string {
        // $RaNDom = function($text) {
        //     for ($i = 0, $c = strlen($text); $i < $c; $i++) {
        //         $text[$i] = (rand(0, 100) > 50
        //             ? strtoupper($text[$i])
        //             : strtolower($text[$i]));
        //     }
        //     return $text;
        // };

        
        // $string = match($case) {
        //     Capitalisation::UPPERCASE() => strtoupper($string),
        //     Capitalisation::lowercase() => strtolower($string),
        //     Capitalisation::camelCase() => lcfirst(ucwords(strtolower($string))),
        //     Capitalisation::StudlyCaps() => ucwords(strtolower($string)),
        //     Capitalisation::RaNDom() => $RaNDom($string),
        //     Capitalisation::Ignore() => $string,
        // };

        switch ($case) {
            case Capitalisation::UPPERCASE:
                $string = strtoupper($string);
                break;

            case Capitalisation::lowercase:
                $string = strtolower($string);
                break;

            case Capitalisation::camelCase:
                $string = lcfirst(ucwords(strtolower($string)));
                break;

            case Capitalisation::StudlyCaps:
                $string = ucwords(strtolower($string));
                break;

            case Capitalisation::RaNDom:
                for ($i = 0, $c = strlen($string); $i < $c; $i++) {
                    $string[$i] = (rand(0, 100) > 50
                        ? strtoupper($string[$i])
                        : strtolower($string[$i]));
                }

                break;

            default:

                break;
        }

        if ($removeSpaces) $string = str_replace(' ', '', $string);

        return $string;
    }

    /**
     * Create Str with $length random characters
     *
     * @param int $length
     * @return Str
     */
    public static function stringWithRandomCharacters(int $length = 6): Str {
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;

        $str = new self();
        while ($str->length < $length) {
            $rand = mt_rand(0, $max);
            $str->append($characters[$rand]);
        }

        return $str;
    }

    /**
     * Changes the case of Str to $case and optionally removes spaces
     *
     * @param Capitalisation $case
     * @param bool $removeSpaces
     * @return Str
     */
    public function toCase(Capitalisation $case, bool $removeSpaces = false): Str {
        $this->_str = self::str_to_case($this->_str, $case, $removeSpaces);
        $this->_case = $case;

        return $this;
    }

    /**
     * Trim chars from beginning and end of string default chars ' ,:-./\\`";'
     *
     * @param string $chars to trim
     * @return Str
     */
    public function trim(string $chars = ' ,:-./\\`";'): Str {
        $this->_str = trim($this->_str, $chars);

        return $this;
    }

    /**
     * highlight str
     * 
     * @param string $style (default, php, php2, html)
     * 
     * @return Str
     */
    public function highlight(string $style = 'default'): Str {
        if ($style == 'php') {
            ini_set('highlight.comment', '#FF8000');
            ini_set('highlight.default', '#0000BB');
            ini_set('highlight.html', '#000000');
            ini_set('highlight.keyword', '#007700');
            ini_set('highlight.string', '#DD0000');
        } else if ($style == 'php2') {
            ini_set('highlight.comment', '#008000');
            ini_set('highlight.default', '#000000');
            ini_set('highlight.html', '#808080');
            ini_set('highlight.keyword', '#0000BB; font-weight: bold');
            ini_set('highlight.string', '#DD0000');
        } else if ($style == 'html') {
            ini_set('highlight.comment', 'green');
            ini_set('highlight.default', '#CC0000');
            ini_set('highlight.html', '#000000');
            ini_set('highlight.keyword', 'black; font-weight: bold');
            ini_set('highlight.string', '#0000FF');
        }

        $text = trim($this->_str);
        $text = highlight_string('<?php ' . $text, true);  // highlight_string() requires opening PHP tag or otherwise it will not colorize the text
        $text = trim($text);
        $text = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", '', $text, 1);  // remove prefix
        $text = preg_replace("|\\</code\\>\$|", '', $text, 1);  // remove suffix 1
        $text = trim($text);  // remove line breaks
        $text = preg_replace("|\\</span\\>\$|", '', $text, 1);  // remove suffix 2
        $text = trim($text);  // remove line breaks
        $this->_str = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $text);  // remove custom added "<?php "

        return $this;
    }

    /**
     * highlight str
     * 
     * @param string $style (default, php, php2, html)
     * 
     * @return Str
     */
    public static function highlightText(string $text, string $style = 'default'): Str {
        $new = new static($text);
        return $new->highlight($style);
    }
}
