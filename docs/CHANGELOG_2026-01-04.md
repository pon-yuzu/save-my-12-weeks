# ブランチ引き継ぎ書

**日付:** 2026-01-04
**ブランチ:** `claude/review-laravel-newsletter-6SCFx`
**作成者:** Claude Code (pon-yuzu依頼)

---

## 概要

このドキュメントは、メインブランチに対して追加・修正した機能の引き継ぎ書です。
このブランチをマージする際の参考にしてください。

---

## 追加された機能一覧

### 1. メール開封率トラッキング機能

**コミット:** `290a0601`

透明1x1ピクセル画像を使用してメール開封を追跡する機能。

**変更ファイル:**
- `backend/app/Http/Controllers/MailTrackingController.php` - 新規作成
- `backend/app/Models/MailLog.php` - `opened_at`カラム追加
- `backend/database/migrations/*_add_opened_at_to_mail_logs.php`
- `backend/routes/web.php` - トラッキングルート追加

**使い方:**
メールテンプレートに以下を含めると開封追跡が有効になる:
```html
<img src="{{ route('mail.pixel', ['id' => $mailLog->id]) }}" width="1" height="1" />
```

---

### 2. メールテンプレートのパーソナライゼーション機能

**コミット:** `896d3d12`

メール本文で変数置換ができる機能。

**対応変数:**
| 変数 | 説明 |
|------|------|
| `{name}` | 登録者名 |
| `{email}` | メールアドレス |
| `{day}` | 講座の日数 |

**変更ファイル:**
- `backend/app/Services/MailPersonalizationService.php` - 新規作成
- `backend/app/Mail/CourseEmail.php` - パーソナライゼーション適用

---

### 3. 配信時間選択機能

**コミット:** `a3d9d443`, `944a069a`

登録者が好みの配信時間を選択できる機能。配信時間選択はDay 0メールとして分離。

**選択肢:**
- 朝 (8:00)
- 昼 (12:00)
- 夜 (20:00)

**変更ファイル:**
- `backend/app/Models/MailSubscription.php` - `preferred_time`カラム追加
- `backend/resources/views/settings/time.blade.php` - 時間選択UI
- `backend/app/Http/Controllers/SettingsController.php`
- `backend/database/migrations/*_add_preferred_time.php`

---

### 4. 診断結果ホイール画像のDay 1メール添付

**コミット:** `2682ff3f`

診断結果のホイールチャート画像をDay 1メールに添付する機能。

**変更ファイル:**
- `backend/app/Jobs/SendCourseEmail.php` - 画像添付ロジック
- `backend/app/Models/DiagnosisResult.php` - `wheel_image_path`カラム

---

### 5. ニックネーム収集機能

**コミット:** `5be682c0`, `210bcc8f`

診断開始時にニックネームを入力してもらう機能（必須）。

**変更ファイル:**
- `backend/resources/js/components/DiagnosisApp.tsx` - Intro1にニックネーム入力追加
- `backend/app/Models/DiagnosisResult.php` - `nickname`カラム

---

### 6. ブロードキャスト（一斉配信）機能

**コミット:** `dfdc9631`

管理画面から任意のタイミングでメールを一斉配信できる機能。

**機能:**
- 全員/フィルター条件指定での配信
- 即時配信 or 予約配信
- 配信状況・開封率の確認

**新規ファイル:**
- `backend/app/Http/Controllers/Admin/BroadcastController.php`
- `backend/app/Models/Broadcast.php`
- `backend/app/Models/BroadcastRecipient.php`
- `backend/app/Jobs/SendBroadcastEmail.php`
- `backend/app/Console/Commands/SendScheduledBroadcasts.php`
- `backend/resources/views/admin/broadcasts/*.blade.php`
- `backend/database/migrations/*_create_broadcasts_table.php`
- `backend/database/migrations/*_create_broadcast_recipients_table.php`

