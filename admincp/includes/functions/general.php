<?php
function isAdmin(){
    if($_SESSION["user_group"]=="SYSTEM_ADMIN"){
        return true;
    } else{
        return false;
    }
}

//general functions
function ddlReplace($ddl, $selected){
    return str_replace('value="'.$selected.'"', 'value="'.$selected.'" SELECTED', $ddl);
}

function arrToDDL($arr){
    $ddl = "";
    foreach($arr AS $key=>$val){
        $ddl .= '<option value="'.$key.'">'.$val.'</option>';
    }
    return $ddl;
}

function date_htmltomysql($date) {
    $new = explode("/",$date);
    $newdate=array ($new[2], $new[1], $new[0]);
    return $n_date=implode("-", $newdate);
}

function send_mail($email, $template, $content_title, $from="Penang Future Foundation <info@penangfuturefoundation.my>", $cc=""){
    
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

    $mail_sent = @mail( $to, $subject, $message, $headers, "-finfo@penangfuturefoundation.my" );
    if ($mail_sent=="1"){

    } else {
            $errMessage.="Your enquiry failed to send. Please try again later, thanks!";
            echo "<div class=\"errMessage\">".$errMessage."</div>";
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