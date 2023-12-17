<?php
require_once('database.php');

$hamle = $_POST['resultInput'];
$sure = $_POST['timeInput'];

$posta="";
session_start();

if(isset($_SESSION['kullanici_eposta'])) 
{
    $posta = $_SESSION['kullanici_eposta'];
}

$kayit="SELECT * FROM kayitli WHERE eposta='$posta'";
$result = $conn->query($kayit);
$kullaniciId="0";
if ($result) {
    // Sorgudan dönen sonuçların kontrolü
    if ($result->num_rows > 0) {
        // Eğer bir sonuç varsa, ID'yi al
        $row = $result->fetch_assoc();
        $kullaniciId = $row['kullanici_id'];
    }
}

$sql="SELECT * FROM leaderboard WHERE kullanici_id = $kullaniciId";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    $sql= "DELETE FROM leaderboard WHERE kullanici_id = $kullaniciId";
    $conn->query($sql);
    $sql = "INSERT INTO leaderboard (kullanici_id, hamle, sure) VALUES ('$kullaniciId', '$hamle', '$sure')";

    $conn->query($sql);

} else {
    $sql = "INSERT INTO leaderboard (kullanici_id, hamle, sure) VALUES ('$kullaniciId', '$hamle', '$sure')";
    $conn->query($sql);

}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #340068; /* DarkViolet rengi */
            color: #ffffff; /* Beyaz metin rengi */
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #bb86fc; /* LightViolet tonu */
        }

        table {
            width: 70%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #3a0074; /* DarkViolet tonlarından */
            color: #ffffff; /* Beyaz metin rengi */
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #5f1797; /* DarkViolet tonlarından */
        }

        th {
            background-color: #4f0caa; /* DarkViolet tonlarından */
            color: #ffffff; /* Beyaz metin rengi */
            text-transform: uppercase;
            font-size: 14px;
        }

        tbody tr:hover {
            background-color: #421083; /* DarkViolet tonlarından */
        }

        tbody tr:nth-child(even) {
            background-color: #330058; /* DarkViolet tonlarından */
        }

        tbody td {
            font-size: 14px;
        }

        .button-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); /* Düğme genişliğini ayarlayabilirsiniz */
    gap: 5px; /* İstediğiniz boşluk ayarını yapabilirsiniz */
    justify-content: center;
}

.custom-button {
    border: none;
    border-radius: 0.3em;
    padding: 1em 1.5em;
    cursor: pointer;
    background-color: darkviolet;
    color: whitesmoke;
}


</style>

    </style>
</head>
<body>

<table>
    <thead>
        <tr>
            <th>Sıra</th>
            <th>Kullanıcı Adı</th>
            <th>Hamle</th>
            <th>Süre</th>
        </tr>
    </thead>
    <tbody>
        <?php

        // SQL sorgusu - leaderboard tablosundan verileri çek
        $sql = "SELECT kayitli.kullanici_adi, leaderboard.hamle, leaderboard.sure 
        FROM leaderboard  
        INNER JOIN kayitli ON kayitli.kullanici_id = leaderboard.kullanici_id
        ORDER BY leaderboard.sure ASC, leaderboard.hamle ASC";


        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Sıra numarası için bir değişken tanımla
            $rank = 1;

            // Verileri döngü ile al ve tabloya ekle
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $rank++ ?></td>
                    <td><?= $row["kullanici_adi"] ?></td>
                    <td><?= $row["hamle"] ?></td>
                    <td><?= $row["sure"] ?></td>
                </tr>
            <?php endwhile;
        } else { ?>
            <tr><td colspan='4'>Tabloda veri bulunamadı</td></tr>
        <?php }
        $conn->close();
        ?>
    </tbody>
</table>

<br>
<div class="button-container">
    <button onclick="redirectToPage()" class="custom-button">Ana Sayfaya Git</button>
    <button onclick="redirectToPage2()" class="custom-button">Oyuna Dön</button>
</div>


<script>
    function redirectToPage() {
    // Yönlendirme yapılacak URL
    let redirectTo = 'index.html';

    // Yönlendirme işlemi
    window.location.href = redirectTo;
  }
  function redirectToPage2() {
    // Yönlendirme yapılacak URL
    let redirectTo = 'game.html';

    // Yönlendirme işlemi
    window.location.href = redirectTo;
  }
</script>


</body>
</html>
