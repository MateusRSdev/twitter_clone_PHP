<?php 

namespace MF\Controller;

abstract class Action{
    protected $view;
    public function __construct() {
		$this->view = new \stdClass();
	}

    protected function render($view,$layout = "layout"){
        $this->view->page = $view;

        if(file_exists("../App/Views/".$layout.".phtml")){
            require_once "../App/Views/".$layout.".phtml";
        }else{
            $this->Content();
        }
        // $this->Content()
    }

    protected function Content(){
        $claseAtual = get_class($this);
    
        $claseAtual =  str_replace("App\\Controllers\\", "", $claseAtual);
    
        $claseAtual = strToLower(str_replace("Controller", "", $claseAtual));
    
        require_once "../App/Views/".$claseAtual."/".$this->view->page.".phtml";
    }
}




?>