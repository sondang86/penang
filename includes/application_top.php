<?php

session_start();

    include('./includes/modules/phpmailer/PHPMailerAutoload.php');
    include('./includes/table.php');
	
    tep_db_connect();
    
    mysql_query("SET time_zone = '+8:00'");
    date_default_timezone_set("Asia/Kuala_Lumpur");
  
  
    

?>
