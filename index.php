<?php
    /* PSR-0 */
    function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        require_once $fileName;
    }

    spl_autoload_register('autoload');

    define('DEBUG', true);

    $app = new \Sunduk\app();

    /**
     * C# style format text (Console.WriteLine, string.Format...)
     * @param string $format Text format
     */
    function clog($format) {
        if(!DEBUG) return;
        $args = func_get_args();
        array_shift($args); // shift format text from stack
        foreach($args as $k => $v) { // replace keys to format {0-9...}
            $args['{'.$k.'}'] = var_export($v, true);
            unset($args[$k]);
        }
        echo str_replace(array_keys($args), array_values($args), $format) . "\r\n";
        echo "-----------------------------------------------------------------------------------\r\n";
    }

    if(DEBUG) {
        echo '<pre>';
        clog('Sunduk application debug information {0}', "test");
        $start = microtime(1);
        $memory_start = memory_get_usage();
    }

    $app->main();

    if(DEBUG) {
        $memory_end = memory_get_usage();
        $end = microtime(1);
        clog("Elapsed time by app::main() execution: " . ($end - $start) . " ms.");
        clog("Used memory by app::main() execution: " . ($memory_end - $memory_start) . " bytes.");
        echo '</pre>';
    }