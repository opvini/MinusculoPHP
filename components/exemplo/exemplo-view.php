<?php

  echo isset($_GET['q'])?$_GET['q']:"";
  
  echo "<pre>";
  print_r( $this->modelo );  
  print_r( $this->login->user );
  echo "</pre>";

?>