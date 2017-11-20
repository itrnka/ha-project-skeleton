<?php
use ha\Internal\DefaultClass\Model\ScalarValues\Booleans;
use ha\Internal\DefaultClass\Model\ScalarValues\Floats;
use ha\Internal\DefaultClass\Model\ScalarValues\Integers;
use ha\Internal\DefaultClass\Model\ScalarValues\Strings;
use ha\Internal\Exception\NotFoundException;

if (!function_exists('main')) {
    /**
     * Returns core container instance (app singleton instance). This is simulation of main() from C languages.
     * @return \ha\App\App
     */
    function main()
    {
        return Main::getInstance();
    }
}

if (!function_exists('getallheaders')) {
    /**
     * Fetch all HTTP request headers.
     * @return array
     */
    function getallheaders()
    {
        $arh = [];
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = [];
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', strtolower($arh_key));
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val) {
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    }
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $arh['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        }
        if (isset($_SERVER['CONTENT_LENGTH'])) {
            $arh['Content-Length'] = $_SERVER['CONTENT_LENGTH'];
        }
        return ($arh);
    }
}

/**
 * Convert text to nice SEO text for URI.
 *
 * @param string $text
 * @param string $separator
 *
 * @return string
 */
function textToURIFormat(string $text, string $separator = '-'): string
{
    $text = preg_replace('#[^\\pL\d\.]+#u', $separator, $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace("/[{$separator}]+/", $separator, $text);
    $text = preg_replace("/[\.]+/", '.', $text);
    $text = preg_replace("#[^{$separator}\w\.]+#", '', $text);
    $text = strtolower($text);
    $text = trim($text, "{$separator}.");
    return $text;
}

/**
 * Generate a globally unique identifier. If com_create_guid() does not exists, create pseudo random value.
 * @return string
 */
function GUID()
{
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }
    return sprintf(
        '%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535),
        mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535)
    );
}

/**
 * Convert camelCase name to underscored format.
 *
 * @param string $value
 *
 * @return string
 */
function camelCaseToUnderscored(string $value): string
{
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9]|[_][A-Za-z]+)|[A-Za-z][a-z0-9]+)!', $value, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
}

/**
 * Convert array keys to underscored from camelCase. Changed will be only string keys.
 *
 * @param array $array
 *
 * @return array
 */
function arrayKeysToUnderscored(array $array): array
{
    $ret = [];
    foreach ($array AS $key => $value) {
        if (is_int($key)) {
            $ret[$key] = $value;
            continue;
        }
        $ret[camelCaseToUnderscored($key)] = $value;
    }
    return $ret;
}

/**
 * Convert array to Booleans object.
 *
 * @param array $array
 *
 * @return Booleans
 */
function booleans(array $array = []): Booleans
{
    return new Booleans($array);
}

/**
 * Convert array to Strings object.
 *
 * @param array $array
 *
 * @return Strings
 */
function strings(array $array = []): Strings
{
    return new Strings($array);
}

/**
 * Convert array to Floats object.
 *
 * @param array $array
 *
 * @return Floats
 */
function floats(array $array = []): Floats
{
    return new Floats($array);
}

/**
 * Convert array to Floats object.
 *
 * @param array $array
 *
 * @return Integers
 */
function integers(array $array = []): Integers
{
    return new Integers($array);
}

/**
 * Get object or array value by provided tree path, e.g. 'items.item.id', ['items', 'item', 'id']...
 *
 * @param array|string $keysTree Keys defined as string with dot separated tree or flat array with keys
 * @param array|\stdClass $source
 * @param \Closure $validator If this function does not return true, script throws TypeError
 *
 * @return mixed
 * @throws \TypeError
 * @throws \InvalidArgumentException
 * @throws \ha\Internal\Exception\NotFoundException
 */
function getArrayValueByKeysTree($keysTree, $source, Closure $validator = null)
{
    // prepare first input (before recursive call)
    if (is_string($keysTree)) {
        $keysTree = explode('.', $keysTree);
    }
    if (!is_array($keysTree)) {
        throw new InvalidArgumentException('$keysTree not an array or string in ' . __FUNCTION__);
    }
    // parse current level
    $key = array_shift($keysTree);
    if ((is_array($source) || $source instanceof ArrayIterator) && isset($source[$key])) {
        $source = $source[$key];
    } elseif (is_object($source) && isset($source->$key)) {
        $source = $source->$key;
    } else {
        throw new NotFoundException($key);
    }
    // final tree level
    if (count($keysTree) === 0) {
        if (isset($validator) && $validator($source) !== true) {
            throw new TypeError('Variable found, but invalid type detected in ' . __FUNCTION__);
        }
        return $source;
    }
    // tree has next levels
    return getArrayValueByKeysTree($keysTree, $source, $validator);
}


