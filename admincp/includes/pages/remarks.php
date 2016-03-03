<?php

if ($_POST['submit']!=""){
    tep_query ("UPDATE ".MEMBER." SET member_remarks = '".$_POST['remarks']."' WHERE member_id = ".$_GET['id']."");
    
    
}

?>
<html>
    <head><title>Applicant Remarks</title></head>
        <script type="text/javascript" src="includes/modules/jquery.js"></script>
        <script type="text/javascript" src="includes/modules/j_tooltip/tipr.min.js"></script>
        <link rel="stylesheet" type="text/css" href="includes/modules/j_tooltip/tipr.css" />
        <script type="text/javascript" src="includes/modules/jquery-ui.js"></script>
        <script type="text/javascript" src="includes/modules/thejs.js"></script>
<style>
    
    html,body{background: #ffffff;margin:0;padding:0;}
    body{font-family: Arial,sans-serif; font-size: 12px; width: 100%; color: #666}
    form {margin:0; padding:0;}
 
    h2, .page_title{ font-family: 'Trebuchet MS'; margin: 5px 0 2px 0; font-size: 16px; color:#333; font-weight: bold}
   
 </style>
 <div style="padding:10px">
<div class="page_title" style="float: left">Applicant's Remarks</div>
<div style="clear:both"></div>
<form method="post">
<?php
        $qry = tep_fetch_object(tep_query("SELECT * FROM ".MEMBER." WHERE member_id = ".$_GET['id'].""));
      
          echo '<textarea style="min-height:250px;width:750px;resize:none" name="remarks">'.$qry->member_remarks.'</textarea><br/><br/>';
          
          if ($_SESSION['user_group']=="SYSTEM_ADMIN"){
              echo '<input type="submit" name="submit" value="Save"/>';
          }
                  

   ?>
</form>
 </div>
</html>
