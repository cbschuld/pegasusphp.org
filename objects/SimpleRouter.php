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
class SimpleRouter
{

    // 404 / not found route
    private static $_not_found = [];

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
        return $method . '_' . self::$_prepend_module_uri . $module . self::$_append_module_uri;
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
            'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
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


    public static function notFound($className = '')
    {
        self::$_not_found = $className;
    }

    /**
     *
     */
    public static function request()
    {
        /** @var string[] $arg */
        $arg = func_get_arg(0);

        if (is_array($arg)) {
            foreach ($arg as $moduleUri => $className) {

                // mimic runtime of: self::get($moduleUri, $className);
                self::$_routes['get_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                    'method' => 'get',
                    'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                    'className' => $className
                ];

                // mimic runtime of: self::post($moduleUri, $className);
                self::$_routes['post_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                    'method' => 'post',
                    'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                    'className' => $className
                ];

                // mimic runtime of: self::put($moduleUri, $className);
                self::$_routes['put_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                    'method' => 'put',
                    'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                    'className' => $className
                ];

                // mimic runtime of: self::delete($moduleUri, $className);
                self::$_routes['delete_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                    'method' => 'delete',
                    'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                    'className' => $className
                ];
            }
        } else {

            list($moduleUri, $className) = $arg;

            // mimic runtime of: self::get($moduleUri, $className);
            self::$_routes['get_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                'method' => 'get',
                'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                'className' => $className
            ];

            // mimic runtime of: self::post($moduleUri, $className);
            self::$_routes['post_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                'method' => 'post',
                'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                'className' => $className
            ];

            // mimic runtime of: self::put($moduleUri, $className);
            self::$_routes['put_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                'method' => 'put',
                'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                'className' => $className
            ];

            // mimic runtime of: self::delete($moduleUri, $className);
            self::$_routes['delete_' . self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri] = [
                'method' => 'delete',
                'module' => self::$_prepend_module_uri . $moduleUri . self::$_append_module_uri,
                'className' => $className
            ];

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
     * @throws \RuntimeException
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
                throw new \RuntimeException('Class of supplied className is not of instance Controller');
            }

        } else {
            if ('' !== self::$_not_found) {
                $notFoundController = new self::$_not_found;
                if ($notFoundController instanceof Controller) {
                    $notFoundController->process();
                } else {
                    throw new \RuntimeException('Class of supplied notFound method is not of instance Controller');
                }
            } else {
                throw new \RuntimeException('Current module and path did not match to any known routes');
            }
        }
    }

    /**
     * @throws \RuntimeException
     */
    public static function process()
    {
        self::match();
        self::dispatch();
    }
}
