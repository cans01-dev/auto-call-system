<?php 

$url = "https://e-ivr.net/storage/users/1/user1_2024-02-15.json";
$header = [
  "Accept: application/json"
];

$ch = curl_init();
curl_setopt_array($ch, [
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => $header,
  CURLOPT_USERPWD => "admin:password"
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo $response;
