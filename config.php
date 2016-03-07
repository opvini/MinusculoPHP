<?php
 
// Caminho para a raiz
// define( 'ABSPATH', dirname( __FILE__ ) );
define( 'ABSPATH', "/sites/MinusculoPHP.com/" ); 
 
// Caminho para a página não encontrada
define( 'PG_404', ABSPATH . '/views/404.php' ); 
 
// Caminho para a página quando não tem permissão
define( 'PG_DANIED', ABSPATH . '/views/sem_permissao-view.php' );  
 
// inicio do LIMIT nas clausulas SELECT
define( 'LIMIT_INI', 0 ); 
 
// define o total de registros a mostrar nas clausulas SELECT
define( 'LIMIT_TOT', 10 );  
 
// Caminho para a pasta de uploads
define( 'UP_ABSPATH', ABSPATH . '/views/_uploads' );
 
// URL da home
define( 'HOME_URI', 'http://localhost:8080/sites/MinusculoPHP.com/' );
 
 // Língua usada
define( 'LANGUAGE', 'pt-br' );
 
// Nome do host da base de dados
define( 'HOSTNAME', 'localhost' );
 
// Nome do DB
define( 'DB_NAME', 'mvc' );
 
// Usuário do DB
define( 'DB_USER', 'root' );
 
// Senha do DB
define( 'DB_PASSWORD', '' );
 
// Charset da conexão PDO
define( 'DB_CHARSET', 'utf8' );
 
// Se você estiver desenvolvendo, modifique o valor para true
define( 'DEBUG', false );

// Define o tempo máximo de inatividade para login
define( 'LOGIN_TIMEOUT', 1200 );

 
?>