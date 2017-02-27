<?php
/**
 * SimpleRouter.php
 *
 * Created: 2/27/2017
 *
 * @author Steve Massey
 * @author Chris Schuld
 * @package PegasusPHP
 * @todo
 *
 */

class SimpleRouter {

    // prepend string to module uri
    private static $_prepend_module_uri = '';

    // append string to module uri
    private static $_append_module_uri = '';

    // collection of routes
    private static $_routes = [];

    // cache of the currently matched route key
    private static $_matched_route_key = '';

    /**
     * @return string
     */
    public static function getPrependModuleUri()
    {
        return self::$_prepend_module_uri;
    }

    /**
     * @param string $prepend_module_uri
     */
    public static function setPrependModuleUri($prepend_module_uri)
    {
        self::$_prepend_module_uri = $prepend_module_uri;
    }

    /**
     * @return string
     */
    public static function getAppendModuleUri()
    {
        return self::$_append_module_uri;
    }

    /**
     * @param string $append_module_uri
     */
    public static function setAppendModuleUri($append_module_uri)
    {
        self::$_append_module_uri = $append_module_uri;
    }

    /**
     * @param string $method
     * @param string $module
     * @return string
     */
    private static function routeKey($method = '', $module = '')
    {
        return $method.'_'.self::getPrependModuleUri() . $module . self::getAppendModuleUri();
    }

    /**
     * @param string $method
     * @param string $moduleUri
     * @param string $className
     * @return array
     */
    private static function createRoute($method = '', $moduleUri = '', $className = '')
    {
        return [
            'method' => $method,
            'module' => self::getPrependModuleUri() . $moduleUri . self::getAppendModuleUri(),
            'className' => $className
        ];
    }

    /**
     * @param array $route
     * @return bool
     */
    private static function registerRoute(array $route)
    {
        $key = self::routeKey($route['method'], $route['module']);
        if (!isset(self::$_routes[$key])) {
            self::$_routes[$key] = $route;
            return true;
        }
        return false;
    }

    /**
     * @param string $moduleUri
     * @param $className
     * @return bool
     */
    public static function get($moduleUri = '', $className)
    {
        return self::registerRoute(self::createRoute('get', $moduleUri, $className));
    }

    /**
     * @param string $moduleUri
     * @param $className
     * @return bool
     */
    public static function post($moduleUri = '', $className)
    {
        return self::registerRoute(self::createRoute('post', $moduleUri, $className));
    }

    /**
     * @param string $moduleUri
     * @param $className
     * @return bool
     */
    public static function put($moduleUri = '', $className)
    {
        return self::registerRoute(self::createRoute('put', $moduleUri, $className));
    }

    /**
     * @param string $moduleUri
     * @param $className
     * @return bool
     */
    public static function delete($moduleUri = '', $className)
    {
        return self::registerRoute(self::createRoute('delete', $moduleUri, $className));
    }

    /**
     *
     */
    public static function request()
    {
        $args = func_get_args();
        if (count($args) && is_array($args[0])) {
            foreach ($args[0] as $moduleUri => $className) {
                self::get($moduleUri, $className);
                self::post($moduleUri, $className);
                self::put($moduleUri, $className);
                self::delete($moduleUri, $className);
            }
        } else {
            list($moduleUri, $className) = $args;
            self::get($moduleUri, $className);
            self::post($moduleUri, $className);
            self::put($moduleUri, $className);
            self::delete($moduleUri, $className);
        }
    }

    /**
     * @return bool
     */
    public static function match()
    {
        $key = self::routeKey(strtolower($_SERVER['REQUEST_METHOD']), Request::module());
        if (isset(self::$_routes[$key])) {
            self::$_matched_route_key = $key;
            return true;
        }
        return false;
    }

    /**
     *
     */
    public static function dispatch()
    {
        if ('' !== self::$_matched_route_key) {
            // attempt to include and instantiate
            $class = self::$_routes[self::$_matched_route_key]['className'];
            $controller = new $class;

            if ($controller instanceof Controller) {
                // invoke the request method of the class
                // controller->process() takes care of the request method
                $controller->process();
            } else {
                // TODO
                // improperly inherited controller
            }

        } else {
            // TODO
            // no match done prior to dispatch
        }
    }

    /**
     * @return bool
     */
    public static function process()
    {
        if (self::match()) {
            self::dispatch();
            return true;
        }
        return false;
    }
}
