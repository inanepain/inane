<?php

/**
 * Enum Abstract Class
 *
 * PHP version 8.1
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Inane\Type
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 *
 * @link    http://github.com/myclabs/php-enum
 * @license http://www.opensource.org/licenses/mit-license.php MIT (see the LICENSE file)
 */

declare(strict_types=1);

namespace Inane\Type;

// use Laminas\Stdlib\ArrayObject as LaminasSystemArrayObject;

use ArrayObject as SystemArrayObject;
use PhpParser\Node\Stmt\Static_;

class ArrayObject extends SystemArrayObject {
    public function __construct(array|object $array = []) {
        foreach ($array as $key => $value) if (is_array($value)) $array[$key] = new static($value);

        parent::__construct($array, SystemArrayObject::ARRAY_AS_PROPS);
    }

    public function getArrayCopy(): array {
        $array = parent::getArrayCopy();
        foreach ($array as $key => $value) if ($value instanceof static) $array[$key] = $value->getArrayCopy();
        return $array;
    }

    public function exchangeArray(object|array $array): array {
        $old = $this->getArrayCopy();
        foreach ($array as $key => $value) if (is_array($value)) $array[$key] = new static($value);
        parent::exchangeArray($array);

        return $old;
    }
}


// $test = ['aa' => 'Aye', 'bb' => 'Bee', 'cb' => 'See', 'dd' => ['da' => 'dAye', 'db' => 'dBee', 'dc' => 'dSee']];
// $a = new ArrayObject(['a' => 'Aye', 'b' => 'Bee', 'c' => 'See', 'd' => ['da' => 'dAye', 'db' => 'dBee', 'dc' => 'dSee']]);

// echo $a->a;

// echo $a->d->da;

// \var_dump($a->d);

// \var_dump($a->exchangeArray($test));

// \var_dump($a->getArrayCopy());

// \var_dump($a->dd);