/**
 * Convert string to integer value (if is not an integer value).
 *
 * @param $value
 *
 * @return int
 * @throws \TypeError
 */
function toInt($value): int
{
    if (is_int($value)) {
        return $value;
    }
    if (is_string($value) && preg_match('/^[-]?([1-9][0-9]*|0)$/', $value)) {
        return intval($value);
    }
    throw new TypeError('Value has not integer format');
}

/**
 * Convert '0', '1', 0, 1, 'true', 'false' to boolean value (if is not a boolean value).
 *
 * @param $value
 *
 * @return bool
 * @throws \TypeError
 */
function toBool($value): bool
{
    if (is_bool($value)) {
        return $value;
    }
    if (is_int($value)) {
        if ($value === 1) {
            return true;
        }
        if ($value === 0) {
            return false;
        }
    }
    if (is_string($value)) {
        if (preg_match('/^(0|1)$/', $value)) {
            return boolval(intval($value));
        }
        if (preg_match('/^true$/i', $value)) {
            return true;
        }
        if (preg_match('/^false$/i', $value)) {
            return false;
        }
    }
    throw new TypeError('Value has not compatible format with boolean.');
}

/**
 * Convert string or integer to float value (if is not a float value).
 *
 * @param $value
 *
 * @return float
 * @throws \TypeError
 */
function toFloat($value): float
{
    #ddd('TODO pregmatch in ' . __FUNCTION__);
    if (is_float($value)) {
        return $value;
    }
    if (is_int($value)) {
        return floatval($value);
    }
    if (is_string($value) && preg_match('/^[-]?([1-9][0-9]*|0)(\.[0-9]+)?$/', $value)) {
        return floatval($value);
    }
    throw new TypeError('Value has not compatible format with float.');
}

/**
 * Convert scalar value to string (if is not a string value).
 *
 * @param $value
 *
 * @return string
 * @throws \TypeError
 */
function toString($value): string
{
    if (is_scalar($value)) {
        return strval($value);
    }
    throw new TypeError('Value has not compatible format with string.');
}

/**
 * Convert HTTP GET variable to integer.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return int
 */
function httpGetVarToInt(string $varName)
{
    $value = toInt(getArrayValueByKeysTree($varName, $_GET));
    return $value;
}

/**
 * Convert HTTP POST variable to integer.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return int
 */
function httpPostVarToInt(string $varName)
{
    $value = toInt(getArrayValueByKeysTree($varName, $_POST));
    return $value;
}

/**
 * Convert HTTP GET variable to boolean.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return bool
 */
function httpGetVarToBool(string $varName)
{
    $value = toBool(getArrayValueByKeysTree($varName, $_GET));
    return $value;
}

/**
 * Convert HTTP POST variable to boolean.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return bool
 */
function httpPostVarToBool(string $varName)
{
    $value = toBool(getArrayValueByKeysTree($varName, $_POST));
    return $value;
}

/**
 * Convert HTTP GET variable to float.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return float
 */
function httpGetVarToFloat(string $varName)
{
    $value = toFloat(getArrayValueByKeysTree($varName, $_GET));
    return $value;
}

/**
 * Convert HTTP POST variable to float.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return float
 */
function httpPostVarToFloat(string $varName)
{
    $value = toFloat(getArrayValueByKeysTree($varName, $_POST));
    return $value;
}

/**
 * Convert HTTP GET variable to string.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return string
 */
function httpGetVarToString(string $varName)
{
    $value = toString(getArrayValueByKeysTree($varName, $_GET));
    return $value;
}

/**
 * Convert HTTP POST variable to string.
 *
 * @param string $varName Key or coma separated key tree.
 *
 * @return string
 */
function httpPostVarToString(string $varName)
{
    $value = toString(getArrayValueByKeysTree($varName, $_POST));
    return $value;
}