#!/bin/bash
# ===========================================
# SSL証明書セットアップスクリプト
# Let's Encrypt + DuckDNS
# ===========================================

set -e

# 色の定義
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# 設定 (ここを編集してください)
DUCKDNS_DOMAIN=""  # 例: myapp (myapp.duckdns.org の場合)
EMAIL=""           # Let's Encrypt通知用メールアドレス

# 引数からドメインとメールを取得
if [ -n "$1" ]; then
    DUCKDNS_DOMAIN="$1"
fi
if [ -n "$2" ]; then
    EMAIL="$2"
fi

# バリデーション
if [ -z "$DUCKDNS_DOMAIN" ]; then
    echo -e "${RED}エラー: DuckDNSドメインが指定されていません${NC}"
    echo "使用法: $0 <duckdns-subdomain> <email>"
    echo "例: $0 myapp your@email.com"
    exit 1
fi

if [ -z "$EMAIL" ]; then
    echo -e "${RED}エラー: メールアドレスが指定されていません${NC}"
    echo "使用法: $0 <duckdns-subdomain> <email>"
    echo "例: $0 myapp your@email.com"
    exit 1
fi

FULL_DOMAIN="${DUCKDNS_DOMAIN}.duckdns.org"

echo "=========================================="
echo "  SSL証明書セットアップ"
echo "  ドメイン: $FULL_DOMAIN"
echo "=========================================="

# 1. Certbotのインストール
echo -e "${YELLOW}[1/4] Certbotをインストール中...${NC}"
sudo apt install -y certbot python3-certbot-nginx

# 2. Nginx設定ファイルを配置 (初期設定)
echo -e "${YELLOW}[2/4] Nginx設定を配置中...${NC}"
NGINX_CONF="/etc/nginx/sites-available/save-my-12-weeks"

# 初期設定ファイルをコピー
sudo cp /var/www/save-my-12-weeks/scripts/nginx/save-my-12-weeks-initial.conf $NGINX_CONF

# ドメイン名を置換
sudo sed -i "s/YOUR_DOMAIN/$DUCKDNS_DOMAIN/g" $NGINX_CONF

# シンボリックリンク作成
sudo ln -sf $NGINX_CONF /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

# Nginx設定テスト
sudo nginx -t
sudo systemctl reload nginx

# 3. SSL証明書を取得
echo -e "${YELLOW}[3/4] SSL証明書を取得中...${NC}"
sudo certbot --nginx -d $FULL_DOMAIN --non-interactive --agree-tos --email $EMAIL

# 4. 最終Nginx設定を適用
echo -e "${YELLOW}[4/4] 本番用Nginx設定を適用中...${NC}"
sudo cp /var/www/save-my-12-weeks/scripts/nginx/save-my-12-weeks.conf $NGINX_CONF
sudo sed -i "s/YOUR_DOMAIN/$DUCKDNS_DOMAIN/g" $NGINX_CONF
sudo nginx -t
sudo systemctl reload nginx

# 自動更新の確認
echo -e "${YELLOW}証明書の自動更新を確認中...${NC}"
sudo certbot renew --dry-run

echo ""
echo -e "${GREEN}=========================================="
echo "  SSL証明書のセットアップ完了！"
echo "==========================================${NC}"
echo ""
echo "サイトURL: https://$FULL_DOMAIN"
echo ""
echo "証明書は90日で期限切れになりますが、"
echo "certbotが自動的に更新します。"
echo ""
