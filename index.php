<?php

//***************************************************************
//
// Inicializa a aplicação
// Verifica a requisição: controlador/método/parâmetros
//
// Nos preocupamos em criar sempre o motor e a view
// O resto o framework faz, como o controle de permissões
//
// O arquivo de configuração tem as definições das constantes
// O arquivo permissoes.php tem as permissões mínimas exigidas
//
/////////////////////////////////////////////////////////////////

// declaração de UTF-8
header('Content-type: text/html; charset=utf-8');

// arquivo de configuração
require_once 'config.php';

// Inicia a sessão
session_start();

// Funções globais
require_once ABSPATH . '/functions/global-functions.php';

$MinApp = new MinusculoPHP();

?>