<?php
function autoload($nameClass) {
    $file = __DIR__.'/class/'.$nameClass.'.php';
    if(is_file($file)) {
        require_once($file);
    }
}

spl_autoload_register('autoload');