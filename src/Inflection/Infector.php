<?php

/**
 * Infector
 * 
 * PHP version 8
 * 
 * @author Philip Michael Raab <peep@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Inflection;

use Inane\Exception\StubException;

use function array_merge;
use function count;
use function in_array;
use function preg_match;
use function preg_replace;

/**
 * Infector
 * 
 * @version 1.0.0
 */
class Infector {
    /**
     * Rules
     * 
     * @var array
     */
    protected static array $rules = [
        'pluralise' => [
            '/(oxen|octopi|viri|aliases|quizzes)$/' => '$1',
            '/(people|men|children|sexes|moves|stadiums)$/' => '$1',
            '/(quiz)$/' => '$1zes',
            '/^(ox)$/' => '$1en',
            '/([m|l])ice$/' => '$1ice',
            '/([m|l])ouse$/' => '$1ice',
            '/(matr|vert|ind)ix|ex$/' => '$1ices',
            '/(x|ch|ss|sh)$/' => '$1es',
            '/([^aeiouy]|qu)y$/' => '$1ies',
            '/(hive)$/' => '$1s',
            '/(?:([^f])fe|([lr])f)$/' => '$1$2ves',
            '/sis$/' => 'ses',
            '/([ti])a$/' => '$1a',
            '/([ti])um$/' => '$1a',
            '/(buffal|tomat)o$/' => '$1oes',
            '/(bu)s$/' => '$1ses',
            '/(alias|status)$/' => '$1es',
            '/(octop|vir)i$/' => '$1i',
            '/(octop|vir)us$/' => '$1i',
            '/(ax|test)is$/' => '$1es',
            '/s$/' => 's',
            '/$/' => 's',
        ],
        'singularise' => [
            '/(quiz)zes$/' => '$1',
            '/(matr)ices$/' => '$1ix',
            '/(vert|ind)ices$/' => '$1ex',
            '/^(ox)en/' => '$1',
            '/(alias|status)$/' => '$1',
            '/(alias|status)es$/' => '$1',
            '/(octop|vir)us$/' => '$1us',
            '/(octop|vir)i$/' => '$1us',
            '/(cris|ax|test)es$/' => '$1is',
            '/(cris|ax|test)is$/' => '$1is',
            '/(shoe)s$/' => '$1',
            '/(o)es$/' => '$1',
            '/(bus)es$/' => '$1',
            '/([m|l])ice$/' => '$1ouse',
            '/(x|ch|ss|sh)es$/' => '$1',
            '/(m)ovies$/' => '$1ovie',
            '/(s)eries$/' => '$1eries',
            '/([^aeiouy]|qu)ies$/' => '$1y',
            '/([lr])ves$/' => '$1f',
            '/(tive)s$/' => '$1',
            '/(hive)s$/' => '$1',
            '/([^f])ves$/' => '$1fe',
            '/(^analy)sis$/' => '$1sis',
            '/(^analy)ses$/' => '$1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '$1$2sis',
            '/([ti])a$/' => '$1um',
            '/(n)ews$/' => '$1ews',
            '/(s|si|u)s$/' => '$1s',
            '/s$/' => '',
        ],
        'irregular' => [
            'child' => 'children',
            'man' => 'men',
            'move' => 'moves',
            'person' => 'people',
            'sex' => 'sexes',
            'stadium' => 'stadiums',
        ],
        'uncountable' => ['equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep'],
        'data' => [
            'singularise' => [],
            'pluralise' => [],
        ],
    ];

    /**
     * Convert to plural
     * 
     * Examples:
     *  - post => posts
     * 
     * @param string $word singular word
     * @return string plural word
     */
    protected static function swapPluralSingular(string $word, string $action): string {
        if (count(static::$rules['data'][$action]) == 0) {
            $data = [];
            if ($action == 'pluralise') foreach (static::$rules['irregular'] as $singular => $plural) $data["/{$singular}/"] = $plural;
            else foreach (static::$rules['irregular'] as $singular => $plural) $data["/{$plural}/"] = $singular;
            static::$rules['data'][$action] = array_merge($data, static::$rules[$action]);
        }

        if (static::isCountable($word)) foreach (static::$rules['data'][$action] as $pattern => $replace) {
            if (preg_match($pattern, $word, $match)) return preg_replace($pattern, $replace, $word);
        }
        return $word;
    }

    /**
     * Convert to plural
     * 
     * Examples:
     *  - post => posts
     * 
     * @param string $word singular word
     * @return string plural word
     */
    public static function pluralise(string $word): string {
        return static::swapPluralSingular($word, __FUNCTION__);
    }

    /**
     * Convert to single
     * 
     * Examples:
     *  - posts => post
     * 
     * @param string $word plural word
     * @return string singular word
     */
    public static function singularise(string $word): string {
        return static::swapPluralSingular($word, __FUNCTION__);
    }

    /**
     * Countable
     * 
     * Examples:
     *  - advice => false
     *  - cat => true
     * 
     * @param string $word
     * @return bool word
     */
    public static function isCountable(string $word): bool {
        return !in_array($word, static::$rules['uncountable']);
    }

