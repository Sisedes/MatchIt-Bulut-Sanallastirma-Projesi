<?php

$servername = "db";
$dbname = "kullanicilar";
$username = "kaan_docker";
$password = "123";

$conn=mysqli_connect($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Baglanti hatasi: " . $conn->connect_error);
}
