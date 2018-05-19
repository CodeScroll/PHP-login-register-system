<?php

spl_autoload_register(function ($class_name) {
	$className = strtolower($class_name);
    require_once $className . '.php';
});