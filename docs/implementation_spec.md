# 実装指示書（Laravel / Save My 12 Weeks）

## 0) 前提

- 既存LP/診断の見た目は維持したまま、Laravelで機能実装。
- 既に移植済みのUIは変更しない。

---

## 1) メルマガシステム

### ユーザー登録（診断後）
- 診断完了後にメール登録導線を出し、メールを取得。
- `mail_subscriptions` テーブルを新規作成。
  - カラム: `id`, `user_id`(nullable), `email`, `token`(unique), `is_active`(bool), `subscribed_at`, `unsubscribed_at`, `created_at`

### 30日講座の自動配信
- 1日1通のシーケンス配信。Laravel Mail + Queue + Schedulerで日次送信。
- `mail_templates`（day別の件名/本文）を作成。
- `mail_deliveries`（送信履歴）を作成。

### 配信停止フロー（必須）
- 仕様: `unsubscribe_system_spec.md` を完全準拠。
- ルート:
  - `GET /unsubscribe/{token}` 停止ページ（自由記述必須）
  - `POST /unsubscribe/{token}` 停止処理
  - `GET /unsubscribe/complete` 完了ページ
- `unsubscribe_reasons` テーブル作成
  - カラム: `id`, `user_id`, `reason`, `unsubscribed_at`

---

## 2) セミナー申込フォーム

### フォーム項目

**必須:**
- お名前
- メールアドレス
- 年代: 20代 / 30代 / 40代 / 50代以上 / 答えたくない
- ご職業: 会社員 / 自営業・フリーランス / パート・アルバイト / 主婦・主夫 / 学生 / 答えたくない / その他（自由記述）
- 流入経路: Sayakaから直接 / 友人・知人の紹介 / Instagram / X（Twitter） / Threads / Facebook / note / その他（自由記述）
- 予定キャンセル経験: ある / ない / 覚えていない
- 12週間あったら何をしたいか

**条件付き必須:**
- キャンセル理由（予定キャンセル経験=「ある」の場合）

**任意:**
- その他ご質問やご要望

### DB
- `seminar_applications`
  - カラム: `id`, `name`, `email`, `age_group`, `occupation`, `occupation_other`(nullable), `referral_source`, `referral_other`(nullable), `has_canceled_plans`, `cancel_reason`(nullable), `twelve_weeks_dream`, `questions`(nullable), `created_at`

---

## 3) セミナー設定（管理画面で可変）

管理画面に「セミナー設定」を追加し、以下を編集可能にする:
- 日程（表示用文字列）
- Zoom参加リンク
- LINEオープンチャットリンク
- 参加コード
- 案内テキスト（任意）

これらは確認メールと完了ページに反映。

---

## 4) 申込後の完了ページ（新規作成）

遷移先を用意（送信後に表示）

### 文面（確定）

**見出し:** お申込みありがとうございます

**本文:**
```
セミナーへのお申込みを受け付けました。

日程：{管理画面で設定した日程}
開催：オンライン（Zoom）

詳細はご登録のメールアドレスにお送りしました。
```

**次のステップ:**
```
▼ 次のステップ

オープンチャットに参加してください

参加リンク：{管理画面で設定したLINEオープンチャット}
参加コード：{管理画面で設定した参加コード}
```

**補足:**
```
※ セミナーはZoomで開催します
※ 品質改善のため録画させていただく場合があります（公開はしません）
```

---

## 5) 申込者向け確認メール（HTML）

**件名:** 【Save My 12 Weeks】お申込みありがとうございます！

**本文:**

