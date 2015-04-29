<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Slimkit\Test\Slim\View;

use Slimkit\Slim\View\SmartyView;
use PHPUnit_Framework_TestCase;

class SmartyViewTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $view = new SmartyView();
        $this->assertInstanceOf(SmartyView::class, $view);
    }
    
    public function testSetGetSmartyInstance()
    {
        $view = new SmartyView();
        $mock = $this->getMockBuilder("\Smarty")
                ->disableOriginalConstructor()
                ->getMock();
        
        $this->assertSame($view, $view->setSmartyInstance($mock));
        $this->assertSame($mock, $view->getSmartyInstance());
    }
    
    public function testSetGetOptions()
    {
        $view = new SmartyView();
        $options = ['foo' => 'bar'];
        $this->assertSame($view, $view->setOptions($options));
        $this->assertSame($options, $view->getOptions());
    }
    
    public function testSetGetTemplate()
    {
        $view = new SmartyView();
        $template = "foobar/tpl";
        $this->assertSame($view, $view->setTemplate($template));
        $this->assertEquals($template, $view->getTemplate());
    }
    
    public function testRender()
    {
        $view = new SmartyView();
        $smartyMock = $this->getMockBuilder("\Smarty")
                ->disableOriginalConstructor()
                ->getMock();
        $view->setSmartyInstance($smartyMock);
        
        $templateName = 'foo.tpl';
        $templateData = ['foo' => 'baz'];
        $templateRendered = 'foo_bar_baz';
        
        $smartyMock->expects($this->once())
                ->method('fetch')
                ->with($templateName, $templateData)
                ->willReturn($templateRendered);
        $this->assertEquals($templateRendered, $view->render($templateName, $templateData));
    }
    
    public function testRenderWithPredefinedTemplate()
    {
        $view = new SmartyView();
        $smartyMock = $this->getMockBuilder("\Smarty")
                ->disableOriginalConstructor()
                ->getMock();
        $view->setSmartyInstance($smartyMock);
        $templateName = 'foo.tpl';
        
        $view->setTemplate($templateName);
        $templateRendered = 'foo_bar_baz';
        
        $smartyMock->expects($this->once())
                ->method('fetch')
                ->with($templateName)
                ->willReturn($templateRendered);
        $this->assertEquals($templateRendered, $view->render());
    }
    
    public function testRenderWithHeaderAndFooter()
    {
        $view = new SmartyView();
        $smartyMock = $this->getMockBuilder("\Smarty")
                ->disableOriginalConstructor()
                ->getMock();
        $view->setSmartyInstance($smartyMock);
        $viewOptions = [
            'header' => 'header.tpl',
            'footer' => 'footer.tpl'
        ];
        $view->setOptions($viewOptions);

        $templateName = 'foo.tpl';
        $templateData = ['foo' => 'baz'];
        $templateRendered = 'header-foo_bar_baz-footer';

        $smartyMock->expects($this->at(1))
                ->method('fetch')
                ->with('header.tpl')
                ->willReturn('header-');
        $smartyMock->expects($this->at(2))
                ->method('fetch')
                ->with($templateName, $templateData)
                ->willReturn('foo_bar_baz');
        $smartyMock->expects($this->at(3))
                ->method('fetch')
                ->with('footer.tpl')
                ->willReturn('-footer');
        
        $this->assertEquals($templateRendered, $view->render($templateName, $templateData));
    }
}