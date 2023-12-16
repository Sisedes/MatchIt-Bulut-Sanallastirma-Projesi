<?php
session_start();
require_once('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST['emailG'];
    $password = $_POST['passwordG'];

    $sql = "SELECT * FROM kayitli WHERE eposta='$mail'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['kullanici_adi'] = $row['kullanici_adi'];
            $_SESSION['kullanici_eposta'] = $mail;
            header("Location:game.html");
        } else {
            header("Location:kayit-giris.html");
            die("Geçersiz şifre");
        }
    } else {
        header("Location:game.html");
        die("Böyle bir e-posta kaydı bulunamadı");
    }
    $conn->close();
} else {
    header("Location:kayit-giris.html");
    die("Geçersiz istek");
}