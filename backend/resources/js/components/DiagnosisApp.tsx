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

// 【1】導入① - ファーストビュー（名前入力付き）
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
    <div className="slide-content items-center text-center swiper-no-swiping">
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
          className="styled-input w-full text-center"
          maxLength={20}
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

      <button onClick={onShowResult} className="cta-button animate-fade-in-up animate-delay-3 animate-pulse-subtle">
        結果を見る
      </button>
    </div>
  );
}

// 【16】結果（ホイール）
function ResultWheel({ scores, onNext }: { scores: number[]; onNext: () => void }) {
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

  return (
    <div className="slide-content items-center text-center">
      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-2 animate-fade-in-up">
        Your Result
      </p>
      <h2 className="heading-lg mb-8 animate-fade-in-up animate-delay-1">
        あなたの<span className="text-[#0d7377]">ライフバランス</span>
      </h2>

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

// 【17】追加質問
function AdditionalQuestion({
  selectedAreas,
  onToggleArea,
  freeText,
  onFreeTextChange,
  onBack,
}: {
  selectedAreas: string[];
  onToggleArea: (id: string) => void;
  freeText: string;
  onFreeTextChange: (text: string) => void;
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

      <div className="grid grid-cols-2 gap-3 w-full max-w-sm mb-10 animate-fade-in-up animate-delay-2">
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

      <div className="w-full max-w-sm animate-fade-in-up animate-delay-3">
        <label className="block text-sm text-[#6b6b6b] mb-3 text-left">
          やりたいことがあれば教えてね
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
      </div>

      <SwipeHint />
    </div>
  );
}

// 【18】メルマガ登録（配信時間はDay 0メールから選択）
function NewsletterSignup({
  email,
  onEmailChange,
  onSubmit,
  isSubmitting,
  error,
}: {
  email: string;
  onEmailChange: (email: string) => void;
  onSubmit: () => void;
  isSubmitting: boolean;
  error: string;
}) {
  return (
    <div className="slide-content items-center text-center">
      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-6 animate-fade-in-up">
        Newsletter
      </p>

      <h2 className="heading-lg mb-3 animate-fade-in-up animate-delay-1">
        <span className="text-[#0d7377]">30日間</span>の
      </h2>
      <h2 className="heading-lg mb-6 animate-fade-in-up animate-delay-2">
        無料メール講座
      </h2>

      <p className="text-[#6b6b6b] text-sm leading-[2] mb-8 animate-fade-in-up animate-delay-3">
        今日から30日間、毎日1通ずつ
        <br />
        自分と向き合うヒントをお届けします。
      </p>

      <div className="w-full max-w-sm space-y-6 animate-fade-in-up animate-delay-4">
        {/* メールアドレス入力 */}
        <div>
          <label className="block text-sm text-[#6b6b6b] mb-3 text-left">
            メールアドレス
          </label>
          <input
            type="email"
            value={email}
            onChange={(e) => onEmailChange(e.target.value)}
            placeholder="your@email.com"
            className="styled-input w-full"
          />
        </div>

        {error && (
          <p className="text-red-500 text-sm">{error}</p>
        )}

        <button
          onClick={onSubmit}
          disabled={isSubmitting || !email}
          className="cta-button w-full disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {isSubmitting ? "登録中..." : "無料で始める"}
        </button>

        <p className="text-sm text-[#9a9a9a] leading-relaxed">
          登録後、配信時間を選べるメールが届きます。
        </p>
      </div>
    </div>
  );
}

// 【19】完了 + セミナー案内 + シェア
function CompleteCTA({ selectedAreas, scores }: { selectedAreas: string[]; scores: number[] }) {
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

      <p className="text-xs font-display-en uppercase tracking-[0.3em] text-[#0d7377] mb-6 animate-fade-in-up">
        Registration Complete
      </p>

      <h2 className="heading-xl mb-6 animate-fade-in-up animate-delay-1">
        ありがとう！
      </h2>

      <p className="text-[#6b6b6b] text-sm leading-[2] mb-8 animate-fade-in-up animate-delay-2">
        メールをチェックしてね。
        <br />
        明日から30日間、毎日届くよ。
      </p>

      <div className="card-minimal mb-8 animate-fade-in-up animate-delay-3">
        <p className="text-[#2d2d2d] text-sm leading-[2] mb-2">
          <span className="text-[#0d7377] font-medium">女性限定の無料セミナー</span>も
          <br />
          開催中！
        </p>
        <a
          href="/seminar"
          className="text-[#0d7377] text-sm underline underline-offset-4"
        >
          セミナーに申し込む →
        </a>
      </div>

      <p className="text-xs text-[#9a9a9a] mb-4 font-display-en uppercase tracking-[0.15em] animate-fade-in-up animate-delay-4">Share</p>
      <div className="flex gap-3 flex-wrap justify-center animate-fade-in-up animate-delay-4">
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

// ホイール画像生成用のヘルパー関数
const generateWheelImageBase64 = async (
  wheelElement: HTMLDivElement | null
): Promise<string | null> => {
  if (!wheelElement) return null;
  try {
    const canvas = await html2canvas(wheelElement, {
      backgroundColor: "#faf8f5",
      scale: 2,
      logging: false,
    });
    return canvas.toDataURL("image/png");
  } catch (error) {
    console.error("ホイール画像の生成に失敗:", error);
    return null;
  }
};

// メインアプリ
export default function DiagnosisApp() {
  const [scores, setScores] = useState<number[]>([5, 5, 5, 5, 5, 5, 5, 5]);
  const [selectedAreas, setSelectedAreas] = useState<string[]>([]);
  const [freeText, setFreeText] = useState("");
  const [nickname, setNickname] = useState("");
  const [swiperInstance, setSwiperInstance] = useState<SwiperType | null>(null);
  const [currentSlide, setCurrentSlide] = useState(1);
  const [resultShown, setResultShown] = useState(false);
  const totalSlides = 20; // ResultMessage削除後

  // メルマガ登録用ステート
  const [email, setEmail] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState("");
  const [diagnosisId, setDiagnosisId] = useState<number | null>(null);
  const [isRegistered, setIsRegistered] = useState(false);

  // ホイール画像生成用ref
  const wheelRef = useRef<HTMLDivElement>(null);

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

  const handleShowResult = useCallback(() => {
    if (swiperInstance) {
      setResultShown(true);
      swiperInstance.slideNext();
    }
  }, [swiperInstance]);

  // Intro1で「始める」を押した時
  const handleStart = useCallback(() => {
    if (nickname.trim() && swiperInstance) {
      swiperInstance.slideNext();
    }
  }, [nickname, swiperInstance]);

  // ResultWheelで「12週間あったら〜」ボタンを押した時
  const handleResultNext = useCallback(() => {
    if (swiperInstance) {
      swiperInstance.slideNext();
    }
  }, [swiperInstance]);

  // AdditionalQuestionで「← 結果を見返す」ボタンを押した時
  const handleBackToResult = useCallback(() => {
    if (swiperInstance) {
      swiperInstance.slideTo(16); // ResultWheelのインデックス
    }
  }, [swiperInstance]);

  // 診断結果を保存してからメルマガ登録
  const handleNewsletterSubmit = useCallback(async () => {
    if (!email || isSubmitting) return;

    setIsSubmitting(true);
    setSubmitError("");

    try {
      // ホイール画像を生成
      const wheelImageBase64 = await generateWheelImageBase64(wheelRef.current);

      // まず診断結果を保存（ホイール画像も含む）
      let currentDiagnosisId = diagnosisId;
      if (!currentDiagnosisId) {
        const diagnosisResponse = await fetch("/api/diagnosis", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            health_score: scores[0],
            mind_score: scores[1],
            money_score: scores[2],
            career_score: scores[3],
            time_score: scores[4],
            living_score: scores[5],
            relationships_score: scores[6],
            vision_score: scores[7],
            selected_areas: selectedAreas,
            free_text: freeText,
            wheel_image_base64: wheelImageBase64,
          }),
        });

        const diagnosisData = await diagnosisResponse.json();
        if (diagnosisData.success) {
          currentDiagnosisId = diagnosisData.diagnosis_id;
          setDiagnosisId(currentDiagnosisId);
        }
      }

      // メルマガ登録
      const subscribeResponse = await fetch("/api/newsletter/subscribe", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          email,
          nickname: nickname || null,
          diagnosis_id: currentDiagnosisId,
        }),
      });

      const subscribeData = await subscribeResponse.json();

      if (subscribeData.success) {
        setIsRegistered(true);
        if (swiperInstance) {
          swiperInstance.slideNext();
        }
      } else {
        setSubmitError(subscribeData.message || "登録に失敗しました。");
      }
    } catch (error) {
      setSubmitError("通信エラーが発生しました。もう一度お試しください。");
    } finally {
      setIsSubmitting(false);
    }
  }, [email, isSubmitting, diagnosisId, scores, selectedAreas, freeText, nickname, swiperInstance]);

  // ホイール描画用の定数と関数（画像生成用）
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

  return (
    <div className="h-screen w-screen relative">
      {/* Background */}
      <div className="mesh-bg" />
      <div className="noise-overlay" />

      {/* ホイール画像生成用の隠しエレメント */}
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
        noSwipingSelector="input[type='range'], input[type='email'], input[type='text'], select, textarea"
        noSwipingClass="swiper-no-swiping"
        touchStartPreventDefault={false}
        resistanceRatio={0}
        touchReleaseOnEdges={false}
        className="h-full w-full pb-[100px]"
        onSwiper={setSwiperInstance}
        onSlideChange={(swiper) => {
          setCurrentSlide(swiper.activeIndex + 1);
          // 結果表示後は、ResultWheel（スライド17、index 16）より前には戻れない
          if (resultShown && swiper.activeIndex < 16) {
            swiper.slideTo(16);
          }
          // 登録完了後は、完了画面（スライド20、index 19）から戻れない
          if (isRegistered && swiper.activeIndex < 19) {
            swiper.slideTo(19);
          }
        }}
      >
        <SwiperSlide><Intro1 name={nickname} onNameChange={setNickname} onStart={handleStart} /></SwiperSlide>
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
        <SwiperSlide><ResultWheel scores={scores} onNext={handleResultNext} /></SwiperSlide>
        <SwiperSlide><AdditionalQuestion selectedAreas={selectedAreas} onToggleArea={handleToggleArea} freeText={freeText} onFreeTextChange={setFreeText} onBack={handleBackToResult} /></SwiperSlide>
        <SwiperSlide>
          <NewsletterSignup
            email={email}
            onEmailChange={setEmail}
            onSubmit={handleNewsletterSubmit}
            isSubmitting={isSubmitting}
            error={submitError}
          />
        </SwiperSlide>
        <SwiperSlide><CompleteCTA selectedAreas={selectedAreas} scores={scores} /></SwiperSlide>
      </Swiper>

      {/* Journey Bar */}
      <JourneyBar currentStep={currentSlide} totalSteps={totalSlides} />
    </div>
  );
}
