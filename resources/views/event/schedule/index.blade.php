{{--
    resources/views/event/schedule/index.blade.php
    Route : GET /event/{eventId}/match-schedule
    Controller : EventMatchController@publicSchedule
--}}
<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->name ?? 'Тэмцээний хуваарь' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,600;9..40,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:          #0b0c14;
            --surface:     #12131e;
            --card:        #181924;
            --border:      rgba(255,255,255,0.07);
            --accent:      #f06020;
            --accent-glow: rgba(240,96,32,0.13);
            --teal:        #1bc5bd;
            --blue:        #3699ff;
            --red:         #f64e60;
            --muted:       #555670;
            --soft:        #8888a8;
            --text:        #ddddef;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg); color: var(--text); font-family: 'DM Sans', sans-serif; min-height: 100vh; }

        /* ===== EVENT BANNER ===== */
        .s-banner {
            position: relative;
            background: linear-gradient(135deg, #0d0e18 0%, #14151f 50%, #1a1020 100%);
            border-bottom: 1px solid var(--border);
            padding: 28px 34px 24px;
            overflow: hidden;
        }
        .s-banner::before {
            content: '';
            position: absolute;
            top: -60%; right: -10%;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(240,96,32,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .s-banner::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), transparent 60%);
        }
        .s-banner-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            position: relative;
            z-index: 2;
        }
        .s-banner-tag {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 14px 4px;
            border-radius: 4px;
        }
        .s-banner-title {
            position: relative;
            z-index: 2;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px;
            letter-spacing: 3px;
            line-height: 1;
            color: #fff;
        }
        .s-banner-title .accent {
            color: var(--accent);
        }
    

        /* ===== STICKY NAV BAR ===== */
        .s-nav {
            position: sticky; top: 0; z-index: 300;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            height: 48px; padding: 0 28px;
        }
        .s-nav-left { display: flex; align-items: center; gap: 14px; }
        .s-logo { display: flex; align-items: center; gap: 14px; }
        .s-logo img { height: 32px; width: auto; display: block; }
        .s-logo-label {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 14px; letter-spacing: 3px; color: var(--soft); font-weight: 300;
        }
        .s-nav-right { display: flex; align-items: center; gap: 14px; }

        .tv-toggle-btn {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 16px;
            background: rgba(255,255,255,.05); border: 1px solid var(--border); border-radius: 8px;
            color: var(--soft); font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 600; letter-spacing: .5px; cursor: pointer;
            transition: background .18s, color .18s, border-color .18s;
        }
        .tv-toggle-btn:hover  { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }
        .tv-toggle-btn.active { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }

        .tv-refresh-ring { display: none; align-items: center; gap: 8px; font-size: 11px; color: var(--muted); }
        .tv-refresh-ring.visible { display: flex; }
        .ring-svg { transform: rotate(-90deg); }
        .ring-track { fill: none; stroke: rgba(255,255,255,.07); stroke-width: 3; }
        .ring-fill  { fill: none; stroke: var(--accent); stroke-width: 3;
                      stroke-dasharray: 50.27; stroke-dashoffset: 0;
                      transition: stroke-dashoffset 1s linear; stroke-linecap: round; }

        .s-live { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: var(--teal); letter-spacing: 1.5px; text-transform: uppercase; }
        .s-live-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--teal); animation: blink 1.6s infinite; }
        @keyframes blink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.3;transform:scale(1.4)} }

        /* LAYOUT */
        .s-layout { display: grid; grid-template-columns: 248px 1fr; min-height: calc(100vh - 48px - 130px); }
        .s-sidebar {
            background: var(--surface); border-right: 1px solid var(--border);
            position: sticky; top: 48px; height: calc(100vh - 48px); overflow-y: auto; padding: 24px 0 32px;
        }
        .s-sidebar::-webkit-scrollbar { width: 3px; }
        .s-sidebar::-webkit-scrollbar-thumb { background: var(--muted); border-radius: 3px; }
        .sb-label { font-size: 10px; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--muted); padding: 0 18px 12px; }
        .sb-day-title { font-family: 'Bebas Neue', sans-serif; font-size: 12px; letter-spacing: 2px; color: var(--soft); padding: 4px 18px; }
        .sb-mat {
            display: flex; align-items: center; gap: 10px; width: 100%; padding: 8px 18px;
            background: none; border: none; border-left: 2px solid transparent;
            color: var(--soft); font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 500;
            cursor: pointer; text-align: left; transition: background .15s, color .15s, border-color .15s;
        }
        .sb-mat:hover { background: var(--accent-glow); color: var(--text); }
        .sb-mat.active { background: var(--accent-glow); color: var(--accent); border-left-color: var(--accent); }
        .sb-mat-num { width: 26px; height: 26px; border-radius: 6px; background: rgba(255,255,255,.05); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0; }
        .sb-mat.active .sb-mat-num { background: var(--accent-glow); color: var(--accent); }
        .sb-divider { height: 1px; background: var(--border); margin: 8px 18px 14px; }

        /* FILTER */
        .s-filters { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 28px; }
        .f-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); }
        .f-select { background: var(--card); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-family: 'DM Sans', sans-serif; font-size: 13px; padding: 8px 14px; cursor: pointer; outline: none; transition: border-color .18s; min-width: 130px; }
        .f-select:focus { border-color: var(--accent); }
        .f-select option { background: #1a1b2a; }
        .f-btn { padding: 9px 22px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700; cursor: pointer; transition: opacity .18s, transform .1s; }
        .f-btn:hover { opacity: .85; }
        .f-btn:active { transform: scale(.97); }

        .s-main { padding: 30px 34px; overflow-y: auto; }

        /* DAY / MAT HEADERS */
        .m-day-header { display: flex; align-items: center; gap: 14px; margin: 36px 0 20px; }
        .m-day-header:first-child { margin-top: 0; }
        .m-day-title { font-family: 'Bebas Neue', sans-serif; font-size: 30px; letter-spacing: 2px; color: var(--accent); line-height: 1; }
        .m-day-date { font-size: 12px; color: var(--muted); font-weight: 500; }
        .m-day-line { flex: 1; height: 1px; background: var(--border); }
        .m-mat-header { font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: var(--soft); margin: 22px 0 10px; display: flex; align-items: center; gap: 10px; }
        .m-mat-header::after { content:''; flex:1; height:1px; background: var(--border); }

        /* MATCH ROW */
        .m-info-label { font-size: 10px; color: var(--muted); padding: 5px 14px 2px; letter-spacing: .4px; }
        .m-row { display: flex; align-items: stretch; background: var(--card); border: 1px solid var(--border); border-radius: 10px; margin-bottom: 5px; overflow: hidden; min-height: 64px; transition: border-color .18s, background .18s; }
        .m-row:hover { border-color: rgba(240,96,32,.25); background: #1d1e2e; }
        .m-status { display: flex; align-items: center; justify-content: center; padding: 0 12px; min-width: 40px; }
        .st-icon { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .st-icon.done   { background: var(--teal); }
        .st-icon.active { background: var(--blue); }
        .st-icon.pending{ background: #1e1f32; border: 1.5px solid var(--muted); }
        .m-num { display: flex; align-items: center; justify-content: center; font-family: 'Bebas Neue', sans-serif; font-size: 26px; color: #fff; padding: 0 16px; min-width: 54px; border-right: 1px solid var(--border); letter-spacing: 1px; }
        .m-players { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 10px 16px; min-width: 0; gap: 5px; }
        .p-row { display: flex; align-items: center; gap: 8px; }
        .p-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
        .p-dot.r { background: var(--red); }
        .p-dot.b { background: var(--blue); }
        .p-name { font-size: 14px; font-weight: 600; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .p-name.w { color: var(--teal); }
        .p-club { font-size: 11px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .p-win { font-size: 10px; font-weight: 700; color: var(--teal); background: rgba(27,197,189,.12); border-radius: 4px; padding: 2px 7px; white-space: nowrap; flex-shrink: 0; }
        .m-time { display: flex; align-items: center; justify-content: center; padding: 0 18px; min-width: 88px; font-size: 13px; font-weight: 600; color: var(--soft); border-left: 1px solid var(--border); white-space: nowrap; }
        .m-time.done { color: var(--teal); font-size: 11px; letter-spacing: .5px; }
        .m-total { display: flex; align-items: center; justify-content: space-between; background: rgba(255,255,255,.025); border: 1px solid var(--border); border-radius: 8px; padding: 9px 16px; margin: 6px 0 4px; font-size: 12px; color: var(--muted); }
        .m-total b { color: var(--soft); }
        .m-total .hi { color: var(--accent); font-weight: 700; }

        /* STATE */
        .s-loading, .s-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 80px 0; gap: 14px; color: var(--muted); font-size: 14px; }
        .s-spinner { width: 34px; height: 34px; border: 3px solid var(--border); border-top-color: var(--accent); border-radius: 50%; animation: spin .75s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .s-empty-icon { font-size: 34px; opacity: .35; }

        /* TV MODE */
        body.tv-mode .s-banner   { display: none; }
        body.tv-mode .s-sidebar  { display: none; }
        body.tv-mode .s-layout   { grid-template-columns: 1fr; min-height: calc(100vh - 48px); }
        body.tv-mode .s-filters  { display: none; }
        body.tv-mode .s-main     { padding: 20px; }
        body.tv-mode .tv-day-block { margin-bottom: 28px; }
        body.tv-mode .tv-day-label { font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 3px; color: var(--accent); margin-bottom: 12px; display: flex; align-items: center; gap: 12px; }
        body.tv-mode .tv-day-label::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        body.tv-mode .tv-mat-grid { display: grid; grid-template-columns: repeat(var(--tv-cols, 2), 1fr); gap: 12px; align-items: start; }
        body.tv-mode .tv-mat-col { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        body.tv-mode .tv-col-header { background: rgba(240,96,32,.08); border-bottom: 1px solid var(--border); padding: 10px 14px; display: flex; align-items: center; justify-content: space-between; }
        body.tv-mode .tv-col-mat-name { font-family: 'Bebas Neue', sans-serif; font-size: 18px; letter-spacing: 2px; color: var(--accent); }
        body.tv-mode .tv-col-stats { font-size: 11px; color: var(--muted); }
        body.tv-mode .tv-col-stats span { color: var(--soft); font-weight: 600; }
        body.tv-mode .m-info-label { font-size: 9px; padding: 4px 10px 1px; }
        body.tv-mode .m-row        { min-height: 54px; border-radius: 0; margin-bottom: 1px; border-left: none; border-right: none; border-top: none; }
        body.tv-mode .m-num        { font-size: 20px; min-width: 40px; padding: 0 10px; }
        body.tv-mode .m-status     { min-width: 32px; padding: 0 8px; }
        body.tv-mode .st-icon      { width: 20px; height: 20px; }
        body.tv-mode .m-players    { padding: 7px 10px; gap: 3px; }
        body.tv-mode .p-name       { font-size: 12px; }
        body.tv-mode .p-club       { font-size: 10px; }
        body.tv-mode .p-win        { font-size: 9px; padding: 1px 5px; }
        body.tv-mode .m-time       { min-width: 66px; padding: 0 10px; font-size: 12px; }
        body.tv-mode .m-time.done  { font-size: 10px; }
        body.tv-mode .m-total      { border-radius: 0; margin: 0; border-left: none; border-right: none; border-bottom: none; }
        body.tv-mode .m-row.is-active { border-left: 3px solid var(--blue) !important; background: rgba(54,153,255,.08) !important; }
        body.tv-mode .m-row.is-next   { border-left: 3px solid var(--accent) !important; }

        .tv-clock { display: none; font-family: 'Bebas Neue', sans-serif; font-size: 15px; letter-spacing: 2px; color: var(--soft); }
        body.tv-mode .tv-clock { display: block; }
        body.tv-mode .s-live   { display: none; }

        .tv-day-selector { display: none; align-items: center; gap: 8px; margin-bottom: 16px; }
        body.tv-mode .tv-day-selector { display: flex; }
        .tv-day-tab { padding: 6px 18px; background: rgba(255,255,255,.04); border: 1px solid var(--border); border-radius: 20px; color: var(--soft); font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 600; cursor: pointer; transition: background .15s, color .15s, border-color .15s; }
        .tv-day-tab:hover  { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }
        .tv-day-tab.active { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }
        .tv-col-picker { display: none; align-items: center; gap: 6px; margin-left: auto; }
        body.tv-mode .tv-col-picker { display: flex; }
        .tv-col-picker span { font-size: 11px; color: var(--muted); }
        .tv-col-opt { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,.04); border: 1px solid var(--border); border-radius: 6px; color: var(--soft); font-size: 12px; font-weight: 700; cursor: pointer; transition: background .15s, color .15s; }
        .tv-col-opt:hover  { background: var(--accent-glow); color: var(--accent); }
        .tv-col-opt.active { background: var(--accent-glow); color: var(--accent); border-color: var(--accent); }

        @media (max-width: 700px) {
            .s-layout { grid-template-columns: 1fr; }
            .s-sidebar { position: static; height: auto; border-right: none; border-bottom: 1px solid var(--border); padding: 12px 0; }
            .s-main { padding: 18px 14px; }
            .s-banner { padding: 20px 18px 18px; }
            .s-banner-title { font-size: 32px; }
            body.tv-mode .tv-mat-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<!-- ===== EVENT BANNER ===== -->
<div class="s-banner">
    <div class="s-banner-top">
        <span class="s-banner-tag">{{ $event->config->sport->name }}</span>
    </div>
    <div class="s-banner-title">
        {{ $event->name }}
    </div>
</div>

<!-- ===== STICKY NAV BAR ===== -->
<nav class="s-nav">
    <div class="s-nav-left">
        <div class="s-logo">
            <span class="s-logo-label">UniQ</span>
        </div>
    </div>
    <div class="s-nav-right">
        <div class="tv-refresh-ring" id="tv-refresh-ring">
            <svg class="ring-svg" width="22" height="22" viewBox="0 0 22 22">
                <circle class="ring-track" cx="11" cy="11" r="8"/>
                <circle class="ring-fill"  cx="11" cy="11" r="8" id="ring-fill"/>
            </svg>
            <span id="tv-countdown">3:00</span>
        </div>
        <div class="tv-clock" id="tv-clock">00:00:00</div>
        <button class="tv-toggle-btn" id="tv-btn" onclick="toggleTV()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
            </svg>
            TV горим
        </button>
        <div class="s-live"><div class="s-live-dot"></div>LIVE</div>
    </div>
</nav>

<div class="s-layout">
    <aside class="s-sidebar">
        <div class="sb-label">Өдөр / Мат</div>
        @php $days = $configViewDict['days'] ?? [] @endphp
        @forelse($days as $day)
            <div class="sb-day">
                <div class="sb-day-title">{{ $day->item_no }}-р өдөр</div>
                @php $dayMates = $configViewDict['mate'][$day->id] ?? [] @endphp
                @foreach($dayMates as $mate)
                    <button class="sb-mat"
                            data-day="{{ $day->id }}"
                            data-mat="{{ $mate->id }}"
                            onclick="sidebarSelect({{ $day->id }}, {{ $mate->id }}, this)">
                        <span class="sb-mat-num">{{ $mate->mate_no }}</span>
                        Мат {{ $mate->mate_no }}
                    </button>
                @endforeach
                <div class="sb-divider"></div>
            </div>
        @empty
            <div style="padding:20px;font-size:13px;color:var(--muted)">Өдөр олдсонгүй</div>
        @endforelse
    </aside>

    <main class="s-main">
        <div class="s-filters">
            <span class="f-label">Шүүлтүүр</span>
            <select class="f-select" id="f-day">
                <option value="">— Өдөр —</option>
                @forelse($days as $day)
                    <option value="{{ $day->id }}">{{ $day->item_no }}-р өдөр</option>
                @empty
                @endforelse
            </select>
            <select class="f-select" id="f-mat">
                <option value="">— Мат —</option>
            </select>
            <button class="f-btn" id="f-btn">Хайх</button>
        </div>

        <div class="tv-day-selector" id="tv-day-selector">
            @forelse($days as $day)
                <button class="tv-day-tab" data-day-id="{{ $day->id }}" onclick="tvSelectDay({{ $day->id }}, this)">
                    {{ $day->item_no }}-р өдөр
                </button>
            @empty
            @endforelse
            <div class="tv-col-picker">
                <span>Багана:</span>
                <div class="tv-col-opt active" data-cols="2" onclick="setTVCols(2,this)">2</div>
                <div class="tv-col-opt" data-cols="3" onclick="setTVCols(3,this)">3</div>
                <div class="tv-col-opt" data-cols="4" onclick="setTVCols(4,this)">4</div>
                <div class="tv-col-opt" data-cols="6" onclick="setTVCols(6,this)">6</div>
            </div>
        </div>

        <div id="s-results">
            <div class="s-loading"><div class="s-spinner"></div><span>Уншиж байна…</span></div>
        </div>
    </main>
</div>

<script>
(function () {
    const EVENT_ID   = {{ (int)($eventConfig->event_id ?? ($eventConfig['event_id'] ?? 0)) }};
    const START_TIME = @json($eventConfig->start_time ?? ($eventConfig['start_time'] ?? '08:00:00'));
    const FETCH_URL  = "{{ route('event.public.schedule.matches', ['eventId' => $eventConfig->event_id ?? ($eventConfig['event_id'] ?? 0)]) }}";

    const MATES_BY_DAY = (function () {
        const raw = @json($configViewDict['mate']);
        const out = {};
        Object.keys(raw).forEach(k => { out[parseInt(k, 10)] = raw[k]; });
        return out;
    })();

    let isTV = false, tvDayId = null, tvCols = 2;
    let tvTimer = null, tvCountdown = 180, lastData = null, clockTimer = null;

    const p2     = n => String(n).padStart(2, '0');
    const fmt    = d => p2(d.getHours()) + ':' + p2(d.getMinutes());
    const fmtDur = m => `${Math.floor(m/60)}ц ${p2(Math.round(m%60))}м`;
    const esc    = s => String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');

    function getMatchInfo(b) {
        const entry = b.reg_one?.entry?.name ?? '';
        const age   = b.reg_one?.age ?? null;
        const belt  = b.reg_one?.belt?.name ?? '';
        const wt    = b.reg_one?.weight?.weight ?? '';
        let ap = '';
        if (age) {
            if (age.start_age == null)    ap = '-' + age.end_age;
            else if (age.end_age == null) ap = age.start_age + '+';
            else                          ap = age.start_age + '-' + age.end_age;
        }
        return [entry, ap, belt, wt ? wt + 'КГ' : ''].filter(Boolean).join(' / ');
    }

    function getWinMethod(b) {
        if (b.status !== 'C' || !b.reg_win_id) return '';
        const m = b.win_method || '';
        let t = '';
        if (b.end_time) { const p = b.end_time.split(':'); t = p[1] + ':' + (p[2]||'00'); }
        return [m, t].filter(Boolean).join(' - ');
    }

    const isBYE = b => (!b?.reg_two_id || !b?.reg_one_id) && b.status === 'C';

    function calcDur(b, cur) {
        if (b.end_time) {
            const [eh,em,es] = b.end_time.split(':').map(Number);
            const end = new Date(cur); end.setHours(eh,em,es||0,0);
            return Math.max(0, (end - cur) / 60000);
        }
        if ((!b.reg_two_id || !b.reg_one_id) && b.status === 'C') return 0;
        return b.duration ?? 0;
    }

    function processData(data) {
        return data.map(day => {
            const dur = {};
            (day.mates||[]).forEach(mat => { (mat.matches||[]).forEach(b => { dur[b.id] = b.entry?.duration ?? 0; }); });
            return {
                day: day.day, day_id: day.id, start_date: day.start_date,
                mates: (day.mates||[]).map(mat => ({
                    mate_no: mat.mate_no, mate_id: mat.id,
                    event_matches: (mat.event_matches||[]).map(m => ({ ...m, duration: dur[m.bracket_id] ?? 0 })),
                })),
            };
        });
    }

    function buildMatchRow(b, idx, curDate) {
        const done = b.status === 'C', active = b.status === 'A';
        const info = getMatchInfo(b), wm = getWinMethod(b);
        let p1n = b.reg_one?.member?.firstname ?? 'TBD', p1l = b.reg_one?.member?.lastname ?? '';
        let p2n = b.reg_two?.member?.firstname ?? 'TBD', p2l = b.reg_two?.member?.lastname ?? '';
        if (p1n==='TBD' && done) p1n='BYE';
        if (p2n==='TBD' && done) p2n='BYE';
        const p1c = b.reg_one?.academy?.name ?? '', p2c = b.reg_two?.academy?.name ?? '';
        const p1w = done && b.reg_win_id && b.reg_win_id === b.reg_one_id;
        const p2w = done && b.reg_win_id && b.reg_win_id === b.reg_two_id;
        let stHtml = done
            ? `<div class="st-icon done"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6L5 9L10 3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>`
            : active
            ? `<div class="st-icon active"><svg width="10" height="10" viewBox="0 0 10 10"><circle cx="5" cy="5" r="4" fill="white"/></svg></div>`
            : `<div class="st-icon pending"></div>`;
        return `
            ${info ? `<div class="m-info-label">${esc(info)}</div>` : ''}
            <div class="m-row${active?' is-active':''}">
                <div class="m-status">${stHtml}</div>
                <div class="m-num">${idx}</div>
                <div class="m-players">
                    <div class="p-row"><span class="p-dot r"></span><span class="p-name${p1w?' w':''}">${esc(p1n)} ${esc(p1l)}</span><span class="p-club">${esc(p1c)}</span>${p1w&&wm?`<span class="p-win">${esc(wm)}</span>`:''}</div>
                    <div class="p-row"><span class="p-dot b"></span><span class="p-name${p2w?' w':''}">${esc(p2n)} ${esc(p2l)}</span><span class="p-club">${esc(p2c)}</span>${p2w&&wm?`<span class="p-win">${esc(wm)}</span>`:''}</div>
                </div>
                ${done ? `<div class="m-time done">Дууссан</div>` : `<div class="m-time">${fmt(curDate)}</div>`}
            </div>`;
    }

    function buildMatContent(mat, day) {
        const [sH,sM] = (START_TIME||'08:00:00').split(':').map(Number);
        const dateStr = (day.start_date??'').replace(/\s.*/,'');
        const cur = new Date(`${dateStr}T${p2(sH)}:${p2(sM)}:00`);
        let totalDur=0, dur=0, idx=0, html='';
        const ids=[];
        (mat.event_matches||[]).forEach(b => {
            if (isBYE(b)) return;
            idx++; ids.push(b.id);
            cur.setMinutes(cur.getMinutes()+dur);
            dur = calcDur(b, cur);
            totalDur += dur;
            html += buildMatchRow(b, idx, new Date(cur));
        });
        cur.setMinutes(cur.getMinutes()+dur);
        return { html, ids, endTime: fmt(cur), totalDur };
    }

    function renderNormal(data) {
        const root = document.getElementById('s-results');
        root.innerHTML = '';
        if (!data.length) { root.innerHTML = '<div class="s-empty"><div class="s-empty-icon">📋</div>Тохирох барилдаан олдсонгүй</div>'; return; }
        data.forEach(day => {
            const dh = document.createElement('div');
            dh.innerHTML = `<div class="m-day-header" id="day-${day.day_id}"><div class="m-day-title">${esc(String(day.day))}-р өдөр</div><div class="m-day-date">${esc(day.start_date??'')}</div><div class="m-day-line"></div></div>`;
            root.appendChild(dh);
            day.mates.forEach(mat => {
                const wrap = document.createElement('div');
                wrap.id = `mat-${day.day_id}-${mat.mate_id}`;
                wrap.insertAdjacentHTML('beforeend', `<div class="m-mat-header">Мат ${mat.mate_no}</div>`);
                const {html, ids, endTime, totalDur} = buildMatContent(mat, day);
                wrap.insertAdjacentHTML('beforeend', html);
                wrap.insertAdjacentHTML('beforeend', `<div class="m-total"><span>Нийт: <b>${ids.length}</b></span><span>Дуусах: <span class="hi">${endTime}</span> &nbsp;·&nbsp; <span class="hi">${fmtDur(totalDur)}</span></span></div>`);
                root.appendChild(wrap);
            });
        });
    }

    function renderTV(data) {
        const root = document.getElementById('s-results');
        root.innerHTML = '';
        if (!data.length) { root.innerHTML = '<div class="s-empty"><div class="s-empty-icon">📺</div>Тохирох тэмцээн олдсонгүй</div>'; return; }
        const filtered = tvDayId ? data.filter(d => d.day_id === tvDayId) : data;
        filtered.forEach(day => {
            const dayBlock = document.createElement('div');
            dayBlock.className = 'tv-day-block';
            if (!tvDayId || data.length > 1) {
                dayBlock.insertAdjacentHTML('beforeend', `<div class="tv-day-label">${esc(String(day.day))}-р өдөр <span style="font-size:13px;color:var(--muted);font-family:'DM Sans',sans-serif;letter-spacing:0">${esc(day.start_date??'')}</span></div>`);
            }
            const grid = document.createElement('div');
            grid.className = 'tv-mat-grid';
            grid.style.setProperty('--tv-cols', tvCols);
            day.mates.forEach(mat => {
                const col = document.createElement('div');
                col.className = 'tv-mat-col';
                const {html, ids, endTime, totalDur} = buildMatContent(mat, day);
                col.innerHTML = `
                    <div class="tv-col-header">
                        <div class="tv-col-mat-name">МАТ ${mat.mate_no}</div>
                        <div class="tv-col-stats"><span>${ids.length}</span> барилдаан &nbsp;·&nbsp; <span>${endTime}</span></div>
                    </div>
                    ${html}
                    <div class="m-total"><span>Нийт: <b>${ids.length}</b></span><span class="hi">${fmtDur(totalDur)}</span></div>`;
                grid.appendChild(col);
            });
            dayBlock.appendChild(grid);
            root.appendChild(dayBlock);
        });
    }

    function fetchMatches(silent) {
        if (!silent) {
            document.getElementById('s-results').innerHTML =
                '<div class="s-loading"><div class="s-spinner"></div><span>Уншиж байна…</span></div>';
        }
        const day = isTV ? (tvDayId||null) : (document.getElementById('f-day').value||null);
        const mat = isTV ? null : (document.getElementById('f-mat').value||null);
        const p = new URLSearchParams({ event_id: EVENT_ID });
        if (day) p.append('day', day);
        if (mat) p.append('mat', mat);
        fetch(FETCH_URL + '?' + p)
            .then(r => { if (!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
            .then(d => { lastData = processData(d); isTV ? renderTV(lastData) : renderNormal(lastData); })
            .catch(e => {
                console.error(e);
                if (!silent) document.getElementById('s-results').innerHTML =
                    '<div class="s-empty"><div class="s-empty-icon">⚠️</div>Мэдээлэл авахад алдаа гарлаа</div>';
            });
    }

    window.toggleTV = function () {
        isTV = !isTV;
        document.body.classList.toggle('tv-mode', isTV);
        document.getElementById('tv-btn').classList.toggle('active', isTV);
        if (isTV) {
            const firstTab = document.querySelector('.tv-day-tab');
            if (firstTab && !tvDayId) tvSelectDay(parseInt(firstTab.dataset.dayId,10), firstTab);
            else fetchMatches(false);
            startTVRefresh(); startClock();
        } else {
            stopTVRefresh(); stopClock(); fetchMatches(false);
        }
    };

    window.tvSelectDay = function (dayId, btn) {
        tvDayId = dayId;
        document.querySelectorAll('.tv-day-tab').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');
        fetchMatches(false);
    };

    window.setTVCols = function (n, el) {
        tvCols = n;
        document.querySelectorAll('.tv-col-opt').forEach(b => b.classList.remove('active'));
        if (el) el.classList.add('active');
        if (lastData) renderTV(lastData);
    };

    const REFRESH_SEC = 180, CIRCUMFERENCE = 50.27;

    function startTVRefresh() {
        stopTVRefresh();
        tvCountdown = REFRESH_SEC;
        document.getElementById('tv-refresh-ring').classList.add('visible');
        updateRing();
        tvTimer = setInterval(() => {
            tvCountdown--;
            updateRing();
            if (tvCountdown <= 0) { fetchMatches(true); tvCountdown = REFRESH_SEC; }
        }, 1000);
    }
    function stopTVRefresh() { clearInterval(tvTimer); tvTimer=null; document.getElementById('tv-refresh-ring').classList.remove('visible'); }
    function updateRing() {
        const fill = document.getElementById('ring-fill');
        const cd   = document.getElementById('tv-countdown');
        if (!fill) return;
        fill.style.strokeDashoffset = CIRCUMFERENCE * (1 - tvCountdown/REFRESH_SEC);
        cd.textContent = Math.floor(tvCountdown/60) + ':' + p2(tvCountdown%60);
    }

    function startClock() {
        stopClock();
        const el = document.getElementById('tv-clock');
        const tick = () => { const n=new Date(); el.textContent=p2(n.getHours())+':'+p2(n.getMinutes())+':'+p2(n.getSeconds()); };
        tick(); clockTimer = setInterval(tick, 1000);
    }
    function stopClock() { clearInterval(clockTimer); clockTimer=null; }

    function fillMats(dayId, preselectMatId) {
        const sel = document.getElementById('f-mat');
        sel.innerHTML = '<option value="">— Мат —</option>';
        (MATES_BY_DAY[parseInt(dayId,10)]??[]).forEach(m => {
            const o = document.createElement('option');
            o.value=m.id; o.text='Мат '+m.mate_no;
            if (preselectMatId && m.id==preselectMatId) o.selected=true;
            sel.appendChild(o);
        });
    }

    window.sidebarSelect = function (dayId, matId, btn) {
        document.querySelectorAll('.sb-mat').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('f-day').value = dayId;
        fillMats(dayId, matId);
        fetchMatches(false);
        setTimeout(() => {
            const el = document.getElementById(`mat-${dayId}-${matId}`);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 450);
    };

    document.getElementById('f-day').addEventListener('change', function () { fillMats(this.value, null); });
    document.getElementById('f-btn').addEventListener('click', () => fetchMatches(false));
    fetchMatches(false);
})();
</script>
</body>
</html>
