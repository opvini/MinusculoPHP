<?php

 
//**************************************************************************
//
// MinusculoPHP Conexao
//
// Criado por: Vinícius Nunes Lage
// Criado em: 09/04/2015
// Modificado em: 19/11/2015
// 
/////////////////////////////////////////////////////////////////////////////
//
//
//
// PUBLIC:
//
// query(): ver PDO
// - $query->rowCount()		total de resultados
// - $query->fetch()		fetch de uma linha
// - $query->fetchObject()	fetch de uma linha, retorna um objeto
// - $query->fetchAll()		fetch de todas as linhas
//
// insert( table, arr_cols )
// update( table, where_field, where_field_value, new_values )
// select( table, arr_dados, start=0, limit=0 )
// delete( table, where_field, where_field_value )
// json	 ( query )
//
// pagination( sql_pdo_txt, data_array = null, page=1, tot=10 )
// is_connected()
//

 
 
class MinConexao
{
	// DB properties
	protected $host      = 'localhost', // Host da base de dados 
	          $db_name   = 'rgbsuite',  // Nome do banco de dados
	          $pass	     = '',          // Senha do usuário da base de dados
	          $user      = 'root',      // Usuário da base de dados
	          $charset   = 'utf8',      // Charset da base de dados
	          $pdo       = null,        // Nossa conexão com o BD
	          $error     = null,        // Configura o erro
	          $last_id   = null,        // Último ID inserido
			  $conected  = 0;			// sinaliza se está conctado


	// pode ser instanciado passando estes parâmetros
	// para conectar em um bd diferente
	public function __construct(
		$host     = null,
		$db_name  = null,
		$pass 	  = null,
		$user     = null,
		$charset  = null
	) {
	
		// Configura as propriedades
		$this->host     = ( ( $host    == null ) && defined( 'HOSTNAME'    )) ? HOSTNAME    : $host;
		$this->db_name  = ( ( $db_name == null ) && defined( 'DB_NAME' 	   )) ? DB_NAME     : $db_name;
		$this->pass 	= ( ( $pass    == null ) && defined( 'DB_PASSWORD' )) ? DB_PASSWORD : $pass;
		$this->user     = ( ( $user    == null ) && defined( 'DB_USER' 	   )) ? DB_USER     : $user;
		$this->charset  = ( ( $charset == null ) && defined( 'DB_CHARSET'  )) ? DB_CHARSET  : $charset;
	
		// Conecta
		$this->connect();
				
	} // __construct()
		


	final protected function connect()
	{
		$pdo_details  = "mysql:host={$this->host};";
		$pdo_details .= "dbname={$this->db_name};";
		$pdo_details .= "charset={$this->charset};";
		 
		try 
		{
			$this->pdo = new PDO($pdo_details, $this->user, $this->pass);
			
			/*
			if ( $this->debug === true )
			{
				$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			}
			*/
			
			$this->conected = 1;
			
			unset( $this->host     );
			unset( $this->db_name  );
			unset( $this->pass	   );
			unset( $this->user     );
			unset( $this->charset  );
		
		} catch (PDOException $e) {	
			$this->conected = 0;
			die();
		}
		
	} // connect()
	

	public function desconect()
	{
		$this->conected = 0;
	}




	/////////////////////////////////////////////// QUERY - CONSULTAS DIRETAS
	//
	//
	public function query( $stmt, $data_array = null )
	{
		// Prepara e executa
		$query      = $this->pdo->prepare( $stmt );
		$check_exec = @$query->execute( $data_array );
		
		// Verifica se a consulta aconteceu
		if ( $check_exec ) {
			return $query;
		} else {
			// Configura o erro
			$error       = $query->errorInfo();
			$this->error = $error[2];
			return false;
		}
	}
	
	

	/////////////////////////////////////////////// INSERE
	//
	//	$db->insert(
	//		'tabela', 
	//		
	//		// Insere uma linha
	//		array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor'),
	//		
	//		// Insere outra linha
	//		array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor'),
	//		
	//		// Insere outra linha
	//		array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor')
	//	);
	//
	
