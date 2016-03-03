<?php            

    if($_COOKIE["user_id"]!="" && $_SESSION["user_id"]!=""){
        $quser = tep_query("SELECT * FROM ".USERS." WHERE user_status='1' AND MD5(CONCAT('tmstms', user_id))='".$_COOKIE["user_id"]."'");
        if(tep_num_rows($quser)>0){
            $user = tep_fetch_object($quser);
            $_SESSION["user_id"] = $user->user_id;
            $_SESSION["user_name"] = $user->user_name;
            $_SESSION["user_email"] = $user->user_email;
            $_SESSION["user_group"] = $user->user_group;
       
        }
    }
    

    switch ($_GET['pages']){
        case "": 
        case "index":                                       $page_title = "Application";                                              break;
        case "users":                                       $page_title = "Users";                   break;
        case "application":                                  $page_title = "Application";                              break;
        case "deleted":                                  $page_title = "Delete Folder";                              break;
        

    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?=SITENAME;?> - <?php echo $page_title; ?></title>
        <link rel="shortcut icon" href="./images/icon.png" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            
        <script type="text/javascript" src="includes/modules/jquery.js"></script>
        <script type="text/javascript" src="includes/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="includes/modules/j_tooltip/tipr.min.js"></script>
        <link rel="stylesheet" type="text/css" href="includes/modules/j_tooltip/tipr.css" />

        <script type="text/javascript" src="includes/modules/jquery-ui.js"></script>
        <script type="text/javascript" src="includes/modules/j_datepicker/jquery.datePicker.js"></script>
        <script type="text/javascript" src="includes/modules/j_datepicker/jquery-ui-timepicker-addon.js"></script>
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/ui.core.css" />
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/ui.datepicker.css" />
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/ui.theme.css" />
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/jquery.ui.datepicker.css" />
        
        <script type="text/javascript" src="includes/ckeditor/ckeditor.js"></script>
        <!-- main -->
        <link rel="stylesheet" type="text/css" href="includes/css/style.css">
        <link rel="stylesheet" type="text/css" href="includes/css/menu_2013.css">
        <!--[if IE 7]><link rel="stylesheet" href="includes/css/style_ie7.css" type="text/css"><![endif]-->
        <script type="text/javascript" src="includes/modules/thejs.js"></script>
         <link rel="stylesheet" type="text/css" href="includes/modules/j_sorting/style.css" />
        <script type="text/javascript" src="includes/modules/j_sorting/jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="includes/modules/j_sorting/jquery.tablesorter.js"></script>
       
    </head>
<body>

<div id="nav_menu">
    <div class="nav_menu_wrapper">
        <ul id="ul_menu">
            
            <li><a href="index.php?pages=application" class="mainLink">Application</a></li>
            <li><a href="index.php?pages=deleted" class="mainLink">Delete Folder</a></li>
            <li><a href="index.php?pages=log_report" class="mainLink">Log Report</a></li>
            <li><a href="index.php?pages=users" class="mainLink">Users</a></li>
          
            <li><a href="index.php?log=out" class="mainLink">Logout</a></li>
        </ul>
    </div>
</div>
<div class="cls"></div>

<div id="container">
    <div id="content">