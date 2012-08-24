<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
   <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
   <title>El convertor in action</title>
</head>
<?php


$nomcomplettemp = $_POST["nomcomplettemp"];
$pathnomtemp = $_POST["pathnomtemp"];
$filenoextemp = $_POST["filenoextmd5"];
$type = $_POST["salecat"];
$path = $_POST["path"];
$filenoext = $_POST["filenoext"];
$finaldir = '/convert/download/';


$command = '/usr/bin/unoconv';
$args = ' -c "socket,host=localhost,port=8100;urp;StarOffice.ComponentContext" ' . '-o ' . $finaldir . ' -f ' . $type . ' ' . $pathnomtemp;
$run = $command . $args;
$file = $finaldir . $filenoextemp . '.' . $type;
$sendfile = $filenoext . '.' . $type;

?>
<body>
<?php
$go = shell_exec ( $run );
if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.UTF8_decode($sendfile));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    //exit;
}

?>

</body>
</html>
