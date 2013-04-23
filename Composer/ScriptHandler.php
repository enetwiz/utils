<?php

namespace Enetwiz\Utils\Composer;

use Composer\Script\Event;

class ScriptHandler
{
    public static function updateAutoloadPaths( Event $event )
    {
        // Get composer object
        $composer = $event->getComposer();
        
        echo 'Update $baseDir inside a <vendor_dir>/composer/autoload_*.php files'.PHP_EOL;
        
        // Define path to autoload files
        $autoloadFilesPath = $composer->getConfig()->get( 'vendor-dir' ).'/composer/';
        
        // Autoload files
        $aFiles = array(
          'autoload_classmap.php',
          'autoload_namespaces.php',
          'autoload_real.php'
        );
        
        // Change $baseDir variable inside autoload files
        foreach( $aFiles as $file )
        {
            // Create full path for file
            $file = $autoloadFilesPath . $file;
            
            if ( file_exists( $file ) ) 
            { 
                // Change file content
                $changeContent = preg_replace( 
                        '#(.*)\$baseDir = dirname\(\$vendorDir\).([\'/a-z_;]+)(.*)#is', 
                        '$1$baseDir = dirname($vendorDir);$3', 
                        file_get_contents( $file ) 
                );
                
                // Save new content
                file_put_contents( $file, $changeContent );
            }
            else
            {
                echo 'Autoload file doesn\'t exists: ' . $file;
            }
        }
        
        return;
    }
}
