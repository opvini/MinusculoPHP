<?php

//********************************************************
//
// MinusculoPHP
// AppMinusculo - Carrega os Controllers
//
// Criado por: Vinícius Nunes Lage
// Criado em: 09/04/2015
// Modificado em: 19/10/2015
//
//////////////////////////////////////////////////////////


class MinusculoPHP
{

	 //////////////////////////////////////////////////
	 //
	 // A pagina tem o padrao
	 // controlador/acao/parametro1/parametro2/...
	 // toda ação é executada pelo método execAction()
	 //
	 //////////////////////////////////////////////////

	private $controlador;
	private $controllerName;
	private $acao;
	private $parametros;

	
	public function __construct () {
		
		// recebe e configura os dados recebidos na URL
		// controlador é criado aqui
		$this->get_url_data();
		
		// se o controlador não existe na URL, chama o controller HomeController e executa o metodo index
		if ( ! $this->controlador ) 
		{
			require_once ABSPATH . '/controllers/home-controller.php';
			$this->controlador = new HomeController('home');
			$this->controlador->index();
			return;
		}
		
		// se existe o controlador na URL porém o arquivo não existe, exibe 404
		if ( ! file_exists( ABSPATH . '/controllers/' . $this->controlador . '.php' ) )
		{
			require_once PG_404;
			return;
		}
		
		
		// se existe o controlador na URL, e o arquivo existe, carrega
		// o arquivo tem padrao nome-controller.php
		require_once ABSPATH . '/controllers/' . $this->controlador . '.php';
		
		// retira qualquer simbolo sem ser letras do nome do controlador
		// para poder chamar a classe
		$this->controlador = preg_replace( '/[^a-zA-Z]/i', '', $this->controlador );
		
		// se a classe não existe no controlador, exibe 404
		if ( ! class_exists( $this->controlador ) )
		{
			require_once PG_404;
			return;
		}
		
		// como existe o controlador e a classe, instancia passando os parametros
		// cria o controlador, que vai chamar o model e a view correspondente
		// passa como parâmetro o nome do controlador
		$this->controlador = new $this->controlador( $this->controllerName );
 
 
 		// se foi passado uma ação na URL de um métodoque não existe
		// sinaliza como página não encontrada
		if ( $this->acao && ! method_exists( $this->controlador, $this->acao ) )
		{
			require_once PG_404;
			return;
		}
		
		
		// caso não tenha sido passado uma ação
		// executa o método index caso ele exista
		if( !$this->acao && method_exists( $this->controlador, 'index' ) )
		{
			$this->acao = 'index';
			$this->controlador->execAction( 'index', $this->parametros );
			return;
		}
	
		 
 		// se existe o método passado pela URL (ação), executa a ação passando os parâmetros
		// através do método execAction() 
		if ( $this->acao && method_exists( $this->controlador, $this->acao ) ) 
		{
			$this->controlador->execAction( $this->acao, $this->parametros );
			return;
		} 
		
		
		// caso nenhuma das condições acima tenha sido aprovada
		require_once PG_404;
		return;
		
	} // __construct()
	
	

	// recebe e trata os dados recebidos na URL
	// guarda em controlador, em ação e em parametros
	public function get_url_data () {
		
		if ( isset( $_GET['exe'] ) ) 
		{
			// limpa e filtra dados
			$exe = $_GET['exe'];
            $exe = rtrim($exe, '/');
            $exe = filter_var($exe, FILTER_SANITIZE_URL);
			
			// armazena já cortado
			$exe = explode('/', $exe);
			$this->controlador    = chk_array( $exe, 0 );
			$this->controllerName = $this->controlador;
			$this->controlador 	 .= '-controller';
			$this->acao           = chk_array( $exe, 1 );
			
			// se existe os parâmetros, armazena eles
			if ( chk_array( $exe, 2 ) )
			{
				unset( $exe[0] );
				unset( $exe[1] );
				$this->parametros = array_values( $exe );
			}
			
		}
	
	} // get_url_data()
	
} // class RGBSuite	