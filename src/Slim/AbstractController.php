<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Slimkit\Slim;

use Slimkit\Slim\Slim;
use Slimkit\Slim\View\SmartyView;

abstract class AbstractController
{
    /**
     * @var Slim
     */
    protected $app;

    /**
     * @var SmartyView
     */
    protected $view;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $errorTemplate = 'global/error.tpl';

    /**
     *
     * @param Slim $app
     * @param type $params
     */
    public function __construct(Slim $app, $params)
    {
        $this->app = $app;
        $this->view = $this->app->view;
        $this->params = $params;
    }

    /**
     * Initialize controller just after instanciation
     */
    public function init()
    {
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

    /**
     * Display error page
     *
     * @param string $errorMessage
     * @param string $returnPage
     * @param string $errorCode
     */
    protected function error($errorMessage, $returnPage = null, $errorCode = null)
    {
        $this->view->setData(
            [
                'errorMessage' => $errorMessage,
                'errorCode'    => $errorCode,
                'returnPage'   => $returnPage
            ]
        );
        $this->view->setTemplate('global/error.tpl');
    }

    /**
     * @return string
     */
    protected function getErrorTemplate()
    {
        return $this->errorTemplate;
    }
}
