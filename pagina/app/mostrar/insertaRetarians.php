<?php


require_once '../../database.php';

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

bbddConexion();

$idTarian2 = $_POST['idTarianRetarians'];
$idTarian = random_int(0, 999999);
$idUser = $_POST['idUserRetarians'];
$textRetarians = $_POST['textRetarians'];
$fechaTarian = date('d/m/Y');
$numRetarian = 1;


echo $idTarian.'<br>';


$resultadoTarians = bbdd()->query("SELECT idUser, txt, img01, video, pdf FROM tarians WHERE idTarian = $idTarian2");

$array_ResulTarians = $resultadoTarians->fetch_array(MYSQLI_NUM);

$text = $array_ResulTarians[1];
$img = $array_ResulTarians[2];
$video = $array_ResulTarians[3];
$pdf = $array_ResulTarians[4];

$resultadoUsuarioT = bbdd()->query("SELECT nombre FROM usuarios WHERE idUser = $array_ResulTarians[0]");

$autor = $resultadoUsuarioT->fetch_array(MYSQLI_NUM)[0];

$resultadoNameImg = bbdd()->query("SELECT nombre, img FROM usuarios WHERE idUser = $idUser");

$arrayImgName = $resultadoNameImg->fetch_array(MYSQLI_NUM);


$nameUser = $arrayImgName[0];
$fotoUser = '.'.$arrayImgName[1];


$bbdd = bbdd();

echo $text;

echo $video;

echo $fechaTarian;

echo $autor;
echo $textRetarians;
echo $numRetarian;

switch (true) {
    case $img!=null:
        $sql = $bbdd->prepare("INSERT INTO tarians (idUser, idTarian, txt, img01, fecha, autor, textRetarian, retarian) VALUES(?,?,?,?,?,?,?,?)");

        $sql->bind_param('iisssssi', $idUser, $idTarian, $text, $img, $fechaTarian, $autor, $textRetarians, $numRetarian);
        
        $sql->execute();
        
        $sql->close();
        break;
    case $video!=null:
        $sql = $bbdd->prepare("INSERT INTO tarians (idUser, idTarian, txt, video, fecha, autor, textRetarian, retarian) VALUES(?,?,?,?,?,?,?,?)");

        $sql->bind_param('iisssssi', $idUser, $idTarian, $text, $video, $fechaTarian, $autor, $textRetarians, $numRetarian);
        
        $sql->execute();
        
        $sql->close();
        break;
    case $pdf!=null:
        $sql = $bbdd->prepare("INSERT INTO tarians (idUser, idTarian, txt, pdf, fecha, autor, textRetarian, retarian) VALUES(?,?,?,?,?,?,?,?)");
    
    $sql->bind_param('iisssssi', $idUser, $idTarian, $text, $pdf, $fechaTarian, $autor, $textRetarians, $numRetarian);
    
    $sql->execute();
    
    $sql->close();
        break;
    default:
        
    $sql = $bbdd->prepare("INSERT INTO tarians (idUser, idTarian, txt, fecha, autor, textRetarian, retarian) VALUES(?,?,?,?,?,?,?)");

    $sql->bind_param('iissssi', $idUser, $idTarian, $text, $fechaTarian, $autor, $textRetarians,$numRetarian);

    $sql->execute();

    $sql->close();   
        break;
}




$arrayResultado = array();

$resultado = bbdd()->query("SELECT txt, img01, video, pdf, fecha, idTarian, autor, textRetarian, retarian FROM tarians WHERE idUser = $idUser ORDER BY fecha DESC");

$row = $resultado->num_rows;

for ($i=0; $i < $row; $i++) { 
    $rows = $resultado->fetch_array(MYSQLI_NUM);
    array_push($arrayResultado, $rows);
}


$fh = fopen($idUser.".json", 'w+');


fwrite($fh, json_encode($arrayResultado)) or die("No se pudo escribir en el archivo");

fclose($fh);


header('Location: ./muestraTarians.html?idUser='.$idUser.', rutaFotoUser='.$fotoUser.', nameUser='.$nameUser);


?>


