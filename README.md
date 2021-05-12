# posts_app

##ディレクトリ構成

app
 - controllers
   - 画面とModelの中間、画面に変数を渡したり、Modelの処理を呼び出したり
 - helpers
   - ModelやControllerに書ききれない処理を切り出し
 - models
   - dbとの接続や処理、それぞれのテーブルに対応
 - views
   - 各テンプレート、スタイルシートなどを保持

config
 - mysql
   - mysqlの設定ファイル群
 - php
   - phpの設定ファイル群

##動作確認

```
   $ docker compose build --no-cache
   $ docker-compose up -d
```

http://localhost:8080/
へ接続。



