<?php


//***************************************************************************
//
// MinusculoPHP Auth
// Executa as acões de login
//
// Criado por: Vinícius Nunes Lage
// Criado em: 20/10/2015
// Modificado em: 22/02/2017
//
/////////////////////////////////////////////////////////////////////////////


/////// ATRIBUTOS E MÉTODOS
//
// index	: pagina inicial
// enter	: faz o login 			: POST {user, pass}
// logout	: finaliza uma sessão	: 
// token	: loga com um token		: GET site.com/login/token/{TOKEN}
//


class AuthController extends MinController
{

	public $form;
	
    public function index() 
	{		
		include $this->thisDir. "/auth-view.php";
    } 
	
	

	// responsável por fazer o login
	// recebe via POST user e pass
	// valida no banco usando MD5() como criptografia na senha
	// ou seja, a senha precisa estar salva criptografada com MD5()
	
    public function enter() {
		
	  $this->form = new MinForm();	
			
	  ///////////////////////////////////////// CAMPOS (class MinForm)
	  $fields = array(
	  	"user" => array(
		  "method"  => "get",
		  "filters"	=> array(
		    "required"   => true,
			"maxlength"  => 30,
			"minlength"  => 4,
			"addslashes" => true	
		   )
	  	),
	  
	  	"pass" => array(
		  "method"  => "get",
		  "filters"	=> array(
		    "required"   => true,
			"maxlength"  => 30,
			"minlength"  => 4,
			"addslashes" => true
			//"md5"		 => true	
		   )
		 )
	  );		
	  
	  $this->form->add( $fields );
	  
	  if( $this->form->isValid() )
	  {	
	  	
		if ( $this->login->login( $this->form->getValue('user') , $this->form->getValue('pass') ) )
		{
			$this->talk->addMessage( array("sid" => session_id() ) );
			$this->talk->addSuccess(0)->show();
		}
		else
			$this->talk->addError(1)->show();
		
	  }
	  else
	  	print $this->talk->addError(2)->show();
	  
	  			
    } // enter()
	
	
	// faz logout
	
	public function logout()
	{
		$this->login->logout();
		$this->talk->addSuccess(3)->show();
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
			if( $this->login->login_with_token($token) )
				$this->talk->addSuccess(0)->show();
			else
				$this->talk->addError(4)->show();
		}
		else
			$this->talk->addError(5)->show();
			
	} // token()
		
		
	
} // class AuthController