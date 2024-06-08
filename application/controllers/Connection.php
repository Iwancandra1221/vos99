<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Connection extends CI_Controller   {

  function __construct()
  {
    parent::__construct(); 
    $this->version = "1.0.0";
  }

  public function index()
  {
    $serverName = "192.168.1.23"; 
    $connectionOptions = array( 
        "Database" => "Vos99", 
        "Uid" => "sa", 
        "PWD" => "Ican123" 
    );  
    $conn = sqlsrv_connect($serverName, $connectionOptions); 
    //var_dump($conn);
    if($conn){
     echo "Connected";
    }
    else{
      echo '<pre>';
      echo print_r( sqlsrv_errors(), true);
      echo '</pre>'; 
    }
  }
 
}
