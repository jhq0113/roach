<?php
require __DIR__.'/Autoload.php';
spl_autoload_register('Autoload::autoload');
Autoload::set('roach', dirname(__DIR__));