**管理画面でのアクセス:** `/admin/broadcasts`

---

### 7. メールフッター更新

**コミット:** `d6c28510`, `ddeb60cd`

- 配信専用の案内文を追加
- 返信OKに変更

---

### 8. LINE URL・YouTube URL修正

**コミット:** `db5901d6`

各種リンクを正しいURLに修正。

---

## デザイン修正

### 9. 診断アプリのデザインディテール反映

**コミット:** `0451d858`

`local-diagnosis-app-backup`ブランチのデザインを反映。

**変更内容:**
- ボタンに脈打ちアニメーション追加（押せるタイミングで注意を引く）
- フレーム・ボタン・入力欄に角丸（border-radius: 8px）適用
- 結果画面の動線変更:
  - スワイプではなく「12週間あったら、どこ変える？→」ボタンで次へ
  - 追加質問画面に「← 結果を見返す」戻るボタン追加
- Intro1画面に名前入力を統合（「始める」ボタン付き）

**変更ファイル:**
- `backend/resources/css/app.css` - アニメーション・角丸スタイル追加
- `backend/resources/js/components/DiagnosisApp.tsx` - フロー・UI変更

---

### 10. 全フォームに角丸適用

**コミット:** `c270652a`

セミナー申込フォーム、設定フォーム、配信停止フォームなど全てのユーザー向けフォームに統一した角丸スタイルを適用。

**変更ファイル:**
- `backend/resources/views/layouts/base.blade.php` - 共通フォームスタイル
- `backend/resources/views/settings/time.blade.php` - 時間選択カードスタイル

---

## データベースマイグレーション

このブランチには以下のマイグレーションが含まれています。マージ後に実行してください:

```bash
cd backend
php artisan migrate
```

**新規テーブル:**
- `broadcasts` - 一斉配信管理
- `broadcast_recipients` - 配信先・開封状況

**カラム追加:**
- `mail_logs.opened_at` - 開封日時
- `mail_subscriptions.preferred_time` - 希望配信時間
- `diagnosis_results.nickname` - ニックネーム
- `diagnosis_results.wheel_image_path` - ホイール画像パス

---

## スケジュールタスク

cronが設定されている前提で、以下のコマンドが毎分実行されます:

```php
// backend/routes/console.php

// 30日講座の配信
Schedule::command('mail:send-daily-course')
    ->dailyAt('08:00')
    ->timezone('Asia/Tokyo');

// 予約ブロードキャストの配信
Schedule::command('broadcasts:send-scheduled')
    ->everyMinute()
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping();
```

---

## 管理画面の新機能

| メニュー | URL | 説明 |
|---------|-----|------|
| ブロードキャスト | `/admin/broadcasts` | 一斉配信の作成・管理 |
| メールテンプレート | `/admin/mail-templates` | 30日講座テンプレート編集 |

---

## 確認用ログイン情報

```
URL:      /admin/login
Email:    admin@example.com
Password: password
```

※ シーダー未実行の場合: `php artisan db:seed --class=AdminSeeder`

---

## マージ手順

```bash
# 1. ブランチを取得
git fetch origin claude/review-laravel-newsletter-6SCFx

# 2. mainにマージ
git checkout main
git merge origin/claude/review-laravel-newsletter-6SCFx

# 3. マイグレーション実行
cd backend
php artisan migrate

# 4. キャッシュクリア
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 5. ビルド
npm run build
```

---

## 注意事項

1. **ニックネーム必須化**: 診断開始時にニックネーム入力が必須になりました。既存の診断結果にはnullが入っている可能性があります。

2. **配信時間のデフォルト**: 既存の登録者は`preferred_time`がnullの場合、朝8:00で配信されます。

3. **ブロードキャスト機能**: キューワーカーを使用する場合は`QUEUE_CONNECTION=database`に設定し、`php artisan queue:work`を実行してください。

---

**質問があればこのドキュメントと一緒にコードを確認してください。**
