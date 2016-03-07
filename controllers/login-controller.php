<?php


//***************************************************************************
//
// MinusculoPHP Login
// Executa as acões de login
//
// Criado por: Vinícius Nunes Lage
// Criado em: 20/10/2015
// Modificado em: 20/10/2015
//
/////////////////////////////////////////////////////////////////////////////


/////// ATRIBUTOS E MÉTODOS
//
// index	: faz o login 			: POST {user, pass}
// logout	: finaliza uma sessão	: 
// token	: loga com um token		: GET site.com/login/token/[TOKEN]
//

class LoginController extends MinController
{

	public $form;

	// responsável por fazer o login
	// recebe via POST user e pass
	// valida no banco usando MD5() como criptografia na senha
	// ou seja, a senha precisa estar salva criptografada com MD5()
	
    public function index() {
		
	  $this->form = new MinForm();	
			
	  ///////////////////////////////////////// CAMPOS (class MinForm)
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
	  
	  $pass = array(
		  "name" 	=> "pass",
		  "method"  => "get",
		  "filters"	=> array(
		    "required"   => true,
			"maxlength"  => 30,
			"minlength"  => 4,
			"addslashes" => true,
			"md5"		 => true	
		   )
	  );		
	  
	  $this->form->add( $user, $pass );
	  
	  if( $this->form->isValid() )
	  {	
	  	
		if ( $this->app->login( $this->form->getValue('user') , $this->form->getValue('pass') ) )
			print '{"success":true, "msg": "Login efetuado com sucesso."}';
		else
			print '{"success":false, "errors": ["Erro #1: usuario ou senha invalidos."]}';
		
	  }
	  else
	  	print '{"success":false, "errors": ["Erro #2: existe um erro em seus campos."]}';
	  
	  			
    } // index()
	
	
	public function logout()
	{
		$this->app->logout();
		print '{"success":true, "msg": "Logout efetuado com sucesso"}';
	}
		
	
	// faz login atraves de um token
	// muito util para envio de links atraves de email
	// ou mesmo em servidores distribuidos
	// padrão de endereço: site.com/login/token/[TOKEN]
	
	public function token()
	{
		$token = isset($this->parametros[0]) ? $this->parametros[0] : "";
		
		if($token != "")
		{
			if( $this->app->login_with_token($token) )
				print '{"success":true, "msg": "Login efetuado com sucesso."}';
			else
				print '{"success":false, "errors": ["Erro #3: token invalido."]}';
		}
		else
			print '{"success":false, "errors": ["Erro #4: token invalido."]}';
			
	} // token()
		
		
	
} // class LoginController