    /**
     * Convert to camel case
     * 
     * Examples:
     *  - active_model => ActiveModel
     *  - active_model/errors => ActiveModel\Errors
     * 
     * @param string $word word
     * @return string camel case word
     */
    public static function camelise(string $word): string {
        // TODO: Function body: camelise
        throw new StubException('Function pending: ' . __FUNCTION__, 320);
        // throw new Exception('Function pending: ', 320);

        // JAVA
        // if (lowerCaseAndUnderscoredWord == null)
		// 	return null;
		// lowerCaseAndUnderscoredWord = lowerCaseAndUnderscoredWord.trim();
		// if (lowerCaseAndUnderscoredWord.length() == 0)
		// 	return "";
		// if (uppercaseFirstLetter) {
		// 	String result = lowerCaseAndUnderscoredWord;
		// 	// Replace any extra delimiters with underscores (before the underscores are converted in the next step)...
		// 	if (delimiterChars != null) {
		// 		for (char delimiterChar : delimiterChars) {
		// 			result = result.replace(delimiterChar, '_');
		// 		}
		// 	}

		// 	// Change the case at the beginning at after each underscore ...
		// 	return replaceAllWithUppercase(result, "(^|_)(.)", 2);
		// }
		// if (lowerCaseAndUnderscoredWord.length() < 2)
		// 	return lowerCaseAndUnderscoredWord;
		// return "" + Character.toLowerCase(lowerCaseAndUnderscoredWord.charAt(0)) + camelCase(lowerCaseAndUnderscoredWord, true, delimiterChars).substring(1);

        return $word;
    }

    /**
     * Underscore word
     * 
     * Examples:
     *  - ActiveModel => active_model
     *  - ActiveModel\Errors => active_model/errors
     * 
     * @param string $word word
     * @return string word
     */
    public static function underscore(string $word): string {
        // TODO: Function body: underscore
        throw new StubException('Function pending: ' . __FUNCTION__, 320);

        // JAVA
        // if (camelCaseWord == null)
		// 	return null;
		// String result = camelCaseWord.trim();
		// if (result.length() == 0)
		// 	return "";
		// result = result.replaceAll("([A-Z]+)([A-Z][a-z])", "$1_$2");
		// result = result.replaceAll("([a-z\\d])([A-Z])", "$1_$2");
		// result = result.replace('-', '_');
		// if (delimiterChars != null) {
		// 	for (char delimiterChar : delimiterChars) {
		// 		result = result.replace(delimiterChar, '_');
		// 	}
		// }
		// return result.toLowerCase();

        return $word;
    }

    /**
     * Capitalise
     * 
     * Returns a copy of the input with the first character converted to uppercase and the remainder to lowercase.
     * 
     * Examples:
     *  - active model => Active model
     *  - ACTIVE => Active
     * 
     * @param string $word word
     * @return string word
     */
    public static function capitalise(string $word): string {
        // TODO: Function body: capitalise
        throw new StubException('Function pending: ' . __FUNCTION__, 320);

        // JAVA
        // public String capitalise(String words) {
        //     if (words == null)
        //         return null;
        //     String result = words.trim();
        //     if (result.length() == 0)
        //         return "";
        //     if (result.length() == 1)
        //         return result.toUpperCase();
        //     return "" + Character.toUpperCase(result.charAt(0)) + result.substring(1).toLowerCase();
        // }

        return $word;
    }
    

    /**
     * Humanise
     * 
     * Examples:
     *  - active_model => Active model
     *  - author_id => Author
     * 
     * @param string $word word
     * @return string word
     */
    public static function humanise(string $word): string {
        // TODO: Function body: humanise
        throw new StubException('Function pending: ' . __FUNCTION__, 320);

        // JAVA
        // if (lowerCaseAndUnderscoredWords == null)
		// 	return null;
		// String result = lowerCaseAndUnderscoredWords.trim();
		// if (result.length() == 0)
		// 	return "";
		// // Remove a trailing "_id" token
		// result = result.replaceAll("_id$", "");
		// // Remove all of the tokens that should be removed
		// if (removableTokens != null) {
		// 	for (String removableToken : removableTokens) {
		// 		result = result.replaceAll(removableToken, "");
		// 	}
		// }
		// result = result.replaceAll("_+", " "); // replace all adjacent underscores with a single space
		// return capitalise(result);

        return $word;
    }

    /**
     * Titleise
     * 
     * Examples:
     *  - man from the boondocks => Man From The Boondocks
     *  - raiders_of_the_lost_ark => Raiders Of The Lost Ark
     * 
     * @param string $word word
     * @return string word
     */
    public static function titleise(string $word): string {
        // TODO: Function body: titleise
        throw new StubException('Function pending: ' . __FUNCTION__, 320);

        // JAVA
        // String result = humanise(words, removableTokens);
		// result = replaceAllWithUppercase(result, "\\b([a-z])", 1); // change first char of each word to uppercase
		// return result;

        return $word;
    }

    /**
     * Ordinal
     * 
     * Examples:
     *  - 1 => st
     *  - 2 => nd
     * 
     * @param int $number
     * @return string word
     */
    public static function ordinal(int $number): string {
        $r = $number % 100;
        if (11 <= $r && $r <= 13) return 'th';

        return match ($number % 10) {
            1 => 'st',
            2 => 'nd',
            3 => 'rd',
            default => 'th'
        };
    }

    /**
     * Ordinalise
     * 
     * Examples:
     *  - 1 => 1st
     *  - 2 => 2nd
     * 
     * @param int $number
     * @return string word
     */
    public static function ordinalise(int $number): string {
        return $number . static::ordinal($number);
    }
}
