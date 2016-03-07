<?php


//**************************************
//
// MinusculoPHP App
//
// Esta é a classe geral para o sistema
// os métodos de inserção, pesquisa, etc
// devem estar aqui
//
// Criado por: Vinícius Nunes Lage
// Criado em: 09/04/2015
// Modificado em: 19/10/2015
//
//**************************************


//**************************************
//
// Deve-se passar o objeto de conexão
// no construtor
//
/////////////// PADRÕES
//
// ARRAY_DADOS:
//
//   $array_dados = array(
//	   "name"  => "id",     // nome do campo no formulario
//	   "value" => 3,        // valor quando é constante
//	   "dbint" => true      // se o campo é do tipo inteiro
//   );
//
//   ou é recebido da classe de formulário
//   $arr_dados  = $form->getAllValuesDB();
//
//
/////////////// ATRIBUTOS PÚBLICOS
//
// user:
//  objeto com os dados do usuario
//
//
/////////////// MÉTODOS PÚBLICOS
//
//// LOGIN:
// login(user, pass)
// check_login()
// logout()
// finalizar()
// login_with_token()
// delete_token()
//
//// MÓDULO PESSOA FÍSICA:
// salva_pf(array_dados)
// delete_pf(array_dados)
// carrega_pf(array_dados)
//



class MinApp{
	
	private $db;
	private $logado = 0;
	public  $user;
	
	
	public function __construct( $conexao ){
		$this->db = $conexao;
	}
	
	
	
	/////////////////////////////////////////////// MODULO LOGIN
	//
	// faz o login
	// salva os dados do usuario em $user
	// carrega permissoes do ususario
	//
	
	public function login($user, $pass)
	{
		if( $this->db->is_conected() )
		{
			$sql = $this->db->query(
					"SELECT * FROM usuario WHERE bool_excluido = 0 AND usuario = ? AND senha = ?;",
					array( $user, $pass )
			);
			
			if( $sql->rowCount() > 0 )
			{	
				$this->user = $sql->fetchObject();	
				$this->load_user_data();		
				$this->set_logado($user, $pass);
				return true;
			}
			else return false;
		}
		else return false;
	}
	
	
	////// carrega todos os dados para user
	//
	private function load_user_data()
	{
		$sql = $this->db->query("SELECT 
									  U.Id, UT.descricao as tipo_usuario, U.fgk_tipo as tipo_id, U.usuario, PF.nome,
									  PF.endereco_numero, PF.cpf, PF.rg, PF.email 
									 FROM usuario as U, pessoa_fisica as PF, user_tipo as UT
									 WHERE 
									  U.Id			  = ? AND									  
									  U.fgk_pessoa    = PF.Id AND
									  U.fgk_tipo	  = UT.Id;",
								 array( $this->user->Id ));
		
		if( $sql->rowCount() > 0)
		{	
			// carrega dados
			$this->user = $sql->fetchObject();
		}

	}
	

	private function set_logado($user, $pass){
		$_SESSION["user"] = $user;
		$_SESSION["pass"] = $pass;
		$_SESSION["when"] = time(); 
		$this->logado 	  = 1;
	}
		
	private function reset_sessions(){
		unset($_SESSION["user"]);
		unset($_SESSION["pass"]);
		unset($_SESSION["when"]);
	}	


	public function check_login()
	{	
		if( isset($_SESSION["when"]) && ( time()-$_SESSION["when"] < LOGIN_TIMEOUT )  )
			if( isset($_SESSION["user"]) && isset($_SESSION["pass"]) && $this->login($_SESSION["user"], $_SESSION["pass"]) ) return true;
			else return false;
		else return false;
	}
	
	public function logout(){
		$this->reset_sessions();
		$this->logado = 0;
	}
	
	public function finalizar(){
		$this->conexao->desconect();
		$this->logado = 0;
	}
	

