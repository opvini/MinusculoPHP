<?php

class HomeController extends MinController
{

    public function index() 
	{		
		// Carrega o arquivo do view
        require ABSPATH . '/views/home-view.php';		
    }
	
	
} // class HomeController