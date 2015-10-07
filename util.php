<?php

$root_directory = __DIR__;
$cache_dir = $root_directory.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR;

/**
* Auto loader, will automatically auto load necessary classes, replaces _ with /
* @param string $class The name of the file to auto load
*
*/
function my_autoloader($class) {
    include 'lib/' . str_replace('_',DIRECTORY_SEPARATOR,$class) . '.php';
}

spl_autoload_register('my_autoloader');

/**
 *  Given a file, i.e. /css/base.css, replaces it with a string ending with the
 *  file's md5 hash, i.e. /css/base.css?[md5 hash]
 *  
 * now instead of <link rel="stylesheet" href="/css/base.css" type="text/css" />
 * use <link rel="stylesheet" href="<?php echo auto_version('/css/base.css'); ?>" type="text/css" />
 *
 *  @param $file  The file to be loaded.  Must be an absolute path (i.e.
 *                starting with slash).
 *  @return  string
 *  @author  Caleb Nelson <calebnelson@mac.com>
 */
function auto_version($file){
    if(!file_exists($file))
            return $file;
        $new_name = $file.'?'.md5_file($file);
    return  $new_name;
}

/**
 * Uses the return from print_r() wrapped in <pre> tags
 * @param  mixed $var The variable to pretty print
 * @return string $var inside
 * @author  Caleb Nelson <calebnelson@mac.com>
 */
function prettyPrint($var){
    $response = "<pre>";
    $ob_start();
    print_r($var);
    $response .= $ob_get_clean()."</pre";
    return $response;
}

/**
 * Generic Binary Search
 * @param  array  $a       The sorted haystack
 * @param  mixed $first   First index of the array to be searched (inclusive).
 * @param  mixed $last    Last index of the array to be searched (exclusive).
 * @param  mixed $key     The key to be searched for.
 * @param  string $compare A user defined function for comparison. Same definition as the one in usort
 * @return integer         index of the search key if found, otherwise return (-insert_index - 1). insert_index is the index of smallest element that is greater than $key or sizeof($a) if $key is larger than all elements in the array.
 * @link   https://terenceyim.wordpress.com/2011/02/01/all-purpose-binary-search-in-php/
 */
function binary_search(array $a, $first, $last, $key, $compare) {
    $lo = $first; 
    $hi = $last - 1;

    while ($lo <= $hi) {
        $mid = (int)(($hi - $lo) / 2) + $lo;
        $cmp = call_user_func($compare, $a[$mid], $key);

        if ($cmp < 0) {
            $lo = $mid + 1;
        } elseif ($cmp > 0) {
            $hi = $mid - 1;
        } else {
            return $mid;
        }
    }
    return -($lo + 1);
}


/**
* Checks to see if a URL exists
* @param $url The url to check
* @link http://stackoverflow.com/a/2280413
*/
function url_exists($url) {
    if (!$fp = curl_init($url)) return false;
    return true;
}

class WarningException              extends ErrorException {}
class ParseException                extends ErrorException {}
class NoticeException               extends ErrorException {}
class CoreErrorException            extends ErrorException {}
class CoreWarningException          extends ErrorException {}
class CompileErrorException         extends ErrorException {}
class CompileWarningException       extends ErrorException {}
class UserErrorException            extends ErrorException {}
class UserWarningException          extends ErrorException {}
class UserNoticeException           extends ErrorException {}
class StrictException               extends ErrorException {}
class RecoverableErrorException     extends ErrorException {}
class DeprecatedException           extends ErrorException {}
class UserDeprecatedException       extends ErrorException {}

?>
