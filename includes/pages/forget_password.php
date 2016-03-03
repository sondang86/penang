<?php
function createRandomPassword() { 

    $chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 

    while ($i <= 7) { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 

    return $pass; 

} 

if($_POST['submit']!=""){
                
                if ($_POST['email']!="" && $_POST['password']!=""){
                   if (!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $_POST['password'])){
                              echo "<script>alert('Password must be alphanumeric!')</script>";  
                    }else if (strlen($_POST['password']) < 8){
                              echo "<script>alert('Password must be more than 8 characters!')</script>";  
                    }else{
                    $query = tep_query("SELECT * FROM ".MEMBER." WHERE member_email='".$_POST['email']."'");
                   
                    if(tep_num_rows($query)>0){
                         $re = tep_fetch_object($query);
                              
                               
                               tep_query("UPDATE ".MEMBER." SET member_password = '".md5($_POST['password'])."', member_status = 0 WHERE member_email = '".trim($_POST['email'])."'");

                               $email = trim($_POST['email']);
				$template = "Hi, <br>
					You recently asked to reset your password, please click the link below to reset it:<<br>
					<a href=\"".HTTP_SERVER."/index.php?pages=forget_password&id=".$re->member_id."&hash=".md5($_POST['password'])."\">http://www.penangfuturefoundation.my/index.php?pages=forget_password&id=".$re->member_id."&hash =".md5($_POST['password'])."</a><br><br>
					--<br><br/>
                                                                                Best regards,<br/>
				Penang Future Foundation\n\n\n<br/>";
				$content_title = "Penang Future Foundation Reset Password";
				send_mail($email, $template, $content_title);
                             
                          redirect('index.php?pages=login&reset=password');
                           }else{
                               echo "<script>alert('Your email is incorrect!')</script>";
                           }
                    }
                    }else{
                        echo "<script>alert('Please key in all the require input!')</script>";
            }
}

if($_GET['hash']!="" && $_GET['id']!=""){
     tep_query("UPDATE ".MEMBER." SET member_status = 1 WHERE member_id = '".$_GET['id']."'");
     redirect('index.php?pages=login&reset=success');
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
   html, body{background-color: #F7F6F1;background:url(./images/bg1.png)}
     
</style>
<script>
$(document).ready(function(){
    $('#email').focus();
});
</script>
<body>
<form action="index.php?pages=forget_password" method="post">
    <div style="text-align:center;width:100%;">
        <div id="main" style="margin-top: 103px">
        <table border="0" cellpadding="5" cellspacing="0" style="margin: 0 auto; border: 1px solid #e5e5e5; background-color: #F7F6F1; font-size: 14px; line-height: 25px; padding: 15px 20px 20px 20px; box-shadow: 0 0 15px 5px rgba(207, 207, 207, 0.8)">
            <tr><th align="center"><img src="./images/logo.png" /></th></tr>
            <tr><td align="center"><h3>Forget Password</h3></td></tr>
            <tr><td><input type="text" name="email" id="email" value="<?=$_POST["email"]?>" placeholder="Email" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" /></td></tr>
            <tr><td><input type="password" name="password" placeholder="New Password" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" /></td></tr>
            <tr><td>
                    <div style="color: #DB1111;margin-top:-23px"><?=$errormessage?></div>
                    <input type="submit" name="submit" value="Reset Password" class="btn btn-blue">
                </td>
            </tr>
            <tr>
                <td align="left"><a href="index.php?pages=login">Login</a> | <a href="index.php?pages=register">Register Here</a></td>
                
            </tr>
        </table><br />
        <div style="text-align:center;font-size:11px;"><?=SITENAME?>
            <br />Powered by <a href="http://www.operion.com.my" target="_blank">Operion Ecommerce & Software Sdn Bhd</a>
        </div>
        </div>
    </div>
</form>


</body>
</html>