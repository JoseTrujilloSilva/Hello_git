<?php

require_once '../../../database.php';

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

$arrayResultadoJson = array();

$url = $_POST['url'];
$idComment = random_int(0, 999999);
$idTarian = $_POST['idTarianComment'];
$idUser = $_POST['idUserComment'];
$comentario = $_POST['comentarioTxt'];
$fechaHoy = date('d/m/Y H:i:s');

bbddConexion();

$bbdd = bbdd();


$sql = $bbdd->prepare("INSERT INTO comentarios VALUES(?,?,?,?,?)");

mysqli_stmt_bind_param($sql, 'iiiss', $idUser, $idTarian, $idComment, $comentario, $fechaHoy);


mysqli_stmt_execute($sql);


$resultado = bbdd()->query("SELECT usuarios.nombre, comentarios.idTarian, comentarios.txt, comentarios.fecha FROM usuarios, comentarios, tarians WHERE usuarios.idUser = comentarios.idUser AND tarians.idTarian= comentarios.idTarian AND tarians.idTarian = $idTarian ORDER BY comentarios.fecha DESC");

$row = $resultado->num_rows;

for ($i=0; $i < $row; $i++) { 
    $rows = $resultado->fetch_array(MYSQLI_NUM);
    array_push($arrayResultadoJson,$rows);
}


$fh = fopen($idTarian."Comment.json", 'w+');


fwrite($fh, json_encode($arrayResultadoJson)) or die("No se pudo escribir en el archivo");

fclose($fh);

header('Location: '.$url);


bbdd()->close();

?>