<?php	
        session_start();

              include('../functions/database.php');
        include('../configuration.php');

        function resizeImage($image, $dir, $width = 800){
            list($width_orig, $height_orig) = getimagesize($image);
            if($width_orig <= $width){
                $width = $width_orig;
            }
            $ext = end(explode('.',$image));
            $thumb =  str_replace(".".$ext, "", end(explode('/',basename($image)))) . '.'  . $ext;
            $height = (int) (($width / $width_orig) * $height_orig);
            $image_p = imagecreatetruecolor($width, $height);

            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'JPEG') {
                    $image = imagecreatefromjpeg($image);
            }
            else if ($ext == 'png' || $ext == 'PNG') {
                    $image = imagecreatefrompng($image);
                    imagealphablending($image_p, false);
                    imagesavealpha($image_p,true);
                    $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
                    imagefilledrectangle($image_p, 0, 0, $width, $height, $transparent);

            } else if ($ext == 'gif' || $ext == 'GIF') {
                    $image = imagecreatefromgif($image);
                    imagealphablending($image_p, false);
                    imagesavealpha($image_p,true);
                    $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
                    imagefilledrectangle($image_p, 0, 0, $width, $height, $transparent);
            }

            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                    if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'JPEG') {
                            ImageJpeg($image_p,  $dir.$thumb, 200);
            }       else if ($ext == 'png' || $ext == 'PNG') {
                        ImagePNG($image_p, $dir.$thumb);
            }       else if ($ext == 'gif' || $ext == 'GIF') {
                        ImageGIF($image_p, $dir.$thumb, 200);
            }
        }
      
        

	$error = "";
	$msg = "";
	$fileElementName = 'fileToUpload';
        
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
 
	}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
	{
		$error = 'No file was uploaded..';
	}else 
	{
			 $allowedExts = array("jpg", "jpeg", "png");
                            $extension = explode(".", $_FILES["file"]["name"]);
                            if(in_array(strtolower($extension[count($extension)-1]), $allowedExts)){
                                if ($_FILES["file"]["error"] == 0){
                                    $dir = "../uploads/document/";
                                    $filename = md5(date("YmdHis").$_SESSION["user_id"]).".".$extension[count($extension)-1];
                                    move_uploaded_file($_FILES["file"]["tmp_name"], $dir.$filename);
                                    resizeImage($dir.$filename, $dir);
                                }
                            }
                            
			$imgloc = $_FILES['fileToUpload']['name'];
			$imgloc = strtolower(str_replace(array('(',')'),'',$imgloc));
//			
//			//move the file to image folder
////			$imgpath = ROOT_PATH.'../uploads/M_'.$_SESSION["merchant_id"].'/merchant/';
//                        $imgpath = '../../../uploads/merchant/';
//			$ext = end(explode('.',$imgloc));
//                        
//                        $x=0;
//			while (file_exists( $imgpath . $imgloc)){
//				$imgloc = str_replace(".".$ext, "", $imgloc) . "_" . ++$x . '.' . $ext;
//			}
//			 			
//			$imgloc = md5(date("YmdHis").$imgloc);
//			
			$msg .= " File Name: " . $imgloc . ", ";
			$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);

			$extension = array ('jpg', 'gif', 'png','jpeg');
			//get extension
			$stuff = end(explode('.',$imgloc));

			if (in_array(strtolower($stuff), $extension))
			{
                             $imgpath = '../../uploads/document/';
                             $imgloc = md5(date("YmdHis")).".".$extension[count($extension)-1];
			  move_uploaded_file ($_FILES['fileToUpload']['tmp_name'],$imgpath.$imgloc);
			}else{
			  $error = "extension .$stuff is not allowed!";
			}
                        
                        $dir = "../../uploads";
//                        resizeImage($dir."/document/".$imgloc, $dir."/merchant/", "800");
                        
                        $SourceFile = $dir."/document/".$imgloc;
                        $DestinationFile = $dir."/document/".$imgloc;
//                        watermarkImage ($SourceFile, $DestinationFile, $_SESSION["watermark"]);
			
//                        resizeImage($dir."/merchant/".$imgloc, $dir."/merchant/thumb/", "300");
	}		
	echo "{";
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "',\n";
	echo				"name: '" . $imgloc. "'\n";
	echo "}";
?>