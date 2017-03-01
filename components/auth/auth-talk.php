<?php


// coloca todas as mensagens em diversas línguas
// podem ser mensagens de sucesso, erro, alerta, etc


class AuthTalk extends MinTalk
{

  public $message = array(
  
	'pt-br' => array(
	  0 => "Login efetuado com sucesso.",
	  1 => "Usuario ou senha invalidos.",
	  2 => "Existe um erro em seus campos.",
	  3 => "Logout efetuado com sucesso.",
	  4 => "Token invalido.",
	  5 => "Token nao informado."
	),
	
	'en' => array(
	  0 => "Login successfully.",
	  1 => "Invalid username or password",
	  2 => "Invalid fields.",
	  3 => "Logout successfully.",
	  4 => "Invalid token.",
	  5 => "Token not set."
	)
	
  );
  

}


?>