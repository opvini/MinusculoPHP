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
	public $error;
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
		
		$this->app->check_login();
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
		$tmp_per = $this->app->check_permissao( $this->controllerName, $this->acao );

		// executa o método caso tenha permissão e o método seja público
		if( !is_callable( array($this, $acao) ))					require_once PG_404;
		else if( is_callable( array($this, $acao) ) && $tmp_per )	$this->load_controller();
		else if( method_exists($this, 'sem_permissao') )			$this->sem_permissao( $this->controllerName, $this->acao);
		else														require_once PG_DANIED;

	}
	
	
	
	
	/////////// CARREGA CONTROLLER, VIEW, MODEL E ERRORS
	// 
	// são simples includes um pouco
	// mais inteligentes
	//
	

	// carrega o controlador
	public function load_controller()
	{
		$this->error = $this->load_errors( $this->controllerName );
		$this->{$this->acao}();
	}


	// carrega o arquivo com erros do controller, caso exista
	public function load_errors( $error_file_name = false )
	{
		if ( ! $error_file_name ) return;

		$error_file_name 	  =  strtolower( $error_file_name ).'-error';
		$error_file_name_path = ABSPATH . '/errors/' . $error_file_name . '.php';

		if ( file_exists( $error_file_name_path ) )
		{			
			require_once $error_file_name_path;
			
			$error_file_name = explode('/', $error_file_name);
			$error_file_name = end( $error_file_name );
			$error_file_name = preg_replace( '/[^a-zA-Z0-9]/is', '', $error_file_name );
			
			if ( class_exists( $error_file_name ) )
 			{
				return new $error_file_name();
			}
			else return new MinError;

		}
		else return new MinError;
		
	} // load_errors()
	
	
	
	// carrega uma view
	public function load_view( $view_name )
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
	public function load_model( $model_name = false )
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
	} // load_model()
	




	// carrega um plugin
	// deve existir no diretório plugins
	public function load_plugin( $plugin_name = false ) {
	
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
	} // load_plugin()
	

 
} // class MinController