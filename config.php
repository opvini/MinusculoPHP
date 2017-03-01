<?php
 
// Caminho para a raiz
// define( 'ABSPATH', dirname( __FILE__ ) );
define( 'ABSPATH', "/sites/MinusculoPHP.com/v0.2/" ); 
 
// Caminho para a página não encontrada
define( 'PG_404', ABSPATH . '/public/404.php' ); 
 
// Caminho para a página quando não tem permissão
define( 'PG_DANIED', ABSPATH . '/public/sem_permissao-view.php' );  
 
// inicio do LIMIT nas clausulas SELECT
define( 'LIMIT_INI', 0 ); 
 
// define o total de registros a mostrar nas clausulas SELECT
define( 'LIMIT_TOT', 10 );  
 
// Caminho para a pasta de uploads
define( 'UP_ABSPATH', ABSPATH . '/public/_uploads' );
 
// URL da home
define( 'HOME_URI', 'http://localhost:8080/sites/MinusculoPHP.com/v0.2/' );
 
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