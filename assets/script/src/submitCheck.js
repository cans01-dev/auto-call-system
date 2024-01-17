function check() {
  if (window.confirm("送信してよろしいですか？")) {
    // 確認ダイアログを表示

    return true; // 「OK」時は送信を実行
  } else {
    // 「キャンセル」時の処理

    window.alert("キャンセルされました"); // 警告ダイアログを表示
    return false; // 送信を中止
  }
}
