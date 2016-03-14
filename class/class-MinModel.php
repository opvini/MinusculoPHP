<?php


//*********************************************************************
// MinusculoPHP Model
// MinModel - Classe do Modelo geral, fará parte de todos os models
//
// Criado por: Vinícius Nunes Lage
// Criado em: 09/04/2015
// Modificado em: 19/10/2015
//
///////////////////////////////////////////////////////////////////////


class MinModel
{

	public $db;
	public $app;
	
	public function __construct( $db, $app )
	{
		$this->db  = $db;
		$this->app = $app;
	}
 
} // MinModel

?>