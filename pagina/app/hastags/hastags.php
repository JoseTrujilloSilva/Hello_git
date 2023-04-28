<?php

require_once '../../database.php';

bbddConexion();

$arrayResultadoJson = array();
$arrayHref = explode('?', $_GET['idCom']) ;
$idUser = $arrayHref[2];
$idCom = $arrayHref[0];
$hastags = '#'.$arrayHref[1];

echo $idCom;
echo $hastags;
echo $idUser;

$resultado = bbdd()->query("SELECT usuarios.nombre, usuarios.img, tarians.txt, tarians.img01, tarians.video, tarians.pdf, tarians.fecha, tarians.idTarian FROM usuarios, tarians WHERE usuarios.idUser = tarians.idUser AND usuarios.idCom = '$idCom' AND tarians.hastags = '$hastags';");

$row = $resultado->num_rows;

for ($i=0; $i < $row; $i++) { 
    $rows = $resultado->fetch_array(MYSQLI_NUM);
    array_push($arrayResultadoJson,$rows);
}

$fh2 = fopen($idCom."Hastags.json", 'w+');


fwrite($fh2, json_encode($arrayResultadoJson)) or die("No se pudo escribir en el archivo");

fclose($fh2);


header('Location: ./hastags.html?idUser='.$idUser.',idCom='.$idCom);


?>