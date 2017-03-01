<?php

class HomeController extends MinController
{

    public function index() 
	{		
		// Carrega o arquivo do view
        require $this->thisDir . '/home-view.php';		
    }
	
	
} // class HomeController