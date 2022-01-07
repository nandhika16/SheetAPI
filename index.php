<?php
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');
require __DIR__ . '/vendor/autoload.php';
require 'database.php';
require 'update.php';


// ======================= Mengambil Data API dari indodax  =============
$openUrl = curl("https://indodax.com/api/tickers");
$result = json_decode($openUrl, true)['tickers'];
$values = [];
$no = 1;
// ====================================================================


// ======================= Mengirim Data Ke database dan SpreadSheet  =============
foreach ($result as $asset => $v) {
    $id = uniqid();
    // kirim DB
    sendDB($id, $asset, $v['high'], $v['low'], $v['last'], hari_indo(date('w', $v['server_time'])), date('Y-m-d', $v['server_time']), date('H:i:s', $v['server_time']));

    $values[] = [$no, $asset, StrToCurr($asset, $v['high']), StrToCurr($asset, $v['low']), StrToCurr($asset, $v['last']), hari_indo(date('w', $v['server_time'])), tgl_indo(date('Y-m-d', $v['server_time'])), date('H:i:s', $v['server_time'])];
    $no += 1;
}

updateData($values); //Kirim data ke spreadsheet
// ====================================================================

?>
<!-- halaman untuk menampilkan data dari spreadsheet dengan format tabel -->
<!DOCTYPE html>
<html>

<head>
    <title>FP API</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="background-image: url(bg.jpg);" class="text-white">

    <!-- ======================= Tampilan Website  ============= -->

    <head>
        <div class="row p-4">
            <div class="col text-center">
                <h1 class="text-center p-3 bg-dark " style="font-family: verdana;">Indodax Sheet API</h1>
                <a href="https://docs.google.com/spreadsheets/d/1PdKhvMf7K7qlxK9A5pVk0kVaUFOxTqgP1Yb3LqZ0Zwk/edit#gid=0" class="btn btn-success btn-lg mx-auto">SpreadSheet</a>
            </div>
        </div>
    </head>
    <!-- ======================= Mengambil Data API dari indodax  ============= -->
    <section class="tabel">
        <div class="container">
            <div class="row">
                <table class="table table-hover table-dark">
                    <thead class="bg-dark text-light fw-bold">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Aset</th>
                            <th scope="col">Tertinggi</th>
                            <th scope="col">Terendah</th>
                            <th scope="col">Terkini</th>
                            <th scope="col">Hari</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="text-white" id="target-">
                        <?php
                        $number = 1;
                        foreach ($result as $asset => $v) {
                            $high = StrToCurr($asset, $v['high']);
                            $low = StrToCurr($asset, $v['low']);
                            $last = StrToCurr($asset, $v['last']);
                            $hari = hari_indo(date('w', $v['server_time']));
                            $tgl = tgl_indo(date('Y-m-d', $v['server_time']));
                            $jam = date('H:i:s', $v['server_time']);

                            print "<tr>";
                            //Penomoran Otomatis
                            print "<td>" . $number . "</td>";
                            //Menayangkan nama aset
                            print "<td>" . $asset . "</td>";
                            print "<td>" . $high . "</td>";
                            print "<td>" . $low . "</td>";
                            print "<td>" . $last . "</td>";
                            print "<td>" . $hari . "</td>";
                            print "<td>" . $tgl . "</td>";
                            print "<td>" . $jam . "</td>";
                            print "<tr/>";

                            $number += 1;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>