<?php
$servername = "localhost"; 
$username   = "root";  
$password   = "73442998"; 
$dbname     = "proyecto_taller"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error al conectar con MySQL: " . $conn->connect_error);
}
?>
