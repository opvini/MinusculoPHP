<?php


//*********************************************************************
// MinusculoPHP Talk
// MinModel - Classe para os erros.
// Sempre carregado junto com o Controller
//
// Criado por: Vinícius Nunes Lage
// Criado em: 23/02/2016
// Modificado em: 23/02/2016
//
///////////////////////////////////////////////////////////////////////


/*

emergency();
alert();
critical();
error();
warning();
notice();
info();
debug();

*/


/******** EXEMPLO DE CONFIGURAÇÃO DE ERRO EM DIFERENTES LÍNGUAS

  public $message = array(
  
	'pt-br' => array(
	  0 => "Esse é o erro zero",
	  1 => "Esse é o erro um"
	),
	
	'en' => array(
	  0 => "This is the message zero",
	  1 => "This is the message onde"
	)
	
  );

****************************************/


/******** EXEMPLO DE CONFIGURAÇÃO DE ERRO BÁSICO

  public $erro = array(
  
	  0 => "Esse é o erro zero",
	  1 => "Esse é o erro um"	
  
  );

****************************************/


class MinTalk
{
	
	public $message = array();
	
	
	public function __construct()
	{
	}
  
	public function setMessage($id, $str)
	{
		if( isset($this->message[LANGUAGE] ) )  $this->message[LANGUAGE][$id] = $str;
		else								  	$this->message[$id] = $str;
	}
	
	public function getMessage($id)
	{
		if( isset($this->message[LANGUAGE][$id]) ) 	return $this->message[LANGUAGE][$id];
		else if( isset($this->message[$id]) )		return $this->message[$id];
	}
	
	public function getAllMessages()
	{
	}
	
	public function success($id)
	{
		echo $this->getMessage($id);
	}
	
	public function error($id)
	{
		echo $this->getMessage($id);
	}
	
	public function showMessage($id)
	{
		echo $this->getMessage($id);
	}
	
	public function showAllMessages()
	{
	}
	
 
} // MinTalk

?>