<?php
$hostname = 'localhost';    // nama server. "localhost" untuk penggunaan server local seperti XAMPP / MAMPP
$username = 'root';         // username untuk mengakses database
$password = '';             // password untuk mengakses database
$db       = 'db_vb6';         // nama databases. sesuaikan dengan database yang dipakai

$conn = mysqli_connect($hostname, $username, $password, $db);

function sendDB($id,$aset, $high, $low, $last, $hari, $tgl, $waktu)
{
    global $conn;
    $query = "INSERT INTO tbl_indodax_3 (id, aset, high, low, last, hari, tgl, waktu) 
            VALUES ('$id','$aset', '$high', '$low', '$last', '$hari', '$tgl', '$waktu')";

    $hasil = mysqli_query($conn, $query);
    return $hasil;
}
