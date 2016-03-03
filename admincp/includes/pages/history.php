
<html>
    <head><title>Applicant History</title></head>
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
<div class="page_title" style="float: left">Applicant's History</div>
<div style="clear:both"></div>
<?php
        $qry = "SELECT * FROM ".MEMBER." m, temp t, temp_academic a, temp_course_details c, temp_financial_details f, temp_personal_details p WHERE m.member_id = ".$_GET['id']." AND t.member_id = m.member_id AND t.temp_id = a.temp_id AND t.temp_id = p.temp_id AND a.temp_id = f.temp_id AND a.temp_id = c.temp_id GROUP  BY t.temp_id";
     
        $query = tep_query($qry);
    echo "<ul>";
    
    while ($re = tep_fetch_object($query)){ 
    
           echo '<li><a href="../index.php?id='.$_GET['id'].'&tempid='.$re->temp_id.'" target="_blank">'.$re->temp_created.'</a></li>';
          
       
        
     
    }
    echo '</ul>';
   ?>
 </div>
</html>
