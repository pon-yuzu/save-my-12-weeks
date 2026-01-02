#!/bin/bash
# ===========================================
# ConoHa VPS 初期セットアップスクリプト
# Save My 12 Weeks - ライフバランス診断アプリ
# ===========================================

set -e

echo "=========================================="
echo "  ConoHa VPS 初期セットアップ開始"
echo "=========================================="

# 色の定義
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. システム更新
echo -e "${YELLOW}[1/7] システムを更新中...${NC}"
sudo apt update && sudo apt upgrade -y

# 2. 必要なパッケージのインストール
echo -e "${YELLOW}[2/7] 必要なパッケージをインストール中...${NC}"
sudo apt install -y curl git nginx

# 3. Node.js 20.x のインストール
echo -e "${YELLOW}[3/7] Node.js 20.x をインストール中...${NC}"
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
    sudo apt-get install -y nodejs
fi
echo "Node.js version: $(node -v)"
echo "npm version: $(npm -v)"

# 4. PM2 のインストール
echo -e "${YELLOW}[4/7] PM2 をインストール中...${NC}"
sudo npm install -g pm2

# 5. アプリケーションディレクトリの作成
echo -e "${YELLOW}[5/7] アプリケーションディレクトリを作成中...${NC}"
sudo mkdir -p /var/www/save-my-12-weeks
sudo chown -R $USER:$USER /var/www/save-my-12-weeks

# 6. PM2 ログディレクトリの作成
echo -e "${YELLOW}[6/7] ログディレクトリを作成中...${NC}"
sudo mkdir -p /var/log/pm2
sudo chown -R $USER:$USER /var/log/pm2

# 7. ファイアウォール設定
echo -e "${YELLOW}[7/7] ファイアウォールを設定中...${NC}"
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw --force enable

echo ""
echo -e "${GREEN}=========================================="
echo "  初期セットアップ完了！"
echo "==========================================${NC}"
echo ""
echo "次のステップ:"
echo "  1. リポジトリをクローン: git clone <repo-url> /var/www/save-my-12-weeks"
echo "  2. デプロイ実行: cd /var/www/save-my-12-weeks && ./scripts/deploy.sh"
echo ""
