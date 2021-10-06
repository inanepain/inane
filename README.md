# Inane Classes

Version: `0.23.0` 22 Jul 2021

For a brief few notes on what's Inane Class check out the [InaneClasses Wiki](https://git.inane.co.za:3000/Inane/tools/wiki "InaneClasses Wiki"). Will be fleshing this out over time. But don't hold your breath. If you want something specific... Ask!

Check out the [CHANGELOG](CHANGELOG.md) if you wanna see the road travelled thus far.

## Installing Inane Classes

### Requirements

- PHP \>= 7.0
- laminas/laminas-http >= 2.8

### Installation

```shell
php composer.phar require inanepain/inane
```

#### OR

Stuff to add to `composer.json`

```json
"require": {
    "inanepain/inane" : ">=0",
}
```

then simply run:

```shell
php composer.phar update
```

### Configuration

#### LogTrait

Database:

```sql
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
```

module.php:

```php
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
```

#### Dumper

A simple dump tool that neatly stacks its collapsed dumps on the bottom of the page.

**option: Dumper::enabled**  

Set it to false to stop ALL output from Dumper. Instant quiet.

**Quick & Easy:**  

When Dumper starts it registers a global function `dd` which is a shortcut for the `Dumper::dump`. Calling `Dumper::dumper()` once early in your apps life cycle means you can use `dd` from then on out.

```php
function dd(mixed $data = null, ?string $label = null, array $options = []): \Inane\Debug\Dumper {
    return \Inane\Debug\Dumper::dump($data, $label, $options);
}
```

**Chaining**  

`Dumper::dump` only takes one set of dumps at a time: item to dump, an optional label and options.
To dump multiple variables simply bracket it right after the first set.

E.G.:

```php
$var1 = someFunction();
$var2 = another($var1);
$var3 = again($var1);

dd($var1, 'someFunction')($var2, 'another')($var3, 'again');

```

### Feedback

Hey, got any ideas or suggestions.

Email me <philip@inane.co.za>
