アプリケーション名
=====
    フリマアプリ
<img width="650" src="https://github.com/user-attachments/assets/8c6547c6-a8a5-4d5d-8b61-6fd951022ac8">

概要説明
=====
    1 利用者は商品の購入や出品ができる

    2 管理者を作成し管理者権限でアプリの管理ができる



作成目的
=====
    1 店舗に通わずどこにいても個人で物品の売買ができるように

    2 個人間での使用にすることで売買の際に交渉ができるように



アプリケーションURL
=====
    http://52.69.67.231/

    ゲストユーザーでもアプリケーションの閲覧は可能。
    詳細機能に関してはメールアドレスとパスワードの登録が必要。



機能一覧
=====
    1 会員登録、ログイン機能

    2 商品の販売と購入

    3 検索フォームでのリアルタイム検索

    4 おすすめリスト(新着順)とマイリスト(お気に入り)のリアルタイム切り替え

    5 出品アイテムと購入アイテムのリアルタイム切り替え

    6 商品詳細ページでイイね(星アイコン)を押してマイリストへの追加と取り消し

    7 商品詳細ページでコメントの追加と削除

    8 プロフィールの作成と編集

    9 購入時の支払い方法と配送先の変更

    10 一般ユーザーと管理者での閲覧可能ページの振り分け

    11 管理者権限でユーザーとコメントの削除

    12 管理者ページからユーザーへのお知らせメールの送信

    13 ブレイクポイント768pxでのレスポンシブデザイン化

    14 PHPUnitで主要機能のテスト

    15 商品の出品やProfile画像のアップロード時に
       画像ファイルをS3ストレージに保存できる





詳細内容
=====
    * シーディングで初期データの作成
      (商品、カテゴリー、決済方法、権限の挿入)

    * 管理者(admin)の作成はシーディングで初期データに含まれる
      管理者(admin)としてアクセスする場合
      email: admin@example.com    password: 00000000

    * イメージ画像は設定していないのでシーディング後も画像は表示されず
      グレーの背景のみが表示されるようになっているが、画像をアップロードすると
      その画像がグレーのスペースに挿入される

    * ゲストユーザーが会員情報やプロフィール情報が必要なアクションをしようとすると
      登録が必要なそれぞれのページにリダイレクトする

    * 商品詳細ページでコメントが増えるとページネーションで他のコメントを表示

    * 商品購入時にプロフィールの住所とは別の配送先を指定できる

    * adminユーザーでのログイン時にトップページで管理画面へのボタンが表示される

    * 管理画面での操作
        ユーザーの削除、コメントの削除、利用者へのお知らせメールの送信が可能

    * stripeでの決済
      商品購入時にクレジットカードを選択するとstripeの決済画面にリダイレクト。
        テストモードなので決済時はテスト用のカード情報を入力して決済確認可能。
        - メールアドレス  任意のメールアドレス (例:test@example.com)
        - カード番号  4242 4242 4242 4242
        - 有効期限  任意の未来の月と年 (例: 08/30)
        - CVC: 任意の3桁  (例:123)
        - カード保有者の名前  任意の名前 (例: 山田太郎)

    * PHP Unitで主要機能のテスト
      "php artisan test" コマンドで以下のテストが実行される
        - 検索機能の実行
        - コメントの追加、削除の処理
        - いいねを押してマイリストへの追加と取り消し処理
        - プロフィールの更新
        - 配送先の変更
        - 支払い方法の変更
        - stripeでの決済
        - ユーザーへのお知らせメールの送信



使用技術
=====
    Docker 27.1.1

    php 8.3.9

    Laravel 8.83.27

    Composer 2.7.8

    nginx 1.27.0

    Mysql 8.0.37

    phpMyAdmin 5.2.1

    imagick 7.1.1-36

    Stripe PHP SDK 15.7



テーブル設計
=====
<img width="650" src="https://github.com/user-attachments/assets/1c85ab88-ac67-44dd-9cca-8aaeb75f8c6f">


ER図
=====
<img width="650" src="https://github.com/user-attachments/assets/0e81fd31-8e12-4625-9f0f-9a42843d521a">


dockerビルド
=====
    1 git clone リンク  https://github.com/Okazuma/laravel-mocktest3.git

    2 docker-compose up -d --build

    * MysqlはOSによって起動しない場合があるので、それぞれのPCに合わせてdocker-compose.ymlを編集してください。



Laravelの環境構築
=====
    1 phpコンテナにログイン        $docker-compose exec php bash

    2 パッケージのインストール      $composer-install

    3 .envファイルの作成          cp .env.example .env

    4 アプリケーションキーの生成    $php artisan key:generate

    5 マイグレーション            $php artisan migrate

    6 シーディング               $php artisan db:seed



メールサーバー設定について
=====
    1 使用しているメールサーバーから必要な設定情報を取得

    2 メールサーバーの情報を.envファイルに設定

    3 .envファイルの変更が反映するようlaravelの設定キャッシュのクリア