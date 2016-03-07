<?php

// os parâmetros e a ação estão definidos em
// $this->acao
// $this->parametros
//

class ExemploController extends MinController
{
	public $modelo;
	
	
	// método executado em caso de não ter passado uma ação
    public function index() 
	{
		$this->title = 'MinusculoPHP Exemplo - index';
	
		// Carrega e instancia o modelo
		$this->modelo = $this->load_model('exemplo');
		
		// Carrega o arquivo do view 
        $this->load_view('exemplo-view.php');
		
    }


	// chamado em "exemplo/uai"
	
    public function uai() 
	{
		$this->title = 'MinusculoPHP Exemplo Metodo Uai';
	
		// Carrega o modelo
		$this->modelo = $this->load_model('exemplo');
		$this->modelo->recebe_form();		

		//$this->load_view('exemplo');

		$this->error->show_error(3);
    }
	
	
	// método caso o usuário não tenha permissão para a ação desejada
	public function sem_permissao( $modulo, $acao )
	{
		echo '{"success": false, "msg":"sem permissao"}';		
	}
	
	
	// exemplo do plugin MinMakeCRUD
	public function makeCrud(){
		
		$CRUD = $this->load_plugin('MinMakeCrud');
		
		
		// array com campos a serem exibidos na tabela de visualizacao
		$arr_exibir = array("id", "nome");
		
		
		// campos que devem conter no formulario
		// especificando quais estaram na mesma linha
		// pode se especificar o label do campo
		// quando não for o mesmo que o nome do campo no banco
		//
		// PADRÃO:
		// array( db_field_name => label_name )
		
		$arr_fields = array(
			array("nome"),
			array("endereco"),
			array("cpf", "rg"),
			array("numero_endereco" => "numero")
		);
				
		
		$CRUD->create( "pessoa_fisica", $arr_exibir, $arr_fields );
		$CRUD->renderTo( "div-crud" );
				
	}
	
	
} // class ExemploController