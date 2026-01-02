#!/bin/bash
# ===========================================
# DuckDNS IP更新スクリプト
# ConoHa VPSのIPをDuckDNSに登録
# ===========================================

# 設定 (ここを編集してください)
DUCKDNS_DOMAIN=""  # 例: myapp
DUCKDNS_TOKEN=""   # DuckDNSのトークン

# 引数から取得
if [ -n "$1" ]; then
    DUCKDNS_DOMAIN="$1"
fi
if [ -n "$2" ]; then
    DUCKDNS_TOKEN="$2"
fi

# バリデーション
if [ -z "$DUCKDNS_DOMAIN" ] || [ -z "$DUCKDNS_TOKEN" ]; then
    echo "エラー: DuckDNSドメインとトークンが必要です"
    echo "使用法: $0 <duckdns-subdomain> <duckdns-token>"
    echo "例: $0 myapp xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
    exit 1
fi

# DuckDNS APIを呼び出してIPを更新
RESULT=$(curl -s "https://www.duckdns.org/update?domains=${DUCKDNS_DOMAIN}&token=${DUCKDNS_TOKEN}&ip=")

if [ "$RESULT" = "OK" ]; then
    echo "DuckDNS更新成功: ${DUCKDNS_DOMAIN}.duckdns.org"
else
    echo "DuckDNS更新失敗: $RESULT"
    exit 1
fi
