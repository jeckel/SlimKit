<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Slimkit\Slim\View;

use Slim\View;

class SmartyView extends View
{
    /**
     *
     * @var string
     */
    protected $template = null;

    /**
     * @var Smarty persistent instance of the Parser object.
     */
    protected $parser = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * Render Template
     *
     * This method will output the rendered template content
     *
     * @param string $template The path to the template, relative to the  templates directory.
     * @param null $data
     *
     * @return string
     */
    public function render($template = null, $data = null)
    {
        if (is_null($template)) {
            $template = $this->template;
        }
        $this->parser->assign($this->all());
        $toReturn = '';
        if (isset($this->options['header'])) {
            $toReturn .= $this->parser->fetch($this->options['header'], $data);
        }
        $toReturn .= $this->parser->fetch($template, $data);
        if (isset($this->options['footer'])) {
            $toReturn .= $this->parser->fetch($this->options['footer'], $data);
        }
        return $toReturn;
    }

    /**
     *
     * @param \Smarty $smarty
     * @return \Jeckel\Gallery\Slim\View\SmartyView
     */
    public function setSmartyInstance(\Smarty $smarty)
    {
        $this->parser = $smarty;
        return $this;
    }

    /**
     *
     * @return \Smarty
     */
    public function getSmartyInstance()
    {
        return $this->parser;
    }

    /**
     *
     * @param array $options
     * @return \Jeckel\Gallery\Slim\View\SmartyView
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     *
     * @param string $template
     *
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}