# 環境構築方法
0. `git clone ~~~`
1. `docker compose build --no-cache` (ビルドする)
2. `docker compose up -d` (コンテナをたてる)
3. `docker compose exec app sh` (appコンテナに入る)
4. `composer install` (src配下にLaravel10をインストール)
5. .env.exampleから.envを作成する(src配下)
6. `src > .env` DBに関する内容を以下のように書き換える
    ```
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=website
    DB_USERNAME=posse
    DB_PASSWORD=password
    ```
7. `src > .env` openAIのAPIキーを設定する
    ```
    OPENAI_API_KEY=ここにOpenAIのAPIキーを入力する
    ```
8. `php artisan migrate:fresh --seed`
9. `src/package.json` の内容のscripts部分を以下のように変更する
    - 変更点としてはdevの`"vite"`のみだったのが `"vite --host"` というhostオプションを付け足しています
    ```json
        "scripts": {
            "dev": "vite --host",
            "build": "vite build"
    },
    ```
10. nodeコンテナに入っていることを確認
   - (入っていなければ、`docker compose exec node sh`)
11. `npm install`
12. `npm run dev`
13. ブラウザで `http://localhost` にアクセスし、ログインページに遷移することを確認

# if. 画面右側に緑色の
`Your app key is missing
Generate app key
Generate your application encryption key using php artisan key:generate.
Generate your application encryption key using `php artisan key:generate`.
Laravel installation`

が表示される場合
`docker compose exec app sh`
`php artisan key:generate` 

## tailwind反映されない場合
1. hotファイル削除
2. `npm run build`
3. `npm run dev`
