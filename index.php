<?php

require 'vendor/autoload.php';

use IvelumTest\App;

echo App::getInstance()->runApplication()->getResult();