	public function insert( $table /* $cols[] */ )
	{
		$cols 		   = array();
		$place_holders = '(';
		$values 	   = array();
		$j 	  		   = 1;
		$data 		   = func_get_args();
		
		// é preciso enviar pelo menos um argumento
		if ( ! isset( $data[1] ) || ! is_array( $data[1] ) )
		{
			return;
		}
		
		// Faz um laço nos argumentos
		for ( $i = 1; $i < count( $data ); $i++ ) {
		
			// Obtém as chaves como colunas e valores como valores
			foreach ( $data[$i] as $col => $val ) {
			
				// A primeira volta do laço configura as colunas
				if ( $i === 1 ){
					$cols[] = "`$col`";
				}
				
				if ( $j <> $i ) {
					// Configura os divisores
					$place_holders .= '), (';
				}
				
				// Configura os place holders do PDO
				$place_holders .= '?, ';
				
				// Configura os valores que vamos enviar
				$values[] = $val;
				
				$j = $i;
			}
			
			// Remove os caracteres extra dos place holders
			$place_holders = substr( $place_holders, 0, strlen( $place_holders ) - 2 );
		}
		
		// Separa as colunas por vírgula
		$cols = implode(', ', $cols);
		
		// Cria a declaração para enviar ao PDO
		$stmt = "INSERT INTO `$table` ( $cols ) VALUES $place_holders) ";
		
		// Insere os valores
		$insert = $this->query( $stmt, $values );
		
		// Verifica se a consulta foi realizada com sucesso
		if ( $insert ) {
			
			// Verifica se temos o último ID enviado
			if ( method_exists( $this->pdo, 'lastInsertId' ) && $this->pdo->lastInsertId() )
			{
				// Configura o último ID
				$this->last_id = $this->pdo->lastInsertId();
			}
			
			// Retorna a consulta
			return $insert;
		}
		return;
	} // insert()
	


	/////////////////////////////////////////////// UPDATE
	//
	//	$db->update(
	//		'tabela', 'campo_where', 'valor_where',
	//		
	//		// Atualiza a linha
	//		array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor')
	//	);
	//
	
	public function update( $table, $where_field, $where_field_value, $values ) {
		// Você tem que enviar todos os parâmetros
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}
		
		$stmt  = " UPDATE `$table` SET ";
		$set   = array();
		$where = " WHERE `$where_field` = ? ";
		
		// Você precisa enviar um array com valores
		if ( ! is_array( $values ) ) {
			return;
		}
		
		// Configura as colunas a atualizar
		foreach ( $values as $column => $value ) {
			$set[] = " `$column` = ?";
		}
		
		// Separa as colunas por vírgula
		$set = implode(', ', $set);
		
		// Concatena a declaração
		$stmt .= $set . $where;
		
		// Configura o valor do campo que vamos buscar
		$values[] = $where_field_value;
		
		// Garante apenas números nas chaves do array
		$values = array_values($values);
				
		// Atualiza
		$update = $this->query( $stmt, $values );
		
		// Verifica se a consulta está OK
		if ( $update ) {
			// Retorna a consulta
			return $update;
		}
		
