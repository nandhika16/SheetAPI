<?php
// Membuka data json indodax
function curl($url)
{
    $ch = curl_init();

    // set URL
    curl_setopt($ch, CURLOPT_URL, $url);
    // return as a string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $hasil = curl_exec($ch);

    curl_close($ch);
    return $hasil;
}

// Merubah nama hari menjadi bahasa indonesia
function hari_indo($hari)
{
    $hari_rubah = array(
        0 =>   'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu',
    );

    return $hari_rubah[$hari];
}

// Merubah tampilan tanggal
function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);



    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

// Update Data ke spreadsheet
function updateData($values)
{
    $client = new Google_Client();
    $client->setApplicationName('Indodax View with Google Sheets API');
    $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
    $client->setAuthConfig(__DIR__ . '/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    $service = new Google_Service_Sheets($client);

    $spreadsheetsID = "1PdKhvMf7K7qlxK9A5pVk0kVaUFOxTqgP1Yb3LqZ0Zwk";
    $range = "Sheet1!A2:H";

    $data = [];
    $data[] = new Google_Service_Sheets_ValueRange([
        'range' => $range,
        'values' => $values
    ]);

    // Additional ranges to update ...
    $body = new Google_Service_Sheets_BatchUpdateValuesRequest([
        'valueInputOption' => 'RAW',
        'data' => $data
    ]);

    // mengirim data spreadsheet
    $result = $service->spreadsheets_values->batchUpdate($spreadsheetsID, $body);
    return ($result->getTotalUpdatedCells() / 8);
}

// Merubah tampilan nominal uang di tabel dan spreadsheet
function StrToCurr($currency, $value)
{
    if (substr($currency, -3) == 'idr') {
        $value = "Rp " . number_format($value, 0, ",", ".");
    } else {
        $value = "$ " . number_format($value, 8, ",", ".");
    }

    return $value;
}
