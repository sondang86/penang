
<html>
    <head><title>Applicant Documents</title></head>
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
<div class="page_title" style="float: left">Applicant's Document</div>
<div style="clear:both"></div>
<?php
        $qry = "SELECT * FROM ".MEMBER." WHERE member_id = ".$_GET['id']."";
        $query = tep_query($qry);
    echo "<ul>";
    
    while ($re = tep_fetch_object($query)){ 
      $document = explode(",",$re->member_document);

       for($i=0;$i<count($document);$i++){
           echo '<li><a href="../uploads/document/'.$document[$i].'" target="_blank">'.$document[$i].'</a></li>';
          
       }
        
     
    }
    echo '</ul>';
   ?>
 </div>
</html>
