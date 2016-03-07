<?php


//*********************************************************************
// MinusculoPHP Error
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
	  0 => "This is the error zero",
	  1 => "This is the error onde"
	)
	
  );

****************************************/


/******** EXEMPLO DE CONFIGURAÇÃO DE ERRO BÁSICO

  public $erro = array(
  
	  0 => "Esse é o erro zero",
	  1 => "Esse é o erro um"	
  
  );

****************************************/


class MinError
{
	
	public $error = array();
	
	
	public function __construct()
	{
	}
  
	public function set_error($id, $str)
	{
		if( isset($this->error[LANGUAGE] ) )  $this->error[LANGUAGE][$id] = $str;
		else								  $this->error[$id] = $str;
	}
	
	public function get_error($id)
	{
		if( isset($this->error[LANGUAGE][$id]) ) 	return $this->error[LANGUAGE][$id];
		else if( isset($this->error[$id]) )			return $this->error[$id];
	}
	
	public function get_all_errors()
	{
	}
	
	public function show_error($id)
	{
		echo $this->get_error($id);
	}
	
	public function show_all_errors()
	{
	}
	
 
} // MinError

?>