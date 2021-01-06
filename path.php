<?php

class Path
{

    private static $tree = [];
    private static $branch_limit;
    private static $leaf_limit;
    
    private static $paths_count;
    private static $depth;
    private static $files_count;

    /**
     * Parse the paths passed by the user
     * and convert each path to tree array
     * 
     * @param array $paths - array of paths passed by the user
     * @return array of tree array
     */
    private static function parsePaths($paths)
    {
        $container = [];
        foreach ($paths as $path) {
            //remove the leading slash at the beginning of the string
            //then convert each path to array
            $exploded_path = explode("/", ltrim($path, "/"));
            $container[] = self::convertToArray($exploded_path);
        }
        return $container;
    }

    /**
     * Give contents to each part of the path as an array
     * 
     * @param array $path - pass the exploded path
     */
    private static function convertToArray($path)
    {
        if (count($path) == 1) {
            return $path;
        }
        $value = array_shift($path);
        return [$value => self::convertToArray($path)];
    }

    /**
     * Merge the paths recursively into 1 tree array
     * 
     * @param array $arr array of tree structured paths
     */
    private static function mergeBranches($paths)
    {
        for ($j = 0; $j < count($paths); $j++) {
            self::$tree = array_merge_recursive(self::$tree, $paths[$j]);
        }
    }

    /**
     * Merge all the converted paths into 1 array
     * 
     * @param array $paths - array of tree structured paths
     */
    public static function convertToTree($paths)
    {
        $convertedPaths = self::parsePaths($paths);
        self::mergeBranches($convertedPaths);
        return self::$tree;
    }

    /**
     * Returns the paths in a readable string format
     * 
     * @param array $paths array of tree structured paths
     * @param int $branch_limit number of branches to be displayed per branch
     * @param int $leaf_limit number of files to be displayed per branch
     */
    public static function printTree(array $paths, int $branch_limit, int $leaf_limit)
    {
        self::$branch_limit = $branch_limit;
        self::$leaf_limit = $leaf_limit;

        $tree = self::convertToTree($paths);
        $container = self::limitItems($tree);
        $str = "";
        $str .= self::generateStringPaths($container, 0, $str);
        return $str;
    }

    /**
     * Will limit the number of branch and leafs to be displayed in the Tree
     * 
     * @param array $item tree array of paths
     */
    private static function limitItems($item)
    {
        // ksort($item, SORT_STRING); //sort array
        $container = [];
        $branch_iterator = 1;
        $leaf_iterator = 1;

        foreach ($item as $key => $value) {
            if (is_array($value)) {
                if ($branch_iterator <= self::$branch_limit) {
                    $container[$key] = self::limitItems($value);
                }
                $branch_iterator++;
            } else {
                if ($leaf_iterator <= self::$leaf_limit) {
                    $container[$key] = $value;
                }
                $leaf_iterator++;
            }
        }
        return $container;
    }

    /**
     * Recursively generate strings that will form the path tree
     * 
     * @param array $item array of tree structured paths
     * @param int $level level which the string will be placed
     * @param string $str the string which the string should be appended
     */
    private static function generateStringPaths($item, $level, &$str)
    {
        $level++;
        $str .= "\n";
        foreach ($item as $key => $value) {
            if (is_array($value)) {
                $str .= str_repeat("&nbsp", $level * 4) . "<strong>$key</strong>";
                $str .= self::generateStringPaths($value, $level, $str);
            } else {
                $str .= str_repeat("&nbsp", $level * 4) . "<i>$value</i>";
                $str .= "\n";
            }
        }
    }

    /**
     * Recursively generate strings that will form the path tree
     * 
     * @param string $base_path the base path for each element of the array
     * @param int $paths number of paths to be created
     * @param int $depth maximum depth of each path
     * @param int $files maximum number or files you want in each lowest-level folder
     */
    public static function create($base_path, $paths, $depth, $files)
    {
        self::$paths_count = $paths;
        self::$depth = $depth;
        self::$files_count = $files;
        
        $container = [];
        $random_paths = self::generatePaths();
        //Append base path at the start and filename at the beginning
        foreach($random_paths as $path){
            $filename = self::createRandomFile();
            $container[] = $base_path.$path.$filename;
        }

        return $container;
    }

    private static function generatePaths()
    {
        $count = self::$paths_count;
        $folder_names = [];
        $paths = [];
        while(count($paths) != $count){
            self::createPath($paths, $folder_names);
        }

        return $paths;
    }

    /**
     * Create random string of paths
     * 
     * @param string $base_path the base path for each element of the array
     * @param array $paths array of randomly generated paths
     * @param array $folder_names array of randomly generated paths. 
     * This is used for monitoring uniqueness of paths
     */
    private static function createPath(&$paths, &$folder_names)
    {
        $depth = self::$depth; 
        $files = self::$files_count;
        $path_count = rand(1,$depth);
        $path = "";

        $k = 1;
        for ($j = 1; $j <= $path_count;) {
            $ext = $j = $k;
            $folder_name = 'folder' . $j;
            $path_name = $folder_name;
            
            if(array_key_exists($path_name, $folder_names)){
                $ext = $k;
                $folder_name = 'folder' . $ext;
                $path_name = $folder_name;
            }
            if(!array_key_exists($path_name, $folder_names)){
                $j++;
                $path .= $path_name .'/';
            }
            $k++;
        }

        if(!array_key_exists($path, $folder_names)){
            $folder_names[$path] = 0;
        }

        if($folder_names[$path] < $files && !empty(trim($path))){
            $folder_names[$path]++;
            $paths[] = $path;
        }else{
            self::createPath($paths, $folder_names);
        }
        
    }

    private static function createRandomFile()
    {
        return substr(md5(mt_rand()), 0, 8).'.txt';
    }

}
