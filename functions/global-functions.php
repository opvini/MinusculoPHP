<?php

//********************************************************
//
// Funções Globais Úteis
//
//////////////////////////////////////////////////////////


// verifica se existe um valor em um array
function chk_array ( $array, $key ) 
{
	if ( isset( $array[ $key ] ) && ! empty( $array[ $key ] ) )
	{
		return $array[ $key ];
	}

	return null;
} 
 
 
////////////////////////////////////////////////////////////
// A FUNÇÃO MAIS IMPORTANTE
//
// carrega todas as classes automaticamente
// do diretório includes/classes/class-<NomeClasse>
//
// Basta instanciar um objeto de uma classe
// que ela buscará no diretório a classe
//
// __autoload() é chamada sempre que uma instancia é criada
//
////////////////////////////////////////////////////////////


function __autoload($class_name)
{
	$file = ABSPATH . '/class/class-' . $class_name . '.php';

	if ( ! file_exists( $file ) ) {
		require_once PG_404;
		return;
	}
	
    require_once $file;
}


// recebe a data em formato americano
// devolve no formato brasileiro

function data_brasil($str)
{
	if(trim($str)!="")
		return substr($str, 8, 2)."/".substr($str, 5, 2)."/".substr($str, 0, 4);
}


// retorna o horário em formaro hh:mm
function time_brasil($str)
{
	return substr($str, 0, 2).":".substr($str, 3, 2);
}



// identar JSON
// para exibir na tela para debug

function json_ident( $json ) 
{
	$result = '';
	$pos = 0;
	$strLen = strlen($json);
	$indentStr = "\t";
	$newLine = "\n";
	$prevChar = '';
	$outOfQuotes = true;
	for ($i=0; $i<=$strLen; $i++):
		$char = substr($json, $i, 1);
		if ($char == '"' && $prevChar != '\\'):
			$outOfQuotes = !$outOfQuotes;
		elseif(($char == '}' || $char == ']') && $outOfQuotes):
			$result .= $newLine;
			$pos --;
			for ($j=0; $j<$pos; $j++):
				$result .= $indentStr;
			endfor;
		endif;
		$result .= $char;
		if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes):
			$result .= $newLine;
			if ($char == '{' || $char == '['):
				$pos ++;
			endif;
			
			for ($j = 0; $j < $pos; $j++):
				$result .= $indentStr;
			endfor;
		endif;
		$prevChar = $char;
	endfor;
	return $result;
}


// para mostrar na tela

function json_show_ident( $json )
{
	return "<pre>\n" . json_ident( $json ) . "\n</pre>";
}


// exporta um array para um arqvuivo em excel
function exportExcel($arr, $fil = "planilha.xls")
{
	$html = "<table><tr>";
			
	// insere os titulos
	foreach( $arr[0] as $id => $value)
		$html .= "<td>".$id."</td>";
	
	$html .= "</tr>";
			
	// varre as linhas
	foreach( $arr as $id => $val)
	{
		$html .= "<tr>";
		
		// varre as colunas de cada linha
		foreach( $arr[$id] as $id => $val)
			$html .= "<td>".$val."</td>";
		
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	// Configurações header para forçar o download
	header ("Expires: Mon, 04 Apr 2020 05:00:00 GMT");
	header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
	header ("Cache-Control: no-cache, must-revalidate");
	header ("Pragma: no-cache");
	header ("Content-type: application/x-msexcel");
	header ("Content-Disposition: attachment; filename=\"{$fil}\"" );
	header ("Content-Description: PHP Generated Data" );		
  
	echo $html;
	
} // exportExcel()



?>