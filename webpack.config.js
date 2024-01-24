const path = require('path'); 

module.exports = {
  mode: 'development', // 開発モードか本番モードかを設定出来る
  entry: './assets/script/src/index.js', // バンドルの起点となるファイル
  output: {
    path: path.resolve(__dirname, 'assets/script/dist/'), // 出力されるディレクトリの指定
    filename: 'main.js' // 出力されるファイル名の指定
  },
}