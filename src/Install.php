<?php
declare (strict_types=1);

namespace jolalau\WebManRedis;

class Install
{
    const WEBMAN_PLUGIN = true;

    /**
     * @var array
     */
    protected static array $pathRelation = [
        'config/cache.php' => 'config/cache.php'
    ];

    /**
     * Install
     * @return void
     */
    public static function install(): void
    {
        $config_file = config_path() . '/bootstrap.php';
        $config = include $config_file;
        if(!in_array(Start::class, $config ?? [])) {
            $config_file_content = file_get_contents($config_file);
            $config_file_content = preg_replace('/\];/', "    jolalau\WebManRedis\Start::class,\n];", $config_file_content);
            file_put_contents($config_file, $config_file_content);
        }
        /*$thinkcache_file = config_path() . '/thinkcache.php';
        if (!is_file($thinkcache_file)) {
            copy(__DIR__ . '/config/thinkcache.php', $thinkcache_file);
        }*/
        static::installByRelation();
    }

    /**
     * Uninstall
     * @return void
     */
    public static function uninstall(): void
    {
        $config_file = config_path() . '/bootstrap.php';
        $config = include $config_file;
        if(in_array(Start::class, $config ?? [])) {
            $config_file = config_path() . '/bootstrap.php';
            $config_file_content = file_get_contents($config_file);
            $config_file_content = preg_replace('/ {0,4}jolalau\\\\WebManRedis\\\\Start::class,?\r?\n?/', '', $config_file_content);
            file_put_contents($config_file, $config_file_content);
        }
        self::uninstallByRelation();
    }

    /**
     * installByRelation
     * @return void
     */
    public static function installByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            if ($pos = strrpos($dest, '/')) {
                $parent_dir = base_path().'/'.substr($dest, 0, $pos);
                if (!is_dir($parent_dir)) {
                    mkdir($parent_dir, 0777, true);
                }
            }
            //symlink(__DIR__ . "/$source", base_path()."/$dest");
            copy_dir(__DIR__ . "/$source", base_path()."/$dest");
            echo "Create $dest
";
        }
    }

    /**
     * uninstallByRelation
     * @return void
     */
    public static function uninstallByRelation()
    {
        foreach (static::$pathRelation as $source => $dest) {
            $path = base_path()."/$dest";
            if (!is_dir($path) && !is_file($path)) {
                continue;
            }
            echo "Remove $dest
";
            if (is_file($path) || is_link($path)) {
                unlink($path);
                continue;
            }
            remove_dir($path);
        }
    }

}