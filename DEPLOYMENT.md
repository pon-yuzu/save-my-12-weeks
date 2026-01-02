# Save My 12 Weeks - VPS デプロイ引き継ぎ

## プロジェクト概要

**ライフバランス診断アプリ** - 8つの領域で人生の現在地を見える化するWebアプリ

### 技術スタック
- **フレームワーク**: Next.js 16.1.1 (App Router)
- **UI**: Swiper.js (横スワイプナビゲーション)
- **スタイル**: Tailwind CSS v4
- **画像生成**: html2canvas (結果保存用)
- **フォント**: Noto Serif JP, Cormorant Garamond (Google Fonts)

---

## 主要ファイル構成

```
src/
├── app/
│   ├── globals.css      # グローバルスタイル、アニメーション
│   ├── layout.tsx       # ルートレイアウト、フォント設定
│   └── page.tsx         # エントリーポイント
└── components/
    ├── DiagnosisApp.tsx # メインアプリ (全スライド、ロジック)
    └── JourneyBar.tsx   # 下部アニメーションバー (女性・猫・蝶)
```

---

## 機能一覧

### 診断フロー (全20スライド)
1. イントロ x 2
2. 質問1-3 (絶対評価: 健康、心の平穏、お金)
3. コーチ説明 x 2
4. 相対評価説明
5. 質問4-8 (相対評価: 仕事、自分の時間、暮らし、人間関係、将来)
6. 結果表示 (ライフバランスホイール)
7. 追加質問 (改善したい領域選択)
8. セミナー案内 & シェア

### シェア機能
- **X (Twitter)**: ハッシュタグ付きシェア
- **LINE**: URLシェア
- **Save**: ホイール画像をPNG保存

### アニメーション
- 下部のJourneyBar: 女性・猫・蝶のシルエットが進行に合わせて移動
- パララックス効果の山背景
- プログレスドット

---

## デプロイ手順

### 1. VPSでの準備
```bash
# Node.js 18以上をインストール
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs

# リポジトリをクローン
git clone https://github.com/[username]/save-my-12-weeks.git
cd save-my-12-weeks

# 依存関係インストール
npm install
```

### 2. 本番ビルド
```bash
npm run build
```

### 3. 実行方法

#### Option A: PM2で常駐化 (推奨)
```bash
npm install -g pm2
pm2 start npm --name "save-my-12-weeks" -- start
pm2 save
pm2 startup
```

#### Option B: 直接起動
```bash
npm start
```

デフォルトポート: 3000

### 4. Nginx リバースプロキシ設定例
```nginx
server {
    listen 80;
    server_name yourdomain.com;

    location / {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
}
```

### 5. SSL設定 (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

---

## 環境変数

特に必要なし。全てクライアントサイドで動作。

---

## 注意事項

1. **モバイルファースト設計**: スマホでの利用を想定
2. **PC対応済み**: 768px以上で中央配置
3. **ブラウザバック防止**: overscroll-behavior で対応済み
4. **結果ページ以降**: 戻るスワイプはResultWheelまでに制限

---

## 外部リンク

- セミナー申込フォーム: Google Forms (SeminarCTA内にリンクあり)
- シェア先: X, LINE

---

## 連絡先

開発に関する質問は GitHub Issues へ
