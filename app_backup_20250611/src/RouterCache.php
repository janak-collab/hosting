<?php
namespace App;

class RouterCache {
    private static $cacheFile = APP_PATH . '/storage/cache/routes.cache';
    
    public static function get() {
        if (file_exists(self::$cacheFile)) {
            return include self::$cacheFile;
        }
        return null;
    }
    
    public static function put($data) {
        $dir = dirname(self::$cacheFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $content = '<?php return ' . var_export($data, true) . ';';
        file_put_contents(self::$cacheFile, $content);
    }
    
    public static function clear() {
        if (file_exists(self::$cacheFile)) {
            unlink(self::$cacheFile);
        }
    }
}
