"use client";

import { useState, useCallback, useEffect, useRef } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import html2canvas from "html2canvas";
import { Pagination, Mousewheel, EffectCreative } from "swiper/modules";
import type { Swiper as SwiperType } from "swiper";
import "swiper/css";
import "swiper/css/pagination";
import "swiper/css/effect-creative";
import JourneyBar from "./JourneyBar";

// 診断の8項目 - オリジナルグラデーションカラー
const categories = [
  { id: "health", name: "健康・体", color: "#ff8c42", question: "どのくらい自分が健康な暮らしを送れているか。10をプロのアスリート並み、1をベッドで寝たきりとしたら、今の自分はいくつ？", scale: "10=プロのアスリート並み、1=寝たきり" },
  { id: "mind", name: "心の平穏", color: "#ffb347", question: "どのくらい穏やかな気持ちで過ごせているか。10を常に穏やか、1を穏やかなことなんてない、とした時に、今の自分はいくつ？", scale: "10=常に穏やか、1=穏やかなことなんてない" },
  { id: "money", name: "お金", color: "#ffd166", question: "どのくらいの経済力か。10を世界有数の資産家、1を消費者金融に借金がある状態としたら、今の自分はいくつ？", scale: "10=世界有数の資産家、1=借金がある状態" },
  { id: "career", name: "仕事・キャリア", color: "#90be6d", question: "今の働き方はどのくらい理想に近い？あなたにとっての理想的な状況を10、5を譲れない基準、最悪な状況を1として、答えてください。", scale: "10=理想、5=譲れない基準、1=最悪" },
  { id: "time", name: "自分の時間", color: "#43aa8b", question: "今の自分は、どのくらい自分の時間が取れてる？あなたにとっての理想的な状況を10、5を譲れない基準、最悪な状況を1として、答えてください。", scale: "10=理想、5=譲れない基準、1=最悪" },
  { id: "living", name: "暮らし・環境", color: "#4ecdc4", question: "今の暮らしは、どのくらい自分の理想に近い？あなたにとっての理想的な状況を10、5を譲れない基準、最悪な状況を1として、答えてください。", scale: "10=理想、5=譲れない基準、1=最悪" },
  { id: "relationships", name: "人間関係", color: "#5dade2", question: "家族や友人、パートナーや同僚など、今の人間関係は、どのくらい理想に近い？あなたにとっての理想的な状況を10、5を譲れない基準、最悪な状況を1として、答えてください。", scale: "10=理想、5=譲れない基準、1=最悪" },
  { id: "vision", name: "将来・ビジョン", color: "#7fb3d5", question: "自分の将来について、今の自分はどのくらい近づこうと行動できてる？あなたにとっての理想的な状況を10、5を譲れない基準、最悪な状況を1として、答えてください。", scale: "10=理想、5=譲れない基準、1=最悪" },
];

// スワイプヒント（横方向）
function SwipeHint({ text = "swipe" }: { text?: string }) {
  return (
    <div className="swipe-hint-horizontal">
      <span className="text-xs font-display-en uppercase tracking-[0.2em] text-[#9a9a9a]">{text}</span>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9a9a9a" strokeWidth="1.5" opacity="0.6">
        <path d="M5 12h14M12 5l7 7-7 7" />
      </svg>
    </div>
  );
}

// 【1】導入① - ファーストビュー
function Intro1({
  name,
  onNameChange,
  onStart,
}: {
  name: string;
  onNameChange: (name: string) => void;
  onStart: () => void;
}) {
  return (
    <div className="slide-content items-center text-center">
      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-8 animate-fade-in-up">
        Save My 12 Weeks
      </p>

      <h1 className="heading-display mb-6 animate-fade-in-up animate-delay-1">
        ライフバランス
        <br />
        診断
      </h1>

      <div className="h-px w-20 bg-[#0d7377] my-8 animate-fade-in-up animate-delay-2" />

      <p className="text-[#6b6b6b] text-sm leading-[2] mb-4 animate-fade-in-up animate-delay-3">
        世界のライフコーチングの現場で
        <br />
        実際に使われている診断ツール。
      </p>

      <p className="text-[#2d2d2d] text-sm leading-[2] mb-8 animate-fade-in-up animate-delay-4">
        人生を8つの領域に分けて、
        <br />
        今の自分の「現在地」を見える化する。
      </p>

      <div className="w-full max-w-xs animate-fade-in-up animate-delay-5">
        <input
          type="text"
          value={name}
          onChange={(e) => onNameChange(e.target.value)}
          placeholder="お名前（ニックネームOK）"
          className="w-full px-4 py-3 border border-[rgba(0,0,0,0.1)] bg-[rgba(255,255,255,0.6)] text-center text-[#2d2d2d] placeholder-[#9a9a9a] focus:outline-none focus:border-[#0d7377] transition-colors rounded-lg"
        />
      </div>

      <div className="h-6" />

      <button
        onClick={onStart}
        disabled={!name.trim()}
        className={`cta-button ${!name.trim() ? "opacity-50 cursor-not-allowed" : "animate-pulse-subtle"}`}
      >
        始める
      </button>
    </div>
  );
}

