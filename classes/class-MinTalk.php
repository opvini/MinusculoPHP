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


/******** EXEMPLO DE CONFIGURAÇÃO DE ERRO EM DIFERENTES LÍNGUAS

  public $erro = array(
  
	'pt-br' => array(
	  0 => "Esse é o erro zero",
	  1 => "Esse é o erro um"
	),
	
	'en' => array(
	  0 => "This is the talk zero",
	  1 => "This is the talk onde"
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
	
	public $talk = array();
	
	
	public function __construct()
	{
	}
  
	public function set_talk($id, $str)
	{
		if( isset($this->talk[LANGUAGE] ) )  $this->talk[LANGUAGE][$id] = $str;
		else								  $this->talk[$id] = $str;
	}
	
	public function get_talk($id)
	{
		if( isset($this->talk[LANGUAGE][$id]) ) 	return $this->talk[LANGUAGE][$id];
		else if( isset($this->talk[$id]) )			return $this->talk[$id];
	}
	
	public function get_all_talks()
	{
	}
	
	public function show_talk($id)
	{
		echo $this->get_talk($id);
	}
	
	public function show_all_talks()
	{
	}
	
 
} // Mintalk

?>