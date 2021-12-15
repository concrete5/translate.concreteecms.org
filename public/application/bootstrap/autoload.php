<?php
defined('C5_EXECUTE') or die('Access Denied.');

# Load in the composer vendor files
require_once __DIR__ . "/../../../vendor/autoload.php";

# Alias old lifecycle events class for compatibility
class_alias(
  \Doctrine\ORM\Event\LifecycleEventArgs::class,
  \Doctrine\Common\Persistence\Event\LifecycleEventArgs::class
);

# Try loading in environment info
$env = new \Dotenv\Dotenv(__DIR__ . '/../../../');
try {
    $env->overload();
} catch (\Exception $e) {
    // Ignore any errors
}

# Add the vendor directory to the include path
ini_set('include_path', __DIR__ . "/../../../vendor" . PATH_SEPARATOR . get_include_path());