// 【2】導入② - 診断前の心構え
function Intro2() {
  return (
    <div className="slide-content">
      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-10 animate-fade-in-up">
        Before you start
      </p>

      <h2 className="heading-xl mb-8 animate-fade-in-up animate-delay-1">
        「いい子」は
        <br />
        いらない。
      </h2>

      <div className="space-y-6 mb-10 animate-fade-in-up animate-delay-2">
        <p className="text-[#6b6b6b] text-sm leading-[2]">
          謙遜したり、よく見せようとしたり、
          <br />
          そういうのは全部置いていこう。
        </p>
        <p className="text-[#2d2d2d] text-sm leading-[2]">
          結果のシェアも強制しない。
          <br />
          <span className="text-[#0d7377] font-medium">正直に、直感で。</span>
        </p>
      </div>

      <div className="flex items-center gap-10 text-sm animate-fade-in-up animate-delay-3">
        <div className="flex items-baseline gap-2">
          <span className="text-3xl font-display-en text-[#0d7377]">3</span>
          <span className="text-[#6b6b6b]">min</span>
        </div>
        <div className="flex items-baseline gap-2">
          <span className="text-3xl font-display-en text-[#0d7377]">8</span>
          <span className="text-[#6b6b6b]">questions</span>
        </div>
      </div>

      <SwipeHint text="start" />
    </div>
  );
}

// 質問スライド
function QuestionSlide({
  category,
  questionNumber,
  score,
  onScoreChange,
}: {
  category: typeof categories[0];
  questionNumber: number;
  score: number;
  onScoreChange: (value: number) => void;
}) {
  return (
    <div className="slide-content items-center text-center">
      {/* カテゴリ表示 */}
      <div className="mb-8 animate-fade-in-up">
        <span className="text-xs font-display-en uppercase tracking-[0.2em] text-[#9a9a9a]">
          {questionNumber} / 8
        </span>
        <p
          className="text-base font-medium mt-2"
          style={{ color: category.color }}
        >
          {category.name}
        </p>
      </div>

      {/* 質問文 */}
      <p className="text-[#2d2d2d] text-base leading-[2] mb-10 max-w-xs animate-fade-in-up animate-delay-1">
        {category.question}
      </p>

      {/* スコア表示 */}
      <div className="score-display animate-fade-in-up animate-delay-2" style={{ color: category.color }}>
        {score}
      </div>

      {/* スライダー */}
      <div className="score-slider animate-fade-in-up animate-delay-3">
        <input
          type="range"
          min="1"
          max="10"
          value={score}
          onChange={(e) => onScoreChange(Number(e.target.value))}
          style={{
            background: `linear-gradient(to right, ${category.color} 0%, ${category.color} ${(score - 1) * 11.1}%, rgba(0,0,0,0.1) ${(score - 1) * 11.1}%, rgba(0,0,0,0.1) 100%)`,
          }}
        />
        <div className="flex justify-between text-xs text-[#9a9a9a] mt-4 font-display-en">
          <span>1</span>
          <span>5</span>
          <span>10</span>
        </div>
      </div>

      <p className="text-xs text-[#9a9a9a] mt-6 animate-fade-in-up animate-delay-4">
        {category.scale}
      </p>

      <SwipeHint />
    </div>
  );
}

// 相対評価の説明
function RelativeIntro() {
  return (
    <div className="slide-content">
      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-10 animate-fade-in-up">
        Questions 4-8
      </p>

      <h2 className="heading-lg mb-8 animate-fade-in-up animate-delay-1">
        ここからは
        <br />
        <span className="text-[#0d7377]">あなた基準</span>で
      </h2>

      <div className="space-y-6 animate-fade-in-up animate-delay-2">
        <p className="text-[#6b6b6b] text-sm leading-[2]">
          ここからの項目は、
          <br />
          人によって10も1も異なります。
        </p>
        <p className="text-[#2d2d2d] text-sm leading-[2]">
          あなたにとっての理想的な状況を<span className="text-[#0d7377] font-medium">10</span>、
          <br />
          譲れない基準を<span className="text-[#0d7377] font-medium">5</span>、
          <br />
          最悪な状況を<span className="text-[#0d7377] font-medium">1</span>として、
          <br />
          答えていってください。
        </p>
        <p className="text-[#9a9a9a] text-sm leading-[2]">
          あまり細部まで考え込まず、
          <br />
          直感的に選んでみてください。
        </p>
      </div>

      <SwipeHint />
    </div>
  );
}

// ライフコーチって何？（1/2）
function CoachInfo1() {
  return (
    <div className="slide-content">
      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-10 animate-fade-in-up">
        About Life Coach
      </p>

      <h2 className="heading-lg mb-8 animate-fade-in-up animate-delay-1">
        <span className="text-[#0d7377]">ライフコーチ</span>って何？
      </h2>

      <div className="space-y-6 animate-fade-in-up animate-delay-2">
        <p className="text-[#6b6b6b] text-sm leading-[2]">
          占いでも、スピリチュアルでも、
          <br />
          カウンセリングでもない。
          <br />
          「私の意見を伝える」コンサルとも違う。
        </p>
        <p className="text-[#2d2d2d] text-sm leading-[2]">
          ライフコーチは、磨き抜かれた質問を使って、
          <br />
          <span className="text-[#0d7377] font-medium">あなたの中にある答えを引き出す</span>
          <br />
          プロセスの実行者。
        </p>
      </div>

      <SwipeHint />
    </div>
  );
}

