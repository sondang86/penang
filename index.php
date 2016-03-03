<?php

ob_start();

include('./includes/functions/database.php');
include "./includes/configuration.php";
include "./includes/application_top.php";
include('./includes/functions/general.php');

//ob_end_clean();


// logout
if ($_GET['log']=="out"){
    setcookie("member_id","",time()-3600);
    session_destroy();
    unset ($_SESSION['member_id']);
    echo "<script language=\"javascript\">window.location='index.php?pages=login';</script>";
}
if($_SESSION['member_id']=="") {
    if ($_GET['pages']=="register"){
         include (DIR_WS_INCLUDES."pages/register.php");
    }else if ($_GET['pages']=="forget_password"){
         include (DIR_WS_INCLUDES."pages/forget_password.php");
    }else if ($_GET['id']!="" && $_GET['code']==""){
        
        $_SESSION['user_id'] = 1;
          build_page();
    }else{
         include (DIR_WS_INCLUDES."pages/login.php");
    }
}
else{
    build_page();
}


function build_page(){
    include DIR_WS_INCLUDES."header.php";
    $thispage="index";
    if ($_GET['pages']!="") {
        $thispage=$_GET['pages'];
    }
   
    if (file_exists(DIR_WS_INCLUDES."pages/".$thispage.".php")) {
        include (DIR_WS_INCLUDES."pages/".$thispage.".php");
    } else {
        echo "Page Not Found"; 
    }
    include DIR_WS_INCLUDES."footer.php";
}
ob_flush();
?>
