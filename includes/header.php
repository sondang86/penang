<?php            

       if($_SESSION["member_id"]!=""){
        $quser = tep_query("SELECT * FROM ".MEMBER." WHERE member_status='1' AND MD5(CONCAT('tmstms', member_id))='".$_SESSION["member_id"]."'");
        if(tep_num_rows($quser)>0){
            $user = tep_fetch_object($quser);
                    $_SESSION['member_id'] = $user->member_id;
                    $_SESSION['member_email'] = $user->member_email;
                    $_SESSION['member_ic'] = $user->member_ic;
                    

        }
    }
    
    switch ($_GET['pages']){
        case "": 
        case "index":                                                                       $page_title = "Personal Details";                                              break;
        case "academic_details":                                                    $page_title = "Academic Details";                                              break;
        case "course_details":                                                      $page_title = "Course Details";                                              break;
        case "financial_information":                                           $page_title = "Financial Information";                                              break;
        

    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title><?=SITENAME;?> - <?php echo $page_title; ?></title>
        <link rel="shortcut icon" href="./images/icon.png" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <script type="text/javascript" src="includes/modules/jquery.js"></script>
        <script type="text/javascript" src="includes/modules/j_tooltip/tipr.min.js"></script>
        <link rel="stylesheet" type="text/css" href="includes/modules/j_tooltip/tipr.css" />
        
        <script type="text/javascript" src="includes/modules/jquery-ui.js"></script>
        <script type="text/javascript" src="includes/modules/j_datepicker/jquery.datePicker.js"></script>
        <script type="text/javascript" src="includes/modules/j_datepicker/jquery-ui-timepicker-addon.js"></script>
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/ui.core.css" />
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/ui.datepicker.css" />
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/ui.theme.css" />
        <link rel="stylesheet" type="text/css" href="includes/modules/j_datepicker/jquery.ui.datepicker.css" />
        <!--j_upload-->
        <link href="includes/modules/j_upload/fileuploader.css" rel="stylesheet" type="text/css"/>
        <script src="includes/modules/j_upload/fileuploader.js"></script>

        <!-- main -->
        <link rel="stylesheet" type="text/css" href="includes/css/style.css">
        <link rel="stylesheet" type="text/css" href="includes/css/menu_2013.css">
        <!--[if IE 7]><link rel="stylesheet" href="includes/css/style_ie7.css" type="text/css"><![endif]-->
        <script type="text/javascript" src="includes/modules/thejs.js"></script>
        <!--<script src="./includes/modules/j_validation/jquery.js"></script>-->
        <script src="./includes/modules/j_validation/jquery.validate.js"></script>

        
    </head>
<body>
  


<div class="cls"></div>
<?php
  include("column_left.php");
  
if ($_SESSION['member_id']=="" && $_SESSION['user_id']!=""){
    echo "<div class='admin_view'>You are viewing this profile as admin</div>";
}
?>
<div id="container">
 
    <div id="content">
        
        <script>

	$(document).ready(function() {

		// validate signup form on keyup and submit
		$("#form").validate();
			
                        });
                        
</script>