// ライフコーチって何？（2/2）
function CoachInfo2() {
  return (
    <div className="slide-content">
      <h2 className="heading-lg mb-8 animate-fade-in-up">
        Coachの語源は<span className="text-[#0d7377]">「馬車」</span>
      </h2>

      <div className="space-y-6 text-sm animate-fade-in-up animate-delay-1">
        <p className="text-[#6b6b6b] leading-[2]">
          お客さんを速く、遠くまで運ぶのが仕事。
          <br />
          現代でいうなら、<span className="text-[#2d2d2d] font-medium">タクシー運転手</span>。
        </p>
        <p className="text-[#6b6b6b] leading-[2]">
          でもこの運転手は普通と違って、
          <br />
          「なんとなくあっちの方に行きたい」もOK。
        </p>

        <div className="card-minimal mt-6 space-y-3">
          <p className="text-[#2d2d2d]">今どこにいるか <span className="text-[#9a9a9a]">— 現在地</span></p>
          <p className="text-[#2d2d2d]">どこに行きたいか <span className="text-[#9a9a9a]">— 目的地</span></p>
          <p className="text-[#2d2d2d]">どうやって行くか <span className="text-[#9a9a9a]">— 手段</span></p>
        </div>

        <p className="text-[#2d2d2d] leading-[2] pt-2">
          を一緒に確認しながら、具体化していく。
          <br />
          でも、<span className="text-[#0d7377] font-medium">決めるのは全部あなた。</span>
        </p>
      </div>

      <SwipeHint />
    </div>
  );
}

// セレブも使ってる
function CelebInfo() {
  return (
    <div className="slide-content">
      <h2 className="heading-lg mb-8 animate-fade-in-up">
        世界の<span className="text-[#0d7377]">トップ</span>も使ってる
      </h2>

      <div className="space-y-4 text-sm animate-fade-in-up animate-delay-1">
        {[
          { name: "オプラ・ウィンフリー", desc: "アメリカの国民的司会者" },
          { name: "レオナルド・ディカプリオ", desc: "オスカー俳優" },
          { name: "ビル・クリントン", desc: "元アメリカ大統領" },
        ].map((person, i) => (
          <div key={i} className="flex items-center gap-4">
            <div className="w-1 h-1 bg-[#0d7377]" />
            <div>
              <span className="text-[#2d2d2d] font-medium">{person.name}</span>
              <span className="text-[#9a9a9a] text-xs ml-3">{person.desc}</span>
            </div>
          </div>
        ))}
      </div>

      <div className="card-minimal mt-8 animate-fade-in-up animate-delay-2">
        <p className="text-[#6b6b6b] text-sm leading-[2]">
          Google元CEO <span className="text-[#2d2d2d] font-medium">エリック・シュミット</span> は
          <br />
          「今までもらった最高のアドバイスは、
          <br />
          <span className="text-[#0d7377] font-medium">コーチを雇うこと</span>」と語っている。
        </p>
      </div>

      <SwipeHint />
    </div>
  );
}

// 世界で14万人以上
function GlobalInfo() {
  return (
    <div className="slide-content items-center text-center">
      <p className="heading-display font-display-en text-[#0d7377] animate-fade-in-up">
        140,000+
      </p>
      <p className="text-[#6b6b6b] text-sm mt-4 mb-10 animate-fade-in-up animate-delay-1">
        世界で活動中のコーチ
      </p>

      <div className="card-minimal text-left space-y-4 animate-fade-in-up animate-delay-2">
        <p className="text-[#6b6b6b] text-sm leading-[2]">
          そのうち約68%が<span className="text-[#0d7377] font-medium">オンライン</span>で提供。
          <br />
          場所を選ばず、誰でもアクセスできる時代。
        </p>
        <p className="text-[#6b6b6b] text-sm leading-[2]">
          あなたも、自分の人生を
          <br />
          「見える化」することから始めてみない？
        </p>
      </div>

      <SwipeHint />
    </div>
  );
}

// 【15】お疲れさまでした！
function Complete({ onShowResult }: { onShowResult: () => void }) {
  return (
    <div className="slide-content items-center text-center">
      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-8 animate-fade-in-up">
        Complete
      </p>

      <h2 className="heading-xl mb-6 animate-fade-in-up animate-delay-1">
        お疲れさま。
      </h2>

      <p className="text-[#6b6b6b] text-sm leading-[2] mb-10 animate-fade-in-up animate-delay-2">
        8つの質問、全部答えてくれてありがとう。
        <br />
        あなたのライフバランスが
        <br />
        「あなたという人生を進む車輪」として
        <br />
        見える化されます。
      </p>

      <button onClick={onShowResult} className="cta-button animate-fade-in-up animate-delay-3">
        結果を見る
      </button>
    </div>
  );
}

