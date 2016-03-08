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
  
	public function set_message($id, $str)
	{
		if( isset($this->message[LANGUAGE] ) )  $this->message[LANGUAGE][$id] = $str;
		else								  $this->message[$id] = $str;
	}
	
	public function get_message($id)
	{
		if( isset($this->message[LANGUAGE][$id]) ) 	return $this->message[LANGUAGE][$id];
		else if( isset($this->message[$id]) )			return $this->message[$id];
	}
	
	public function get_all_messages()
	{
	}
	
	public function show_message($id)
	{
		echo $this->get_message($id);
	}
	
	public function show_all_messages()
	{
	}
	
 
} // Mintalk

?>