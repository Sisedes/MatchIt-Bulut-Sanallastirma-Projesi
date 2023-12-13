<?php

$servername = "localhost";
$dbname = "kullanicilar";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Baglanti hatasi: " . $conn->connect_error);
}
else{
    echo"Baglanti basarili";
}