// 【16】結果（ホイール）
function ResultWheel({ scores, userName, onNext }: { scores: number[]; userName: string; onNext: () => void }) {
  const wheelRef = useRef<HTMLDivElement>(null);
  const centerRadius = 22;
  const minRadius = 38;
  const maxRadius = 130;

  const getRadiusForScore = (score: number) => {
    return minRadius + ((maxRadius - minRadius) * (score - 1) / 9);
  };

  const round = (n: number) => Math.round(n * 100) / 100;

  const createArcPath = (startAngle: number, endAngle: number, innerRadius: number, outerRadius: number) => {
    const startInnerX = round(Math.cos(startAngle) * innerRadius);
    const startInnerY = round(Math.sin(startAngle) * innerRadius);
    const endInnerX = round(Math.cos(endAngle) * innerRadius);
    const endInnerY = round(Math.sin(endAngle) * innerRadius);
    const startOuterX = round(Math.cos(startAngle) * outerRadius);
    const startOuterY = round(Math.sin(startAngle) * outerRadius);
    const endOuterX = round(Math.cos(endAngle) * outerRadius);
    const endOuterY = round(Math.sin(endAngle) * outerRadius);
    const largeArcFlag = endAngle - startAngle > Math.PI ? 1 : 0;

    return `M ${startInnerX} ${startInnerY}
            L ${startOuterX} ${startOuterY}
            A ${outerRadius} ${outerRadius} 0 ${largeArcFlag} 1 ${endOuterX} ${endOuterY}
            L ${endInnerX} ${endInnerY}
            A ${innerRadius} ${innerRadius} 0 ${largeArcFlag} 0 ${startInnerX} ${startInnerY}
            Z`;
  };

  const angleStep = (Math.PI * 2) / categories.length;
  const gap = 0.03;

  const handleSave = async () => {
    if (!wheelRef.current) return;
    try {
      const canvas = await html2canvas(wheelRef.current, {
        backgroundColor: "#faf8f5",
        scale: 2,
      });
      const link = document.createElement("a");
      link.download = "life-balance-wheel.png";
      link.href = canvas.toDataURL("image/png");
      link.click();
    } catch (error) {
      console.error("保存に失敗しました", error);
    }
  };

  return (
    <div className="slide-content items-center text-center relative">
      {/* 保存用の隠しホイール */}
      <div ref={wheelRef} style={{ position: "absolute", left: "-9999px", padding: "20px", background: "#faf8f5" }}>
        <p style={{ textAlign: "center", fontSize: "12px", color: "#0d7377", marginBottom: "4px", fontFamily: "serif" }}>
          {userName}のライフバランス
        </p>
        <p style={{ textAlign: "center", fontSize: "10px", color: "#9a9a9a", marginBottom: "10px" }}>
          {new Date().toLocaleDateString("ja-JP", { year: "numeric", month: "long", day: "numeric" })}
        </p>
        <svg viewBox="-170 -170 340 340" width="300" height="300">
          {[5, 10].map((i) => (
            <circle key={i} cx={0} cy={0} r={minRadius + ((maxRadius - minRadius) * (i - 1) / 9)} fill="none" stroke="rgba(0,0,0,0.06)" strokeWidth={1} />
          ))}
          {categories.map((cat, i) => {
            const startAngle = angleStep * i - Math.PI / 2 + gap;
            const endAngle = angleStep * (i + 1) - Math.PI / 2 - gap;
            const midAngle = (startAngle + endAngle) / 2;
            const fillRadius = minRadius + ((maxRadius - minRadius) * (scores[i] - 1) / 9);
            const labelRadius = maxRadius + 22;
            const labelX = Math.round(Math.cos(midAngle) * labelRadius * 100) / 100;
            const labelY = Math.round(Math.sin(midAngle) * labelRadius * 100) / 100;
            const scoreRadius = (centerRadius + fillRadius) / 2;
            const scoreX = Math.round(Math.cos(midAngle) * scoreRadius * 100) / 100;
            const scoreY = Math.round(Math.sin(midAngle) * scoreRadius * 100) / 100;
            return (
              <g key={cat.id}>
                <path d={createArcPath(startAngle, endAngle, centerRadius, maxRadius)} fill="rgba(255,255,255,0.6)" stroke="rgba(0,0,0,0.04)" strokeWidth={1} />
                <path d={createArcPath(startAngle, endAngle, centerRadius, fillRadius)} fill={cat.color} opacity={0.85} />
                <text x={labelX} y={labelY} fontSize={9} fill="#6b6b6b" textAnchor="middle" dominantBaseline="middle">{cat.name}</text>
                <text x={scoreX} y={scoreY} fontSize={14} fill="#fff" textAnchor="middle" dominantBaseline="middle" fontWeight={600}>{scores[i]}</text>
              </g>
            );
          })}
          <circle cx={0} cy={0} r={centerRadius - 1} fill="#faf8f5" stroke="#0d7377" strokeWidth={1} />
          <text x={0} y={1} fontSize={9} fill="#0d7377" textAnchor="middle" dominantBaseline="middle" fontWeight={500}>LIFE</text>
        </svg>
        <p style={{ textAlign: "center", fontSize: "10px", color: "#9a9a9a", marginTop: "10px" }}>
          Save My 12 Weeks
        </p>
      </div>

      {/* 右上の保存ボタン */}
      <button
        onClick={handleSave}
        className="absolute top-0 right-0 p-2 text-[#9a9a9a] hover:text-[#0d7377] transition-colors"
        title="画像を保存"
      >
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
        </svg>
      </button>

      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-2 animate-fade-in-up">
        Your Result
      </p>
      <h2 className="heading-lg mb-2 animate-fade-in-up animate-delay-1">
        {userName}の<span className="text-[#0d7377]">ライフバランス</span>
      </h2>
      <p className="text-xs text-[#9a9a9a] mb-6 animate-fade-in-up animate-delay-1">
        {new Date().toLocaleDateString("ja-JP", { year: "numeric", month: "long", day: "numeric" })}
      </p>

      <div className="relative w-[300px] h-[300px] mx-auto animate-fade-in-up animate-delay-2">
        <svg viewBox="-170 -170 340 340" className="w-full h-full">
          {/* グリッド円 */}
          {[5, 10].map((i) => (
            <circle
              key={i}
              cx={0}
              cy={0}
              r={getRadiusForScore(i)}
              fill="none"
              stroke="rgba(0,0,0,0.06)"
              strokeWidth={1}
            />
          ))}

          {/* セグメント */}
          {categories.map((cat, i) => {
            const startAngle = angleStep * i - Math.PI / 2 + gap;
            const endAngle = angleStep * (i + 1) - Math.PI / 2 - gap;
            const midAngle = (startAngle + endAngle) / 2;
            const fillRadius = getRadiusForScore(scores[i]);
            const labelRadius = maxRadius + 22;
            const labelX = round(Math.cos(midAngle) * labelRadius);
            const labelY = round(Math.sin(midAngle) * labelRadius);
            const scoreRadius = (centerRadius + fillRadius) / 2;
            const scoreX = round(Math.cos(midAngle) * scoreRadius);
            const scoreY = round(Math.sin(midAngle) * scoreRadius);

            return (
              <g key={cat.id}>
                {/* 背景 */}
                <path
                  d={createArcPath(startAngle, endAngle, centerRadius, maxRadius)}
                  fill="rgba(255,255,255,0.6)"
                  stroke="rgba(0,0,0,0.04)"
                  strokeWidth={1}
                />
                {/* 塗り */}
                <path
                  d={createArcPath(startAngle, endAngle, centerRadius, fillRadius)}
                  fill={cat.color}
                  opacity={0.85}
                />
                {/* ラベル */}
                <text x={labelX} y={labelY} fontSize={9} fill="#6b6b6b" textAnchor="middle" dominantBaseline="middle" fontWeight={400}>
                  {cat.name}
                </text>
                {/* スコア */}
                <text
                  x={scoreX}
                  y={scoreY}
                  fontSize={14}
                  fill="#fff"
                  textAnchor="middle"
                  dominantBaseline="middle"
                  fontWeight={600}
                >
                  {scores[i]}
                </text>
              </g>
            );
          })}

          {/* 中心 */}
          <circle cx={0} cy={0} r={centerRadius - 1} fill="#faf8f5" stroke="#0d7377" strokeWidth={1} />
          <text x={0} y={1} fontSize={9} fill="#0d7377" textAnchor="middle" dominantBaseline="middle" fontWeight={500} className="font-display-en">LIFE</text>
        </svg>
      </div>

      {/* 凡例 */}
      <div className="grid grid-cols-2 gap-2 mt-6 text-xs animate-fade-in-up animate-delay-3">
        {categories.map((cat, i) => (
          <div key={cat.id} className="flex items-center gap-2">
            <div className="w-2 h-2" style={{ backgroundColor: cat.color }} />
            <span className="text-[#6b6b6b]">{cat.name}: {scores[i]}</span>
          </div>
        ))}
      </div>

      <div className="h-4" />

      <button onClick={onNext} className="cta-button animate-pulse-subtle">
        12週間あったら、どこ変える？→
      </button>
    </div>
  );
}

