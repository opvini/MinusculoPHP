<?php


//**************************************
//
// MinusculoPHP Conexao CRUD
//
// Criado por: Vinícius Nunes Lage
// Criado em: 09/04/2015
// Modificado em: 19/10/2015
//
//**************************************


/////////////// PADRÕES DE DADOS DA CLASSE RGBForm
//
// Deve existir o campo Id nas tabelas (chave primária)
// Deve existir o campo bool_excluido (nunca exclui de verdade)
//
//
// PUBLIC:
//
// save( arr_dados, table )							// utiliza dados da classe MinForm
// del ( table, where_field, where_field_value ) 	// seta o bool_excluido
// view( arr_dados, table, start=0, limit=0 )		// select com AND
// 
//
/// HERDADO DA CLASSE MinCONEXAO
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


class MinConexaoCRUD extends MinConexao
{
	
	private $id_login = 1;
	
	
	/////////////////////////////////////////////// CRUD GENÉRICO: [CREATE, READ, UPDATE, DELETE]
	//
	// ARRAY_DADOS MANUAL:
	//
	//   $array_dados = array(
	//		array(
	//	   		"name"  => "id",     	// nome do campo, se usou id é UPDATE!
	//	   		"value" => 3,        	// valor quando é constante
	//		),
	//		array(
	//	   		"value"   => 3,        	// valor quando é constante
	//	   		"dbfield" => "user_id",	// nome do campo no banco, caso seja diferente de name
	//		),	
	//   );
	//
	//   ou é recebido da classe de formulário
	//   $arr_dados  = $form->getAllValuesDB();
	//	 utiliza sempre o campo name ou dbfield, caso tenha sido definido em form-fields
	//
	//
	
	public function save($table, $arr_dados)
	{
		//if( $this->is_conectado() && $this->logado )
		//{			
			$tmp_edit 	= 0;
			$SQL_struct = "";
			$arrPDO		= array();
			$fieldId	= 0;		
			$agora 		= date("Y-m-d H:i:s");
			
			// cria a SQL
			foreach($arr_dados as $index => $value ){
				
				// configura sempre o dbfield como campo de banco, mesmo passando name
				if( ! isset($arr_dados[$index]["dbfield"]) && isset($arr_dados[$index]["name"]) ){
					$arr_dados[$index]["dbfield"] = $arr_dados[$index]["name"];
				}
				// ignora o dado, falta o nome do campo
				else if( ! isset($arr_dados[$index]["dbfield"]) && ! isset($arr_dados[$index]["name"])) {
					$arr_dados[$index]["dbfield"] = 0;
				}
				
				if( $arr_dados[$index]["dbfield"] !== 0 && $index !== "id" && $arr_dados[$index]["dbfield"] !== "id" ) {
					$SQL_struct .= $arr_dados[$index]["dbfield"]." = ?, ";
					$arrPDO[]    = $arr_dados[$index]["value"];
				}
				else if( $index === "id" || $arr_dados[$index]["dbfield"] === "id" ){
					$fieldId = $arr_dados[$index]["value"];
				}
			}
			
			// INSERINDO OU ALTERANDO
			if( $fieldId !== 0 ){
				$tmp_sql  = "UPDATE $table";
				$tmp_sqi  = "WHERE Id = ?";
				$tmp_edit = 1;
			}
			else{
				$tmp_sql = "INSERT INTO $table";
				$tmp_sqi = "";
			}

			$arrPDO[]  = $this->id_login;
			$arrPDO[]  = $agora;
			
			$SQL = $tmp_sql." SET ".$SQL_struct."				
				fgk_user_registro = ?,
				data_registro	  = ? $tmp_sqi;
			";
			
			if( $tmp_edit ) $arrPDO[] = $fieldId;
			
			$tmp_id = $this->query($SQL, $arrPDO);
			
			if($tmp_id) return $this->pdo->lastInsertId();
			else		return 0;
			
		//}
		//else return false;			
	}
	
	
	////// DELETE
	//
	// bool_excluido = 1
	// apenas passar a tabela e o id
	// porém não apaga o registro em si
	//
	
	public function del( $table, $where_field, $where_field_value )
	{
		//if( $this->is_conectado() )
		//{			
			$SQL = "UPDATE $table SET bool_excluido = 1 WHERE $where_field = ?;";
			$tmp = $this->query( $SQL, array( $where_field_value ) );
			return $tmp;
		//}
		//else return false;			
	}
	
	
	//////// BUSCA COM ARRAY E CONECTIVO "AND"
	//
	// busca no banco baseado em um array do formulario
	// com os campos dbfield
	//
	//	$arr = array(		  
	//	  array(
	//	   "name"  	  => "nome",    // nome do campo no formulario
	//	   "value"    => "vini",  	// valor quando é constante
	//	  ),
	//	  array(
	//	   "value"    => "vini",  	// valor quando é constante
	//	   "dbfield"  => "casa",	// nome do campo no banco, caso seja diferente de name
	//	  )
	//	);
	//
	
	public function view($table, $arr_dados, $start=0, $limit=0)
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
				
				// configura sempre o dbfield como campo de banco, mesmo passando name
				if( ! isset($arr_dados[$index]["dbfield"]) && isset($arr_dados[$index]["name"]) ){
					$arr_dados[$index]["dbfield"] = $arr_dados[$index]["name"];
				}
				// ignora o dado, falta o nome do campo
				else if( ! isset($arr_dados[$index]["dbfield"]) && ! isset($arr_dados[$index]["name"])) {
					$arr_dados[$index]["dbfield"] = -1;
				}
				
				if( $arr_dados[$index]["dbfield"] !== (-1) ) {
				  $SQL_struct .= $arr_dados[$index]["dbfield"]." = ?";
				  $arrPDO[]	 = $arr_dados[$index]["value"];
				  if($i<$tot) $SQL_struct .= " AND "; 
				}
			}
			
			if($start!=0 || $limit!=0)
				$SQL = "SELECT * FROM $table WHERE ".$SQL_struct." LIMIT $start, $limit;";
			else
				$SQL = "SELECT * FROM $table WHERE ".$SQL_struct.";";
			
			$query = $this->query($SQL, $arrPDO);
			
			if($query){
				$arr_result["total"]   = $this->get_tot_rows();
				$arr_result["results"] = $this->get_array_result();
				return $arr_result;
			}
			else return false;
		//}
		//else return false;			
	}

}


?>