	public function check_permissao( $modulo, $acao )
	{	
	
		// arquivo com as permissões para cada ação
		require_once 'permissoes.php';
		
		// permissão minima exigida
		// verifica se tem permissão genérica para ação
		// ou expecificamente para esta ação deste módulo
		
		if 		( isset($_PERMS[ strtolower($modulo.'/'.$acao) ]) )		$tmp_perm_min = $_PERMS[ strtolower($modulo.'/'.$acao) ];
		else if	( isset($_PERMS_ACAO[ strtolower($acao) ]) )			$tmp_perm_min = $_PERMS_ACAO[ strtolower($acao) ];
		else 															$tmp_perm_min = 0;

		
		if( $this->logado )
		{				
		
			// verifica a permissão para este tipo de usuário
			$sql = $this->db->query("SELECT * FROM 
									   user_permissao as UP, modulo as M
									  WHERE 
									   M.descricao	    = ? AND
									   UP.fgk_tipo_user = ? AND
									   UP.fgk_modulo	= M.Id ;",
									  array( $modulo, $this->user->tipo_id ) );
									  
			// verifica se existe a permissao no banco
			// verifica de acordo com o definido para esta ação e módulo
			// a permissão mínima exigida
			// faz um AND entre a minima exigida e a permissao do user
			// se o resultado for igual a minima exigida, ele pode
			
			if( $sql->rowCount() > 0 )
			{
				$tmp = $sql->fetchObject();
				return( ($tmp->permissao & $tmp_perm_min) == $tmp_perm_min );
			}
			
			// se não tem a permissão pro user no banco
			// carrega a permissão default para o módulo
			// se não tem o módulo registrado
			// diz que o usuário tem permissão
			else
			{
				$sql = $this->db->query("SELECT * FROM modulo WHERE descricao = ?;", array( $modulo ) );
				
				if( $sql->rowCount() > 0 )
				{
				  $tmp = $sql->fetchObject();
				  return( ($tmp->permissao_default & $tmp_perm_min) == $tmp_perm_min );
				}
				else return true;
			}

		}	
		
		// se o usuário não está logado
		// verifica se o módulo está registrado no banco
		// caso não esteja, ele é tratado como módulo público
		// caso esteja registrado, verifica se essa ação especificamente é pública
		// ou seja, foi registrada em permissoes.php como 000 para o CRU
		else
		{
			$sql = $this->db->query("SELECT * FROM modulo WHERE descricao = ?;", array( $modulo ) );
			
			if( $sql->rowCount() <= 0 )								return true;
			else if($sql->rowCount() > 0 && $tmp_perm_min == 0)		return true;
			else													return false;
		}
		
	}
	
		
	// login através de um token
	public function login_with_token( $token="" )
	{
		if( $this->db->is_conected() )
		{
			$agora = date("Y-m-d H:i:s");
			$sql = $this->db->query("SELECT * FROM token, usuario WHERE
									token.bool_excluido = 0 AND
									token.token	  = ? AND
									token.expires > ? AND 
									token.fgk_user = usuario.Id;", array( $token, $agora ));
			
			if( $sql->rowCount() > 0)
			{	
				$tmp = $sql->fetchObject();
				$this->login( $tmp->usuario, $tmp->senha );
				return true;
			}
			else return false;
		}
		else return false;
	}
	
	// exclui um token
	public function delete_token( $token="" )
	{
		if( $this->db->is_conected() )
		{
			$agora = date("Y-m-d H:i:s");
			$sql   = $this->db->query("SELECT * FROM token WHERE bool_excluido = 0 AND token = ?;", array( $token ));
			
			if( $sql->rowCount() > 0)
			{	
				$tmp = $sql->fetchObject();
				$this->db->del( "token", "Id", $tmp->Id );
				return true;
			}
			else return false;
		}
		else return false;
	}
			
	

	/////////////////////////////////////////////// MODULO ENDEREÇOS
	
	public function salva_endereco($arr_dados)
	{	
		$this->save($arr_dados,"endereco");
	}
	
	public function delete_endereco($id,$arr_dados)
	{	
		$this->del($id,"endereco");
	}
		
	public function carrega_enderecos($arr_dados, $start=0, $limit=0)
	{
		return $this->view($arr_dados,"endereco", $start, $limit);
	}

	
	
	
	
	/////////////////////////////////////////////// MODULO PESSOA FÍSICA
	
	public function salva_pf($arr_dados)
	{	
		$this->save($arr_dados,"pessoa_fisica");
	}
	
	public function delete_pf($id,$arr_dados)
	{	
		$this->del($id,"pessoa_fisica");
	}
		
	public function carrega_pf($arr_dados, $start=0, $limit=0)
	{
		return $this->view($arr_dados,"pessoa_fisica", $start, $limit);
	}
	
}