// 【16後半】メルマガ登録
function NewsletterSignup({ onNext, email, onEmailChange }: { onNext: () => void; email: string; onEmailChange: (email: string) => void }) {
  const handleSubmit = () => {
    if (email.trim() && email.includes("@")) {
      onNext();
    }
  };

  return (
    <div className="slide-content items-center text-center">
      <div className="text-center max-w-sm animate-fade-in-up">
        <p className="text-[#6b6b6b] leading-[2.2] text-sm">
          私、今まで何してたんだろう。
          <br />
          そう思ったなら、<span className="text-[#0d7377] font-medium">今が踏み出すタイミング。</span>
        </p>
        <div className="h-px w-16 bg-[#0d7377]/20 mx-auto my-6" />
        <p className="text-[#6b6b6b] leading-[2.2] text-sm">
          12週間のうち、最初の<span className="text-[#0d7377] font-medium">30日</span>で
          <br />
          ライフコーチのアプローチを体験してみない？
        </p>
        <p className="text-[#6b6b6b] leading-[2.2] text-sm mt-4">
          毎日届くメールで、
          <br />
          あなたの人生の車輪から「行動」に落とし込んでいこう。
        </p>
      </div>

      <div className="w-full max-w-xs mt-8 animate-fade-in-up animate-delay-1">
        <input
          type="email"
          value={email}
          onChange={(e) => onEmailChange(e.target.value)}
          placeholder="メールアドレス"
          className="w-full px-4 py-3 border border-[rgba(0,0,0,0.1)] bg-[rgba(255,255,255,0.6)] text-center text-[#2d2d2d] placeholder-[#9a9a9a] focus:outline-none focus:border-[#0d7377] transition-colors rounded-lg"
        />
      </div>

      <div className="h-4" />

      <button
        onClick={handleSubmit}
        disabled={!email.trim() || !email.includes("@")}
        className={`cta-button ${!email.trim() || !email.includes("@") ? "opacity-50 cursor-not-allowed" : "animate-pulse-subtle"}`}
      >
        30日間、試してみる →
      </button>
    </div>
  );
}

