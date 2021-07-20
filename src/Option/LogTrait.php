<?php

/**
 * This file is part of the InaneClasses package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Philip Michael Raab <philip@inane.co.za>
 * @package Forms\Options
 *
 * @license MIT
 * @license https://inane.co.za/license/MIT
 *
 * @copyright 2015-2019 Philip Michael Raab <philip@inane.co.za>
 */

namespace Inane\Option;

/**
 * LogTrait - getLog()
 *
 * Easy log access
 *
 * @package Inane\Option
 * @version 1.0.0
 */
trait LogTrait {
    /*
    CREATE TABLE `logs` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `priority` int(11) unsigned DEFAULT NULL,
        `priorityName` varchar(10) DEFAULT NULL,
        `timestamp` datetime DEFAULT NULL,
        `message` text,
        `extra_route` varchar(100) DEFAULT NULL,
        `extra_user_id` int(11) unsigned DEFAULT NULL,
        `extra_session` varchar(15) DEFAULT NULL,
        `extra_ip_address` varchar(20) DEFAULT NULL,
        `extra_file` varchar(200) DEFAULT NULL,
        `extra_line` int(11) unsigned DEFAULT NULL,
        `extra_class` varchar(45) DEFAULT NULL,
        `extra_function` varchar(20) DEFAULT NULL,
        `extra_options` text,
        PRIMARY KEY (`id`)
    );
    */
    
    /*
    'factories' => [
        'LogService' => function ($sm) {
            $logger = new Logger();
            $priority = new Priority(getenv('LOG_LEVEL') ?: $logger::WARN);

            $fileWriterGeneral = new Stream('log/general.log');

            $dbWriter = new Db(GlobalAdapterFeature::getStaticAdapter(), 'logs');
            $dbWriter->setFormatter(new \Laminas\Log\Formatter\Db('Y-m-d H:i:s'));

            $fileWriterGeneral->addFilter($priority);
            $dbWriter->addFilter($priority);

            $logger->addWriter($fileWriterGeneral);
            $logger->addWriter($dbWriter);

            return $logger;
        },
    ]
    */

    /**
     * @var \Laminas\Log\Logger the log service
     */
    protected $logService;

    /**
     * Get Log Service
     *
     * @return \Laminas\Log\Logger the logger
     */
    public function getLog(): \Laminas\Log\Logger {
        if (null === $this->logService) {
            $this->logService = $this->getEvent()->getApplication()->getServiceManager()->get('LogService');
        }
        return $this->logService;
    }
}
