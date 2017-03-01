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


/******** EXEMPLO DE CONFIGURAÇÃO DE ERRO EM DIFERENTES LÍNGUAS - exemplo-talk.php

  protectes $message = array(
  
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
	
	protected	$message = array();
	private 	$talk	 = array();
	private 	$msg	 = array();
	private 	$item	 = array();
	private		$success = true;
	
	
	public function __construct()
	{
	}
	
	
	
	// addError() e addSuccess() se diferenciam apenas pelo resultado do "success"
	// as mensagens de erro/sucesso estão salvas no diretório talks
	
	public function addError( $id )
	{
		$this->addMsg(abs($id));
		$this->success = false;
		return $this;
	}
	
	public function addSuccess( $id )
	{
		$this->addMsg($id);
		$this->success = true;
		return $this;
	}
	
	
	// adiciona uma mensagem dinâmica à resposta
	// como por exemplo o SID do login
	public function addMessage($arr)
	{
		$this->msg[] = $arr;
		return $this;
	}


	// adiciona um item à resposta
	public function addItem($arr)
	{
		$this->item = array_merge($this->item, $arr);
		return $this;
	}	
	
	
	////////////////////////////////////////// MÉTODOS PRIVADOS
	
	private function addMsg($id)
	{
		$this->msg[] = $this->getMessage($id);
	}
	
	
	private function getMessage($id)
	{
		if( isset($this->message[LANGUAGE][$id]) ) 	return $this->message[LANGUAGE][$id];
		else if( isset($this->message[$id]) )		return $this->message[$id];
	}
	
	
	private function renderTalk()
	{
		$this->talk = array(
						 "success"  => $this->success,
						 "lenguage" => LANGUAGE,
						 "messages" => $this->msg
		);
		
		$this->talk = array_merge($this->talk, $this->item);
	}
	
	
	
	
	////////////////////////////////////////// RESPOSTAS
	
	
	// retorna o array
	public function response()
	{
		$this->renderTalk();
		return $this->talk;
	}
	
	
	// retorna o JSON
	public function show()
	{
		$this->renderTalk();
		echo json_encode($this->talk);
	}
	
		
 
} // MinTalk

?>