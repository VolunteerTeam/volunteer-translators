<html>
<head>
  <title>Результат загрузки файла</title>
</head>
<body>
<?php
error_reporting(E_ALL); 
$imageinfo = getimagesize($_FILES['uploadfile']['tmp_name']);
if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg' && $imageinfo['mime'] != 'image/png') {
echo "error";
}else{
$uploaddir = './images/';
$img_ganerate_name = md5(uniqid(rand(),true));
$imgname = $img_ganerate_name.".png";
$uploadfile = $uploaddir . basename($_FILES['uploadfile'].$imgname);	
move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile);
echo $imgname;	
}		
?>

</body>
</html>