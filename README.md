# Save My 12 Weeks

ライフバランス診断アプリ + メルマガシステム

## 構成

```
save-my-12-weeks/
├── frontend/     # Next.js（診断UI - 開発用）
├── backend/      # Laravel（本番環境 + API）
└── docs/         # 仕様書
```

## Frontend (Next.js)

開発・プロトタイプ用のNext.jsアプリケーション。

```bash
cd frontend
npm install
npm run dev
```

## Backend (Laravel)

本番環境用のLaravelアプリケーション。

```bash
cd backend
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan serve
npm run dev  # Vite dev server
```

### 機能

- ライフバランス診断（React）
- メルマガシステム（30日講座）
- セミナー申込フォーム
- 管理画面
- 配信停止システム

## ドキュメント

- [配信停止システム仕様書](docs/unsubscribe_system_spec.md)
- [デプロイ手順](docs/DEPLOYMENT.md)
- [ブランチ引き継ぎ書 (2026-01-04)](docs/CHANGELOG_2026-01-04.md)