// 【17】追加質問
function AdditionalQuestion({
  selectedAreas,
  onToggleArea,
  freeText,
  onFreeTextChange,
  onNext,
  onBack,
}: {
  selectedAreas: string[];
  onToggleArea: (id: string) => void;
  freeText: string;
  onFreeTextChange: (text: string) => void;
  onNext: () => void;
  onBack: () => void;
}) {
  return (
    <div className="slide-content items-center text-center">
      <h2 className="heading-lg mb-3 animate-fade-in-up">
        もし<span className="text-[#0d7377]">12週間</span>あったら
      </h2>
      <p className="text-[#6b6b6b] text-sm mb-8 animate-fade-in-up animate-delay-1">
        どこを変えたい？
      </p>

      <div className="grid grid-cols-2 gap-2 w-full max-w-xs mb-8 animate-fade-in-up animate-delay-2">
        {categories.map((cat) => (
          <button
            key={cat.id}
            onClick={() => onToggleArea(cat.id)}
            className={`select-button ${selectedAreas.includes(cat.id) ? "selected" : ""}`}
            style={{
              backgroundColor: selectedAreas.includes(cat.id) ? cat.color : undefined,
              borderColor: selectedAreas.includes(cat.id) ? cat.color : undefined,
            }}
          >
            {cat.name}
          </button>
        ))}
      </div>

      <div className="w-full max-w-xs animate-fade-in-up animate-delay-3">
        <label className="block text-xs text-[#9a9a9a] mb-3 text-left font-display-en uppercase tracking-[0.15em]">
          What would you like to do?
        </label>
        <textarea
          value={freeText}
          onChange={(e) => onFreeTextChange(e.target.value)}
          placeholder="例：週3でジムに通いたい..."
          className="styled-textarea"
        />
      </div>

      <div className="flex items-center justify-center gap-4 mt-8 w-full max-w-xs animate-fade-in-up animate-delay-4">
        <button onClick={onBack} className="text-sm text-[#9a9a9a] hover:text-[#0d7377] transition-colors">
          ← 結果を見返す
        </button>
        <button onClick={onNext} className="cta-button">
          次へ →
        </button>
      </div>
    </div>
  );
}

