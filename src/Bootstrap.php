<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Slimkit;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Slimkit\Slim\Slim;
use Slimkit\Slim\View\SmartyView;
use Smarty;

class Bootstrap
{
    /**
     * Configuration
     * @var array
     */
    protected $config = [];
    
    /**
     *
     * @var Slim
     */
    protected $app;
    
    /**
     *
     * @var SmartyView
     */
    protected $view;
    
    /**
     * Database connector
     * @var Capsule
     */
    protected $capsule;
    
    /**
     * Constructor
     * 
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }
    
    /**
     * Define configuration values
     * 
     * @param array $config
     * 
     * @return \Jeckel\Gallery\Bootstrap
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }
    
    /**
     * Return configuration
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
    
    public function run()
    {
        date_default_timezone_set('Europe/Paris');
        $app = $this->getSlim();
        return $app->run();
    }
    
    /**
     * Instanciate view renderer
     * 
     * @return SmartyView
     */
    public function getView()
    {
        if ($this->view instanceof SmartyView) {
            return $this->view;
        }
        
        $smarty = new Smarty();
        if (isset($this->config['smarty'])) {
            $smartyConfig = $this->config['smarty'];
            if (isset($smartyConfig['templates_path'])) {
                $smarty->template_dir = $smartyConfig['templates_path'];
            }
            if (isset($smartyConfig['compile_path'])) {
                $smarty->compile_dir = $smartyConfig['compile_path'];
            }
            if (isset($smartyConfig['extension_path'])) {
                $smarty->addPluginsDir($smartyConfig['extension_path']);
            }
            if (isset($smartyConfig['cache_path'])) {
                $smarty->cache_dir = $smartyConfig['cache_path'];
            }
        }
        
        $this->view = new SmartyView();
        $this->view->setSmartyInstance($smarty);
        if (isset($this->config['view'])) {
            $this->view->setOptions($this->config['view']);
        }
        return $this->view;
    }
    
    public function getSlim()
    {
        if ($this->app instanceof Slim) {
            return $this->app;
        }
        
        $config = isset($this->config['slim']) ? $this->config['slim'] : [];
        $config['view'] = $this->getView();
        
        $this->app = new Slim($config);
        $this->app->capsule = $this->getDatabase();
        return $this->app;
    }
    
    /**
     * Initiate database
     * 
     * @return Capsule
     */
    public function getDatabase()
    {
        if ($this->capsule instanceof Capsule) {
            return $this->capsule;
        }
            
        $this->capsule = new Capsule;
        $this->capsule->addConnection($this->config['database']);
        $this->capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $this->capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $this->capsule->bootEloquent();
        return $this->capsule;
    }
}
