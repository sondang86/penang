<?
//Ecommerce Login Script
//Delegate Functions
if($_POST['submit']!=""){
             
            $query = tep_query("SELECT * FROM ".USERS." WHERE user_status='1' AND user_email='".$_POST['email']."'");
            
            if(tep_num_rows($query)>0){
                $info = tep_fetch_object($query);
                if (md5($_POST['password'])==$info->user_password){
                    setcookie("user_id", md5("tmstms".$info->user_id), strtotime(date("23:59:59")));
                    $_SESSION['user_id'] = $info->user_id;
                    tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
                                VALUES('login','member_id', '".$info->user_id."', '', 'Login', NOW(), '".$info->user_id."')");
                    echo "<script language=\"javascript\">window.location='index.php';</script>";
                } else{
                $errormessage="Invalid user.";
            }
}else{
                $errormessage="Invalid user.";
            }
}

?>

<html>
<head>
    <title><?=SITENAME?></title>
    <script type="text/javascript" src="includes/modules/jquery.js"></script>
    <link rel="shortcut icon" href="<?php echo HTTP_SERVER; ?>/images/operion_icon.png" />
    <link rel="stylesheet" type="text/css" href="includes/css/style.css">
</head>
<style>
    html, body{background-color: #F7F6F1}
    
</style>
<script>
$(document).ready(function(){
    $('#email').focus();
});
</script>
<body>
<form action="index.php?pages=login" method="post">
    <div style="text-align:center;width:100%;">
        <div id="main" style="margin-top: 103px">
        <table border="0" cellpadding="5" cellspacing="0" style="margin: 0 auto; border: 1px solid #e5e5e5; background-color: #F7F6F1; font-size: 14px; line-height: 25px; padding: 15px 20px 20px 20px; box-shadow: 0 0 15px 5px rgba(207, 207, 207, 0.8)">
            <tr><th align="center"><img src="./images/logo.png" /></th></tr>
            <tr><td><input type="text" name="email" id="email" value="<?=$_POST["email"]?>" placeholder="Email" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" /></td></tr>
            <tr><td><input type="password" name="password" placeholder="Password" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" /></td></tr>
            <tr><td>
                    <div style="color: #DB1111;padding-top:10px"><?=$errormessage?></div>
                    <input type="submit" name="submit" value="Sign in" class="btn btn-blue">
                </td>
            </tr>
            
        </table><br />
        <div style="text-align:center;font-size:11px;"><?=SITENAME?>
            <br />Powered by <a href="http://www.operion.com,my" target="_blank">Operion Ecommerce & Software Sdn Bhd</a>
        </div>
        </div>
    </div>
</form>


</body>
</html> 