<?php

use \nabu\core\CNabuEngine;

require_once 'common.php';

CNabuEngine::setOperationModeClustered();
CNabuEngine::getEngine()
    ->enableLogTrace()
    ->runApplication('\\nabu\\cms\\apps\\CNabuCMSApplication');
