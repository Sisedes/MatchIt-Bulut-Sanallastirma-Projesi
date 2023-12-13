<?php
require_once('database.php');

// POST yöntemiyle gönderilen verileri al
$kullaniciAd = $_POST['nameK'];
$mail = $_POST['emailK'];
$password = $_POST['passwordK'];

$password_hash = password_hash($password, PASSWORD_DEFAULT);


$errorMessages = array(); // Hata mesajlarını saklamak için bir dizi oluşturuldu

if (empty($_POST["nameK"])) {
    $errorMessages[] = "Kullanıcı adı gereklidir";
}

if (!filter_var($_POST["emailK"], FILTER_VALIDATE_EMAIL)) {
    $errorMessages[] = "Geçerli bir e-posta adresi giriniz";
}

if (strlen($_POST["passwordK"]) < 8) {
    $errorMessages[] = "Şifreniz en az 8 karakterden oluşmalıdır";
}

if (!preg_match("/[a-z]/i", $_POST["passwordK"])) {
    $errorMessages[] = "Şifreniz en az bir harf içermelidir";
}

if (!preg_match("/[0-9]/", $_POST["passwordK"])) {
    $errorMessages[] = "Şifreniz en az bir rakam içermelidir";
}

if ($_POST["passwordK"] !== $_POST["password_confirmationK"]) {
    $errorMessages[] = "Şifreler uyuşmuyor";
}

session_start();
// Verileri SQL sorgusuyla ekleme
$sql = "INSERT INTO kayitli (eposta, password_hash, kullanici_adi) VALUES ('$mail', '$password_hash', '$kullaniciAd')";

if ($conn->query($sql) === TRUE) {
    $_SESSION['kullanici_adi'] = $kullaniciAd; // Kullanıcı adını session'a kaydet
    $_SESSION['kullanici_eposta'] = $mail; // E-postayı session'a kaydet
    echo"<br>";
    echo "Veri başarıyla eklendi";
    header("Location:game.html");
} else {
    echo "Hata: " . $sql . "<br>" . $conn->error;
    if ($conn->errno === 1062) {
        header("Location:kayit-giris.html");
        die("Bu eposta kullanılıyor.");
        }
}

// Veritabanı bağlantısını kapat
$conn->close();
?>