// 【18】登録完了 + セミナー案内
function ThankYou({ selectedAreas, scores }: { selectedAreas: string[]; scores: number[] }) {
  const [appUrl, setAppUrl] = useState("");
  const wheelRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    setAppUrl(window.location.href);
  }, []);

  const selectedNames = selectedAreas.map((id) => categories.find((c) => c.id === id)?.name).filter(Boolean);
  const shareText = `今の自分を8つの視点で見える化してみた。\n私が12週間あったら改善したいのは「${selectedNames[0] || "自分の時間"}」\n\n#SaveMy12Weeks #ライフバランス診断\n@rebellious1124`;

  // ホイール描画用の関数
  const centerRadius = 22;
  const minRadius = 38;
  const maxRadius = 130;
  const getRadiusForScore = (score: number) => minRadius + ((maxRadius - minRadius) * (score - 1) / 9);
  const round = (n: number) => Math.round(n * 100) / 100;
  const createArcPath = (startAngle: number, endAngle: number, innerRadius: number, outerRadius: number) => {
    const startInnerX = round(Math.cos(startAngle) * innerRadius);
    const startInnerY = round(Math.sin(startAngle) * innerRadius);
    const endInnerX = round(Math.cos(endAngle) * innerRadius);
    const endInnerY = round(Math.sin(endAngle) * innerRadius);
    const startOuterX = round(Math.cos(startAngle) * outerRadius);
    const startOuterY = round(Math.sin(startAngle) * outerRadius);
    const endOuterX = round(Math.cos(endAngle) * outerRadius);
    const endOuterY = round(Math.sin(endAngle) * outerRadius);
    const largeArcFlag = endAngle - startAngle > Math.PI ? 1 : 0;
    return `M ${startInnerX} ${startInnerY} L ${startOuterX} ${startOuterY} A ${outerRadius} ${outerRadius} 0 ${largeArcFlag} 1 ${endOuterX} ${endOuterY} L ${endInnerX} ${endInnerY} A ${innerRadius} ${innerRadius} 0 ${largeArcFlag} 0 ${startInnerX} ${startInnerY} Z`;
  };
  const angleStep = (Math.PI * 2) / categories.length;
  const gap = 0.03;

  const handleSave = async () => {
    if (!wheelRef.current) return;
    try {
      const canvas = await html2canvas(wheelRef.current, {
        backgroundColor: "#faf8f5",
        scale: 2,
      });
      const link = document.createElement("a");
      link.download = "life-balance-wheel.png";
      link.href = canvas.toDataURL("image/png");
      link.click();
    } catch (error) {
      console.error("保存に失敗しました", error);
    }
  };

  return (
    <div className="slide-content items-center text-center">
      {/* 保存用の隠しホイール */}
      <div ref={wheelRef} style={{ position: "absolute", left: "-9999px", padding: "20px", background: "#faf8f5" }}>
        <p style={{ textAlign: "center", fontSize: "14px", color: "#0d7377", marginBottom: "10px", fontFamily: "serif" }}>
          My Life Balance
        </p>
        <svg viewBox="-170 -170 340 340" width="300" height="300">
          {[5, 10].map((i) => (
            <circle key={i} cx={0} cy={0} r={getRadiusForScore(i)} fill="none" stroke="rgba(0,0,0,0.06)" strokeWidth={1} />
          ))}
          {categories.map((cat, i) => {
            const startAngle = angleStep * i - Math.PI / 2 + gap;
            const endAngle = angleStep * (i + 1) - Math.PI / 2 - gap;
            const midAngle = (startAngle + endAngle) / 2;
            const fillRadius = getRadiusForScore(scores[i]);
            const labelRadius = maxRadius + 22;
            const labelX = round(Math.cos(midAngle) * labelRadius);
            const labelY = round(Math.sin(midAngle) * labelRadius);
            const scoreRadius = (centerRadius + fillRadius) / 2;
            const scoreX = round(Math.cos(midAngle) * scoreRadius);
            const scoreY = round(Math.sin(midAngle) * scoreRadius);
            return (
              <g key={cat.id}>
                <path d={createArcPath(startAngle, endAngle, centerRadius, maxRadius)} fill="rgba(255,255,255,0.6)" stroke="rgba(0,0,0,0.04)" strokeWidth={1} />
                <path d={createArcPath(startAngle, endAngle, centerRadius, fillRadius)} fill={cat.color} opacity={0.85} />
                <text x={labelX} y={labelY} fontSize={9} fill="#6b6b6b" textAnchor="middle" dominantBaseline="middle">{cat.name}</text>
                <text x={scoreX} y={scoreY} fontSize={14} fill="#fff" textAnchor="middle" dominantBaseline="middle" fontWeight={600}>{scores[i]}</text>
              </g>
            );
          })}
          <circle cx={0} cy={0} r={centerRadius - 1} fill="#faf8f5" stroke="#0d7377" strokeWidth={1} />
          <text x={0} y={1} fontSize={9} fill="#0d7377" textAnchor="middle" dominantBaseline="middle" fontWeight={500}>LIFE</text>
        </svg>
        <p style={{ textAlign: "center", fontSize: "10px", color: "#9a9a9a", marginTop: "10px" }}>
          Save My 12 Weeks
        </p>
      </div>

      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-4 animate-fade-in-up">
        Thank You
      </p>

      <h2 className="heading-xl mb-6 animate-fade-in-up animate-delay-1">
        登録しました！
      </h2>

      <div className="card-minimal text-center max-w-sm mb-8 animate-fade-in-up animate-delay-2">
        <p className="text-[#6b6b6b] text-sm leading-[2]">
          メールが届かない場合は、
          <br />
          <span className="text-[#0d7377] font-medium">迷惑メールフォルダ</span>も確認してね。
        </p>
      </div>

      <div className="h-px w-16 bg-[#0d7377]/20 mx-auto mb-8 animate-fade-in-up animate-delay-2" />

      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-4 animate-fade-in-up animate-delay-3">
        Next Step
      </p>

      <p className="text-[#6b6b6b] text-sm leading-[2] mb-6 animate-fade-in-up animate-delay-3">
        女性限定の無料セミナーで、
        <br />
        一緒に次の一歩を踏み出しませんか？
      </p>

      <a
        href="https://docs.google.com/forms/d/e/1FAIpQLSfwMWzx0PhMKFJYQvYMCAabNUHb3wFH_-HeRlDvWikwApNzww/viewform?usp=header"
        target="_blank"
        rel="noopener noreferrer"
        className="cta-button mb-10 animate-fade-in-up animate-delay-4"
      >
        無料セミナーに参加する
      </a>

      <p className="text-xs text-[#9a9a9a] mb-4 font-display-en uppercase tracking-[0.15em] animate-fade-in-up animate-delay-5">Share</p>
      <div className="flex gap-3 flex-wrap justify-center animate-fade-in-up animate-delay-5">
        <a
          href={`https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(appUrl)}`}
          target="_blank"
          rel="noopener noreferrer"
          className="share-button share-x"
        >
          X
        </a>
        <a
          href={`https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(appUrl)}`}
          target="_blank"
          rel="noopener noreferrer"
          className="share-button share-line"
        >
          LINE
        </a>
        <button onClick={handleSave} className="share-button share-save">
          Save
        </button>
      </div>
    </div>
  );
}

