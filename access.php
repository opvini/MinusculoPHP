<?php

//////////////////////////////
// Modificado em 20/01/2016	//
//////////////////////////////

//////////////////////////////////////////////// PERMISSÕES PARA MÓDULOS E AÇÕES
//
// LEMBRANDO: todas as páginas do sistema tem o padrão de acesso
// - endereço/MODULO/ACÃO
//
// Cada usuário tem as permissões definidas no banco de dados
// Na tabela permissoes
// Todos os módulos devem estar registrados no banco
// Na tabela modulos
// Todas as ações tem sua permissão mínima exigida
// Registrada logo abaixo em $_PERMS ou $_PERMS_ACAO
//


////////////////////////////////////////////////
//
// Definições para as permissões
// Não alterar aqui
// CRU (Create, Read, Update/Delete)
// 101 = 5: pode criar e alterar/deletar(exemplo)

define( 'PERM_CREATE', 	4); // 100
define( 'PERM_READ', 	2); // 010
define( 'PERM_UPDATE', 	1); // 001
define( 'PERM_PUBLIC', 	0); // 000

////////////////////////////////////////////////



/////////////////////////////////////////////////////// PERMISSÕES MÍNIMAS DE CADA AÇÃO E MÓDULO
//
// Para definir a permissão de uma ação para um determinado módulo: usa $_PERMS 
// E o nome para definir tem o padrão: MODULO/AÇÃO
//
// Se deseja criar uma permissão para uma ação determinada para TODOS OS MÓDULOS: usa-se $_PERMS_ACAO
// E o nome para definir tem o padrão: NOME_AÇÃO
//
// A prioridade é para permissões em $_PERMS (definidas especificamente para um módulo ou modelo)
// Depois para $_PERMS_ACAO (ação genérica)
//
// Exemplo:
// - pode criar uma permissão para a ação CREATE para todos os módulos ($_PERMS_ACAO)
// - ou apenas para o módulo user, ou seja, user/create ($_PERMS)
// 
// as permissões são similares ao LINUX
// porém em vez de RWX é usado o CR(UD)
// primeiro bit CREATE, segundo READ, terceiro UPDATE OR DELETE
// exemplo:
//  7 = 111 = pode inserir, ler e alterar
//  4 = 100 = só pode inserir
//
//
// IMPORTANTE: Os nomes devem conter apenas letras minusculas
//


// Permissões genéricas (definidas para qualquer módulo)
$_PERMS_ACAO = array(
	"create" 	=> PERM_CREATE,
	"update" 	=> PERM_UPDATE,
	"del" 		=> PERM_UPDATE,
	"view" 		=> PERM_READ,
	"list" 		=> PERM_PUBLIC
);


// Permissões exclusivas pertencenter a algum módulo
$_PERMS = array(
	"exemplo/index"		=> PERM_READ,
	"exemplo/create"	=> PERM_CREATE,
	"exemplo/update" 	=> PERM_UPDATE,
	"exemplo/del" 		=> PERM_UPDATE,
	"exemplo/view" 		=> PERM_READ,
	"exemplo/list" 		=> PERM_PUBLIC
);


?>