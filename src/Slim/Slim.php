<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Slimkit\Slim;

class Slim extends \Slim\Slim
{
    protected $module     = 'index';
    protected $controller = 'index';
    protected $action     = 'index';

    /**
     * Override run method to include home made default dispatch using /Module/Controller/Action url
     *
     * @see \Slim\Slim::run()
     *
     * @return void
     */
    public function run()
    {
        $this->get('/', array($this, 'dispatchController'));
        $this->map('(/:module(/:controller(/:action(/:params+))))', array($this, 'dispatchController'))
                ->via('GET', 'POST')
                ->name('default');
        parent::run();
    }   // function run

    /**
     * Default dispatch for module/controller/action urls
     *
     * @param string $moduleName     Module name
     * @param string $controllerName Controller name in module's directyr
     * @param string $actionName     Action name in controller
     * @param array  $params         Other params
     *
     * @return void
     */
    public function dispatchController($moduleName = 'index', $controllerName = 'index', $actionName = 'index', array $params = array())
    {
        $this->module = $moduleName;
        $this->controller = $controllerName;
        $this->action = $actionName;
        
        $controllerClassname = $this->config('controller_namespace').ucfirst($moduleName).'\\'.ucfirst($controllerName) . 'Controller';
        $methodName          = $actionName . 'Action';

        $action_params = array_merge($this->parseParams($params), $_POST);
        $controller = new $controllerClassname($this, $action_params);
        $this->view()->setTemplate(
            strtolower($moduleName) . DIRECTORY_SEPARATOR . strtolower($controllerName) . DIRECTORY_SEPARATOR . strtolower($actionName) . '.tpl'
        );
        $this->view()->appendData(array('slim' => $this));
        try {
            $controller->beforeActionProcess();
            $controller->$methodName();
            $controller->afterActionProcess();
            echo $this->view->render();
        } catch(\Exception $e) {
            var_dump($e);
            /*$errorParams = $this->parseParams($params);
            $errorParams = array_merge(
                $action_params, array(
                    'module' => $moduleName,
                    'controller' => $controllerName,
                    'action' => $actionName,
                    'exception' => $e
                )
            );
            $errorController = new ErrorController($this, $errorParams);
            $errorController->exceptionAction();*/
        }
    }   // function dispatchController    

    /**
     * Parse list parameters into an arry with param[n] as key and param[n+1] as value
     *
     * @param array $params List of params
     *
     * @return array
     */
    public function parseParams($params)
    {
        $toReturn = array();
        for ($i = 0; $i < count($params); $i+=2) {
            if (isset($params[$i+1])) {
                $toReturn[$params[$i]] = $params[$i+1];
            } else {
                $toReturn[] = $params[$i];
            }
        }
        return $toReturn;
    }   // function parseParams    

    /**
     * Override urlFor method to add default module/controller/action with default route
     *
     * @param string $name   Route name
     * @param array  $params Route parameters
     *
     * @return string
     *
     * @see \Slim\Slim::urlFor()
     */
    public function urlFor($name, $params = array())
    {
        if ($name == 'default') {
            if (! isset($params['module'])) {
                $params['module'] = $this->module;
            }
            if (! isset($params['controller'])) {
                $params['controller'] = $this->controller;
            }
            if (! isset($params['action'])) {
                $params['action'] = $this->action;
            }
        }
        return parent::urlFor($name, $params);
    }   // function urlFor    
}