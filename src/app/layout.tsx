import type { Metadata, Viewport } from "next";
import { Noto_Serif_JP, Cormorant_Garamond } from "next/font/google";
import "./globals.css";

// 日本語明朝体フォント
const notoSerifJP = Noto_Serif_JP({
  variable: "--font-serif-jp",
  subsets: ["latin"],
  weight: ["400", "500", "600", "700"],
});

// 英語セリフフォント
const cormorant = Cormorant_Garamond({
  variable: "--font-cormorant",
  subsets: ["latin"],
  weight: ["400", "500", "600", "700"],
});

export const metadata: Metadata = {
  title: "ライフバランス診断 | Save My 12 Weeks",
  description: "今の自分を8つの視点で見える化する。世界のライフコーチングの現場で使われている診断ツール。",
  openGraph: {
    title: "ライフバランス診断 | Save My 12 Weeks",
    description: "今の自分を8つの視点で見える化する",
    type: "website",
  },
  twitter: {
    card: "summary_large_image",
    title: "ライフバランス診断 | Save My 12 Weeks",
    description: "今の自分を8つの視点で見える化する",
  },
};

export const viewport: Viewport = {
  width: "device-width",
  initialScale: 1,
  maximumScale: 1,
  userScalable: false,
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="ja">
      <body className={`${notoSerifJP.variable} ${cormorant.variable} antialiased`}>
        {children}
      </body>
    </html>
  );
}
