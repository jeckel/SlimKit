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
    protected $app;
    
    protected $params;
    
    public function __construct(Slim $app, $params)
    {
        $this->app = $app;
        $this->params = $params;
    }
    
    public function beforeActionProcess()
    {
        
    }
    
    public function afterActionProcess()
    {
        
    }
}
