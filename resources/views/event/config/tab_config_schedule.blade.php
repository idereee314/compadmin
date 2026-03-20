<style>
    /* ============================================
       MATCH SCHEDULE — Bold Tournament Design
       ============================================ */

    :root {
        --ms-bg-deep: #0a0a14;
        --ms-bg-primary: #0f0f1a;
        --ms-bg-card: #161625;
        --ms-bg-card-hover: #1c1c32;
        --ms-bg-surface: #1a1a2e;
        --ms-bg-total: #0d0d1a;
        --ms-text-primary: #f0f0f5;
        --ms-text-secondary: #9d9db5;
        --ms-text-muted: #6b6b85;
        --ms-accent-win: #00e6a0;
        --ms-accent-blue: #4d8dff;
        --ms-accent-red: #ff4d6a;
        --ms-accent-orange: #ff9f1c;
        --ms-accent-live: #ff3b5c;
        --ms-border: rgba(255, 255, 255, 0.06);
        --ms-border-accent: rgba(77, 141, 255, 0.15);
        --ms-radius: 12px;
        --ms-radius-sm: 8px;
        --ms-transition: 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        --ms-shadow: 0 2px 12px rgba(0, 0, 0, 0.3);
        --ms-shadow-hover: 0 8px 32px rgba(0, 0, 0, 0.45);
    }

    /* ==========================================
       SEARCH PANEL
       ========================================== */
    .schedule-search-panel {
        background: var(--ms-bg-card);
        border: 1px solid var(--ms-border);
        border-radius: var(--ms-radius);
        padding: 20px;
        position: sticky;
        top: 20px;
    }
    .schedule-search-panel h5 {
        color: var(--ms-text-primary);
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--ms-border);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .schedule-search-panel h5::before {
        content: '';
        width: 3px;
        height: 16px;
        background: var(--ms-accent-blue);
        border-radius: 2px;
    }
    .search-field-group {
        margin-bottom: 12px;
    }
    .search-field-label {
        font-size: 11px;
        color: var(--ms-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
        font-weight: 600;
    }
    .search-field-group .form-control,
    .search-field-group .bootstrap-select .btn {
        background: var(--ms-bg-deep) !important;
        border: 1px solid var(--ms-border) !important;
        color: var(--ms-text-primary) !important;
        border-radius: var(--ms-radius-sm) !important;
        font-size: 13px;
        height: 40px;
        transition: border-color var(--ms-transition);
    }
    .search-field-group .form-control:focus,
    .search-field-group .bootstrap-select .btn:focus {
        border-color: var(--ms-accent-blue) !important;
        box-shadow: 0 0 0 3px rgba(77, 141, 255, 0.12) !important;
    }
    .search-action-btn {
        width: 100%;
        height: 42px;
        border: none;
        border-radius: var(--ms-radius-sm);
        background: linear-gradient(135deg, var(--ms-accent-blue), #3366cc);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all var(--ms-transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 16px;
        position: relative;
        overflow: hidden;
    }
    .search-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 20px rgba(77, 141, 255, 0.35);
    }
    .search-action-btn:active { transform: translateY(0); }
    .search-action-btn:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .search-action-btn .btn-spinner {
        display: none;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
    }
    .search-action-btn.loading .btn-spinner { display: block; }
    .search-action-btn.loading .btn-label  { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    .auto-refresh-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 12px;
        padding: 8px;
        border-radius: var(--ms-radius-sm);
        background: rgba(77, 141, 255, 0.06);
        border: 1px solid rgba(77, 141, 255, 0.1);
    }
    .auto-refresh-dot {
        width: 6px;
        height: 6px;
        background: var(--ms-accent-blue);
        border-radius: 50%;
        animation: refreshPulse 2s ease-in-out infinite;
    }
    @keyframes refreshPulse {
        0%, 100% { opacity: 0.4; }
        50%      { opacity: 1; }
    }
    .auto-refresh-text {
        font-size: 11px;
        color: var(--ms-text-muted);
        letter-spacing: 0.3px;
    }

    /* ==========================================
       DAY HEADER
       ========================================== */
    .day-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
        margin-top: 32px;
    }
    .day-header:first-child { margin-top: 0; }
    .day-badge {
        background: linear-gradient(135deg, var(--ms-accent-blue), #3366cc);
        color: #fff;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        padding: 6px 16px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .day-header-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, var(--ms-border-accent), transparent);
    }

    /* ==========================================
       MAT SECTION
       ========================================== */
    .mat-section { margin-bottom: 32px; }
    .mat-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
        padding: 0 4px;
    }
    .mat-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--ms-text-muted);
        text-transform: uppercase;
        letter-spacing: 1.2px;
    }
    .mat-header-line {
        flex: 1;
        height: 1px;
        background: var(--ms-border);
    }
    .mat-match-count {
        font-size: 11px;
        color: var(--ms-text-muted);
        background: var(--ms-bg-deep);
        padding: 2px 10px;
        border-radius: 10px;
        border: 1px solid var(--ms-border);
    }

    /* ==========================================
       MATCH INFO LABEL
       ========================================== */
    .match-info-label {
        font-size: 10px;
        color: var(--ms-text-muted);
        padding: 6px 16px 2px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-weight: 600;
    }

    /* ==========================================
       MATCH ROW
       ========================================== */
    .match-row {
        display: flex;
        align-items: stretch;
        background: var(--ms-bg-card);
        border: 1px solid var(--ms-border);
        border-radius: var(--ms-radius);
        margin-bottom: 8px;
        overflow: hidden;
        min-height: 70px;
        transition: all var(--ms-transition);
        animation: matchSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) both;
        position: relative;
    }
    .match-row::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: transparent;
        transition: background var(--ms-transition);
        border-radius: 3px 0 0 3px;
        z-index: 1;
    }
    .match-row:hover {
        background: var(--ms-bg-card-hover);
        border-color: var(--ms-border-accent);
        box-shadow: var(--ms-shadow-hover);
        transform: translateY(-2px);
    }
    .match-row:hover::before { background: var(--ms-accent-blue); }
    .match-row.is-completed::before { background: var(--ms-accent-win); }
    .match-row.is-active::before    { background: var(--ms-accent-live); }
    .match-row.is-active {
        border-color: rgba(255, 59, 92, 0.2);
        box-shadow: 0 0 24px rgba(255, 59, 92, 0.08);
    }

    @keyframes matchSlideIn {
        from { opacity: 0; transform: translateX(-12px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .match-status {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px 0 14px;
        min-width: 38px;
    }
    .status-icon {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform var(--ms-transition);
    }
    .match-row:hover .status-icon { transform: scale(1.15); }

    .status-icon.completed {
        background: rgba(0, 230, 160, 0.15);
        color: var(--ms-accent-win);
        border: 2px solid var(--ms-accent-win);
    }
    .status-icon.pending {
        background: rgba(255, 159, 28, 0.1);
        color: var(--ms-accent-orange);
        border: 2px solid rgba(255, 159, 28, 0.4);
    }
    .status-icon.active {
        background: rgba(255, 59, 92, 0.15);
        color: var(--ms-accent-live);
        border: 2px solid var(--ms-accent-live);
        animation: livePulse 1.5s ease-in-out infinite;
    }
    @keyframes livePulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 59, 92, 0.3); }
        50%      { box-shadow: 0 0 0 6px rgba(255, 59, 92, 0); }
    }

    .match-number {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 800;
        color: var(--ms-text-muted);
        padding: 0 12px;
        min-width: 48px;
        border-right: 1px solid var(--ms-border);
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.5px;
    }
    .match-row:hover .match-number { color: var(--ms-text-secondary); }

    .match-players {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 10px 16px;
        min-width: 0;
        gap: 4px;
    }
    .player-row {
        display: flex;
        align-items: center;
        gap: 10px;
        line-height: 1.4;
        min-width: 0;
    }
    .player-corner {
        width: 4px;
        height: 22px;
        border-radius: 2px;
        flex-shrink: 0;
        transition: height var(--ms-transition);
    }
    .match-row:hover .player-corner { height: 26px; }
    .player-corner.red  { background: var(--ms-accent-red); }
    .player-corner.blue { background: var(--ms-accent-blue); }

    .player-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--ms-text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color var(--ms-transition);
    }
    .player-name.winner { color: var(--ms-accent-win); }
    .player-name.loser  { color: var(--ms-text-muted); font-weight: 500; }
    .player-name.bye    { color: var(--ms-text-muted); font-style: italic; font-weight: 400; }

    .club-name {
        font-size: 11px;
        color: var(--ms-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 160px;
    }
    .player-divider {
        width: 1px;
        height: 10px;
        background: var(--ms-border);
        flex-shrink: 0;
    }
    .win-info {
        font-size: 11px;
        font-weight: 700;
        color: var(--ms-accent-win);
        margin-left: auto;
        white-space: nowrap;
        padding: 2px 8px;
        border-radius: 4px;
        background: rgba(0, 230, 160, 0.08);
        flex-shrink: 0;
        letter-spacing: 0.3px;
    }

    .match-time {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0 16px;
        min-width: 80px;
        font-size: 13px;
        color: var(--ms-text-secondary);
        white-space: nowrap;
        border-left: 1px solid var(--ms-border);
        font-variant-numeric: tabular-nums;
        gap: 2px;
    }
    .match-time .time-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--ms-text-muted);
        font-weight: 600;
    }
    .match-time.finished { color: var(--ms-accent-win); }
    .match-time.finished .time-label { color: rgba(0, 230, 160, 0.6); }
    .match-time.active-time { background: rgba(255, 59, 92, 0.06); }
    .live-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--ms-accent-live);
        font-weight: 800;
        font-size: 12px;
        letter-spacing: 1px;
    }
    .live-dot {
        width: 7px;
        height: 7px;
        background: var(--ms-accent-live);
        border-radius: 50%;
        animation: liveDot 1s ease-in-out infinite;
    }
    @keyframes liveDot {
        0%, 100% { opacity: 1; }
        50%      { opacity: 0.3; }
    }

    .match-edit-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 12px;
    }
    .match-edit-btn .edit-button {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: 1px solid transparent;
        color: var(--ms-text-muted);
        cursor: pointer;
        transition: all var(--ms-transition);
        opacity: 0;
    }
    .match-row:hover .edit-button { opacity: 1; }
    .edit-button:hover {
        background: rgba(77, 141, 255, 0.1) !important;
        border-color: rgba(77, 141, 255, 0.2) !important;
        color: var(--ms-accent-blue) !important;
    }

    /* ==========================================
       TOTAL ROW
       ========================================== */
    .match-total-row {
        display: flex;
        align-items: center;
        background: var(--ms-bg-total);
        border-radius: var(--ms-radius);
        padding: 12px 18px;
        margin-top: 12px;
        color: var(--ms-text-muted);
        font-size: 12px;
        justify-content: space-between;
        border: 1px solid var(--ms-border);
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    .match-total-row strong { color: var(--ms-text-secondary); font-weight: 700; }
    .total-stat { display: flex; align-items: center; gap: 12px; }
    .total-divider { width: 1px; height: 14px; background: var(--ms-border); }

    /* ==========================================
       LOADING & EMPTY
       ========================================== */
    .match-skeleton {
        background: linear-gradient(90deg, var(--ms-bg-card) 25%, var(--ms-bg-card-hover) 50%, var(--ms-bg-card) 75%);
        background-size: 200% 100%;
        border-radius: var(--ms-radius);
        height: 70px;
        margin-bottom: 8px;
        animation: skeletonWave 1.8s ease-in-out infinite;
        border: 1px solid var(--ms-border);
    }
    @keyframes skeletonWave {
        0%   { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .match-empty-state {
        text-align: center;
        padding: 64px 20px;
        color: var(--ms-text-muted);
    }
    .empty-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--ms-bg-card);
        border: 1px solid var(--ms-border);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .empty-icon svg { opacity: 0.4; }
    .empty-title { font-size: 14px; font-weight: 600; color: var(--ms-text-secondary); margin-bottom: 4px; }
    .empty-desc  { font-size: 12px; color: var(--ms-text-muted); }

    /* ==========================================
       RESPONSIVE
       ========================================== */
    @media (max-width: 991px) {
        .schedule-search-panel { position: static; margin-bottom: 24px; }
    }
    @media (max-width: 768px) {
        .match-number  { font-size: 14px; min-width: 38px; padding: 0 8px; }
        .match-players { padding: 8px 10px; }
        .player-name   { font-size: 13px; }
        .club-name, .player-divider { display: none; }
        .match-time    { min-width: 60px; padding: 0 10px; font-size: 12px; }
        .match-edit-btn .edit-button { opacity: 1; }
        .win-info      { font-size: 10px; padding: 2px 6px; }
        .match-row     { min-height: 60px; }
        .day-badge     { font-size: 11px; padding: 5px 12px; }
    }
    @media (max-width: 480px) {
        .match-status  { min-width: 30px; padding: 0 4px 0 8px; }
        .status-icon   { width: 22px; height: 22px; }
        .match-number  { min-width: 32px; padding: 0 6px; font-size: 13px; }
        .match-time    { min-width: 52px; padding: 0 8px; }
        .match-info-label { font-size: 9px; padding: 4px 12px 0; }
        .match-players { padding: 6px 8px; gap: 2px; }
        .player-corner { width: 3px; height: 18px; }
        .player-name   { font-size: 12px; }
        .match-total-row { flex-direction: column; gap: 4px; align-items: flex-start; }
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-12 order-lg-1 order-2">
            <div id="match-loading" style="display:none;">
                <div class="match-skeleton"></div>
                <div class="match-skeleton"></div>
                <div class="match-skeleton"></div>
                <div class="match-skeleton"></div>
                <div class="match-skeleton"></div>
            </div>
            <div id="dynamic-day-matches"></div>
        </div>
        <div class="col-lg-4 col-md-12 order-lg-2 order-1">
            <div class="schedule-search-panel">
                <h5>Хайлт</h5>
                <div class="search-field-group">
                    <div class="search-field-label">Өдөр</div>
                    <select class="form-control selectpicker" id="search-day" name="type">
                        @forelse(@$configViewDict['days'] as $day)
                            <option value="{{ $day->id }}">Day {{ $day->item_no }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="search-field-group">
                    <div class="search-field-label">Мат</div>
                    <select class="form-control selectpicker" id="search-mat" name="type">
                    </select>
                </div>
                <button id="search-button" class="search-action-btn" type="button">
                    <span class="btn-label">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        Хайх
                    </span>
                    <span class="btn-spinner"></span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    'use strict';

    const disableEdit  = @json($disableEdit ?? false);
    const matesByDay   = @json($configViewDict['mate']);
    const eventConfig  = @json($eventConfig) || {};
    const genderMap    = @json(config('enums.gender_code'));
    const eventId      = @json($eventConfig['event_id']);

    const ROUTES = {
        fetchMatches: '{{ ($disableEdit ?? false)
            ? route("event.public.schedule.matches", ["eventId" => $eventConfig["event_id"]])
            : route("event.config.search.matches", ["event_id" => $eventConfig["event_id"]]) }}',
        editMatch: '{!! route("event.config.match.edit_status", ["match_id" => "__MATCH_ID__"]) !!}',
    };

    const STATUS = { COMPLETED: 'C', ACTIVE: 'A', PENDING: 'P' };
    const $container = $('#dynamic-day-matches');
    const $loading   = $('#match-loading');
    const $searchBtn = $('#search-button');
    const $searchDay = $('#search-day');
    const $searchMat = $('#search-mat');

    /* --- Utilities --- */
    function pad(n, w = 2) { return String(n).padStart(w, '0'); }
    function formatToHHmm(d) { return `${pad(d.getHours())}:${pad(d.getMinutes())}`; }
    function cloneDate(d) { return new Date(d.getTime()); }
    function formatMinutesToHHmm(m) {
        return `${Math.floor(m / 60)}ц ${pad(Math.round(m % 60))}м`;
    }
    function isBye(b) {
        return (!b.reg_two_id || !b.reg_one_id) && b.status === STATUS.COMPLETED;
    }
    function get(obj, path, fallback = '') {
        return path.split('.').reduce((o, k) => (o && o[k] != null ? o[k] : fallback), obj);
    }
    function escapeHtml(str) {
        if (!str) return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(str).replace(/[&<>"']/g, c => map[c]);
    }

    /* --- Data helpers --- */
    function getMatchInfo(bracket) {
        const entry      = get(bracket, 'reg_one.entry.name');
        const age        = get(bracket, 'reg_one.age', null);
        const genderCode = get(bracket, 'reg_one.entry.gender_code', null);
        const gender     = genderCode ? (genderMap[genderCode] || '') : '';
        const belt       = get(bracket, 'reg_one.belt.name');
        const weight     = get(bracket, 'reg_one.weight.weight', '');
        let ageName = '';
        if (age) {
            if (age.start_age == null)     ageName = '-' + age.end_age;
            else if (age.end_age == null)  ageName = age.start_age + '+';
            else                           ageName = age.start_age + '-' + age.end_age;
        }
        const entryWithAge = ageName ? `${entry} (${ageName})` : entry;
        return [entryWithAge, gender, belt, weight ? `-${weight}KG` : ''].filter(Boolean).join(' / ');
    }

    function getWinMethodDisplay(bracket) {
        if (bracket.status !== STATUS.COMPLETED || !bracket.reg_win_id) return '';
        const method = bracket.win_method || '';
        const endTime = bracket.end_time || '';
        let timeStr = '';
        if (endTime) {
            const parts = endTime.split(':');
            timeStr = parts[1] + ':' + (parts[2] || '00');
        }
        return [method, timeStr].filter(Boolean).join(' – ');
    }

    function getPlayerName(reg, isCompleted) {
        const first = get(reg, 'member.firstname', 'TBD');
        const last  = get(reg, 'member.lastname', '');
        const name  = first === 'TBD' && isCompleted ? 'BYE' : first;
        return { name, last, club: get(reg, 'academy.name'), isBye: name === 'BYE' };
    }

    /* --- HTML builders --- */
    const SVG = {
        check:   '<svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6L5 9L10 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        active:  '<svg width="8" height="8" viewBox="0 0 8 8" fill="none"><circle cx="4" cy="4" r="3" fill="currentColor"/></svg>',
        pending: '<svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>',
        edit:    '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
    };

    function buildStatusIcon(status) {
        const map = {
            [STATUS.COMPLETED]: `<div class="status-icon completed">${SVG.check}</div>`,
            [STATUS.ACTIVE]:    `<div class="status-icon active">${SVG.active}</div>`,
        };
        return map[status] || `<div class="status-icon pending">${SVG.pending}</div>`;
    }

    function buildPlayerRow(player, cornerClass, isWinner, isLoser, winHtml) {
        let nameClass = 'player-name';
        if (player.isBye)       nameClass += ' bye';
        else if (isWinner)      nameClass += ' winner';
        else if (isLoser)       nameClass += ' loser';
        const clubHtml = player.club
            ? `<span class="player-divider"></span><span class="club-name">${escapeHtml(player.club)}</span>` : '';
        return `<div class="player-row">
                <span class="player-corner ${cornerClass}"></span>
                <span class="${nameClass}">${escapeHtml(player.name)} ${escapeHtml(player.last)}</span>
                ${clubHtml}${winHtml}
            </div>`;
    }

    function buildTimeColumn(isCompleted, isActive, timeStr) {
        if (isCompleted) return `<div class="match-time finished"><span class="time-label">Дууссан</span><span>✓</span></div>`;
        if (isActive)    return `<div class="match-time active-time"><span class="live-badge"><span class="live-dot"></span>LIVE</span></div>`;
        return `<div class="match-time"><span class="time-label">Эхлэх</span><span>${timeStr}</span></div>`;
    }

    function buildEditButton(matchId) {
        if (disableEdit) return '';
        return `<div class="match-edit-btn"><a href="javascript:;" class="edit-button" data-match-id="${matchId}">${SVG.edit}</a></div>`;
    }

    /* --- Main render --- */
    function renderMatches(data) {
        if (!data || data.length === 0) {
            $container.html(`<div class="match-empty-state">
                <div class="empty-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2M9 5h6"/></svg></div>
                <div class="empty-title">Тоглолт олдсонгүй</div>
                <div class="empty-desc">Хайлтын утгаа өөрчилж дахин оролдоно уу</div>
            </div>`);
            return;
        }

        const fragment = document.createDocumentFragment();

        data.forEach(day => {
            const dayDiv = document.createElement('div');
            dayDiv.classList.add('mb-4');
            dayDiv.innerHTML = `<div class="day-header"><span class="day-badge">Day ${day.day}</span><span class="day-header-line"></span></div>`;

            const baseDate = new Date(day.start_date);
            if (eventConfig.start_time) {
                const [cH, cM, cS] = eventConfig.start_time.split(':').map(Number);
                baseDate.setHours(cH, cM, cS || 0, 0);
            } else {
                baseDate.setHours(0, 0, 0, 0);
            }

            day.mates.forEach(mat => {
                const matDiv = document.createElement('div');
                matDiv.classList.add('mat-section');

                let currentTime = cloneDate(baseDate);
                let totalDuration = 0, prevDuration = 0, matchIndex = 0;
                const matchIds = [];
                let matchRowsHtml = '';

                mat.event_matches.forEach(bracket => {
                    if (isBye(bracket)) return;
                    matchIndex++;
                    matchIds.push(bracket.id);
                    currentTime.setMinutes(currentTime.getMinutes() + prevDuration);

                    if (bracket.end_time) {
                        const [h, m, s] = bracket.end_time.split(':').map(Number);
                        const endMs = cloneDate(currentTime);
                        endMs.setHours(h, m, s || 0, 0);
                        prevDuration = (endMs.getTime() - currentTime.getTime()) / 60000;
                    } else if (isBye(bracket)) {
                        prevDuration = 0;
                    } else {
                        prevDuration = bracket.duration ?? 0;
                    }
                    totalDuration += prevDuration;

                    const isCompleted = bracket.status === STATUS.COMPLETED;
                    const isActive    = bracket.status === STATUS.ACTIVE;
                    const matchInfo   = getMatchInfo(bracket);
                    const winMethod   = getWinMethodDisplay(bracket);
                    const p1 = getPlayerName(bracket.reg_one, isCompleted);
                    const p2 = getPlayerName(bracket.reg_two, isCompleted);
                    const p1IsWinner = isCompleted && bracket.reg_win_id === bracket.reg_one_id;
                    const p2IsWinner = isCompleted && bracket.reg_win_id === bracket.reg_two_id;
                    const p1WinHtml = p1IsWinner && winMethod ? `<span class="win-info">${escapeHtml(winMethod)}</span>` : '';
                    const p2WinHtml = p2IsWinner && winMethod ? `<span class="win-info">${escapeHtml(winMethod)}</span>` : '';
                    const statusClass = isCompleted ? 'is-completed' : (isActive ? 'is-active' : '');
                    const infoHtml = matchInfo ? `<div class="match-info-label">${escapeHtml(matchInfo)}</div>` : '';

                    matchRowsHtml += `${infoHtml}
                        <div class="match-row ${statusClass}" style="animation-delay:${matchIndex * 40}ms">
                            <div class="match-status">${buildStatusIcon(bracket.status)}</div>
                            <div class="match-number">${matchIndex}</div>
                            <div class="match-players">
                                ${buildPlayerRow(p1, 'red', p1IsWinner, p2IsWinner, p1WinHtml)}
                                ${buildPlayerRow(p2, 'blue', p2IsWinner, p1IsWinner, p2WinHtml)}
                            </div>
                            ${buildTimeColumn(isCompleted, isActive, formatToHHmm(currentTime))}
                            ${buildEditButton(bracket.id)}
                        </div>`;
                });

                currentTime.setMinutes(currentTime.getMinutes() + prevDuration);

                try { sessionStorage.setItem(`${eventId}_${day.day_id}_${mat.mate_id}`, JSON.stringify(matchIds)); } catch (e) {}

                matDiv.innerHTML = `
                    <div class="mat-header">
                        <span class="mat-label">Mat ${mat.mate_no}</span>
                        <span class="mat-header-line"></span>
                        <span class="mat-match-count">${matchIds.length} тоглолт</span>
                    </div>
                    ${matchRowsHtml}
                    <div class="match-total-row">
                        <span>Нийт: <strong>${matchIds.length}</strong> тоглолт</span>
                        <div class="total-stat">
                            <span>Дуусах: <strong>${formatToHHmm(currentTime)}</strong></span>
                            <span class="total-divider"></span>
                            <span>Хугацаа: <strong>${formatMinutesToHHmm(totalDuration)}</strong></span>
                        </div>
                    </div>`;
                dayDiv.appendChild(matDiv);
            });

            fragment.appendChild(dayDiv);
        });

        $container.empty();
        $container[0].appendChild(fragment);
    }

    /* --- Data processing --- */
    function handleMatchesData(data) {
        return data.map(day => {
            const bd = {};
            day.mates.forEach(mat => { mat.matches.forEach(b => { bd[b.id] = get(b, 'entry.duration', 0); }); });
            return {
                day: day.day, day_id: day.id, start_date: day.start_date,
                mates: day.mates.map(mat => ({
                    mate_no: mat.mate_no, mate_id: mat.id,
                    event_matches: mat.event_matches.map(m => ({ ...m, duration: bd[m.bracket_id] || 0 })),
                })),
            };
        });
    }

    /* --- Fetch --- */
    let fetchController = null;

    function fetchMatches() {
        if (fetchController) fetchController.abort();
        fetchController = new AbortController();
        const params = { event_id: eventId, day: $searchDay.val() || null, mat: $searchMat.val() || null };

        $loading.show();
        $container.hide();
        $searchBtn.addClass('loading').prop('disabled', true);

        $.ajax({
            url: ROUTES.fetchMatches, method: 'GET', data: params,
            success(response) { renderMatches(handleMatchesData(response)); },
            error(xhr, textStatus) {
                if (textStatus === 'abort') return;
                $container.html(`<div class="match-empty-state">
                    <div class="empty-icon" style="border-color:rgba(255,77,106,0.2);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--ms-accent-red)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                    <div class="empty-title" style="color:var(--ms-accent-red);">Алдаа гарлаа</div>
                    <div class="empty-desc">Дахин оролдоно уу</div>
                </div>`);
            },
            complete() {
                $loading.hide(); $container.show();
                $searchBtn.removeClass('loading').prop('disabled', false);
            },
        });
    }

    /* --- Dependent dropdown --- */
    function populateMatSelect(dayId) {
        $searchMat.html('<option value="">-- Бүгд --</option>');
        if (matesByDay[dayId]) {
            matesByDay[dayId].forEach(mate => {
                $searchMat.append($('<option>', { value: mate.id, text: 'Mat ' + mate.mate_no }));
            });
        }
        $searchMat.selectpicker('refresh');
        $searchMat.prop('selectedIndex', 0);
    }

    

    /* --- Events & Init --- */
    $searchDay.on('changed.bs.select', function () { populateMatSelect(this.value); });
    $searchBtn.on('click', fetchMatches);
    $(document).off('click', '.edit-button').on('click', '.edit-button', function () {
        window.open(ROUTES.editMatch.replace('__MATCH_ID__', $(this).data('match-id')), '_blank');
    });

    $searchDay.selectpicker();
    $searchMat.selectpicker();
    $searchDay.prop('selectedIndex', 0);
    $searchDay.trigger('changed.bs.select');
    fetchMatches();
});
</script>
