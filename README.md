## Usage
```php
<?php

error_reporting(-1);
ini_set('display_errors', true);
ini_set('html_errors', false);

header('Content-Type: text/plain; charset=utf-8');

require 'src/Prototype/Prototype.php';


class Dummy extends Prototype\Prototype
{}

class Woody extends Prototype\Prototype
{
    protected $dateTime;
    protected $dateFormat;

    public function __construct($dateFormat = DATE_ATOM)
    {
        $this->dateTime = new DateTime;
        $this->dateFormat = $dateFormat;
    }

    public function notice($message)
    {
        $this->log($message);
    }

    public function warning($message)
    {
        $this->log('Warning: ' . $message);
    }

    public function fatal($message)
    {
        $this->log('FATAL: ' . $message);
    }

    protected function log($message)
    {
        echo '['. $this->dateTime->format($this->dateFormat) .'] ' . $message . PHP_EOL;
    }
}

$dummy = new Dummy;
$dummy->logger = new Woody('d.m.Y H:i:s');
$dummy->logger->notice('Started Woody');

// Register an alias.
$dummy->logger->info = function()
{
    call_user_func_array(array($this, 'notice'), func_get_args());
};

$dummy->logger->info('Registered alias `info` for `notice`.');

$dummy->logger->warning('Shutting down...');
$dummy->logger->fatal('Halted');
```