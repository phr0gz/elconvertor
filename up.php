<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
   <title>The ultimate convertor</title>
<?php
$intranetsrvurl = 'http://intra.dlsi.siege';
$pathtocss = '/templates/';
$csspath = $intranetsrvurl . $pathtocss;
?>
 <link rel="stylesheet" href="<?php echo $csspath . 'system/css/system.css' ?>" type="text/css" />
 <link rel="stylesheet" href="<?php echo $csspath . 'system/css/general.css' ?>" type="text/css" />
 <link rel="stylesheet" type="text/css" href="<?php echo $csspath . 'dlsi201111_1/css/template.css' ?>" media="screen" />

</head>
<body>
<?php

$temppath = '/convert/temp/';      // directory to store the uploaded files
$uploadpath = '/convert/upload/';
$max_size = 12000;          // maximum file size, in KiloBytes
$allowtype = array('doc','docx','rtf','odt','wps','txt','wks','ods','sdw','wpd','dot','jpg','png','jpeg','tiff','gif','tif','mdi','bmp','ppt','pptx','pps','ppsx','xls','xlsx','csv','xlm');        // allowed extensions
$docutype = array('doc','docx','rtf','odt','wps','txt','wks','ods','sdw','wpd','dot');
$imgtype = array('jpg','png','jpeg','tiff','gif','tif','mdi','bmp');
$prestype = array('ppt','pptx','pps','ppsx');
$calctype = array('xls','xlsx','csv','xlm');
$convertdocto = array('pdf','doc','docx','rtf');
$convertimgto = array('pdf','jpg','gif','eps');
$convertcaclto = array('pdf','xls','xlsx','csv');
$convertpresto = array('pdf','pptx','png','ppt');

if(isset($_FILES['fileup']) && strlen($_FILES['fileup']['name']) > 1)
  {
  $temppath = $temppath . basename( $_FILES['fileup']['name']);       // gets the file name
  $sepext = explode('.', strtolower($_FILES['fileup']['name']));
  $minusfilename = strtolower($_FILES['fileup']['name']);
  $type = strtolower(end($sepext));       // gets extension
  $filenoext = reset($sepext); // gets name

  $err = '';         // to store the errors

  // Checks if the file has allowed type, size, width and height (for images)
  if(!in_array($type, $allowtype)) {
	if ($type == $minusfilename) { 
		$err .= 'Le fichier: <b>'. $_FILES['fileup']['name']. "</b> est corrompu, merci d'utiliser un fichier lisible.";
		$notconvertible = 1;
	}
	elseif ($type == pdf){
		$err .= 'Le fichier: <b>'. $_FILES['fileup']['name']. "</b> ne pourra jamais être converti";
		$notconvertible = 1;
	} else {
		$err .= 'Le fichier: <b>'. $_FILES['fileup']['name']. "</b> n'est pas autorisé.";
	}
 }


  if($_FILES['fileup']['size'] > $max_size*1000) $err .= '<br/>le fichier doit etre moins grand que: '. $max_size. ' KB.';


  // If no errors, upload the image, else, output the errors
  if($err == '') {
      $md5nom = md5(uniqid(rand(), true));
      $filenoextmd5 = $md5nom;
      $nomcomplettemp = $md5nom . '.' . $type;
      $pathnomtemp = $uploadpath . $md5nom . '.' . $type;
    if(move_uploaded_file($_FILES['fileup']['tmp_name'], $pathnomtemp)) {
      // test extension to know the possible convert ext
      if((in_array($type, $docutype)) or (in_array($type, $prestype)) or (in_array($type, $calctype)) or (in_array($type, $imgtype)) ) {
        if(in_array($type, $docutype)) {
          $convertto = $convertdocto;
        }   
        if(in_array($type, $imgtype)) {
          $convertto = $convertimgto;
        }
        if(in_array($type, $calctype)) {
          $convertto = $convertcaclto;
        }
        if(in_array($type, $prestype)) {
          $convertto = $convertpresto;
        }
      }
      else echo '<br/><b> CONVERSION IMPOSSIBLE </b><br/>';
         
?>
   <p>
   <h2 class="art-postheader">Etape 2. Choix du type de document :</div>
   <form method="POST" enctype="multipart/form-data" name="format" onSubmit="window.location='up.php'" action='convert.php' target='_blank' >
   <div class="art-postcontent">a. Vous pouvez convertir le document en:</div>
   <select name="salecat">
      <?php foreach($convertto as $option) { ?>
           <option><?php echo $option ?>
           </option>
      <?php }?>
   </select>
   <br /><br /><div class="art-postcontent">b. Cliquez sur l'image pour convertir ci-dessous.</div><br /><br />
   <input type="hidden" name="path" value="<?php echo $temppath ?>">
   <input type="hidden" name="nomcomplettemp" value="<?php echo $nomcomplettemp ?>">
   <input type="hidden" name="pathnomtemp" value="<?php echo $pathnomtemp ?>">
   <input type="hidden" name="filenoextmd5" value="<?php echo $filenoextmd5 ?>">
   <input type="hidden" name="filenoext" value="<?php echo $filenoext ?>">
   <input type="image" name='submit' src="img/convert.png" border="0" />
   </form>
   <p>
   <p>

<?php
    }
    else echo '<b>Unable to upload the file.</b>';
  }
  else echo $err;
 }

 if(!(isset($_FILES['fileup']) && strlen($_FILES['fileup']['name']) > 1)) { 
?>
<div style="margin:1em auto; width:333px; text-align:left;">
 <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
 <h2 class="art-postheader">Etape 1. Recherchez le fichier à  convertir</h2><br/><br/>
  <div class="art-postcontent">
  a. Cliquez sur le bouton "Parcourir...". Le contenu de votre disque dur s'affichera.<br/><br/>
  Choisissez ensuite le fichier à  convertir.<br/><br/><br/></div>
  <input type="file" name="fileup" /><br/>
  <div class="art-postcontent"><br/>b. Valider votre choix<br/></div>
  <input type="submit" name='submit' value="Valider" />
 </form>
</div>
<?php } ?>

<?php if (!isset($notconvertible)) { ?>
<div class="art-postcontent">ce convertisseur est en test, si votre fichier n'est pas reconnu merci de cliquer ci-dessous:</div>
<A HREF="mailto:helpdesk@groupedlsi.com?subject=Convertisseur de document&body=Merci de rajouter le format de la piece jointe sur le convertisseur. (Mettre en piece jointe le fichier a convertir)">Demande d'ajout</A>
<?php } ?>
</body>
</html>


