<?php

ob_start();
include('./includes/functions/database.php');
include('./includes/functions/general.php');
include "./includes/configuration.php";
include "../common_en.php";
ob_end_clean();

// logout
if ($_GET['log']=="out"){
    setcookie("user_id","",time()-3600);
    session_destroy();
    echo "<script language=\"javascript\">window.location='index.php';</script>";
}
if($_COOKIE['user_id']=="") {
    include (DIR_WS_INCLUDES."pages/login.php");
}

else{
    build_page();
}


function build_page(){
    if ($_GET['pages']=="document") {
         include (DIR_WS_INCLUDES."pages/document.php");
     }elseif ($_GET['pages']=="report_export") {
         include (DIR_WS_INCLUDES."pages/report_export.php");
     }elseif ($_GET['pages']=="history") {
         include (DIR_WS_INCLUDES."pages/history.php");
     }elseif ($_GET['pages']=="remarks") {
         include (DIR_WS_INCLUDES."pages/remarks.php");
     }else{
    include DIR_WS_INCLUDES."header.php";
    $thispage="application";
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
}
?>