```
セミナーへのお申込みありがとうございます！

さっきの質問の答え、何だと思いましたか？
「何かを始めるのに一番いいタイミング」

私ははじめてこの質問をされた時、「…2月？」と答えて、師匠に呆れられました。
「今」とか、「やりたいとき」って答える人の方が多いかもしれませんね。

答えは「昨日」です。
えーずる！って思いました？私もです。
でも、真理だと思います。

もしも過去に戻れるなら。
もっと早くはじめていたら。
もっと若い頃に知っていたら。

そんな風に思うことはたくさんあります。
だから、「昨日始めておけた」が、あったかもしれないベストタイミングなんです。

...でも昨日には戻れない。
だから、今日は次に一番いいタイミング。

このセミナーに申し込んだあなたは、もう動き出してます。
踏み出してくれて、ありがとう。

━━━━━━━━━━━━━━━
日程：{管理画面で設定した日程}
開催：オンライン（Zoom）
━━━━━━━━━━━━━━━

▼ 次のステップ
オープンチャットに参加してください

参加リンク
{管理画面で設定したLINEオープンチャットリンク}

参加コード：{管理画面で設定した参加コード}

▼ オープンチャットに参加すると...
・今の自分を8つの視点で見える化する「ライフバランス診断」が受けられます
・セミナーのアーカイブ動画が見られます
・主催者のSayakaに質問したり、他の参加者と交流ができます

まずはオープンチャットへ！
お会いできるのを楽しみにしています。

※ セミナーはZoomで開催します
※ 品質改善のため録画させていただく場合があります（公開はしません）

━━━━━━━━━━━━━━━
Save My 12 Weeks｜私の12週間を取り戻せ
主催：Sayaka
━━━━━━━━━━━━━━━
```

---

## 6) 管理者通知（申込時）

- **通知方法:** メールのみ（LINE Notifyは後から追加可能な設計に）
- **宛先:** ponglish.yukarizu@gmail.com
- **送信タイミング:** 申込完了時
- **内容:** 申込項目の要約
  - 氏名 / メール / 年代 / 職業 / 流入 / キャンセル有無 / 理由 / 12週間 / その他

---

## 7) 管理画面（Blade）

- ダッシュボード
- 登録者管理（メルマガ登録）
- 診断結果閲覧
- メールテンプレート作成（30日講座）
- セミナー申込一覧
- セミナー設定
- 配信停止理由一覧（unsubscribe_reasons）

---

## DB設計まとめ

### users（既存を拡張）
```sql
- id
- email (unique)
- name
- unsubscribe_token (unique, 推測不可)
- created_at
- updated_at
```

### mail_subscriptions
```sql
- id
- user_id (nullable, FK)
- email
- token (unique)
- is_active (default: true)
- current_day (1-30, 講座進捗)
- subscribed_at
- unsubscribed_at
- created_at
```

### mail_templates
```sql
- id
- day_number (1-30)
- subject
- body (HTML)
- is_active
- created_at
- updated_at
```

### mail_deliveries
```sql
- id
- subscription_id (FK)
- template_id (FK)
- sent_at
- status (sent/failed)
- created_at
```

### unsubscribe_reasons
```sql
- id
- subscription_id (FK)
- reason (text)
- unsubscribed_at
```

### diagnosis_results
```sql
- id
- subscription_id (FK, nullable)
- health_score (1-10)
- mind_score
- money_score
- career_score
- time_score
- living_score
- relationships_score
- vision_score
- selected_areas (JSON)
- free_text
- created_at
```

### seminar_applications
```sql
- id
- name
- email
- age_group (20s/30s/40s/50s_plus/prefer_not)
- occupation
- occupation_other (nullable)
- referral_source
- referral_other (nullable)
- has_canceled_plans (yes/no/dont_remember)
- cancel_reason (nullable)
- twelve_weeks_dream
- questions (nullable)
- created_at
```

### seminar_settings
```sql
- id
- key (unique)
- value (text)
- updated_at
```

### admins
```sql
- id
- email
- password (hashed)
- name
- created_at
```

---

## 通知設計（拡張可能）

```php
// app/Services/NotificationService.php
interface NotificationChannel {
    public function send(string $message, array $data): void;
}

class EmailNotification implements NotificationChannel { ... }
class LineNotification implements NotificationChannel { ... } // 後から追加
```

---

## 実装優先順位

1. DB マイグレーション
2. セミナー申込フォーム + 通知
3. 管理画面（認証 + 基本CRUD）
4. メルマガ登録フロー
5. 配信停止システム
6. 30日講座配信（Queue/Scheduler）
