<?php


//*********************************************************************
// MinusculoPHP Model
// MinModel - Classe do Modelo geral, fará parte de todos os models
//
// Criado por: Vinícius Nunes Lage
// Criado em: 09/04/2015
// Modificado em: 22/02/2017
//
///////////////////////////////////////////////////////////////////////


class MinModel
{

	public $db;
	public $login;
	
	public function __construct( $db, $login )
	{
		$this->db     = $db;
		$this->login  = $login;
	}
 
} // MinModel

?>