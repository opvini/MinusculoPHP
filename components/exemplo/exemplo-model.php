<?php

class ExemploModel extends MinModel
{
	
	public $form;
		
	public function recebeForm()
	{
	
	  $this->form = new MinForm();	
		
	  print_r($this->login);	
	  	
	  $user = array(
		"name" 	=> "user",
		"method"  => "get",
		"filters"	=> array(
		  "required"   => true,
		  "maxlength"  => 30,
		  "minlength"  => 4,
		  "addslashes" => true	
		 )
	  );
	  
	  $this->form->add( $user );
	  
	  if($this->form->isValid())
	  {
	  	echo $this->form->getValue("user")." - ";
	  }
	  
	}
	
} // class ExemploModel