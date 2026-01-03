# Save My 12 Weeks - デプロイ・設定引き継ぎ書

## 概要

このドキュメントは、ConoHa VPSへのデプロイとメール配信設定のための引き継ぎ書です。
Claude Codeで読み取って設定作業をサポートできるよう記載しています。

---

## 1. 現在の実装状況

### 完了している機能

| 機能 | 状態 | 備考 |
|------|------|------|
| ライフバランス診断（フロントエンド） | 完了 | React + Swiper |
| セミナー申込フォーム | 完了 | バリデーション付き |
| 管理画面 | 完了 | 認証、ダッシュボード、各種管理 |
| メール送信機能（コード） | 完了 | 設定が必要 |
| 30日講座自動配信（コード） | 完了 | cron設定が必要 |
| 配信停止システム | 完了 | - |

### 未設定（本番環境で必要）

- [ ] メールサーバー（SMTP）設定
- [ ] cronジョブ設定（30日講座の自動配信用）
- [ ] 本番用データベース設定（MySQL推奨）
- [ ] APP_KEY再生成
- [ ] ドメイン・SSL設定

---

## 2. メール設定（SMTP）

### 必要な情報

クライアントから以下の情報を取得してください：

```
SMTPホスト:     ___________________________
SMTPポート:     587 (TLS) または 465 (SSL)
ユーザー名:     ___________________________（通常はメールアドレス全体）
パスワード:     ___________________________
送信元アドレス: ___________________________
暗号化方式:     tls または ssl
```

### 設定ファイル

`backend/.env` に以下を設定：

```env
MAIL_MAILER=smtp
MAIL_HOST=（SMTPホスト）
MAIL_PORT=587
MAIL_USERNAME=（ユーザー名）
MAIL_PASSWORD=（パスワード）
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=（送信元アドレス）
MAIL_FROM_NAME="Save My 12 Weeks"
```

### 設定後の確認コマンド

```bash
cd /path/to/backend
php artisan tinker
```

tinker内で：
```php
Mail::raw('テスト', function($m) { $m->to('test@example.com')->subject('Test'); });
```

---

## 3. cronジョブ設定（30日講座自動配信）

### 目的

毎朝8:00 JSTに、登録者へ30日講座メールを自動配信する。

### 設定方法

1. サーバーにSSH接続
2. cronを編集：
   ```bash
   crontab -e
   ```
3. 以下を追加：
   ```
   * * * * * cd /var/www/save-my-12-weeks/backend && php artisan schedule:run >> /dev/null 2>&1
   ```

### 手動実行（テスト用）

```bash
php artisan mail:send-daily-course
```

---

## 4. 本番環境の.env設定例

```env
APP_NAME="Save My 12 Weeks"
APP_ENV=production
APP_KEY=（php artisan key:generate で生成）
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# データベース（MySQL推奨）
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=savemy12weeks
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# セッション・キャッシュ
CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# メール（クライアントから情報取得後に設定）
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 5. デプロイ手順

### 5.1 ファイルをサーバーにアップロード

```bash
# Git cloneの場合
git clone https://github.com/pon-yuzu/save-my-12-weeks.git
cd save-my-12-weeks/backend
```

### 5.2 依存関係インストール

```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 5.3 環境設定

```bash
cp .env.example .env
# .envを編集（上記の設定例を参考に）
php artisan key:generate
```

### 5.4 データベース設定

```bash
# MySQLでデータベース作成後
php artisan migrate
php artisan db:seed
```

### 5.5 パーミッション設定

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5.6 キャッシュ最適化

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 6. 管理画面ログイン情報

初期設定（シーダーで作成）：

```
URL:      https://your-domain.com/admin/login
Email:    admin@savemy12weeks.com
Password: password123
```

**本番環境では必ずパスワードを変更してください。**

パスワード変更方法：
```bash
php artisan tinker
```
```php
$admin = App\Models\Admin::first();
$admin->password = bcrypt('新しいパスワード');
$admin->save();
```

---

## 7. トラブルシューティング

### メールが送信されない

1. `.env`の設定を確認
2. ログを確認：`tail -f storage/logs/laravel.log`
3. SMTPポートがファイアウォールで開いているか確認

### 500エラーが出る

1. `storage/logs/laravel.log` を確認
2. パーミッションを確認：`chmod -R 775 storage`
3. キャッシュクリア：`php artisan cache:clear && php artisan config:clear`

### 30日講座が配信されない

1. cronが動いているか確認：`crontab -l`
2. 手動実行でテスト：`php artisan mail:send-daily-course`
3. `mail_subscriptions`テーブルに`is_active=1`のレコードがあるか確認

---

## 8. ファイル構成

```
save-my-12-weeks/
├── frontend/          # Next.js（開発用・参考）
├── backend/           # Laravel（本番環境）
│   ├── app/
│   │   ├── Console/Commands/SendDailyCourseEmails.php  # 30日講座配信
│   │   ├── Http/Controllers/Admin/                      # 管理画面
│   │   ├── Mail/                                        # メールクラス
│   │   └── Models/                                      # Eloquentモデル
│   ├── database/migrations/                             # DBスキーマ
│   ├── resources/views/                                 # Bladeテンプレート
│   └── routes/
│       ├── web.php                                      # 公開ルート
│       ├── admin.php                                    # 管理画面ルート
│       └── api.php                                      # APIルート
└── docs/
    ├── implementation_spec.md                           # 実装仕様書
    └── deployment_handover.md                           # この文書
```

---

## 9. 連絡事項

- 実装に関する質問はこのリポジトリのコードを参照
- 詳細な実装仕様は `docs/implementation_spec.md` を参照
- Claude Codeでこのファイルを読み込めば設定作業をサポート可能

---

**作成日:** 2026-01-03
**作成者:** Claude Code
