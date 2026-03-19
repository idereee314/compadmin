<!DOCTYPE html>
<html lang="mn">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Schedule Manager — {{ $event->name ?? 'Тэмцээн' }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            /* ─── RESET & ROOT ─── */
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0
            }

            :root {
                --bg: #08090c;
                --surf: #0e0f15;
                --card: #13141c;
                --card2: #181920;
                --border: rgba(255, 255, 255, .07);
                --border2: rgba(255, 255, 255, .12);
                --accent: #f06020;
                --accent-g: rgba(240, 96, 32, .12);
                --teal: #1bc5bd;
                --blue: #3699ff;
                --red: #f64e60;
                --green: #1a9e6e;
                --purple: #7c5cbf;
                --amber: #e69b2f;
                --pink: #c4367a;
                --text: #ddddef;
                --soft: #8888a8;
                --muted: #555670;
                --muted2: #2e2f3d;
                --mono: 'JetBrains Mono', monospace;
                --sans: 'Inter', sans-serif;
                --rad: 8px;
            }

            html,
            body {
                height: 100%;
                overflow: hidden;
                background: var(--bg);
                color: var(--text);
                font-family: var(--sans);
                font-size: 13px
            }

            ::-webkit-scrollbar {
                width: 4px;
                height: 4px
            }

            ::-webkit-scrollbar-track {
                background: transparent
            }

            ::-webkit-scrollbar-thumb {
                background: var(--muted2);
                border-radius: 4px
            }

            /* ─── TOPBAR ─── */
            .topbar {
                height: 54px;
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 0 16px;
                background: var(--surf);
                border-bottom: 1px solid var(--border);
                position: sticky;
                top: 0;
                z-index: 200
            }

            .tb-logo {
                font-family: 'Bebas Neue';
                font-size: 20px;
                letter-spacing: 2px;
                color: #fff;
                white-space: nowrap
            }

            .tb-logo span {
                color: var(--accent)
            }

            .tb-event {
                font-size: 12px;
                color: var(--soft);
                max-width: 240px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                border-left: 1px solid var(--border2);
                padding-left: 10px;
                margin-left: 4px
            }

            .tb-sep {
                width: 1px;
                height: 22px;
                background: var(--border2);
                margin: 0 4px;
                flex-shrink: 0
            }

            .tb-tabs {
                display: flex;
                gap: 2px
            }

            .tb-tab {
                background: none;
                border: 1px solid var(--border2);
                color: var(--soft);
                font-size: 11px;
                font-weight: 600;
                padding: 5px 14px;
                border-radius: 6px;
                cursor: pointer;
                font-family: var(--sans);
                transition: all .15s;
                letter-spacing: .3px
            }

            .tb-tab.active {
                background: var(--accent);
                border-color: var(--accent);
                color: #fff
            }

            .tb-spacer {
                flex: 1
            }

            .tb-stat {
                font-family: var(--mono);
                font-size: 11px;
                padding: 4px 10px;
                border-radius: 5px;
                background: rgba(255, 255, 255, .04);
                color: var(--muted);
                white-space: nowrap
            }

            .tb-stat b {
                color: var(--text)
            }

            .tbtn {
                display: flex;
                align-items: center;
                gap: 6px;
                background: none;
                border: 1px solid var(--border2);
                color: var(--soft);
                font-size: 11px;
                padding: 6px 12px;
                border-radius: 6px;
                cursor: pointer;
                font-family: var(--sans);
                transition: all .15s;
                white-space: nowrap
            }

            .tbtn:hover {
                border-color: #555;
                color: var(--text)
            }

            .tbtn.save {
                background: var(--accent);
                border-color: var(--accent);
                color: #fff;
                font-weight: 600
            }

            .tbtn.save:hover {
                background: #d45418
            }

            .tbtn.recalc {
                border-color: var(--teal);
                color: var(--teal)
            }

            .tbtn.recalc:hover {
                background: rgba(27, 197, 189, .1)
            }

            /* ─── SHELL ─── */
            .shell {
                display: grid;
                grid-template-columns: 260px 1fr 320px;
                height: calc(100vh - 54px);
                overflow: hidden
            }

            /* ─── LEFT: POOL ─── */
            .pool-panel {
                background: var(--surf);
                border-right: 1px solid var(--border);
                display: flex;
                flex-direction: column;
                overflow: hidden
            }

            .panel-hdr {
                padding: 10px 14px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-shrink: 0
            }

            .panel-hdr-title {
                font-size: 9px;
                font-weight: 700;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: var(--muted)
            }

            .panel-hdr-actions {
                display: flex;
                gap: 5px
            }

            .icon-btn {
                background: none;
                border: 1px solid var(--border2);
                color: var(--muted);
                width: 24px;
                height: 24px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 13px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all .15s;
                flex-shrink: 0
            }

            .icon-btn:hover {
                border-color: var(--accent);
                color: var(--accent)
            }

            .pool-search {
                padding: 8px 10px;
                border-bottom: 1px solid var(--border);
                flex-shrink: 0
            }

            .pool-search input {
                width: 100%;
                background: var(--card);
                border: 1px solid var(--border2);
                border-radius: 6px;
                color: var(--text);
                font-size: 12px;
                padding: 6px 10px;
                outline: none;
                font-family: var(--sans)
            }

            .pool-search input:focus {
                border-color: var(--accent)
            }

            .pool-scroll {
                flex: 1;
                overflow-y: auto;
                padding: 8px
            }

            .pool-group {
                margin-bottom: 10px
            }

            .pool-group-hdr {
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 5px 6px;
                cursor: pointer;
                border-radius: 5px;
                user-select: none
            }

            .pool-group-hdr:hover {
                background: rgba(255, 255, 255, .03)
            }

            .pool-group-arrow {
                font-size: 9px;
                color: var(--muted);
                transition: transform .2s;
                width: 12px;
                text-align: center
            }

            .pool-group-arrow.open {
                transform: rotate(90deg)
            }

            .pool-group-name {
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                color: var(--muted);
                flex: 1
            }

            .pool-group-cnt {
                font-size: 9px;
                font-family: var(--mono);
                color: var(--muted2);
                background: rgba(255, 255, 255, .04);
                padding: 1px 6px;
                border-radius: 10px
            }

            .pool-items {}

            .pool-item {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 7px 8px;
                border-radius: 6px;
                background: var(--card);
                border: 1px solid var(--border);
                margin-bottom: 4px;
                cursor: grab;
                user-select: none;
                transition: border-color .15s, background .15s;
            }

            .pool-item:hover {
                border-color: var(--border2);
                background: var(--card2)
            }

            .pool-item.dragging {
                opacity: .4;
                cursor: grabbing
            }

            .pool-item.in-schedule {
                opacity: .45;
                pointer-events: none;
                filter: grayscale(.4)
            }

            .pi-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                flex-shrink: 0
            }

            .pi-m {
                background: var(--blue)
            }

            .pi-f {
                background: var(--pink)
            }

            .pi-j {
                background: var(--amber)
            }

            .pi-info {
                flex: 1;
                min-width: 0
            }

            .pi-name {
                font-size: 11px;
                font-weight: 600;
                color: var(--text);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis
            }

            .pi-meta {
                font-size: 10px;
                color: var(--muted);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis
            }

            .pi-badge {
                font-size: 9px;
                font-family: var(--mono);
                color: var(--soft);
                background: rgba(255, 255, 255, .05);
                padding: 2px 6px;
                border-radius: 10px;
                flex-shrink: 0;
                white-space: nowrap
            }

            .pi-handle {
                color: var(--muted2);
                font-size: 12px;
                flex-shrink: 0;
                cursor: grab
            }

            /* ─── CENTER: TIMELINE ─── */
            .timeline-panel {
                display: flex;
                flex-direction: column;
                overflow: hidden;
                background: var(--bg)
            }

            .tl-toolbar {
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 9px 14px;
                background: var(--surf);
                border-bottom: 1px solid var(--border);
                flex-shrink: 0;
                flex-wrap: wrap
            }

            .tl-info {
                font-size: 11px;
                color: var(--muted);
                font-family: var(--mono)
            }

            .tl-info b {
                color: var(--text)
            }

            .tl-spacer {
                flex: 1
            }

            .tl-scroll {
                flex: 1;
                overflow: auto;
                padding: 14px
            }

            .tl-grid {
                display: grid;
                gap: 10px
            }

            /* dynamic columns set by JS: grid-template-columns */
            .mat-col {
                background: var(--surf);
                border: 1px solid var(--border);
                border-radius: 10px;
                overflow: hidden;
                min-width: 200px;
                transition: border-color .2s;
            }

            .mat-col.drag-over {
                border-color: var(--accent);
                background: rgba(240, 96, 32, .05)
            }

            .mat-col-hdr {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 9px 12px;
                border-bottom: 1px solid var(--border);
                background: rgba(255, 255, 255, .02);
            }

            .mat-col-name {
                font-family: 'Bebas Neue';
                font-size: 14px;
                letter-spacing: 2px;
                color: var(--accent)
            }

            .mat-col-stats {
                font-size: 10px;
                color: var(--muted);
                font-family: var(--mono)
            }

            .mat-col-stats span {
                color: var(--soft)
            }

            .mat-drop-zone {
                min-height: 80px;
                padding: 6px;
                transition: background .2s;
            }

            .mat-drop-zone.drag-over {
                background: rgba(240, 96, 32, .07)
            }

            .mat-drop-empty {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 64px;
                border: 1.5px dashed var(--muted2);
                border-radius: 6px;
                color: var(--muted2);
                font-size: 11px;
                letter-spacing: .5px;
                transition: border-color .2s, color .2s;
                margin: 4px;
            }

            .mat-drop-zone.drag-over .mat-drop-empty {
                border-color: var(--accent);
                color: var(--accent)
            }

            /* bracket block inside mat */
            .bracket-block {
                background: var(--card);
                border: 1px solid var(--border);
                border-radius: 7px;
                margin-bottom: 5px;
                overflow: hidden;
                cursor: pointer;
                transition: border-color .2s, background .15s;
                position: relative;
            }

            .bracket-block:hover {
                border-color: var(--border2);
                background: var(--card2)
            }

            .bracket-block.locked {
                cursor: default
            }

            .bracket-block.locked::before {
                content: '🔒';
                position: absolute;
                top: 5px;
                right: 7px;
                font-size: 9px;
                opacity: .5
            }

            .bb-head {
                display: flex;
                align-items: center;
                gap: 7px;
                padding: 7px 10px;
                border-bottom: 1px solid var(--border)
            }

            .bb-dot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                flex-shrink: 0
            }

            .bb-name {
                font-size: 11px;
                font-weight: 600;
                color: var(--text);
                flex: 1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis
            }

            .bb-weight {
                font-size: 10px;
                font-family: var(--mono);
                color: var(--soft);
                flex-shrink: 0
            }

            .bb-body {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 0
            }

            .bb-stat {
                padding: 5px 8px;
                text-align: center;
                border-right: 1px solid var(--border)
            }

            .bb-stat:last-child {
                border-right: none
            }

            .bb-stat-val {
                font-size: 14px;
                font-weight: 700;
                color: var(--text);
                font-family: var(--mono)
            }

            .bb-stat-lbl {
                font-size: 9px;
                color: var(--muted);
                letter-spacing: .5px
            }

            .bb-time {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 4px 10px;
                background: rgba(255, 255, 255, .02);
                border-top: 1px solid var(--border);
                font-size: 10px;
                color: var(--muted);
                font-family: var(--mono);
            }

            .bb-time-val {
                color: var(--soft)
            }

            .bb-drag-handle {
                display: flex;
                align-items: center;
                padding: 0 6px;
                color: var(--muted2);
                cursor: grab;
                font-size: 11px;
                flex-shrink: 0
            }

            .bb-remove {
                position: absolute;
                top: 5px;
                right: 7px;
                background: none;
                border: none;
                color: var(--muted2);
                cursor: pointer;
                font-size: 12px;
                padding: 2px;
                display: none;
                z-index: 2
            }

            .bracket-block:hover:not(.locked) .bb-remove {
                display: block
            }

            .bb-remove:hover {
                color: var(--red)
            }

            .bracket-block.locked .bb-remove {
                display: none !important
            }

            /* lunch break */
            .lunch-bar {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 6px;
                margin-bottom: 5px;
                background: rgba(27, 197, 189, .06);
                border: 1px solid rgba(27, 197, 189, .2);
                border-radius: 6px;
                font-size: 10px;
                color: var(--teal);
                letter-spacing: .5px;
                font-weight: 600;
            }

            /* end-of-mat */
            .mat-end-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 6px 10px;
                background: rgba(255, 255, 255, .02);
                border-top: 1px solid var(--border);
                font-size: 10px;
                color: var(--muted);
            }

            .mat-end-val {
                color: var(--accent);
                font-weight: 700;
                font-family: var(--mono)
            }

            /* ─── RIGHT: DETAIL PANEL ─── */
            .detail-panel {
                background: var(--surf);
                border-left: 1px solid var(--border);
                display: flex;
                flex-direction: column;
                overflow: hidden
            }

            .dp-hdr {
                padding: 10px 14px;
                border-bottom: 1px solid var(--border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-shrink: 0
            }

            .dp-hdr-title {
                font-size: 9px;
                font-weight: 700;
                letter-spacing: 2px;
                text-transform: uppercase;
                color: var(--muted)
            }

            .dp-close {
                background: none;
                border: none;
                color: var(--muted);
                cursor: pointer;
                font-size: 16px;
                padding: 2px;
                line-height: 1;
                transition: color .15s
            }

            .dp-close:hover {
                color: var(--text)
            }

            .dp-scroll {
                flex: 1;
                overflow-y: auto;
                padding: 14px
            }

            .dp-empty {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
                gap: 12px;
                color: var(--muted);
                font-size: 13px
            }

            .dp-empty-icon {
                font-size: 36px;
                opacity: .25
            }

            /* match list inside detail */
            .match-row {
                display: flex;
                align-items: stretch;
                background: var(--card);
                border: 1px solid var(--border);
                border-radius: 8px;
                margin-bottom: 5px;
                overflow: hidden;
                min-height: 58px;
                transition: border-color .15s;
            }

            .match-row:hover {
                border-color: var(--border2)
            }

            .mr-status {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 10px;
                min-width: 36px
            }

            .mr-st {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0
            }

            .mr-st.done {
                background: var(--teal)
            }

            .mr-st.active {
                background: var(--blue)
            }

            .mr-st.pending {
                background: var(--card2);
                border: 1.5px solid var(--muted)
            }

            .mr-num {
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Bebas Neue';
                font-size: 22px;
                color: #fff;
                padding: 0 12px;
                min-width: 44px;
                border-right: 1px solid var(--border);
                letter-spacing: 1px
            }

            .mr-players {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 8px 12px;
                gap: 4px;
                min-width: 0
            }

            .pr {
                display: flex;
                align-items: center;
                gap: 7px
            }

            .pdot {
                width: 7px;
                height: 7px;
                border-radius: 50%;
                flex-shrink: 0
            }

            .pdot.r {
                background: var(--red)
            }

            .pdot.b {
                background: var(--blue)
            }

            .pname {
                font-size: 12px;
                font-weight: 600;
                color: #fff;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis
            }

            .pname.w {
                color: var(--teal)
            }

            .pclub {
                font-size: 10px;
                color: var(--muted);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis
            }

            .pwin {
                font-size: 9px;
                font-weight: 700;
                color: var(--teal);
                background: rgba(27, 197, 189, .12);
                border-radius: 4px;
                padding: 1px 5px;
                flex-shrink: 0
            }

            .mr-time {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0 12px;
                min-width: 70px;
                font-size: 11px;
                font-weight: 600;
                color: var(--soft);
                border-left: 1px solid var(--border)
            }

            .mr-time.done {
                color: var(--teal);
                font-size: 10px;
                letter-spacing: .5px
            }

            .match-info-label {
                font-size: 9px;
                color: var(--muted);
                padding: 4px 10px 2px;
                letter-spacing: .3px
            }

            /* detail header */
            .dp-cat-hdr {
                padding: 12px 0 10px;
                border-bottom: 1px solid var(--border);
                margin-bottom: 12px
            }

            .dp-cat-name {
                font-size: 15px;
                font-weight: 700;
                color: #fff;
                margin-bottom: 4px
            }

            .dp-cat-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 6px
            }

            .dp-tag {
                display: inline-flex;
                align-items: center;
                padding: 3px 8px;
                border-radius: 10px;
                font-size: 10px;
                font-weight: 600;
                background: rgba(255, 255, 255, .06);
                color: var(--soft)
            }

            .dp-tag.blue {
                background: rgba(54, 153, 255, .15);
                color: #6aadff
            }

            .dp-tag.pink {
                background: rgba(196, 54, 122, .15);
                color: #f080c0
            }

            .dp-tag.amber {
                background: rgba(230, 155, 47, .15);
                color: #f0b040
            }

            .dp-tag.teal {
                background: rgba(27, 197, 189, .15);
                color: var(--teal)
            }

            .dp-fights-total {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 8px 10px;
                background: rgba(255, 255, 255, .02);
                border-radius: 7px;
                margin-bottom: 10px;
                font-size: 11px;
                color: var(--muted)
            }

            .dp-fights-total b {
                color: var(--text)
            }

            .dp-fights-total .hi {
                color: var(--accent);
                font-weight: 700;
                font-family: var(--mono)
            }

            /* ─── MODALS ─── */
            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .7);
                z-index: 500;
                display: none;
                align-items: center;
                justify-content: center
            }

            .overlay.show {
                display: flex
            }

            .modal {
                background: var(--card);
                border: 1px solid var(--border2);
                border-radius: 12px;
                width: 480px;
                max-width: 94vw;
                max-height: 85vh;
                display: flex;
                flex-direction: column;
                overflow: hidden
            }

            .modal-hdr {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 18px;
                border-bottom: 1px solid var(--border);
                flex-shrink: 0
            }

            .modal-title {
                font-size: 14px;
                font-weight: 700;
                color: #fff
            }

            .modal-close {
                background: none;
                border: none;
                color: var(--muted);
                cursor: pointer;
                font-size: 20px;
                line-height: 1;
                padding: 2px;
                transition: color .15s
            }

            .modal-close:hover {
                color: var(--text)
            }

            .modal-body {
                padding: 18px;
                overflow-y: auto;
                flex: 1
            }

            .modal-footer {
                padding: 12px 18px;
                border-top: 1px solid var(--border);
                display: flex;
                justify-content: flex-end;
                gap: 8px;
                flex-shrink: 0
            }

            .mf-btn {
                padding: 8px 18px;
                border-radius: 7px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                border: none;
                font-family: var(--sans);
                transition: all .15s
            }

            .mf-btn.cancel {
                background: rgba(255, 255, 255, .06);
                color: var(--soft)
            }

            .mf-btn.cancel:hover {
                background: rgba(255, 255, 255, .1)
            }

            .mf-btn.confirm {
                background: var(--accent);
                color: #fff
            }

            .mf-btn.confirm:hover {
                background: #d45418
            }

            /* form fields */
            .f-group {
                margin-bottom: 14px
            }

            .f-label {
                display: block;
                font-size: 10px;
                font-weight: 700;
                letter-spacing: 1.5px;
                text-transform: uppercase;
                color: var(--muted);
                margin-bottom: 6px
            }

            .f-input,
            .f-select2 {
                width: 100%;
                background: var(--card2);
                border: 1px solid var(--border2);
                border-radius: 7px;
                color: var(--text);
                font-size: 13px;
                padding: 9px 12px;
                outline: none;
                font-family: var(--sans);
                transition: border-color .15s
            }

            .f-input:focus,
            .f-select2:focus {
                border-color: var(--accent)
            }

            .f-row {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px
            }

            /* weight chips */
            .weight-chips {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
                margin-top: 4px
            }

            .wchip {
                display: flex;
                align-items: center;
                gap: 5px;
                padding: 5px 10px;
                border-radius: 6px;
                background: var(--card2);
                border: 1px solid var(--border2);
                font-size: 11px;
                color: var(--soft);
                cursor: pointer;
                transition: all .15s;
                user-select: none;
            }

            .wchip:hover {
                border-color: var(--border2);
                color: var(--text)
            }

            .wchip.sel {
                background: rgba(240, 96, 32, .15);
                border-color: var(--accent);
                color: var(--accent)
            }

            .wchip-kg {
                font-family: var(--mono);
                font-weight: 600
            }

            .wchip-n {
                font-family: var(--mono);
                font-size: 10px;
                color: inherit;
                opacity: .7
            }

            /* category chips for day assignment */
            .day-chips {
                display: flex;
                gap: 6px;
                flex-wrap: wrap
            }

            .day-chip {
                padding: 6px 14px;
                border-radius: 6px;
                border: 1px solid var(--border2);
                background: var(--card2);
                color: var(--soft);
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                transition: all .15s
            }

            .day-chip.sel {
                background: rgba(240, 96, 32, .15);
                border-color: var(--accent);
                color: var(--accent)
            }

            /* toast */
            .toast-wrap {
                position: fixed;
                top: 66px;
                right: 16px;
                z-index: 999;
                display: flex;
                flex-direction: column;
                gap: 6px;
                pointer-events: none
            }

            .toast {
                padding: 10px 16px;
                background: var(--card2);
                border: 1px solid var(--border2);
                border-radius: 8px;
                font-size: 12px;
                color: var(--text);
                opacity: 0;
                transform: translateY(-8px);
                transition: all .25s;
                pointer-events: none
            }

            .toast.show {
                opacity: 1;
                transform: translateY(0)
            }

            .toast.success {
                border-color: rgba(27, 197, 189, .4);
                color: var(--teal)
            }

            .toast.error {
                border-color: rgba(246, 78, 96, .4);
                color: var(--red)
            }

            .toast.warn {
                border-color: rgba(230, 155, 47, .4);
                color: var(--amber)
            }

            /* spinner inline */
            .spin {
                display: inline-block;
                width: 14px;
                height: 14px;
                border: 2px solid rgba(255, 255, 255, .2);
                border-top-color: currentColor;
                border-radius: 50%;
                animation: s .6s linear infinite
            }

            @keyframes s {
                to {
                    transform: rotate(360deg)
                }
            }

            /* ─── MISC ─── */
            .badge-m {
                background: rgba(54, 153, 255, .15);
                color: #6aadff
            }

            .badge-f {
                background: rgba(196, 54, 122, .15);
                color: #f080c0
            }

            .badge-j {
                background: rgba(230, 155, 47, .15);
                color: #f0b040
            }

            .sys-de {
                background: rgba(246, 78, 96, .15);
                color: var(--red)
            }

            .sys-rr {
                background: rgba(54, 153, 255, .15);
                color: #6aadff
            }

            .sys-rr2 {
                background: rgba(27, 197, 189, .15);
                color: var(--teal)
            }

            .sys-ijf {
                background: rgba(230, 155, 47, .15);
                color: #f0b040
            }

            .sys-bo3 {
                background: rgba(124, 92, 191, .15);
                color: #b090ef
            }

            .loading-full {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100%;
                gap: 12px;
                color: var(--muted);
                font-size: 13px
            }

            .spin-lg {
                width: 32px;
                height: 32px;
                border: 3px solid var(--muted2);
                border-top-color: var(--accent);
                border-radius: 50%;
                animation: s .7s linear infinite
            }
        </style>
    </head>
    <body>
        <!-- ─── TOAST ─── -->
        <div class="toast-wrap" id="toast-wrap"></div>
        <!-- ─── TOPBAR ─── -->
        <div class="topbar">
            <div class="tb-logo">MNUE <span>OPEN</span>
            </div>
            <div class="tb-event">{{ $event->name ?? '' }}</div>
            <div class="tb-sep"></div>
            <div class="tb-tabs" id="day-tabs"> @forelse($days ?? [] as $day) <button class="tb-tab {{ $loop->first ? 'active' : '' }}" data-day-id="{{ $day->id }}" onclick="selectDay({{ $day->id }}, this)">
                    {{ $day->item_no }}-р өдөр </button> @empty <span style="font-size:11px;color:var(--muted)">Өдөр олдсонгүй</span> @endforelse </div>
            <div class="tb-spacer"></div>
            <div class="tb-stat" id="stat-pax">Нийт: <b>—</b>
            </div>
            <div class="tb-stat" id="stat-fights">Тулаан: <b>—</b>
            </div>
            <div class="tb-stat" id="stat-end">Дуусах: <b>—</b>
            </div>
            <div class="tb-sep"></div>
            <button class="tbtn recalc" onclick="recalcSchedule()">
                <span>⟳</span> Тооцоолох </button>
            <button class="tbtn" onclick="openAddCatModal()">
                <span>＋</span> Категори </button>
            <button class="tbtn save" id="save-btn" onclick="saveSchedule()"> Хадгалах </button>
        </div>
        <!-- ─── SHELL ─── -->
        <div class="shell">
            <!-- LEFT: POOL -->
            <div class="pool-panel">
                <div class="panel-hdr">
                    <span class="panel-hdr-title">Хуваарьлаагүй</span>
                    <div class="panel-hdr-actions">
                        <button class="icon-btn" title="Шинэ жингийн анги нэмэх" onclick="openAddCatModal()">＋</button>
                    </div>
                </div>
                <div class="pool-search">
                    <input type="text" placeholder="Хайх…" id="pool-search" oninput="filterPool(this.value)">
                </div>
                <div class="pool-scroll" id="pool-list">
                    <div class="loading-full">
                        <div class="spin-lg"></div>
                        <span>Ачааллаж байна…</span>
                    </div>
                </div>
            </div>
            <!-- CENTER: TIMELINE -->
            <div class="timeline-panel">
                <div class="tl-toolbar">
                    <div class="tl-info" id="tl-info"> Дэвжээ тус бүрт <b id="tl-mat-count">—</b> · Эхлэх: <b id="tl-start">09:00</b>
                    </div>
                    <div class="tb-sep"></div>
                    <button class="tbtn" onclick="openMatConfigModal()">⚙ Дэвжээ</button>
                    <div class="tb-spacer"></div>
                    <span style="font-size:10px;color:var(--muted)">Drag & Drop →</span>
                </div>
                <div class="tl-scroll" id="tl-scroll">
                    <div class="tl-grid" id="tl-grid">
                        <div class="loading-full">
                            <div class="spin-lg"></div>
                            <span>Уншиж байна…</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- RIGHT: DETAIL -->
            <div class="detail-panel">
                <div class="panel-hdr">
                    <span class="panel-hdr-title" id="dp-title">Дэлгэрэнгүй</span>
                    <button class="dp-close" onclick="closeDetail()">×</button>
                </div>
                <div class="dp-scroll" id="dp-scroll">
                    <div class="dp-empty">
                        <div class="dp-empty-icon">🥋</div> Bracket сонгон тулааны <br>жагсаалт харах
                    </div>
                </div>
            </div>
        </div>
        <!-- ─── MODAL: ADD CATEGORY ─── -->
        <div class="overlay" id="modal-cat">
            <div class="modal">
                <div class="modal-hdr">
                    <div class="modal-title" id="mc-title">Шинэ категори нэмэх</div>
                    <button class="modal-close" onclick="closeModal('modal-cat')">×</button>
                </div>
                <div class="modal-body">
                    <div class="f-row">
                        <div class="f-group">
                            <label class="f-label">Категорийн нэр (Entry)</label>
                            <select class="f-select2" id="mc-entry" onchange="onEntryChange()">
                                <option value="">— Сонгох —</option> @forelse($entries ?? [] as $entry) <option value="{{ $entry->id }}" data-gender="{{ $entry->gender ?? 'M' }}" data-sport="{{ $entry->sport ?? '' }}">
                                    {{ $entry->name }}
                                </option> @empty @endforelse
                            </select>
                        </div>
                        <div class="f-group">
                            <label class="f-label">Насны анги (Age)</label>
                            <select class="f-select2" id="mc-age" onchange="onAgeChange()">
                                <option value="">— Сонгох —</option>
                            </select>
                        </div>
                    </div>
                    <div class="f-row">
                        <div class="f-group">
                            <label class="f-label">Тулааны хугацаа (мин)</label>
                            <input class="f-input" type="number" id="mc-dur" min="1" max="20" value="3">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Өдөр</label>
                            <div class="day-chips" id="mc-day-chips"> @forelse($days ?? [] as $day) <div class="day-chip {{ $loop->first ? 'sel' : '' }}" data-day-id="{{ $day->id }}" onclick="selectDayChip(this)">
                                    {{ $day->item_no }}-р өдөр
                                </div> @empty @endforelse </div>
                        </div>
                    </div>
                    <div class="f-group">
                        <label class="f-label">Жингийн ангиуд <span style="color:var(--muted);font-weight:400;letter-spacing:0;text-transform:none">— тоо засах боломжтой</span>
                        </label>
                        <div class="weight-chips" id="mc-weights">
                            <span style="font-size:11px;color:var(--muted)">Entry болон Age сонгоно уу</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="mf-btn cancel" onclick="closeModal('modal-cat')">Болих</button>
                    <button class="mf-btn confirm" onclick="confirmAddCat()">Нэмэх</button>
                </div>
            </div>
        </div>
        <!-- ─── MODAL: MAT CONFIG ─── -->
        <div class="overlay" id="modal-mat">
            <div class="modal" style="width:380px">
                <div class="modal-hdr">
                    <div class="modal-title">Дэвжээний тохиргоо</div>
                    <button class="modal-close" onclick="closeModal('modal-mat')">×</button>
                </div>
                <div class="modal-body">
                    <div class="f-row">
                        <div class="f-group">
                            <label class="f-label">Дэвжээний тоо</label>
                            <input class="f-input" type="number" id="cfg-mats" min="1" max="8" value="4">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Эхлэх цаг</label>
                            <input class="f-input" type="time" id="cfg-start" value="09:00">
                        </div>
                    </div>
                    <div class="f-row">
                        <div class="f-group">
                            <label class="f-label">Завсарлага (мин)</label>
                            <input class="f-input" type="number" id="cfg-break" min="0" max="10" value="2">
                        </div>
                        <div class="f-group">
                            <label class="f-label">Үдийн завсарлага</label>
                            <select class="f-select2" id="cfg-lunch">
                                <option value="1">13:00–14:00 байна</option>
                                <option value="0">Байхгүй</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="mf-btn cancel" onclick="closeModal('modal-mat')">Болих</button>
                    <button class="mf-btn confirm" onclick="applyMatConfig()">Хэрэглэх</button>
                </div>
            </div>
        </div>
        <!-- ─── SCRIPT ─── -->
        <script>
            (function() {
                'use strict';
                // ── CONFIG ───────────────────────────────────────────────────────────────────
                const EVENT_ID = {
                    {
                        (int)($eventConfig - > event_id ?? ($event - > id ?? 0))
                    }
                };
                const SAVE_URL = "{{ route('event.match.save-bracket', ['event_id' => $eventConfig->event_id ?? ($event->id ?? 0)]) }}";
                const PREVIEW_URL = "{{ route('event.schedule.preview', ['eventId' => $eventConfig->event_id ?? ($event->id ?? 0)]) }}";
                const MATCHES_URL = "{{ route('event.public.schedule.matches', ['eventId' => $eventConfig->event_id ?? ($event->id ?? 0)]) }}";
                const CSRF = "{{ csrf_token() }}";
                // Static entry/age data from server
                const ENTRIES = @json($entries ?? []);
                const DAYS = @json($days ?? []);
                // ── STATE ────────────────────────────────────────────────────────────────────
                let cfg = {
                    mats: 4,
                    start: '09:00',
                    brk: 2,
                    lunch: true
                };
                let currentDayId = DAYS.length ? DAYS[0].id : null;
                // schedule[dayId][matIndex] = [ bracketObj, ... ]
                let schedule = {};
                // pool: all brackets not yet assigned
                let pool = [];
                // matchCache[bracketKey] = [matchObj,...]
                let matchCache = {};
                // drag state
                let dragSrc = null; // { type:'pool'|'mat', dayId, matIdx, bIdx, bracketKey }
                // ── INIT ─────────────────────────────────────────────────────────────────────
                init();
                async function init() {
                    DAYS.forEach(d => {
                        schedule[d.id] = Array.from({
                            length: cfg.mats
                        }, () => []);
                    });
                    await loadScheduleData();
                    renderPool();
                    renderTimeline();
                }
                // ── DATA LOAD ─────────────────────────────────────────────────────────────────
                async function loadScheduleData() {
                    try {
                        const r = await fetch(PREVIEW_URL);
                        const j = await r.json();
                        if (j.status !== 'success') return;
                        // Build pool from categories
                        const data = j.data;
                        pool = [];
                        [data.day1, data.day2].forEach((dayData, di) => {
                            const dayId = DAYS[di]?.id;
                            if (!dayId) return;
                            dayData.mats.forEach((mat, mi) => {
                                // ensure schedule slot exists
                                if (!schedule[dayId]) schedule[dayId] = Array.from({
                                    length: cfg.mats
                                }, () => []);
                                while (schedule[dayId].length < cfg.mats) schedule[dayId].push([]);
                                (mat.blocks || []).forEach(b => {
                                    const key = `${b.entry_id}_${b.age_id || ''}_${b.weight_id || ''}_${b.belt_id || ''}`;
                                    const bracket = {
                                        key,
                                        entry_id: b.entry_id,
                                        age_id: b.age_id,
                                        belt_id: b.belt_id,
                                        weight_id: b.weight_id,
                                        name: b.cat_name,
                                        weight: b.weight,
                                        belt: b.belt,
                                        n: b.n,
                                        fights: b.fights,
                                        mins: b.dur,
                                        sys: b.sys,
                                        sys_label: b.sys_label,
                                        gender: b.gender,
                                        is_judo: b.is_judo,
                                        locked: false,
                                    };
                                    schedule[dayId][mi].push(bracket);
                                });
                            });
                        });
                        // Remaining unassigned → pool (categories not in any mat)
                        // Pool is built from all categories that appear in preview but not assigned yet
                        updateStats();
                    } catch (e) {
                        console.error('loadScheduleData error:', e);
                        // show empty state
                        renderPool();
                        renderTimeline();
                    }
                }
                // ── POOL ─────────────────────────────────────────────────────────────────────
                function allScheduledKeys() {
                    const keys = new Set();
                    DAYS.forEach(d => {
                        (schedule[d.id] || []).forEach(mat => mat.forEach(b => keys.add(b.key)));
                    });
                    return keys;
                }

                function renderPool(filter = '') {
                    const el = document.getElementById('pool-list');
                    const fl = filter.toLowerCase();
                    // Group pool items by category name
                    const groups = {};
                    pool.forEach(b => {
                        const g = b.is_judo ? 'Жудо' : (b.gender === 'F' ? 'Эмэгтэй' : 'Эрэгтэй');
                        if (!groups[g]) groups[g] = [];
                        groups[g].push(b);
                    });
                    // Also add unassigned from schedule view (items in schedule are NOT in pool)
                    if (!pool.length && !Object.keys(groups).length) {
                        el.innerHTML = `
												<div style="padding:20px;text-align:center;font-size:11px;color:var(--muted)">Бүгд хуваарьт орсон</div>`;
                        return;
                    }
                    let html = '';
                    Object.entries(groups).forEach(([g, items]) => {
                        const vis = items.filter(b => !fl || b.name.toLowerCase().includes(fl) || (b.weight || '').toString().includes(fl));
                        if (!vis.length) return;
                        html += `
												<div class="pool-group">
													<div class="pool-group-hdr" onclick="toggleGroup(this)">
														<span class="pool-group-arrow open">▶</span>
														<span class="pool-group-name">${esc(g)}</span>
														<span class="pool-group-cnt">${vis.length}</span>
													</div>
													<div class="pool-items">`;
                        vis.forEach(b => {
                            const dot = b.is_judo ? 'pi-j' : (b.gender === 'F' ? 'pi-f' : 'pi-m');
                            html += `
														<div class="pool-item" draggable="true"
          data-key="${b.key}"
          ondragstart="onPoolDragStart(event,'${b.key}')"
          ondragend="onDragEnd(event)">
															<span class="pi-handle">⠿</span>
															<span class="pi-dot ${dot}"></span>
															<div class="pi-info">
																<div class="pi-name">${esc(b.name)}</div>
																<div class="pi-meta">${esc(b.weight||'')}кг · ${b.n}х тамирчин</div>
															</div>
															<div class="pi-badge">${esc(b.sys_label)}</div>
														</div>`;
                        });
                        html += `
													</div>
												</div>`;
                    });
                    el.innerHTML = html || `
												<div style="padding:20px;text-align:center;font-size:11px;color:var(--muted)">Хайлт олдсонгүй</div>`;
                }

                function filterPool(v) {
                    renderPool(v);
                }
                window.toggleGroup = function(hdr) {
                    const arrow = hdr.querySelector('.pool-group-arrow');
                    const items = hdr.nextElementSibling;
                    arrow.classList.toggle('open');
                    items.style.display = arrow.classList.contains('open') ? '' : 'none';
                };
                // ── TIMELINE RENDER ───────────────────────────────────────────────────────────
                function renderTimeline() {
                    const dayId = currentDayId;
                    if (!dayId) return;
                    const mats = schedule[dayId] || [];
                    const grid = document.getElementById('tl-grid');
                    const n = cfg.mats;
                    grid.style.gridTemplateColumns = `repeat(${n}, minmax(200px, 1fr))`;
                    const [sH, sM] = cfg.start.split(':').map(Number);
                    const lunchStartMins = cfg.lunch ? 13 * 60 - (sH * 60 + sM) : null;
                    let html = '';
                    for (let mi = 0; mi < n; mi++) {
                        const brackets = mats[mi] || [];
                        // compute times
                        let curMins = 0;
                        let blocksHtml = '';
                        if (!brackets.length) {
                            blocksHtml = `
												<div class="mat-drop-empty">Drag & Drop →</div>`;
                        } else {
                            brackets.forEach((b, bi) => {
                                // lunch injection
                                if (lunchStartMins !== null && curMins < lunchStartMins && curMins + b.mins > lunchStartMins) {
                                    blocksHtml += `
												<div class="lunch-bar">🍽 ҮДИЙН ЗАВСАРЛАГА 13:00–14:00</div>`;
                                }
                                const wallStart = curMins + (lunchStartMins !== null && curMins >= lunchStartMins ? 60 : 0);
                                const startAbs = sH * 60 + sM + wallStart;
                                const endAbs = startAbs + b.mins;
                                const dot = b.is_judo ? 'pi-j' : (b.gender === 'F' ? 'pi-f' : 'pi-m');
                                const dotC = b.is_judo ? '#e69b2f' : (b.gender === 'F' ? '#c4367a' : '#3699ff');
                                const sysCls = {
                                    'DE': 'sys-de',
                                    'RR': 'sys-rr',
                                    'RR2': 'sys-rr2',
                                    'IJF': 'sys-ijf',
                                    'BO3': 'sys-bo3'
                                } [b.sys] || '';
                                blocksHtml += `
												<div class="bracket-block${b.locked?' locked':''}"
            data-key="${b.key}" data-mat="${mi}" data-bi="${bi}"
            draggable="${b.locked?'false':'true'}"
            ondragstart="onBracketDragStart(event,'${b.key}',${mi},${bi})"
            ondragend="onDragEnd(event)"
            onclick="showDetail('${b.key}',${mi})">
          ${!b.locked ? `
													<button class="bb-remove" onclick="removeBracket(event,'${b.key}',${mi},${bi})">×</button>` : ''}
          
													<div class="bb-head">
														<span class="bb-drag-handle">⠿</span>
														<span class="bb-dot" style="background:${dotC}"></span>
														<span class="bb-name">${esc(b.name)}</span>
														<span class="bb-weight">${esc(b.weight||'')}кг</span>
													</div>
													<div class="bb-body">
														<div class="bb-stat">
															<div class="bb-stat-val">${b.n}</div>
															<div class="bb-stat-lbl">Тамирчин</div>
														</div>
														<div class="bb-stat">
															<div class="bb-stat-val">${b.fights}</div>
															<div class="bb-stat-lbl">Тулаан</div>
														</div>
														<div class="bb-stat">
															<div class="bb-stat-val">${b.mins}</div>
															<div class="bb-stat-lbl">Минут</div>
														</div>
													</div>
													<div class="bb-time">
														<span>${minsToTime(sH*60+sM+wallStart)}</span>
														<span class="bb-time-val">${minsToTime(endAbs)}</span>
														<span style="font-size:9px;padding:1px 5px;border-radius:4px;margin-left:4px" class="${sysCls}">${esc(b.sys_label)}</span>
													</div>
												</div>`;
                                curMins += b.mins;
                            });
                        }
                        // end time
                        const wallEnd = curMins + (lunchStartMins !== null && curMins > lunchStartMins ? 60 : 0);
                        const endTime = minsToTime(sH * 60 + sM + wallEnd);
                        const totalFights = brackets.reduce((a, b) => a + b.fights, 0);
                        html += `
												<div class="mat-col" id="mat-col-${mi}">
													<div class="mat-col-hdr">
														<div class="mat-col-name">МАТ ${mi+1}</div>
														<div class="mat-col-stats">
															<span>${brackets.length}</span> бр · 
															<span>${totalFights}</span> тулаан
														</div>
													</div>
													<div class="mat-drop-zone" id="drop-${mi}"
           ondragover="onDragOver(event,${mi})"
           ondragleave="onDragLeave(event,${mi})"
           ondrop="onDrop(event,${mi})">
        ${blocksHtml}
      </div>
													<div class="mat-end-bar">
														<span>Дуусах цаг</span>
														<span class="mat-end-val">${endTime}</span>
													</div>
												</div>`;
                    }
                    grid.innerHTML = html;
                    document.getElementById('tl-mat-count').textContent = `${n} дэвжээ`;
                    document.getElementById('tl-start').textContent = cfg.start;
                    updateStats();
                }
                // ── DRAG & DROP ───────────────────────────────────────────────────────────────
                window.onPoolDragStart = function(e, key) {
                    const b = findBracketByKey(key);
                    if (!b) return;
                    dragSrc = {
                        type: 'pool',
                        key
                    };
                    e.dataTransfer.effectAllowed = 'move';
                    e.currentTarget.classList.add('dragging');
                };
                window.onBracketDragStart = function(e, key, matIdx, bi) {
                    const b = findInSchedule(currentDayId, matIdx, key);
                    if (!b || b.locked) {
                        e.preventDefault();
                        return;
                    }
                    dragSrc = {
                        type: 'mat',
                        key,
                        matIdx,
                        bi
                    };
                    e.dataTransfer.effectAllowed = 'move';
                    e.stopPropagation();
                };
                window.onDragEnd = function(e) {
                    document.querySelectorAll('.dragging').forEach(el => el.classList.remove('dragging'));
                    document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
                    dragSrc = null;
                };
                window.onDragOver = function(e, matIdx) {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    document.getElementById(`drop-${matIdx}`)?.classList.add('drag-over');
                    document.getElementById(`mat-col-${matIdx}`)?.classList.add('drag-over');
                };
                window.onDragLeave = function(e, matIdx) {
                    if (!e.currentTarget.contains(e.relatedTarget)) {
                        document.getElementById(`drop-${matIdx}`)?.classList.remove('drag-over');
                        document.getElementById(`mat-col-${matIdx}`)?.classList.remove('drag-over');
                    }
                };
                window.onDrop = function(e, matIdx) {
                    e.preventDefault();
                    document.getElementById(`drop-${matIdx}`)?.classList.remove('drag-over');
                    document.getElementById(`mat-col-${matIdx}`)?.classList.remove('drag-over');
                    if (!dragSrc) return;
                    const dayId = currentDayId;
                    if (!schedule[dayId]) schedule[dayId] = Array.from({
                        length: cfg.mats
                    }, () => []);
                    if (dragSrc.type === 'pool') {
                        // move from pool to mat
                        const idx = pool.findIndex(b => b.key === dragSrc.key);
                        if (idx === -1) return;
                        const [b] = pool.splice(idx, 1);
                        schedule[dayId][matIdx].push(b);
                        toast(`${b.name} ${b.weight}кг → Мат ${matIdx+1}`, 'success');
                    } else if (dragSrc.type === 'mat') {
                        if (dragSrc.matIdx === matIdx) return; // same mat
                        const src = schedule[dayId][dragSrc.matIdx];
                        const bi = src.findIndex(b => b.key === dragSrc.key);
                        if (bi === -1) return;
                        const b = src[bi];
                        if (b.locked) {
                            toast('Түгжигдсэн bracket шилжүүлэх боломжгүй', 'warn');
                            return;
                        }
                        src.splice(bi, 1);
                        schedule[dayId][matIdx].push(b);
                        toast(`${b.name} → Мат ${matIdx+1}`, 'success');
                    }
                    renderPool();
                    renderTimeline();
                    dragSrc = null;
                };
                // ── REMOVE FROM MAT ───────────────────────────────────────────────────────────
                window.removeBracket = function(e, key, matIdx, bi) {
                    e.stopPropagation();
                    const dayId = currentDayId;
                    const arr = schedule[dayId]?.[matIdx];
                    if (!arr) return;
                    const idx = arr.findIndex(b => b.key === key);
                    if (idx === -1) return;
                    const [b] = arr.splice(idx, 1);
                    pool.push(b);
                    toast(`${b.name} pool-д буцлаа`, 'warn');
                    renderPool();
                    renderTimeline();
                };
                // ── DETAIL PANEL ──────────────────────────────────────────────────────────────
                window.showDetail = async function(key, matIdx) {
                    const dayId = currentDayId;
                    const b = findInSchedule(dayId, matIdx, key);
                    if (!b) return;
                    const dp = document.getElementById('dp-scroll');
                    const title = document.getElementById('dp-title');
                    title.textContent = `${b.name} ${b.weight}кг`;
                    const gTag = b.gender === 'F' ? `
												<span class="dp-tag pink">Эмэгтэй</span>` : `
												<span class="dp-tag blue">Эрэгтэй</span>`;
                    const jTag = b.is_judo ? `
												<span class="dp-tag amber">JUDO</span>` : '';
                    const sTag = `
												<span class="dp-tag ${sysCls(b.sys)}">${esc(b.sys_label)}</span>`;
                    const mTag = `
												<span class="dp-tag teal">Мат ${matIdx+1}</span>`;
                    dp.innerHTML = `
												<div class="dp-cat-hdr">
													<div class="dp-cat-name">${esc(b.name)} 
														<span style="color:var(--soft)">${esc(b.weight||'')}кг</span>
													</div>
													<div class="dp-cat-meta">${gTag}${jTag}${sTag}${mTag}</div>
												</div>
												<div class="dp-fights-total">
													<span>${b.n} тамирчин · 
														<b>${b.fights}</b> тулаан
													</span>
													<span class="hi">${b.mins} мин</span>
												</div>
												<div id="dp-matches">
													<div class="loading-full" style="height:120px">
														<div class="spin-lg"></div>
													</div>
												</div>`;
                    // fetch matches
                    if (!matchCache[key]) {
                        try {
                            const p = new URLSearchParams({
                                event_id: EVENT_ID,
                                bracket_key: key
                            });
                            const r = await fetch(MATCHES_URL + '?' + p);
                            const j = await r.json();
                            matchCache[key] = processMatches(j, b);
                        } catch (e) {
                            matchCache[key] = [];
                        }
                    }
                    renderMatches(matchCache[key], b);
                };

                function processMatches(data, bracket) {
                    // data is the normal schedule response — extract matches for this bracket
                    const matches = [];
                    (Array.isArray(data) ? data : []).forEach(day => {
                        (day.mates || []).forEach(mat => {
                            (mat.event_matches || []).forEach(m => {
                                // filter by entry/weight
                                const eId = m.reg_one?.entry_id ?? m.bracket_entry_id;
                                if (eId == bracket.entry_id || !bracket.entry_id) matches.push(m);
                            });
                        });
                    });
                    return matches;
                }

                function renderMatches(matches, bracket) {
                    const el = document.getElementById('dp-matches');
                    if (!el) return;
                    if (!matches.length) {
                        el.innerHTML = `
												<div style="text-align:center;padding:24px;font-size:12px;color:var(--muted)">Тулаан олдсонгүй
													<br>
														<span style="font-size:10px;opacity:.6">Дараа нь дахин шалгана уу</span>
													</div>`;
                        return;
                    }
                    let html = '',
                        idx = 0;
                    matches.forEach(m => {
                        const bye = (!m.reg_two_id || !m.reg_one_id) && m.status === 'C';
                        if (bye) return;
                        idx++;
                        const done = m.status === 'C',
                            active = m.status === 'A';
                        const p1n = m.reg_one?.member?.firstname ?? 'TBD';
                        const p1last = m.reg_one?.member?.lastname ?? '';
                        const p2n = m.reg_two?.member?.firstname ?? 'TBD';
                        const p2last = m.reg_two?.member?.lastname ?? '';
                        const p1c = m.reg_one?.academy?.name ?? '';
                        const p2c = m.reg_two?.academy?.name ?? '';
                        const p1w = done && m.reg_win_id === m.reg_one_id;
                        const p2w = done && m.reg_win_id === m.reg_two_id;
                        let wm = '';
                        if (done && m.win_method) {
                            wm = m.win_method;
                            if (m.end_time) {
                                const p = m.end_time.split(':');
                                wm += ' ' + p[1] + ':' + (p[2] || '00');
                            }
                        }
                        const stIco = done ? `
													<div class="mr-st done">
														<svg width="10" height="10" viewBox="0 0 12 12" fill="none">
															<path d="M2 6L5 9L10 3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
														</svg>
													</div>` : active ? `
													<div class="mr-st active">
														<svg width="8" height="8" viewBox="0 0 10 10">
															<circle cx="5" cy="5" r="4" fill="white"/>
														</svg>
													</div>` : `
													<div class="mr-st pending"></div>`;
                        html += `
													<div class="match-row${active?' is-active':''}">
														<div class="mr-status">${stIco}</div>
														<div class="mr-num">${idx}</div>
														<div class="mr-players">
															<div class="pr">
																<span class="pdot r"></span>
																<span class="pname${p1w?' w':''}">${esc(p1n)} ${esc(p1last)}</span>
																<span class="pclub">${esc(p1c)}</span>${p1w&&wm?`
																<span class="pwin">${esc(wm)}</span>`:''}
															</div>
															<div class="pr">
																<span class="pdot b"></span>
																<span class="pname${p2w?' w':''}">${esc(p2n)} ${esc(p2last)}</span>
																<span class="pclub">${esc(p2c)}</span>${p2w&&wm?`
																<span class="pwin">${esc(wm)}</span>`:''}
															</div>
														</div>
      ${done ? `
														<div class="mr-time done">Дууссан</div>` : `
														<div class="mr-time">—</div>`}
    
													</div>`;
                    });
                    el.innerHTML = html || `
													<div style="text-align:center;padding:24px;font-size:12px;color:var(--muted)">Тулаан байхгүй</div>`;
                }
                window.closeDetail = function() {
                    document.getElementById('dp-scroll').innerHTML = `
													<div class="dp-empty">
														<div class="dp-empty-icon">🥋</div>Bracket сонгон тулааны
														<br>жагсаалт харах
														</div>`;
                    document.getElementById('dp-title').textContent = 'Дэлгэрэнгүй';
                };
                // ── RECALC (auto greedy) ──────────────────────────────────────────────────────
                window.recalcSchedule = async function() {
                    const btn = document.querySelector('.tbtn.recalc');
                    btn.innerHTML = `
														<span class="spin"></span> Тооцоолж байна…`;
                    btn.disabled = true;
                    try {
                        const r = await fetch(PREVIEW_URL + '?mat_count_day1=' + cfg.mats + '&mat_count_day2=' + cfg.mats + '&start_time_day1=' + cfg.start + '&start_time_day2=' + cfg.start + '&break_minutes=' + cfg.brk + '&lunch_enabled=' + (cfg.lunch ? 1 : 0));
                        const j = await r.json();
                        if (j.status !== 'success') throw new Error(j.message || 'Error');
                        // Reset schedule from server calculation
                        DAYS.forEach((d, di) => {
                            schedule[d.id] = Array.from({
                                length: cfg.mats
                            }, () => []);
                            const dayData = di === 0 ? j.data.day1 : j.data.day2;
                            dayData.mats.forEach((mat, mi) => {
                                if (mi >= cfg.mats) return;
                                (mat.blocks || []).forEach(b => {
                                    schedule[d.id][mi].push({
                                        key: `${b.entry_id}_${b.age_id||''}_${b.weight_id||''}_${b.belt_id||''}`,
                                        entry_id: b.entry_id,
                                        age_id: b.age_id,
                                        belt_id: b.belt_id,
                                        weight_id: b.weight_id,
                                        name: b.cat_name,
                                        weight: b.weight,
                                        belt: b.belt,
                                        n: b.n,
                                        fights: b.fights,
                                        mins: b.dur,
                                        sys: b.sys,
                                        sys_label: b.sys_label,
                                        gender: b.gender,
                                        is_judo: b.is_judo,
                                        locked: false
                                    });
                                });
                            });
                        });
                        pool = [];
                        renderPool();
                        renderTimeline();
                        toast('Автомат тооцоолол хийгдлээ', 'success');
                    } catch (e) {
                        toast('Тооцоолол амжилтгүй: ' + e.message, 'error');
                    } finally {
                        btn.innerHTML = `
														<span>⟳</span> Тооцоолох`;
                        btn.disabled = false;
                    }
                };
                // ── SAVE ─────────────────────────────────────────────────────────────────────
                window.saveSchedule = async function() {
                    const btn = document.getElementById('save-btn');
                    btn.innerHTML = `
														<span class="spin"></span> Хадгалж байна…`;
                    btn.disabled = true;
                    const scheduleDays = [];
                    DAYS.forEach(day => {
                        const mats = [];
                        (schedule[day.id] || []).forEach((brackets, mi) => {
                            const bs = brackets.map(b => ({
                                entry_id: b.entry_id,
                                entry_belt_id: b.belt_id,
                                entry_age_id: b.age_id,
                                entry_weight_id: b.weight_id,
                            }));
                            mats.push({
                                mat: mi + 1,
                                brackets: bs
                            });
                        });
                        scheduleDays.push({
                            day: day.id,
                            mats
                        });
                    });
                    try {
                        const r = await fetch(SAVE_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF
                            },
                            body: JSON.stringify({
                                schedule: scheduleDays
                            }),
                        });
                        const j = await r.json();
                        if (j.status === 'success') {
                            toast('Хуваарь хадгалагдлаа ✓', 'success');
                            // mark saved brackets as potentially locked (has matches)
                            if (j.bye_match_ids?.length) toast(`${j.bye_match_ids.length} bye match үүслээ`, 'warn');
                        } else {
                            throw new Error(j.message || 'Server error');
                        }
                    } catch (e) {
                        toast('Хадгалах амжилтгүй: ' + e.message, 'error');
                    } finally {
                        btn.innerHTML = 'Хадгалах';
                        btn.disabled = false;
                    }
                };
                // ── DAY SELECT ────────────────────────────────────────────────────────────────
                window.selectDay = function(dayId, btn) {
                    currentDayId = dayId;
                    document.querySelectorAll('.tb-tab').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    renderTimeline();
                };
                // ── MAT CONFIG MODAL ──────────────────────────────────────────────────────────
                window.openMatConfigModal = function() {
                    document.getElementById('cfg-mats').value = cfg.mats;
                    document.getElementById('cfg-start').value = cfg.start;
                    document.getElementById('cfg-break').value = cfg.brk;
                    document.getElementById('cfg-lunch').value = cfg.lunch ? '1' : '0';
                    openModal('modal-mat');
                };
                window.applyMatConfig = function() {
                    const newMats = Math.max(1, Math.min(8, parseInt(document.getElementById('cfg-mats').value) || 4));
                    const newStart = document.getElementById('cfg-start').value || '09:00';
                    const newBrk = parseInt(document.getElementById('cfg-break').value) || 2;
                    const newLunch = document.getElementById('cfg-lunch').value === '1';
                    // Resize schedule mats
                    DAYS.forEach(d => {
                        if (!schedule[d.id]) schedule[d.id] = [];
                        while (schedule[d.id].length < newMats) schedule[d.id].push([]);
                        // if shrinking, move excess to pool
                        while (schedule[d.id].length > newMats) {
                            const excess = schedule[d.id].pop();
                            excess.forEach(b => {
                                if (!b.locked) pool.push(b);
                            });
                        }
                    });
                    cfg = {
                        mats: newMats,
                        start: newStart,
                        brk: newBrk,
                        lunch: newLunch
                    };
                    closeModal('modal-mat');
                    renderPool();
                    renderTimeline();
                    toast(`${newMats} дэвжээ · ${newStart} эхлэл тохируулагдлаа`, 'success');
                };
                // ── ADD CATEGORY MODAL ────────────────────────────────────────────────────────
                window.openAddCatModal = function() {
                    document.getElementById('mc-entry').value = '';
                    document.getElementById('mc-age').innerHTML = ' < option value = "" > —Сонгох— < /option>';
                    document.getElementById('mc-dur').value = '3';
                    document.getElementById('mc-weights').innerHTML = ' < span style = "font-size:11px;color:var(--muted)" > Entry болон Age сонгоно уу < /span>';
                    openModal('modal-cat');
                };
                window.onEntryChange = function() {
                    const sel = document.getElementById('mc-entry');
                    const entry = ENTRIES.find(e => e.id == sel.value);
                    const ageSel = document.getElementById('mc-age');
                    ageSel.innerHTML = ' < option value = "" > —Сонгох— < /option>';
                    if (!entry) return;
                    (entry.ages || []).forEach(a => {
                        const o = document.createElement('option');
                        o.value = a.id;
                        o.textContent = a.name;
                        ageSel.appendChild(o);
                    });
                    document.getElementById('mc-weights').innerHTML = ' < span style = "font-size:11px;color:var(--muted)" > Age сонгоно уу < /span>';
                };
                window.onAgeChange = function() {
                    const entryId = document.getElementById('mc-entry').value;
                    const ageId = document.getElementById('mc-age').value;
                    const entry = ENTRIES.find(e => e.id == entryId);
                    const age = (entry?.ages || []).find(a => a.id == ageId);
                    if (!age) return;
                    if (age.fight_duration) document.getElementById('mc-dur').value = age.fight_duration;
                    // Build weight chips
                    const cont = document.getElementById('mc-weights');
                    let html = '';
                    (age.belts || age.weights || []).forEach(belt => {
                        (belt.weights || [belt]).forEach(w => {
                            html += `
														<div class="wchip sel" data-weight-id="${w.id}" data-belt-id="${belt.id||w.belt_id||''}" data-weight="${w.weight||w.name||''}" onclick="this.classList.toggle('sel')">
															<span class="wchip-kg">${w.weight||w.name}кг</span>
															<span class="wchip-n" id="wn-${w.id}">0х</span>
														</div>`;
                        });
                    });
                    cont.innerHTML = html || ' < span style = "font-size:11px;color:var(--muted)" > Жингийн анги олдсонгүй < /span>';
                    // Auto-fill participant counts from schedule if available
                    (schedule[currentDayId] || []).forEach(mat => mat.forEach(b => {
                        if (b.entry_id == entryId && b.age_id == ageId) {
                            const el = document.getElementById(`wn-${b.weight_id}`);
                            if (el) el.textContent = b.n + 'х';
                        }
                    }));
                };
                window.selectDayChip = function(el) {
                    document.querySelectorAll('.day-chip').forEach(c => c.classList.remove('sel'));
                    el.classList.add('sel');
                };
                window.confirmAddCat = function() {
                    const entryId = parseInt(document.getElementById('mc-entry').value);
                    const ageId = parseInt(document.getElementById('mc-age').value);
                    const dur = parseInt(document.getElementById('mc-dur').value) || 3;
                    const dayChip = document.querySelector('.day-chip.sel');
                    const dayId = dayChip ? parseInt(dayChip.dataset.dayId) : currentDayId;
                    if (!entryId || !ageId) {
                        toast('Entry болон Age сонгоно уу', 'warn');
                        return;
                    }
                    const entry = ENTRIES.find(e => e.id == entryId);
                    const chips = document.querySelectorAll('#mc-weights .wchip.sel');
                    if (!chips.length) {
                        toast('Жингийн анги сонгоно уу', 'warn');
                        return;
                    }
                    chips.forEach(chip => {
                        const wId = parseInt(chip.dataset.weightId);
                        const bId = parseInt(chip.dataset.beltId);
                        const wt = chip.dataset.weight;
                        const nEl = chip.querySelector('.wchip-n');
                        const n = parseInt(nEl?.textContent) || 0;
                        const key = `${entryId}_${ageId}_${wId}_${bId}`;
                        if (findBracketByKey(key)) return; // already exists
                        const sys = getSys(n, entry?.sport);
                        const fights = getFights(n, entry?.sport);
                        const mins = fights * (dur + cfg.brk);
                        const b = {
                            key,
                            entry_id: entryId,
                            age_id: ageId,
                            belt_id: bId,
                            weight_id: wId,
                            name: (entry?.name || '') + ' ' + (document.getElementById('mc-age').options[document.getElementById('mc-age').selectedIndex]?.text || ''),
                            weight: wt,
                            belt: '',
                            n,
                            fights,
                            mins,
                            sys,
                            sys_label: sysLabel(sys),
                            gender: entry?.gender || 'M',
                            is_judo: entry?.sport === 'judo',
                            locked: false,
                        };
                        pool.push(b);
                    });
                    closeModal('modal-cat');
                    renderPool();
                    renderTimeline();
                    toast('Жингийн ангиуд pool-д нэмэгдлээ', 'success');
                };
                // ── STATS ─────────────────────────────────────────────────────────────────────
                function updateStats() {
                    let totalPax = 0,
                        totalFights = 0;
                    DAYS.forEach(d => {
                        (schedule[d.id] || []).forEach(mat => mat.forEach(b => {
                            totalPax += b.n;
                            totalFights += b.fights;
                        }));
                    });
                    document.getElementById('stat-pax').innerHTML = `Нийт: 
														<b>${totalPax}</b>`;
                    document.getElementById('stat-fights').innerHTML = `Тулаан: 
														<b>${totalFights}</b>`;
                    const dayId = currentDayId;
                    const mats = schedule[dayId] || [];
                    const [sH, sM] = cfg.start.split(':').map(Number);
                    const lunchSt = cfg.lunch ? 13 * 60 - (sH * 60 + sM) : null;
                    const matEnds = mats.map(mat => {
                        let cur = mat.reduce((a, b) => a + b.mins, 0);
                        if (lunchSt !== null && cur > lunchSt) cur += 60;
                        return cur;
                    });
                    const latest = Math.max(...matEnds, 0);
                    document.getElementById('stat-end').innerHTML = `Дуусах: 
														<b>${minsToTime(sH*60+sM+latest)}</b>`;
                }
                // ── UTILITIES ─────────────────────────────────────────────────────────────────
                function findBracketByKey(key) {
                    const inPool = pool.find(b => b.key === key);
                    if (inPool) return inPool;
                    for (const d of DAYS) {
                        for (const mat of (schedule[d.id] || [])) {
                            const found = mat.find(b => b.key === key);
                            if (found) return found;
                        }
                    }
                    return null;
                }

                function findInSchedule(dayId, matIdx, key) {
                    return (schedule[dayId]?.[matIdx] || []).find(b => b.key === key);
                }

                function minsToTime(m) {
                    return `${Math.floor(m/60)}:${String(m%60).padStart(2,'0')}`;
                }

                function esc(s) {
                    const d = document.createElement('div');
                    d.textContent = String(s ?? '');
                    return d.innerHTML;
                }

                function getSys(n, sport) {
                    if (sport === 'judo') {
                        if (n >= 6) return 'IJF';
                        if (n >= 3) return 'RR';
                        if (n === 2) return 'BO3';
                        return 'NONE';
                    }
                    if (n >= 7) return 'DE';
                    if (n === 6) return 'RR2';
                    if (n >= 3) return 'RR';
                    if (n === 2) return 'BO3';
                    return 'NONE';
                }

                function getFights(n, sport) {
                    const s = getSys(n, sport);
                    if (s === 'IJF') return Math.ceil(n / 2) * 2 + 3;
                    if (s === 'DE') return 2 * (n - 1);
                    if (s === 'RR2') return 8;
                    if (s === 'RR') return n * (n - 1) / 2;
                    if (s === 'BO3') return 3;
                    return 0;
                }

                function sysLabel(s) {
                    return {
                        DE: 'Double Elim',
                        RR2: '2Pool RR',
                        RR: 'Round Robin',
                        IJF: 'IJF Bracket',
                        BO3: 'Best of 3',
                        NONE: '—'
                    } [s] || s;
                }

                function sysCls(s) {
                    return {
                        DE: 'sys-de',
                        RR2: 'sys-rr2',
                        RR: 'sys-rr',
                        IJF: 'sys-ijf',
                        BO3: 'sys-bo3'
                    } [s] || '';
                }
                // toast
                function toast(msg, type = 'success', dur = 2800) {
                    const wrap = document.getElementById('toast-wrap');
                    const el = document.createElement('div');
                    el.className = `toast ${type}`;
                    el.textContent = msg;
                    wrap.appendChild(el);
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => el.classList.add('show'));
                    });
                    setTimeout(() => {
                        el.classList.remove('show');
                        setTimeout(() => el.remove(), 300);
                    }, dur);
                }
                // modals
                function openModal(id) {
                    document.getElementById(id).classList.add('show');
                }
                window.closeModal = function(id) {
                    document.getElementById(id).classList.remove('show');
                };
                document.querySelectorAll('.overlay').forEach(o => {
                    o.addEventListener('click', e => {
                        if (e.target === o) o.classList.remove('show');
                    });
                });
            })();
        </script>
    </body>
</html>