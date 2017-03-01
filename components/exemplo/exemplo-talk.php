<?php


// coloca todas as mensagens em diversas línguas
// podem ser mensagens de sucesso, erro, alerta, etc


class ExemploTalk extends MinTalk
{

  protected $message = array(
  
	'pt-br' => array(
	  0 => "Sem permissao.",
	  1 => "Esse é o erro um",
	  2 => "Esse é o erro dois",
	  3 => "Esse é o erro três",
	  4 => "Esse é o erro quatro"
	),
	
	'en' => array(
	  0 => "You dont have access.",
	  1 => "This is the error one",
	  2 => "This is the error two",
	  3 => "This is the error three",
	  4 => "This is the error four"
	)
	
  );
  

}


?>