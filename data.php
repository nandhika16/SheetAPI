<?php
require __DIR__ . '/vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('Google Sheets API PHP Quickstart');
$client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
$client->setAuthConfig('credentials.json');
$client->setAccessType('offline');
$client->setPrompt('select_account consent');
$service = new Google_Service_Sheets($client);

$spreadsheetId = '1PdKhvMf7K7qlxK9A5pVk0kVaUFOxTqgP1Yb3LqZ0Zwk';
$range = 'Sheet1!A2:H';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$data = $response->getValues();
