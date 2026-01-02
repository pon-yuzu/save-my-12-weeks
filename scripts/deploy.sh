#!/bin/bash
# ===========================================
# デプロイスクリプト
# Save My 12 Weeks - ライフバランス診断アプリ
# ===========================================

set -e

APP_DIR="/var/www/save-my-12-weeks"
APP_NAME="save-my-12-weeks"

# 色の定義
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo "=========================================="
echo "  デプロイ開始: $APP_NAME"
echo "=========================================="

cd $APP_DIR

# 1. 最新のコードを取得
echo -e "${YELLOW}[1/5] 最新のコードを取得中...${NC}"
git fetch origin
git reset --hard origin/main

# 2. 依存関係のインストール
echo -e "${YELLOW}[2/5] 依存関係をインストール中...${NC}"
npm ci --production=false

# 3. 本番ビルド
echo -e "${YELLOW}[3/5] 本番ビルドを実行中...${NC}"
npm run build

# 4. PM2でアプリケーションを再起動
echo -e "${YELLOW}[4/5] アプリケーションを再起動中...${NC}"
if pm2 describe $APP_NAME > /dev/null 2>&1; then
    pm2 reload $APP_NAME
else
    pm2 start ecosystem.config.js
fi

# 5. PM2の状態を保存
echo -e "${YELLOW}[5/5] PM2の状態を保存中...${NC}"
pm2 save

echo ""
echo -e "${GREEN}=========================================="
echo "  デプロイ完了！"
echo "==========================================${NC}"
echo ""
pm2 status
echo ""
echo "ログ確認: pm2 logs $APP_NAME"
echo ""
