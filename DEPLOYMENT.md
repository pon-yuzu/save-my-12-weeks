# Save My 12 Weeks - ConoHa VPS デプロイガイド

## プロジェクト概要

**ライフバランス診断アプリ** - 8つの領域で人生の現在地を見える化するWebアプリ

### 技術スタック
- **フレームワーク**: Next.js 16.1.1 (App Router)
- **UI**: Swiper.js (横スワイプナビゲーション)
- **スタイル**: Tailwind CSS v4
- **画像生成**: html2canvas (結果保存用)
- **フォント**: Noto Serif JP, Cormorant Garamond (Google Fonts)

---

## ファイル構成

```
save-my-12-weeks/
├── src/
│   ├── app/
│   │   ├── globals.css      # グローバルスタイル
│   │   ├── layout.tsx       # ルートレイアウト
│   │   └── page.tsx         # エントリーポイント
│   └── components/
│       ├── DiagnosisApp.tsx # メインアプリ
│       └── JourneyBar.tsx   # 下部アニメーションバー
├── scripts/
│   ├── setup-vps.sh         # VPS初期セットアップ
│   ├── deploy.sh            # デプロイスクリプト
│   ├── setup-ssl.sh         # SSL証明書セットアップ
│   ├── update-duckdns.sh    # DuckDNS IP更新
│   └── nginx/
│       ├── save-my-12-weeks.conf         # Nginx設定 (SSL有効)
│       └── save-my-12-weeks-initial.conf # Nginx設定 (SSL取得前)
├── ecosystem.config.js      # PM2設定
└── package.json
```

---

## デプロイ手順 (ConoHa VPS + DuckDNS)

### 前提条件
- ConoHa VPS (Ubuntu 22.04 推奨)
- DuckDNSアカウント (無料: https://www.duckdns.org/)

---

### Step 1: DuckDNSでドメインを取得

1. https://www.duckdns.org/ にアクセス
2. GitHubやGoogleでログイン
3. サブドメインを作成 (例: `savemy12weeks`)
4. **Token** をメモしておく (後で使用)

作成後のドメイン例: `savemy12weeks.duckdns.org`

---

### Step 2: ConoHa VPSの準備

#### 2.1 VPSを作成
1. ConoHaコントロールパネルにログイン
2. VPS → サーバー追加
3. 推奨スペック:
   - **OS**: Ubuntu 22.04
   - **プラン**: 1GB以上 (512MBでも動作可)
4. SSHキーを設定 (推奨)

#### 2.2 VPSのIPアドレスをDuckDNSに登録
```bash
# VPSにSSH接続
ssh root@<VPSのIPアドレス>

# DuckDNSにIPを登録
curl "https://www.duckdns.org/update?domains=YOUR_SUBDOMAIN&token=YOUR_TOKEN&ip="
# → "OK" と表示されれば成功
```

---

### Step 3: VPS初期セットアップ

```bash
# VPSにSSH接続
ssh root@<VPSのIPアドレス>

# 作業用ユーザーを作成 (推奨)
adduser deploy
usermod -aG sudo deploy
su - deploy

# リポジトリをクローン
git clone https://github.com/YOUR_USERNAME/save-my-12-weeks.git /var/www/save-my-12-weeks
cd /var/www/save-my-12-weeks

# スクリプトに実行権限を付与
chmod +x scripts/*.sh

# VPS初期セットアップを実行
./scripts/setup-vps.sh
```

このスクリプトが実行する内容:
- システム更新
- Node.js 20.x インストール
- PM2 インストール
- Nginx インストール
- ファイアウォール設定 (22, 80, 443ポート開放)

---

### Step 4: アプリケーションのデプロイ

```bash
cd /var/www/save-my-12-weeks

# 依存関係インストール & ビルド & PM2起動
./scripts/deploy.sh
```

確認:
```bash
# PM2の状態確認
pm2 status

# ログ確認
pm2 logs save-my-12-weeks
```

この時点で http://YOUR_SUBDOMAIN.duckdns.org でアクセス可能になります。

---

### Step 5: SSL証明書の設定 (HTTPS化)

```bash
cd /var/www/save-my-12-weeks

# SSL証明書を取得
./scripts/setup-ssl.sh YOUR_SUBDOMAIN your@email.com

# 例:
# ./scripts/setup-ssl.sh savemy12weeks user@example.com
```

成功すると https://YOUR_SUBDOMAIN.duckdns.org でアクセス可能になります。

---

### Step 6: PM2の自動起動設定

```bash
# システム起動時にPM2を自動起動
pm2 startup

# 表示されたコマンドをコピーして実行
# 例: sudo env PATH=$PATH:/usr/bin pm2 startup systemd -u deploy --hp /home/deploy

# 現在の状態を保存
pm2 save
```

---

## 運用コマンド

### アプリケーション管理

```bash
# 状態確認
pm2 status

# ログ確認
pm2 logs save-my-12-weeks

# 再起動
pm2 restart save-my-12-weeks

# 停止
pm2 stop save-my-12-weeks

# 削除
pm2 delete save-my-12-weeks
```

### 更新デプロイ

```bash
cd /var/www/save-my-12-weeks
./scripts/deploy.sh
```

### Nginx管理

```bash
# 設定テスト
sudo nginx -t

# 再読み込み
sudo systemctl reload nginx

# ステータス確認
sudo systemctl status nginx
```

---

## トラブルシューティング

### ビルドエラー: Google Fontsの取得失敗

環境によってはGoogle Fontsへのアクセスが制限される場合があります。
VPS上では通常問題ありません。

### ポート3000が使用中

```bash
# 使用中のプロセスを確認
lsof -i :3000

# PM2で管理されているか確認
pm2 list
```

### SSL証明書の更新

Let's Encryptの証明書は90日で期限切れになりますが、
certbotが自動的に更新します。手動で更新する場合:

```bash
sudo certbot renew
```

### DuckDNS IPの更新

VPSのIPが変わった場合:
```bash
./scripts/update-duckdns.sh YOUR_SUBDOMAIN YOUR_TOKEN
```

---

## セキュリティ推奨事項

1. **SSH鍵認証を使用** (パスワード認証を無効化)
2. **rootログインを無効化**
3. **fail2banをインストール**
4. **定期的なシステム更新**

```bash
# fail2banのインストール
sudo apt install fail2ban
sudo systemctl enable fail2ban
```

---

## 連絡先

開発に関する質問は GitHub Issues へ
