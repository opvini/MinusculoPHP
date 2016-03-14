<?php

//***************************************************************************
//
// MinusculoPHP Controller
// MinController - Todos os Controllers deverão estender dessa essa classe
//
// Criado por: Vinícius Nunes Lage
// Criado em: 09/04/2015
// Modificado em: 01/02/2016
//
/////////////////////////////////////////////////////////////////////////////

 
class MinController
{
 
	public $db;
	public $app;
	public $talk;
 	public $phpass;
	
	
	// um controlador é instanciado com ação e parâmetros
	// controller/acao/param1/param2...
	
	protected $controllerName;
	protected $acao;
 	protected $parametros = array();
	
	
	////////////////////////////////////////////////////////////////////////// AQUI DEVEM INICIAR TODAS AS CLASSES
	//
	// abre conexão com o banco de dados
	// instancia os objetos necessários
	//
	
	public function __construct( $controllerName )
	{
		$this->controllerName = $controllerName;
		
		$this->db  = new MinConexaoCRUD();
		$this->app = new MinApp( $this->db );
		
		$this->app->checkLogin();
	}
	
	
	
	// toda ação é executada através deste método
	// de forma a poder executar testes para todos antecipadamente
	// como por exemplo verificar as permissões	
	
	public function execAction( $acao, $parametros )
	{		
		$this->acao		  = $acao;
		$this->parametros = $parametros;

		// verifica se o usuário tem permissão
		// para a ação deste módulo que está tentando executar
		// configurado em permissoes.php e no banco
		$tmp_per = $this->app->checkPermissao( $this->controllerName, $this->acao );

		// executa o método caso tenha permissão e o método seja público
		if( !is_callable( array($this, $acao) ))					require_once PG_404;
		else if( is_callable( array($this, $acao) ) && $tmp_per )	$this->loadController();
		else if( method_exists($this, 'semPermissao') )				$this->loadSemPermissao();
		else														require_once PG_DANIED;
		
		// finaliza a aplicação
		$this->finalizar();
		return true;
	}
	
	private function finalizar()
	{
		$this->app->finalizar();
		$this->db->desconect();
	}
	
	
	
	/////////// CARREGA CONTROLLER, VIEW, MODEL E talkS
	// 
	// são simples includes um pouco
	// mais inteligentes
	//
	
	
	// carrega o método próprio de sem permissão
	public function loadSemPermissao()
	{
		$this->talk = $this->loadTalks( $this->controllerName );
		$this->semPermissao( $this->controllerName, $this->acao);
	}

	// carrega o controlador
	public function loadController()
	{
		$this->talk = $this->loadTalks( $this->controllerName );
		$this->{$this->acao}();
	}


	// carrega o arquivo com erros do controller, caso exista
	public function loadTalks( $talk_file_name = false )
	{
		if ( ! $talk_file_name ) return;

		$talk_file_name 	  =  strtolower( $talk_file_name ).'-talk';
		$talk_file_name_path = ABSPATH . '/talks/' . $talk_file_name . '.php';

		if ( file_exists( $talk_file_name_path ) )
		{			
			require_once $talk_file_name_path;
			
			$talk_file_name = explode('/', $talk_file_name);
			$talk_file_name = end( $talk_file_name );
			$talk_file_name = preg_replace( '/[^a-zA-Z0-9]/is', '', $talk_file_name );
			
			if ( class_exists( $talk_file_name ) )
 			{
				return new $talk_file_name();
			}
			else return new MinTalk;

		}
		else return new MinTalk;
		
	} // loadTalks()
	
	
	
	// carrega uma view
	public function loadView( $view_name )
	{
		if ( ! $view_name ) return;

		$view_name =  strtolower( $view_name ).'-view';
		$view_path = ABSPATH . '/views/' . $view_name . '.php';
				
		if ( file_exists( $view_path ) )
		{			
			require_once $view_path;
			return;
		}
	}
	
	
	// carrega o model correspondente passado pela URL com um simples include
	// deve existir no diretório models
	// é um include mais inteligente e já instancia a classe do modelo
	public function loadModel( $model_name = false )
	{
		if ( ! $model_name ) return;

		$model_name =  strtolower( $model_name ).'-model';
		$model_path = ABSPATH . '/models/' . $model_name . '.php';

		if ( file_exists( $model_path ) )
		{			
			require_once $model_path;
			
			$model_name = explode('/', $model_name);
			$model_name = end( $model_name );
			$model_name = preg_replace( '/[^a-zA-Z0-9]/is', '', $model_name );
			
			if ( class_exists( $model_name ) )
 			{
				return new $model_name( $this->db, $this->app );
			}
			
			return;
		}
	} // loadModel()
	




	// carrega um plugin
	// deve existir no diretório plugins
	public function loadPlugin( $plugin_name = false ) {
	
		if ( ! $plugin_name ) return;

		$plugin_name = strtolower( $plugin_name );
		$plugin_path = ABSPATH . '/plugins/'.$plugin_name.'/'.$plugin_name.'.php';
			
		if ( file_exists( $plugin_path ) )
		{		
			require_once $plugin_path;
			
			$plugin_name = explode('/', $plugin_name);
			$plugin_name = end( $plugin_name );
			$plugin_name = preg_replace( '/[^a-zA-Z0-9]/is', '', $plugin_name );
			
			if ( class_exists( $plugin_name ) )
 			{
				return new $plugin_name();
			}
			
			return;
		}
	} // loadPlugin()
	

 
} // class MinController