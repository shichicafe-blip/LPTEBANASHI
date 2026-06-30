# TEBANASHI LP（静的ミラー）

tebanashi.jp の静的フロントエンド（HTML / CSS / JavaScript）一式です。

## 構成

| パス | 内容 |
|------|------|
| `index.html` | ページ本体 |
| `style.css` | スタイル（アニメーション keyframes 含む） |
| `js/main.js` | スクロール演出・タブ・アコーディオン等の制御（要 jQuery, CDN） |
| `php/mailform-js.php` | 資料請求フォーム用スクリプト |
| `img/` | 画像アセット（webp / svg / png / jpg、PC・SP 両用） |
| `move.mp4` | ヒーロー背景動画 |
| `serve.py` | ローカルプレビュー用サーバ（マルチスレッド + Range 対応） |

## ローカルでプレビューする

ディレクトリ直下で静的サーバを立てて開きます。

```bash
python3 serve.py 8765
# → http://127.0.0.1:8765/
```

`python3 -m http.server` でも表示できますが、`move.mp4`（約48MB）の配信で
他リクエストがブロックされることがあるため、同梱の `serve.py` を推奨します。

## 注意

- フォント（Google Fonts: LINE Seed JP / M PLUS 1 / Noto Serif JP）、
  Font Awesome、jQuery は CDN を参照します（オフラインでは未読込）。
- 資料請求フォームの送信先（`php/mailform.php`）はサーバサイド処理のため、
  静的配信では動作しません（送信時 405/501 になります）。
