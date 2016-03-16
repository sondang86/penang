<?php
require_once('includes/modules/PHPMailer/PHPMailerAutoload.php');

function PHPMail($email, $template, $content_title){
    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;                               // Enable verbose debug output

    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'dang.viet.son.hp4@gmail.com';                 // SMTP username
    $mail->Password = 'haiphong@!#123';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('dang.viet.son.hp@gmail.com', 'Mailer');
    $mail->addAddress($email);     // Add a recipient

    $mail->isHTML(true);                                  // Set email format to HTML

    $mail->Subject = $content_title;
    $mail->Body    = $template;

    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;die;
    } else {
        echo 'Message has been sent';
    }
}

function tep_state(){
    $arr = array(
       "Johor","Kedah","Kelantan","Kuala Lumpur","Labuan","Melaka","Negeri Sembilan","Pahang","Perak","Perlis","Pulau Pinang","Putrajaya","Sabah","Sarawak","Selangor","Terengganu","Others"
    );
return $arr;
}

function tep_academic_year(){
    $arr = array(
       "1","2","3"
    );
return $arr;
}

function tep_academic_semester(){
    $arr = array(
       "1","2","3","4"
    );
return $arr;
}

function tep_year(){
    $arr = array(
      "2006","2007","2008","2009","2010","2011","2012","2013","2014","2015"
    );
return $arr;
}

function tep_awyear(){
    $arr = array(
    "2013","2014","2015"
    );
return $arr;
}

function tep_subject(){
    $arr = array(
      "General Studies","MUET","Physics","Chemistry","Biology","Computing","Mathematics T","Mathematics S","Economics","Business Studies","Accounting","Others"
    );
return $arr;
}

function tep_coposition(){
    $arr = array(
       "President","Vice President","Treasurer","Secretary","Prefect","Monitor"
    );
return $arr;
}

function tep_awprize(){
    $arr = array(
      "First","Second","Third"
    );
return $arr;
}

function tep_awlevel(){
    $arr = array(
      "State","National","International"
    );
return $arr;
}

function tep_qualification(){
    $arr = array(
       "STPM","Matriculation","Diploma","A Level","HSC","Foundation / Pre-University","Other"
    );
return $arr;
}

function tep_month(){
    $arr = array(
       "January"=>1,
        "February"=>2,
        "March"=>3,
        "April"=>4,
        "May"=>5,
        "June"=>6,
        "July"=>7,
        "August"=>8,
        "September"=>9,
        "October"=>10,
        "November"=>11,
        "December"=>12
    );
return $arr;
}

function tep_courseYear(){
    $arr = array(
       "2012","2013","2014","2015","2016"
    );
return $arr;
}

function tep_CompletionYear(){
    $arr = array(
       "2015","2016","2017","2018","2019","2020"
    );
return $arr;
}

function tep_durationApplied(){
    for($i=1;$i<=48;$i++){
        $arr[] = $i;
    }
 
return $arr;
}

//general functions
function ddlReplace($ddl, $selected){
    return str_replace('value="'.$selected.'"', 'value="'.$selected.'" SELECTED', $ddl);
}

function arrToDDL($arr){
    $ddl = "";
    foreach($arr AS $val){
        $ddl .= '<option value="'.$val.'">'.$val.'</option>';
    }
    return $ddl;
}

function arrToDDLWithKey($arr){
    $ddl = "";
    foreach($arr AS $key=>$val){
        $ddl .= '<option value="'.$val.'">'.$key.'</option>';
    }
    return $ddl;
}

function date_htmltomysql($date) {
    $new = explode("/",$date);
    $newdate=array ($new[2], $new[1], $new[0]);
    return $n_date=implode("-", $newdate);
}

function send_mail($email, $template, $content_title, $from="Penang Future Foundation <no-reply@penangfuturefoundation.my>", $cc=""){
    
    $mime_boundary = "----Penang Future Foundation----".md5(time());
    $email_subject = $content_title;
    $to = "$email";
    $subject = $email_subject;

    $headers = "From: ".$from."\n";
    if($cc!=""){
        $headers .= "Cc: ".$cc."\n";
    }
    $headers .= "Reply-To: ".$from."\n";
    $headers .= "MIME-Version: 1.0\n";
    $headers .= "Content-Type: multipart/alternative; boundary=\"$mime_boundary\"\n";

    $message = "--$mime_boundary\n";
    $message .= "Content-Type: text/html; charset=UTF-8\n";
    $message .= "Content-Transfer-Encoding: 8bit\n\n";
    $message .= $template;

    $mail_sent = @mail( $to, $subject, $message, $headers );
    if ($mail_sent=="1"){
        echo "sent";
    } else {
            $errMessage.="Your enquiry failed to send. Please try again later, thanks!";
            echo "<div class=\"errMessage\">".$errMessage."</div>";die;
    }
}

