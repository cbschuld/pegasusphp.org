<?php


/**
 *
 * @author cbschuld
 * @package PegasusPHP
 */
class RequestPHP4WrapperForSmarty
{
    public function get($k)
    {
        return Request::get($k);
    }

    public function explode($k)
    {
        return Request::explode($k);
    }

    public function set($k, $v)
    {
        Request::set($k, $v);
    }

    public function exists($k)
    {
        return Request::exists($k);
    }

    public function action()
    {
        return Request::action();
    }

    public function module()
    {
        return Request::module();
    }
}

/**
 *
 * @author cbschuld
 * @package PegasusPHP
 */
class SessionPHP4WrapperForSmarty
{
    public function get($k)
    {
        return Session::get($k);
    }

    public function set($k, $v)
    {
        Session::set($k, $v);
    }

    public function exists($k)
    {
        return Session::exists($k);
    }

    public function userLoggedIn()
    {
        return Session::userLoggedIn();
    }
}


/**
 *
 * @author cbschuld
 * @package PegasusPHP
 */
class View
{

    /** @var Smarty */
    private static $_smarty = null;

    private static $_requestPHP4WrapperForSmarty;
    private static $_sessionPHP4WrapperForSmarty;

    private static $_smartyContainerFilename;
    private static $_bShowContainer = true;

    public static function created()
    {
        return self::$_smarty != null;
    }

    public static function create()
    {

        self::$_smarty = new Smarty();

        self::$_smarty->addPluginsDir(constant('FRAMEWORK_PATH') . '/includes/smarty3-plugins/');

        self::$_smartyContainerFilename = constant('SMARTY_CONTAINER_PAGE');

        self::$_requestPHP4WrapperForSmarty = new RequestPHP4WrapperForSmarty();
        self::$_sessionPHP4WrapperForSmarty = new SessionPHP4WrapperForSmarty();
        self::$_smarty->assignByRef('request', self::$_requestPHP4WrapperForSmarty);
        self::$_smarty->assignByRef('session', self::$_sessionPHP4WrapperForSmarty);


        // Pegasus is a global variable instance and referenced directly here
        self::$_smarty->assignByRef('pegasus', new Pegasus());

        // If the user has been encapsulated setup the existence in the view
        if (Session::created() && Session::userLoggedIn()) {
            self::assign(constant('USER_VAR'), Session::get(constant('USER_VAR')));
        } else {
            self::assign(constant('USER_VAR'), null);
        }

        // Assign and set the standard module and action values
        self::assign('module', Request::get('module'));
        self::assign('action', Request::get('action'));
    }

    public static function getSmarty()
    {
        return self::$_smarty;
    }

    public static function compile_dir($v = null)
    {
        if ($v != null) {
            self::$_smarty->setCompileDir($v);
        }
        return self::$_smarty->getCompileDir();
    }

    public static function template_dir($v = null)
    {
        if ($v != null) {
            self::$_smarty->setTemplateDir($v);
        }
        return self::$_smarty->getTemplateDir();
    }

    public static function cache_dir($v = null)
    {
        if ($v != null) {
            self::$_smarty->setCacheDir($v);
        }
        return self::$_smarty->getCacheDir();
    }

    public static function prependPluginDir($path)
    {
        if (is_array(self::$_smarty->getPluginsDir())) {
            self::$_smarty->setPluginsDir(array_merge(array($path), self::$_smarty->getPluginsDir()));
        } else {
            self::$_smarty->setPluginsDir(array($path, self::$_smarty->getPluginsDir()));
        }
    }

    /**
     * Assign a value to a key in the smarty template
     *
     * @param string $key
     * @param string $value
     */
    public static function assign($key, $value)
    {
        self::$_smarty->assignGlobal($key, $value);
    }

    /**
     * Assign a template result to a key in the smarty template
     *
     * @param string $key
     * @param string $t
     */
    public static function assignTemplate($key, $t)
    {
        self::assign($key, self::fetch($t));
    }

    /**
     * Assign a value to a key in the smarty template
     *
     * @param string $key
     * @param string $value
     */
    public static function assignByRef($key, &$value)
    {
        self::$_smarty->assignByRef($key, $value);
    }

    public static function templateExists($t)
    {
        return self::$_smarty->templateExists($t);
    }

    //deprecated
    public static function template_exists($t)
    {
        return self::$_smarty->templateExists($t);
    }

    /**
     * overloads smarty's fetch to allow for a "memory" compile (a raw eval on the content) to
     * allow for error messages, built in demos/tests which do not have access to a writable path
     */
    public static function fetch($template, $cache_id = null, $compile_id = null, $parent = null, $display = false)
    {
        return self::$_smarty->fetch($template, $cache_id, $compile_id, $parent, $display);
    }

    /**
     * Displays a give template page within the container.
     *
     * @param string $strTemplateName The filename of the template to
     *                                display
     */
    public static function displayPage($strTemplateName)
    {
        self::$_smarty->display($strTemplateName);
        exit; // final call to toss content to screen!
    }

    /**
     * Displays a give template page within the container.
     *
     * @param string $strTemplateName The filename of the template to
     *                                display
     */
    public static function display($strTemplateName)
    {
        self::$_smarty->display($strTemplateName);
    }

    /**
     * setShowContainer() allows you to suspend the main container from being
     * displayed.
     * @param bool $bShowContainer If set the main container is shown otherwise
     * it is not shown
     */
    public static function setShowContainer($bShowContainer = true)
    {
        self::$_bShowContainer = $bShowContainer;
    }

    /**
     * Displays the container template set the caller.
     */
    public static function showContainer()
    {
        if (self::$_bShowContainer) {
            echo self::$_smarty->fetch(self::$_smartyContainerFilename);
        } else {
            echo self::$_smarty->getTemplateVars('content');
        }
    }
}
