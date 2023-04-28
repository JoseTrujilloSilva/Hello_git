<?php


ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

require_once '../../database.php';

$arrayResultadoJson = array();
$arrayResultadoJson2 = array();
$arrayHref = explode('?', $_GET['idUser']);
$rutaFotoUser = explode('=', $arrayHref[1])[1];
$idUser = $arrayHref[0];
$nameUser = explode('=', $arrayHref[2])[1];

var_dump($idUser);

bbddConexion();

$resultado = bbdd()->query("SELECT idCom FROM usuarios WHERE idUser = $idUser");

$idCom = $resultado->fetch_array(MYSQLI_NUM)[0];

$resultado2 = bbdd()->query("SELECT nombre, img, idUser FROM usuarios WHERE idCom = $idCom");

$row = $resultado2->num_rows;

for ($i=0; $i < $row; $i++) { 
    $rows = $resultado2->fetch_array(MYSQLI_NUM);
    array_push($arrayResultadoJson,$rows);
}

$fh = fopen($idCom.".json", 'w+');


fwrite($fh, json_encode($arrayResultadoJson)) or die("No se pudo escribir en el archivo");

fclose($fh);

$resultado3 = bbdd()->query("SELECT tarians.txt, tarians.fecha, tarians.hastags, tarians.likes, usuarios.idUser, usuarios.img, usuarios.nombre

FROM tarians, usuarios

WHERE tarians.idUser = usuarios.idUser AND usuarios.idCom = '$idCom'");

$row2 = $resultado3->num_rows;

for ($i=0; $i < $row2; $i++) { 
    $rowsH = $resultado3->fetch_array(MYSQLI_NUM);
    array_push($arrayResultadoJson2,$rowsH);
}

$fh2 = fopen($idCom."Hastags.json", 'w+');


fwrite($fh2, json_encode($arrayResultadoJson2)) or die("No se pudo escribir en el archivo");

fclose($fh2);


header('Location: ./explorar.html?idCom='.$idCom.',idUser='.$idUser.',nameUser='.$nameUser.',rutaFotoUser='.$rutaFotoUser);


?>