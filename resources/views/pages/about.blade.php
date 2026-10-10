@extends('layouts.app')
@section('title', 'About Us · Christ Embassy Lekki')
@section('content')

<style>
  .ab {
    --ab-navy: #0f1b3d;
    --ab-navy-2: #17275a;
    --ab-gold: #b8913f;
    --ab-gold-soft: #f6efdd;
    --ab-ink: var(--ink, #1c2340);
    --ab-ink-soft: var(--ink-soft, #5b6380);
    --ab-line: #e8e4d9;
    --ab-serif: 'Playfair Display', 'Cormorant Garamond', Georgia, 'Times New Roman', serif;
  }
  .ab * { box-sizing: border-box; }
  .ab-wrap { max-width: 1120px; margin: 0 auto; padding: 0 20px; }

  /* Header */
  .ab-head { text-align: center; padding: 64px 0 40px; }
  .ab-eyebrow {
    display: inline-block; font-size: 12px; font-weight: 600; letter-spacing: .28em;
    text-transform: uppercase; color: var(--ab-gold);
  }
  .ab-head h1 {
    font-family: var(--ab-serif); font-size: clamp(2.4rem, 5vw, 3.6rem);
    color: var(--ab-ink); margin: 14px 0 0; font-weight: 600; line-height: 1.1;
  }
  .ab-rule { display: flex; align-items: center; justify-content: center; gap: 12px; margin: 22px 0 18px; }
  .ab-rule span { width: 56px; height: 1px; background: var(--ab-gold); }
  .ab-rule i { width: 7px; height: 7px; background: var(--ab-gold); transform: rotate(45deg); display: block; }
  .ab-lead { max-width: 640px; margin: 0 auto; color: var(--ab-ink-soft); line-height: 1.85; font-size: 1.05rem; }

  /* Cards */
  .ab-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; padding-bottom: 72px; }
  .ab-card {
    position: relative; background: #fff; border: 1px solid var(--ab-line); border-radius: 18px;
    padding: 36px 30px 32px; text-align: center; overflow: hidden;
    box-shadow: 0 1px 2px rgba(15,27,61,.04), 0 12px 32px -18px rgba(15,27,61,.18);
    transition: transform .3s ease, box-shadow .3s ease;
  }
  .ab-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, transparent, var(--ab-gold), transparent);
  }
  .ab-card:hover { transform: translateY(-6px); box-shadow: 0 1px 2px rgba(15,27,61,.04), 0 22px 44px -18px rgba(15,27,61,.28); }
  .ab-icon {
    width: 58px; height: 58px; margin: 0 auto 20px; border-radius: 50%;
    background: var(--ab-gold-soft); color: var(--ab-gold);
    display: flex; align-items: center; justify-content: center;
  }
  .ab-icon svg { width: 26px; height: 26px; }
  .ab-card h3 { font-family: var(--ab-serif); font-size: 1.35rem; color: var(--ab-ink); margin: 0 0 12px; font-weight: 600; }
  .ab-card p { color: var(--ab-ink-soft); line-height: 1.8; margin: 0; font-size: .98rem; }

  /* Global identity */
  .ab-global {
    position: relative; background: linear-gradient(135deg, var(--ab-navy) 0%, var(--ab-navy-2) 100%);
    color: #fff; overflow: hidden;
  }
  .ab-global::before, .ab-global::after {
    content: ''; position: absolute; border-radius: 50%; border: 1px solid rgba(184,145,63,.28);
  }
  .ab-global::before { width: 520px; height: 520px; top: -240px; right: -140px; }
  .ab-global::after { width: 360px; height: 360px; bottom: -200px; left: -100px; }
  .ab-global-grid {
    position: relative; z-index: 1; display: grid; grid-template-columns: 1.1fr .9fr;
    gap: 56px; align-items: center; padding: 88px 0;
  }
  .ab-global .ab-eyebrow { color: #d9b866; }
  .ab-global h2 {
    font-family: var(--ab-serif); font-size: clamp(2rem, 4vw, 2.9rem); margin: 14px 0 20px;
    font-weight: 600; line-height: 1.15;
  }
  .ab-global p { color: rgba(255,255,255,.78); line-height: 1.9; font-size: 1.02rem; margin: 0 0 26px; }
  .ab-badge {
    display: inline-flex; align-items: center; gap: 10px; padding: 10px 20px;
    border: 1px solid rgba(217,184,102,.5); border-radius: 999px;
    font-size: 12px; letter-spacing: .22em; font-weight: 600; color: #d9b866; text-transform: uppercase;
  }
  .ab-badge i { width: 6px; height: 6px; border-radius: 50%; background: #d9b866; display: block; }
  .ab-quote {
    background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
    border-radius: 20px; padding: 44px 36px 36px; backdrop-filter: blur(4px); position: relative;
  }
  .ab-quote svg { width: 38px; height: 38px; color: #d9b866; margin-bottom: 14px; }
  .ab-quote blockquote {
    margin: 0; font-family: var(--ab-serif); font-style: italic; font-size: 1.3rem; line-height: 1.7; color: #fff;
  }
  .ab-quote cite {
    display: block; margin-top: 22px; font-style: normal; font-size: 12px; letter-spacing: .22em;
    text-transform: uppercase; color: #d9b866; font-family: inherit;
  }

  /* Worship */
  .ab-worship { padding: 88px 0 96px; }
  .ab-worship .ab-head { padding: 0 0 48px; }
  .ab-times { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
  .ab-time {
    background: #fff; border: 1px solid var(--ab-line); border-radius: 18px; padding: 30px 26px;
    display: flex; flex-direction: column; position: relative;
    box-shadow: 0 12px 32px -20px rgba(15,27,61,.22);
    transition: transform .3s ease, border-color .3s ease;
  }
  .ab-time:hover { transform: translateY(-5px); border-color: var(--ab-gold); }
  .ab-tag {
    align-self: flex-start; font-size: 11px; font-weight: 700; letter-spacing: .18em; text-transform: uppercase;
    color: var(--ab-gold); background: var(--ab-gold-soft); padding: 6px 12px; border-radius: 999px; margin-bottom: 20px;
  }
  .ab-time h3 { font-family: var(--ab-serif); font-size: 1.3rem; margin: 0 0 8px; color: var(--ab-ink); font-weight: 600; }
  .ab-time p { color: var(--ab-ink-soft); font-size: .93rem; line-height: 1.7; margin: 0 0 22px; flex: 1; }
  .ab-clock {
    display: flex; align-items: center; gap: 10px; padding-top: 18px; border-top: 1px dashed var(--ab-line);
    font-family: var(--ab-serif); font-size: 1.55rem; color: var(--ab-navy); font-weight: 600;
  }
  .ab-clock svg { width: 20px; height: 20px; color: var(--ab-gold); flex-shrink: 0; }
  .ab-clock small { display: block; font-family: inherit; font-size: .78rem; font-weight: 500; color: var(--ab-ink-soft); font-family: system-ui, sans-serif; margin-top: 2px; }

  @media (max-width: 960px) {
    .ab-cards { grid-template-columns: 1fr; max-width: 520px; margin: 0 auto; }
    .ab-global-grid { grid-template-columns: 1fr; gap: 40px; padding: 64px 0; }
    .ab-times { grid-template-columns: repeat(2, 1fr); }
  }
  @media (max-width: 560px) {
    .ab-head { padding: 44px 0 28px; }
    .ab-times { grid-template-columns: 1fr; }
    .ab-quote { padding: 34px 24px 28px; }
    .ab-quote blockquote { font-size: 1.12rem; }
    .ab-worship { padding: 64px 0 72px; }
  }
</style>

<div class="ab">

  {{-- Page header --}}
  <div class="ab-wrap">
    <header class="ab-head">
      <span class="ab-eyebrow">Christ Embassy Lekki</span>
      <h1>About Us</h1>
      <div class="ab-rule"><span></span><i></i><span></span></div>
      <p class="ab-lead">
        Christ Embassy is more than a place you visit on Sundays. It is a family, a calling, and a
        global movement carrying the divine life to every nation.
      </p>
    </header>

    {{-- Three pillars --}}
    <section class="ab-cards" aria-label="Who we are">
      <article class="ab-card">
        <div class="ab-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
        </div>
        <h3>Our Vision</h3>
        <p>To take His divine presence to the peoples and nations of the world, demonstrating the character of His Spirit.</p>
      </article>

      <article class="ab-card">
        <div class="ab-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20a15 15 0 0 1 0-20z"/></svg>
        </div>
        <h3>More Than A Church</h3>
        <p>You become part of something that’s more than a local assembly; you become part of God’s global vision.</p>
      </article>

      <article class="ab-card">
        <div class="ab-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4h7a3 3 0 0 1 3 3v13a2 2 0 0 0-2-2H2z"/><path d="M22 4h-7a3 3 0 0 0-3 3v13a2 2 0 0 1 2-2h8z"/></svg>
        </div>
        <h3>Life Meaning</h3>
        <p>One Word from God will revolutionize your life forever, establishing blessings in every area of your world.</p>
      </article>
    </section>
  </div>

  {{-- A Global Identity --}}
  <section class="ab-global">
    <div class="ab-wrap">
      <div class="ab-global-grid">
        <div>
          <span class="ab-eyebrow">Who We Are</span>
          <h2 style="color: white;">A Global Identity.</h2>
          <p>
            Christ Embassy is not just a local assembly; it’s a vision. The Lord has called us to fulfill
            a very definite purpose, which is to take His divine presence to the peoples and nations.
          </p>
          <span class="ab-badge"><i></i> LoveWorld Incorporated</span>
        </div>

        <figure class="ab-quote" style="margin:0">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M9.5 5C6 6 3.5 9 3.5 13v6h7v-7h-3.4c.2-2 1.4-3.5 3.4-4.3zm10 0c-3.5 1-6 4-6 8v6h7v-7h-3.4c.2-2 1.4-3.5 3.4-4.3z"/></svg>
          <blockquote>
            “This is achieved through every available means, as the Ministry is driven by a passion to see
            men all over the world come to the knowledge of the divine life.”
          </blockquote>
          <cite>— Our Mandate</cite>
        </figure>
      </div>
    </div>
  </section>

  {{-- Worship With Us --}}
  <section class="ab-worship">
    <div class="ab-wrap">
      <header class="ab-head">
        <span class="ab-eyebrow">Experience The Miraculous</span>
        <h1 style="font-size:clamp(2rem,4vw,3rem)">Worship With Us</h1>
        <div class="ab-rule"><span></span><i></i><span></span></div>
      </header>

      @php
        $services = [
          ['tag' => 'Weekly', 'title' => 'Sunday Service', 'desc' => 'Join us every Sunday to worship, hear the Word, and experience God’s presence.', 'time' => '9:00 AM', 'note' => '9:00 AM session'],
          ['tag' => 'Wednesday', 'title' => 'Mid-Week Service', 'desc' => 'A mid-week refreshing in the Word to keep you strong and rooted.', 'time' => '6:30 PM', 'note' => null],
          ['tag' => '1st Sunday of the month', 'title' => 'Communion Service', 'desc' => 'Global broadcast service with Pastor Chris Oyakhilome.', 'time' => '3:00 PM', 'note' => null],
          ['tag' => '1st Saturday of the month', 'title' => 'Manifestation Rally', 'desc' => 'Prayer and prophecy session for Lagos Zone 5.', 'time' => '6:00 AM', 'note' => null],
        ];
      @endphp

      <div class="ab-times">
        @foreach ($services as $s)
          <article class="ab-time">
            <span class="ab-tag">{{ $s['tag'] }}</span>
            <h3>{{ $s['title'] }}</h3>
            <p>{{ $s['desc'] }}</p>
            <div class="ab-clock">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
              <div>
                {{ $s['time'] }}
                @if ($s['note'])<small>{{ $s['note'] }}</small>@endif
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>

</div>
@endsection