// メインアプリ
export default function DiagnosisApp() {
  const [userName, setUserName] = useState("");
  const [scores, setScores] = useState<number[]>([5, 5, 5, 5, 5, 5, 5, 5]);
  const [selectedAreas, setSelectedAreas] = useState<string[]>([]);
  const [freeText, setFreeText] = useState("");
  const [email, setEmail] = useState("");
  const [swiperInstance, setSwiperInstance] = useState<SwiperType | null>(null);
  const [currentSlide, setCurrentSlide] = useState(1);
  const [resultShown, setResultShown] = useState(false);
  const totalSlides = 20;

  const handleScoreChange = useCallback((index: number, value: number) => {
    setScores((prev) => {
      const newScores = [...prev];
      newScores[index] = value;
      return newScores;
    });
  }, []);

  const handleToggleArea = useCallback((id: string) => {
    setSelectedAreas((prev) =>
      prev.includes(id) ? prev.filter((a) => a !== id) : [...prev, id]
    );
  }, []);

  const handleStart = useCallback(() => {
    if (swiperInstance && userName.trim()) {
      swiperInstance.slideNext();
    }
  }, [swiperInstance, userName]);

  const handleShowResult = useCallback(() => {
    if (swiperInstance) {
      setResultShown(true);
      swiperInstance.slideNext();
    }
  }, [swiperInstance]);

  return (
    <div className="h-screen w-screen relative">
      {/* Background */}
      <div className="mesh-bg" />
      <div className="noise-overlay" />

      <Swiper
        modules={[Pagination, Mousewheel, EffectCreative]}
        direction="horizontal"
        effect="creative"
        creativeEffect={{
          prev: {
            translate: ["-100%", 0, -1],
            opacity: 0,
          },
          next: {
            translate: ["100%", 0, 0],
            opacity: 1,
          },
        }}
        pagination={false}
        mousewheel={{
          forceToAxis: true,
        }}
        speed={500}
        spaceBetween={0}
        slidesPerView={1}
        noSwipingSelector="input, textarea"
        touchStartPreventDefault={false}
        resistanceRatio={0}
        touchReleaseOnEdges={false}
        allowSlideNext={currentSlide < 17 && (currentSlide !== 1 || userName.trim().length > 0)}
        allowSlidePrev={currentSlide < 17}
        className="h-full w-full pb-[100px]"
        onSwiper={setSwiperInstance}
        onSlideChange={(swiper) => {
          setCurrentSlide(swiper.activeIndex + 1);
          // 結果表示後は、ResultWheel（スライド17、index 16）より前には戻れない
          if (resultShown && swiper.activeIndex < 16) {
            swiper.slideTo(16);
          }
        }}
      >
        <SwiperSlide><Intro1 name={userName} onNameChange={setUserName} onStart={handleStart} /></SwiperSlide>
        <SwiperSlide><Intro2 /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[0]} questionNumber={1} score={scores[0]} onScoreChange={(v) => handleScoreChange(0, v)} /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[1]} questionNumber={2} score={scores[1]} onScoreChange={(v) => handleScoreChange(1, v)} /></SwiperSlide>
        <SwiperSlide><CoachInfo1 /></SwiperSlide>
        <SwiperSlide><CoachInfo2 /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[2]} questionNumber={3} score={scores[2]} onScoreChange={(v) => handleScoreChange(2, v)} /></SwiperSlide>
        <SwiperSlide><RelativeIntro /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[3]} questionNumber={4} score={scores[3]} onScoreChange={(v) => handleScoreChange(3, v)} /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[4]} questionNumber={5} score={scores[4]} onScoreChange={(v) => handleScoreChange(4, v)} /></SwiperSlide>
        <SwiperSlide><CelebInfo /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[5]} questionNumber={6} score={scores[5]} onScoreChange={(v) => handleScoreChange(5, v)} /></SwiperSlide>
        <SwiperSlide><GlobalInfo /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[6]} questionNumber={7} score={scores[6]} onScoreChange={(v) => handleScoreChange(6, v)} /></SwiperSlide>
        <SwiperSlide><QuestionSlide category={categories[7]} questionNumber={8} score={scores[7]} onScoreChange={(v) => handleScoreChange(7, v)} /></SwiperSlide>
        <SwiperSlide><Complete onShowResult={handleShowResult} /></SwiperSlide>
        <SwiperSlide><ResultWheel scores={scores} userName={userName} onNext={() => { if (swiperInstance) { swiperInstance.allowSlideNext = true; swiperInstance.slideNext(); } }} /></SwiperSlide>
        <SwiperSlide><AdditionalQuestion selectedAreas={selectedAreas} onToggleArea={handleToggleArea} freeText={freeText} onFreeTextChange={setFreeText} onNext={() => { if (swiperInstance) { swiperInstance.allowSlideNext = true; swiperInstance.slideNext(); } }} onBack={() => { if (swiperInstance) { swiperInstance.allowSlidePrev = true; swiperInstance.slideTo(16); } }} /></SwiperSlide>
        <SwiperSlide><NewsletterSignup email={email} onEmailChange={setEmail} onNext={() => { if (swiperInstance) { swiperInstance.allowSlideNext = true; swiperInstance.slideNext(); } }} /></SwiperSlide>
        <SwiperSlide><ThankYou selectedAreas={selectedAreas} scores={scores} /></SwiperSlide>
      </Swiper>

      {/* Journey Bar - 結果画面以降は非表示 */}
      {currentSlide > 1 && currentSlide < 17 && <JourneyBar currentStep={currentSlide} totalSteps={totalSlides} />}
    </div>
  );
}