		return;
	} // update()
 


	/////////////////////////////////////////////// DELETE
	//
	// $db->delete( 'tabela', 'campo_where', 'valor_where' );
	//
	
	public function delete( $table, $where_field, $where_field_value ) {
		// Você precisa enviar todos os parâmetros
		if ( empty($table) || empty($where_field) || empty($where_field_value)  ) {
			return;
		}
		
		// Inicia a declaração
		$stmt = " DELETE FROM `$table` ";
 
		// Configura a declaração WHERE campo=valor
		$where = " WHERE `$where_field` = ? ";
		
		// Concatena tudo
		$stmt .= $where;
		
		// O valor que vamos buscar para apagar
		$values = array( $where_field_value );
 
		// Apaga
		$delete = $this->query( $stmt, $values );
		
		// Verifica se a consulta está OK
		if ( $delete ) {
			// Retorna a consulta
			return $delete;
		}
		
		// The end :)
		return;
	} // delete()
	
	
	
	//////// SELECT COM ARRAY E CONECTIVO "AND"
	//
	// busca no banco baseado em um array do formulario
	// com os campos dbfield
	//
	//	$db->select(
	//		'tabela', 
	//		
	//		// array com campos e valores para a busca
	//		array('campo_tabela' => 'valor', 'outro_campo'  => 'outro_valor', etc)
	//		
	//	);
	//
	
	public function select($table, $arr_dados, $start=0, $limit=0)
	{

		// definido no arquivo config
		if( defined('LIMIT_INI') ) $start = LIMIT_INI;
		if( defined('LIMIT_TOT') ) $limit = LIMIT_TOT;
		
		//if( $this->is_conectado() && $this->logado )
		//{			
			$SQL_struct = "";
			$arr_result = array();
			$arrPDO		= array();
			$tot		= count($arr_dados);
			$i          = 0;
			
			foreach($arr_dados as $index => $value ){
				$i++;
				$SQL_struct .= $index." = ?";
				$arrPDO[]	 = $value;
				if($i<$tot) $SQL_struct .= " AND "; 
			}
			
			if($start!=0 || $limit!=0)
				$SQL = "SELECT * FROM $table WHERE ".$SQL_struct." LIMIT $start, $limit;";
			else
				$SQL = "SELECT * FROM $table WHERE ".$SQL_struct.";";
						
			$query = $this->query($SQL, $arrPDO);
			
			if($query){
				return $query;
			}
			else return false;
		//}
		//else return false;			
	}
	
	
	
	/////////////////////////////////////// JSON 
	//
	// retorna o resultado completo de uma query
	// em json, no seguinte padrão:
	//
	// { 
	//		total: 2,
	//		results: 
	//		[
	//			{coluna1: resultado1, coluna2: reultado2} ,
	//			{coluna1: resultado1, coluna2: resultado2} 
	//		]
	//	}
	//
	
	public function json( $query ){
		$arrResult = array();
				
		// com paginação
		if( isset($query->totPages) && isset($query->totResults) && isset($query->currentPage) ){
			$arrResult[]  = array( 
									"total" 	   => $query->totResults,
									"pages" 	   => $query->totPages,
									"current_page" => $query->currentPage,
									"showing" 	   => $query->showing,
									"results"	   => $query->fetchAll() 
								);
		}
		
		// sem paginação, normal
		else{
			$arrResult[]  = array( "total" => $query->rowCount(), "results" => $query->fetchAll() );
		}
		
		return json_encode( $arrResult );
	}
	
	
	//////////////////////////////////////// GET and SETTERS
	
	public function is_conected(){
		return $this->conected;
	}
		
	
	//////////////////////////////////////// MÓDULO PAGINACAO
	//
	// similar a query()
	// porem é passado como parâmetro os limites:
	// - página inicial (iniciando com 1)
	// - total de resultados
	//
	// Utilize:
	// $obj->totResults   	para o total de resultados
	// $obj->totPages     	para o total de paginas
	// $obj->currentPage 	para a página atual
	// $obj->showing		para o total na pagina atual
	// 
	// retorna os resultados daquela página
	//
	//

	public function pagination( $stmt, $data_array = null, $page=1, $tot=10 )
	{
		if($page <= 0) $page = 1;
		
		// antes da paginação
		// busca normal
		$stmt  = str_replace(";","",$stmt);
		$query = $this->query( $stmt, $data_array );
			
		$totResults = $query->rowCount();
		
		if($tot > $totResults){
			$tot = $totResults;
		}
		
		$totPages = ceil($totResults/$tot);
		
		if( $page > $totPages ){
			$page = $totPages;
		}
		
		$page_sql = $page * $tot - $tot;
		
		// com a paginação
		$stmt  = $stmt . " LIMIT $page_sql, $tot;";
		$query = $this->query( $stmt, $data_array );
		
		$query->totPages    = $totPages;
		$query->totResults  = $totResults;
		$query->currentPage = $page;
		$query->showing   	= $query->rowCount();
		return $query;
	}
	
	
	
} // class Conexao