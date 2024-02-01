# 予約情報ファイル、結果ファイル変更案

#### 目的
結果ファイルから回答の流れとCans側で扱う質問・エンディングを紐づけるため

#### 変更点
draft2
- 質問情報ファイル.ending（音声ファイル名） 削除
- 結果ファイル.calls[].answersのエンディングにending_id追加

#### 疑問点
- labelは必要か？->greeting後最初に再生する質問はjson配列の最初の質問にするとか

### 質問情報ファイルの変更
```json
{
  "ac_id": 223,
  "user_id": 1,
  "date": "2024-01-24",
  "start": "17:00",
  "end": "21:00",
  "greeting": "greeting223.wav",
  // "ending": "ending223.wav", // Tokutome - 不要です。// Cans 削除
  "faqs": [
    {
      "label": "stage1",
      "faq_id": "11",　// Cans側で扱う質問とラベルを紐づけるため
      "voice": "voice3121.wav",
      "options": {
        "0": "ending1",  // Tokutome - 動作：ending1ラベルに移動し、ennding1のアナウンスを再生後、通話を切断。
        "1": "stage2",
        "2": "stage3"
      }
    },
        {
      "label": "stage2",
      "faq_id": "16",
      "voice": "voice3122.wav",
      "options": {
        "0": "ending2",     // Tokutome - 動作：ending2ラベルに移動し、ennding2のアナウンスを再生後、通話を切断。
        "1": "stage3",
        "2": "stage4"
      }
    }

  ],
  "endings": [ // エンディングの複数分岐に対応するため
    {
      "label": "ending1",   // Tokutome - ラベル名を ending1 に変更。
      "ending_id": "14",
      "voice": "voiceend1.wav"
    },
    {
      "label": "ending2",   // Tokutome - ラベル名 ending2 に変更。
      "ending_id": "21",
      "voice": "voiceend2.wav"
    }
  ],
  "numbers": [
    "080-1234-5678",
    "080-1234-5623",
    "080-1234-5645",
    "080-1234-5621"
  ]
}
```

### 結果ファイルの変更
```json
{
  "ac_id": 1,
  "user_id": 1,
  "calls": [ // 名称変更
    {
      "number": "080-1234-5678",
      "status": 1,
      "last_label": "ending2",
      "call_time": "18:12:25",
      "keep_time": "00:01:23",
      "answers": [ // 名称変更
        {
          "label": "stage1",
          "faq_id": "11", // Cans側で扱う質問とラベルを紐づけるため
          "dial": "1"
        },
        {
          "label": "stage2",
          "faq_id": "16",
          "dial": "0"
        },
        {
          "label": "ending2",
          "ending_id": "21", // cans - 追加
          "dial": null
        }

      ]
    }
  ]
}
```