<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Slimkit\Slim;

use Slimkit\Slim\Slim;

abstract class AbstractController
{
    /**
     *
     * @var Slim
     */
    protected $app;
    
    /**
     *
     * @var array
     */
    protected $params = [];
    
    /**
     * 
     * @param Slim $app
     * @param type $params
     */
    public function __construct(Slim $app, $params)
    {
        $this->app = $app;
        $this->params = $params;
    }
    
    /**
     * Called before all actions
     * 
     * @return void
     */
    public function beforeActionProcess()
    {
    }
    
    /**
     * Called after all actions
     * 
     * @return void
     */
    public function afterActionProcess()
    {
    }
}
