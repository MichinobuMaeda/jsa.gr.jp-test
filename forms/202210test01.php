<?php
// 表題
$title = 'JSA 24総学参加登録テスト';
// 状態 ( prepared: 準備中, active: 受付中, closed: 受付終了 )
$status = 'active';
// イベントの情報を掲載したURL
$url = 'https://jsa.gr.jp/d/sougaku/start';
// 自動送信メールアドレス ※迷惑メールとされることを防ぐために @jsa.gr.jp のアドレスを使うこと。
$from = 'autosend@jsa.gr.jp';
// 登録受付メール文面
$reply = <<<END
第24回総合学術研究集会の参加登録ありがとうございました。
会場（接続方法）と予稿集は日本科学者会議ホームページの
「第24回総合学術研究集会 in 大阪」コーナー
https://jsa.gr.jp/d/sougaku/start
の「アクセス情報・予稿集」をご参照ください。

ユーザー名 : 24sougaku
パスワード : hefvagmin-43

また、参加登録は以下の内容で受け付けました。
登録内容に修正、取り消しがある場合は、参加登録の再入力をお願いします。
END;
// 表示項目・入力項目
$items = [
  [
    'type' => 'message',
    'label' => <<<END
日本科学者会議 第24回 総合学術研究集会 ( 11/19 ～ 12/11 )
オンライン開催・参加無料です。
登録いただいた方に後日、参加（接続）方法のご案内をお送りします。
END,
  ],
  [
    'type' => 'message',
    'label' => <<<END
メールアドレスは @jsa.gr.jp からのメールを受信できるアドレスとしてください。
登録受付後に、記入していただいたメールアドレス宛に受け付けた内容を自動でお送りします。
END,
  ],
  [
    'type' => 'email',
    'label' => 'Email',
    'name' => 'email',
    'required' => true,
  ],
  [
    'type' => 'text',
    'label' => '氏名',
    'name' => 'name',
    'required' => true,
  ],
  [
    'type' => 'radio',
    'label' => '会員・非会員',
    'name' => 'membership',
    'required' => true,
    'options' => [
      '会員',
      '非会員',
    ],
  ],
  [
    'type' => 'radio',
    'label' => '一般・大学院生・学生',
    'name' => 'classification',
    'required' => true,
    'options' => [
      '一般',
      '大学院生',
      '学生',
      'その他',
    ],
  ],
  [
    'type' => 'text',
    'label' => '都道府県（会員は所属支部、非会員は職場もしくは居住地の都道府県）',
    'name' => 'prefecture',
    'required' => true,
  ],
  [
    'type' => 'radio',
    'label' => '性別（回答は任意です。大まかな参加者の分布を知るためにお伺いするものです。）',
    'name' => 'gender',
    'required' => false,
    'options' => [
      '男性',
      '女性',
      'その他',
      'わからない・答えたくない',
    ],
  ],
  [
    'type' => 'radio',
    'label' => '年齢（回答は任意です。大まかな参加者の分布を知るためにお伺いするものです。）',
    'name' => 'age',
    'required' => false,
    'options' => [
      '10歳代',
      '20歳代',
      '30歳代',
      '40歳代',
      '50歳代',
      '60歳以上',
      '答えたくない',
    ],
  ],
  [
    'type' => 'message',
    'label' => <<<END
予稿集購入申込：研究集会終了後に予稿集（印刷版冊子）を作成し、申込のあった方に販売します。 1冊 2,000円（送料込み）の予定です。
※研究集会前に予稿集オンライン版は無料でダウンロードいただけます。
END,
  ],
  [
    'type' => 'checkbox',
    'label' => '予稿集（冊子）購入を申し込む。',
    'name' => 'book',
  ],
  [
    'type' => 'message',
    'label' => <<<END
予稿集（冊子）を申し込みされた方は、予稿集のお届け先住所を入力してください。
郵便番号と建物・部屋番号もご記載ください。
END,
  ],
  [
    'type' => 'textarea',
    'label' => '住所',
    'name' => 'address',
    'required' => false,
  ],
  [
    'type' => 'message',
    'label' => <<<END
興味のある企画・分科会：おおよその参加人数を見積もるための調査です。わかる範囲でご回答ください。当日はどの企画・分科会にもご参加いただけます。
※複数選択可
END,
  ],
  [
    'type' => 'checkbox',
    'label' => '全体会１：特別報告「戦争と平和 ― 核兵器の開発の歴史を中心に」（11月19日13:00～）',
    'name' => 'Z1',
  ],
  [
    'type' => 'checkbox',
    'label' => '全体会２：特別報告「コロナウイルスと One Health」（11月19日15:15～）',
    'name' => 'Z2',
  ],
  [
    'type' => 'checkbox',
    'label' => '全体会３：特別報告「気候危機と人権 － 気候危機を回避するために」（12月11日15:45～）',
    'name' => 'Z3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ａ１：国際社会における平和と人権 ～ ウクライナ侵攻が突きつける国際問題研究の新たな課題',
    'name' => 'A1',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ａ２：戦争と平和をめぐる科学者の社会的責任、市民との共同',
    'name' => 'A2',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ａ３：「ウクライナ侵略戦争」が起きて ― 改憲阻止の闘い方と戦争廃絶の方策を語ろう',
    'name' => 'A3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ａ４：ロシアのウクライナ軍事侵攻 ― 2022年',
    'name' => 'A4',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ１：気候危機に立ち向かう ― 自然エネルギーと省エネの社会に向けて',
    'name' => 'B1',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ２：再生可能エネルギーと健康・環境影響',
    'name' => 'B2',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ３：脱原発への課題と展望',
    'name' => 'B3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ５：避難の権利 ― 原発訴訟2022年',
    'name' => 'B5',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ６：公害・環境問題の現在',
    'name' => 'B6',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ７：大阪での公害・環境問題に取り組んできて',
    'name' => 'B7',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ８：リニア中央新幹線問題の検討と運動・経験交流(part6)',
    'name' => 'B8',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｂ９：災害分科会',
    'name' => 'B9',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｃ１：新型コロナウイルス感染症をめぐる現状と課題',
    'name' => 'C1',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｃ２：日本の食と農を考える',
    'name' => 'C2',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｃ３：オセアニア海洋文化とモンゴル遊牧文化からSDGsを考える',
    'name' => 'C3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｃ４：労働者の権利擁護と全国一律の最低賃金の大幅引上げ',
    'name' => 'C4',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｄ１：自然科学の進展を俯瞰する',
    'name' => 'D1',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｄ２：科学技術の現状批判 ― 日本の科学・技術の健全な発展のための課題(part14)',
    'name' => 'D2',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｄ３：科学技術サロン ― 日本の科学・技術の現状とロマンを語る(part15)',
    'name' => 'D3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｄ４：加速する科学・技術の進展とその社会実装について考える',
    'name' => 'D4',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｅ１：経済安保法と国際卓越研究大学法は、学問の自由と大学の自治に何をもたらすのか',
    'name' => 'E1',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｅ２：いま改めて研究者の権利・地位と倫理を考える',
    'name' => 'E2',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｅ３：持続可能な高等教育を考える',
    'name' => 'E3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｆ１：社会的ひきこもり・不登校',
    'name' => 'F1',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｆ２：社会的ひきこもり・不登校を語る(当事者、経験者歓迎)',
    'name' => 'F2',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｆ３：学校における学びの保障を考える',
    'name' => 'F3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｆ４：現在の情勢をジェンダー視点から考える',
    'name' => 'F4',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｇ１：アメリカの現状と今後を考える',
    'name' => 'G1',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｇ３：市民と科学者を結ぶ雑誌『日本の科学者』の歴史的役割と展望',
    'name' => 'G3',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｇ４：高校生と一緒に考える大阪の未来',
    'name' => 'G4',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｇ５：モンゴルにおける言葉と文化の21世紀',
    'name' => 'G5',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会Ｇ６：転換期における抵抗運動の論理',
    'name' => 'G6',
  ],
  [
    'type' => 'message',
    'label' => <<<END
この研究集会の開催をどこで知りましたか？
※複数選択可
END,
  ],
  [
    'type' => 'checkbox',
    'label' => '『日本の科学者』',
    'name' => 'J',
  ],
  [
    'type' => 'checkbox',
    'label' => '日本科学者会議配布物（サーキュラ等）',
    'name' => 'P',
  ],
  [
    'type' => 'checkbox',
    'label' => '日本科学者会議Webサイト',
    'name' => 'W',
  ],
  [
    'type' => 'checkbox',
    'label' => '分科会設置者からの紹介',
    'name' => 'M',
  ],
  [
    'type' => 'checkbox',
    'label' => 'SNS（FacebookやTwitterなど）',
    'name' => 'S',
  ],
  [
    'type' => 'checkbox',
    'label' => 'その他',
    'name' => 'O',
  ],
  [
    'type' => 'textarea',
    'label' => 'その他、ご質問やご意見があればお願いします（任意）',
    'name' => 'message',
    'required' => false,
  ],
];
