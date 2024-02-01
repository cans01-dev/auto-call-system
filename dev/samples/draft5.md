# 予約情報ファイル結果ファイル変更案

### 目的
Cans側で扱うIDと結果ファイルの一致

### draft5 変更点
###### 予約情報ファイル
- label削除
- answers大幅変更
###### 結果ファイル
- last_label削除
- answersのendingを削除
- keep_time, call_time 名称変更

#### 疑問点

### 質問情報ファイルの変更
```json
{
  "id": 223,
  "user_id": 1,
  "date": "2024-01-24",
  "start": "17:00",
  "end": "21:00",
  "greeting": "greeting223.wav",
  "faqs": [
    {
      "id": "11",
      "voice": "voice3121.wav",
      "options": [ // 変更
        {
          "id": 312,
          "next_type": "ending",
          "next_id": 1, // -> endings[1]に遷移
        },
        {
          "id": 312,
          "next_type": "faq",
          "next_id": 2,
        },
        {
          "id": 312,
          "next_type": "faq",
          "next_id": 3, // -> faqs[3]に遷移
        }
      ]
    },
  ],
  "endings": [
    {
      "id": 14, // ending_id -> id
      "voice": "voiceend1.wav"
      // "label" 削除
    },
  ],
  "numbers": [
    "080-1234-5678",
  ]
}
```

### 結果ファイルの変更
```json
{
  "id": 1, // ac_id -> id
  "user_id": 1,
  "calls": [
    {
      "number": "080-1234-5678",
      "status": 1,
      // "last_label" 削除
      "duration": "00:01:23", // keep_time -> duration
      "time": "18:12:25", // call_time -> time
      "answers": {
        "id": 12, // faq_id
        "option_id": 32,
        // "label" 削除
        // "dial" 削除
      }
    },
  ]
}
```