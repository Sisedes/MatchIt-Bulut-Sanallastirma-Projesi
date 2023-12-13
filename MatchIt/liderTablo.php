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
            <th>Kullanıcı ID</th>
            <th>Hamle</th>
            <th>Süre</th>
        </tr>
    </thead>
    <tbody>
        <?php
       require_once('database.php');

        // SQL sorgusu - leaderboard tablosundan verileri çek
        $sql = "SELECT kullanici_id, hamle, sure FROM leaderboard ORDER BY sure ASC, hamle ASC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Sıra numarası için bir değişken tanımla
            $rank = 1;

            // Verileri döngü ile al ve tabloya ekle
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $rank++ . "</td>";
                echo "<td>" . $row["kullanici_id"] . "</td>";
                echo "<td>" . $row["hamle"] . "</td>";
                echo "<td>" . $row["sure"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tabloda veri bulunamadı</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

</body>
</html>
