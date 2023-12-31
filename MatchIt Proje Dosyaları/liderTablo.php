<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
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
        require_once('database.php');

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

</body>
</html>
