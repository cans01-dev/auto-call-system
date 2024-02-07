<?php

function text_to_speech($text) {
  $google_api_key = "AIzaSyCVOtglUcy3xRxk-x1qI2m8e-JmJ_RZZJU";
  $google_tts_api_url = "https://texttospeech.googleapis.com/v1/text:synthesize?key=".$google_api_key;
  
  $ch = curl_init();
  curl_setopt_array($ch, [
    CURLOPT_URL => $google_tts_api_url,
    CURLOPT_HTTPHEADER => ["Content-Type: application/json"],
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => json_encode([
      "audioConfig" => [
        "audioEncoding" => "LINEAR16",
        "pitch" => 0,
        "speakingRate" => 1 
      ],
      "input" => [
        "text" => $text,
      ],
      "voice" => [
        "languageCode" => "ja-JP",
        "name" => "ja-JP-Standard-A"
      ]
    ])
  ]);
  $response = curl_exec($ch);
  curl_close($ch);
  
  $array = json_decode($response, true);

  return base64_decode($array["audioContent"]);
}

$text = "こちらは、電力〇〇センターです。〇〇電力管内にお住まいの皆様へ、〇〇電力のお得なプランに切り替えた場合、
どれくらい電気代が削減できるかの診断精査を行っております。
１分程度の音声質問にご協力をお願いします。尚、音声の途中でもご回答頂けます。";

$voice_content = text_to_speech($text);
file_put_contents("a.wav", $voice_content);