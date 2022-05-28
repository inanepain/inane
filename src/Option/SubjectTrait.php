<?php

/**
 * This file is part of InaneTools.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * PHP version 7
 *
 * @author Philip Michael Raab <peep@inane.co.za>
 * @package Inane\Option
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2020 Philip Michael Raab <peep@inane.co.za>
 */

declare(strict_types=1);

namespace Inane\Option;

use SplObjectStorage;
use SplObserver;

/**
 * SubjectTrait
 *
 * extends SplSubject
 *
 * @author philip
 *
 * @method static Notice ROUTE_INVALID()
 *
 * @package Inane\Option
 * @version 0.1.0
 */
trait SubjectTrait {
    /**
     * Observers
     *
     * @var null|SplObjectStorage
     */
    private $_observers = null;

    /**
     * Subject Name
     *
     * @var string
     */
    private $_name = '';

    /**
     * Get the Observers
     *
     * @return SplObjectStorage
     */
    protected function getObservers(): SplObjectStorage {
        if (!$this->_observers) $this->_observers = new SplObjectStorage();
        return $this->_observers;
    }

    /**
     * Attach Observer
     *
     * @param SplObserver $observer
     * @return void
     */
    public function attach(SplObserver $observer) {
        $this->_observers->attach($observer);
    }

    /**
     * Detach Observer
     *
     * @param SplObserver $observer
     * @return void
     */
    public function detach(SplObserver $observer) {
        $this->_observers->detach($observer);
    }

    /**
     * Notify Observers
     *
     * @return void
     */
    public function notify() {
        foreach ($this->_observers as $observer) $observer->update($this);
    }

    /**
     * Name
     *
     * @return string the name
     */
    public function getName(): string {
        return $this->_name;
    }
}
