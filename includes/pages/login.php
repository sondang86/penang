
<?php
if ($_GET['reset']=="password"){
    echo "<script>alert('Please check your email to reset your password!')</script>";   
}

if ($_GET['reset']=="success"){
    echo "<script>alert('You had successed reset your password!')</script>";  
}

if ($_GET['email']=="registered"){
    echo "<script>alert('This email already registered!')</script>";
}

if ($_GET['ic']=="registered"){
    echo "<script>alert('This NRIC No already registered!')</script>";
}

if ($_GET['register']=="verify"){
     echo "<script>alert('We have accepted your registration and the link has been sent to your email!')</script>";
}

if ($_GET['register']=="success"){
     echo "<script>alert('You had successful verify  your account!')</script>";   
}



if($_POST['submit']!=""){            
//            $query = tep_query("SELECT * FROM ".MEMBER." WHERE member_status='1' AND member_ic='".$_POST['ic']."'");
            $query = tep_query("SELECT * FROM ".MEMBER." WHERE member_ic='".$_POST['ic']."'");
            $info = tep_fetch_object($query);
            
            //Wrong Password
            if(tep_num_rows($query) !== 0){
                $account_query = tep_query("SELECT * FROM account_registration WHERE member_id=$info->member_id");            
                $account_registration = tep_fetch_object($account_query);                
            } else {
                $errormessage="Invalid user.";
            }


            
            if(tep_num_rows($query)>0 && $info->member_status == 1){ 
                // User email verified                 
                if (md5($_POST['password'])==$info->member_password){
                    setcookie("member_id", md5("tmstms".$info->member_id ), strtotime(date("23:59:59")));
                    $_SESSION['member_id'] = $info->member_id;
                    $_SESSION['member_email'] = $info->member_email;
                    $_SESSION['member_ic'] = $info->member_ic;
                    tep_query("INSERT INTO ".AUDITS."(`audit_page`, `audit_key`, `audit_value`, `audit_reference`,`audit_details`,`action`, `action_by`) 
                                VALUES('login','member_id', '".$info->member_id."', '', 'Login', NOW(), '".$info->member_id."')");
                    
                        redirect('index.php?pages=index');
                    
                }else{ //correct Id but wrong password
                    $errormessage="Invalid user.";
                }
            } elseif (tep_num_rows($query)>0 && $info->member_status == 0 && md5($_POST['password'])==$info->member_password) { 
                    //User account not yet activate
                    $errormessage="<div class='blink_me'>Your account has not yet activate. Click <form method='post'><input type='submit' name='resend_email' value='here'> to resend the verification link</div></form>";
                    $_SESSION['member_email'] = $info->member_email;
                    $_SESSION['member_created'] = $info->member_created;
                    $_SESSION['verification_link'] = $account_registration->verification_link;
                
                } else{ //Wrong email or IC
                            $errormessage="Invalid user.";
                }
}



//re-send email when username & password match
if(isset($_POST['resend_email'])){ 

    //Localhost send mail method
    $headers = 'From: info@penangfuturefoundation.my' . "\r\n";
    $content_title = "Penang Future Foundation Account Verification";
    $template = "Hi, <br/><br/>Thank you for your registration in Penang Future Foundation<br><br>
        Please click on the link below to verify your account<br>
        <a href=\"http://localhost/penangfuture/" .$_SESSION['verification_link'] ."\">http://localhost/penangfuture/".$_SESSION['verification_link']."</a><br><br>
            ========= User Account Log ========= <br>
            ".$_SESSION['member_created']."             Account Registration <br><br>
        --<br><br/>
                                                                    Best regards,<br/>
        Penang Future Foundation\n\n\n<br/>";    

    PHPMail($_SESSION['member_email'],$template,$content_title );
    
//    Production send mail method
//    $template = "Hi, \r\n Thank you for your registration in Penang Future Foundation \r\n "
//                . "Please click on the link below to verify your account \r\n http://form.penangfuturefoundation.my/".$_SESSION['verification_link']." \r\n \r\n". "========= User Account Log ========= \r\n".$_SESSION['member_created']."             Account Registration \r\n \r\n -- \r\n \r\n"
//                . "Best regards,\r\n Penang Future Foundation \r\n \r\n \r\n \r\n";
//    mail($_SESSION['member_email'], $content_title, $template, $headers);
    
    //Remove sessions to avoid security issues
    unset($_SESSION['member_created']);
    unset($_SESSION['member_email']);
    unset($_SESSION['verification_link']);
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
    .blink_me {
    -webkit-animation-name: blinker;
    -webkit-animation-duration: 1s;
    -webkit-animation-timing-function: linear;
    -webkit-animation-iteration-count: infinite;
    
    -moz-animation-name: blinker;
    -moz-animation-duration: 1s;
    -moz-animation-timing-function: linear;
    -moz-animation-iteration-count: infinite;
    
    animation-name: blinker;
    animation-duration: 1s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}


@-moz-keyframes blinker {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}

@-webkit-keyframes blinker {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}

@keyframes blinker {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}
    
</style>
<script>
$(document).ready(function(){
    $('#email').focus();
});
</script>
<body>
    
<form  method="post">
    <div style="text-align:center;width:100%;">
        
        <div id="main" style="margin-top: 103px">
            <p class="blink_me">Please <a href="index.php?pages=register">register here</a> to continue fill up the form.</p>
        <table border="0" cellpadding="5" cellspacing="0" style="margin: 0 auto; border: 1px solid #e5e5e5; background-color: #F7F6F1; font-size: 14px; line-height: 25px; padding: 15px 20px 20px 20px; box-shadow: 0 0 15px 5px rgba(207, 207, 207, 0.8)">
            <tr><th align="center"><img src="./images/logo.png" /></th></tr>
            <tr><td align="center"><h3>Login</h3></td></tr>
            <tr><td><input type="text" name="ic" id="ic" value="<?=$_POST["ic"]?>" placeholder="NRIC No" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" required /></td></tr>
            <tr><td><input type="password" name="password" placeholder="Password" style="width:318px; height: 35px; padding: 1px 6px; font-size: 14px;" required/></td></tr>
            <tr><td>
                    <div style="color: #DB1111;margin-top:-5px"><?=$errormessage?></div>
                    <input type="submit" name="submit" value="Sign in" class="btn btn-blue">
                </td>
            </tr>
            <tr>
                <td align="left"><a href="index.php?pages=register">Register Here</a> | <a href="index.php?pages=forget_password">Forget Password?</a></td>
                
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

<?php
//    $dataaa = md5("123456");
//    print_r($dataaa);
?>