function getEmailFooter(){
    return '<br /><br />
            --<br />
            '.SITENAME.'<br />
            Website: <a href="http://www.penangfuturefoundation.my">www.penangfuturefoundation.my</a><br />'.$ext;
}

function succMsg($text){
    return "<div class=\"succMessage\">".$text."<span>&times;</span></div>";
}

function errMsg($text){
    return "<div class=\"errMessage\">".$text."<span>&times;</span></div>";
}

function redirect($url){
    header('Location: '.$url.'');
}

//function to return a parameter string limiting the rows getting out
function pagination ($rowsPerPage)
{
	$pageNum = 1;

	if ($_GET['page']!= "")
	{
		$pageNum = $_GET['page'];
	}

	// counting the offset
	$offset = ($pageNum - 1) * $rowsPerPage;
	$param = " LIMIT $offset, $rowsPerPage";

	return $param;
}

//function to create paging navigation
function paging_navigation ($numrows, $rowsPerPage)
{

	define('L_LINK_PREV','Prev');
	define('L_LINK_PREV_LAST','First');
	define('L_LINK_NEXT','Next');
	define('L_LINK_NEXT_LAST','Last');

	//if cant fit one page... just exit
	if ($numrows == 0  || ($numrows / $rowsPerPage) <= 1)
	{
		echo '<table>
			  <tr><td colspan=\"3\"><span class=\"description\">Page 1 of 1</span></td></tr>
			  </table>';
		return false;
	}


	//get current page number
	$pageNum = 1;

	if ($_GET['page']!= "")
	{
		$pageNum = $_GET['page'];
	}

	$self = 'index.php?';

	// print the link to access each page
	foreach ($_GET as $key=>$values)
	{
		if ($key != "page")
		{
			$self .= "&" . $key . "=" . $values;
		}
	}

	$nav  = '';
	$x=0;

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);
        for($page = max(1, $pageNum - 10); $page <= min($pageNum + 10, $maxPage); $page++){
          
		if ($page == $pageNum)
		{
			if($maxPage != 1) {$nav .= "<span style=\"padding:0px 6px; line-height:100%; font-family:verdana; font-size:12px; text-decoration:underline;\">". $page ."</span>";} // no need to create a link to current page
		}
		else
		{
			$nav .=  "<a style=\"padding:0px 6px; line-height:100%; font-family:verdana; font-size:12px;\" href=\"".$self."&page=".$page."\">$page</a>"; // <option value=\"$self"."&page=$page\">$page</option>
		}
	}

	// creating previous and next link
	// plus the link to go straight to
	// the first and last page

	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;
		$prev  = " <a href=\"$self"."&page=$page\">".L_LINK_PREV."</a> ";


		$first = " <a href=\"$self"."&page=1\">[".L_LINK_PREV_LAST."]</a> ";
	}
	else
	{
		$prev  = ''.L_LINK_PREV.'&nbsp;'; // we're on page one, don't print previous link
		$first = '['.L_LINK_PREV_LAST.']&nbsp'; // nor the first page link
	}

	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href=\"$self"."&page=$page\">".L_LINK_NEXT."</a> ";

		$last = " <a href=\"$self"."&page=$maxPage\">[".L_LINK_NEXT_LAST."]</a> ";
	}
	else
	{
		$next = ''.L_LINK_NEXT.'&nbsp;'; // we're on the last page, don't print next link
		$last = '['.L_LINK_NEXT_LAST.']'; // nor the last page link
	}


	// print the navigation link
	echo "<table class=\"paging\">
			<tr>

			  <td>". $first . "</td>
			  <td style=\"line-height:10px;\"><div style=\"text-align:center;\">".$nav."</div></td>
			  <td>" . $last . "</td>

			</tr>

			   <!--<tr><td colspan=\"3\"><span class=\"description\">Page $pageNum of $maxPage</span></td></tr>-->

		  </table>";
}

?>