# 予約情報ファイル、結果ファイル変更案

### 質問情報ファイルの変更
```json
{
  "ac_id": 223,
  "user_id": 1,
  "date": "2024-01-24",
  "start": "17:00",
  "end": "21:00",
  "greeting": "greeting223.wav",
  "ending": "ending223.wav",
  "faqs": [
    {
      "label": "stage1",
      "faq_id": "1",　// Cans側で扱う質問とラベルを紐づけるため
      "voice": "voice3121.wav",
      "options": {
        "0": "ending1",
        "1": "stage2",
        "2": "stage3"
      }
    }
  ],
  "endings": [ // エンディングの複数分岐に対応するため
    {
      "label": "ending1",
      "ending_id": 12,
      "voice": "voice3121.wav"
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
      "last_label": "stage3",
      "call_time": "18:12:25",
      "keep_time": "00:01:23",
      "answers": [ // 名称変更
        {
          "label": "stage1",
          "faq_id": "1", // Cans側で扱う質問とラベルを紐づけるため
          "dial": "1"
        }
      ]
    }
  ]
}
```