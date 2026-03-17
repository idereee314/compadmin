
<style>
    .match-row {
        display: flex;
        align-items: stretch;
        background: #1e1e2d;
        border-radius: 8px;
        margin-bottom: 6px;
        overflow: hidden;
        min-height: 62px;
    }
    .match-row:hover {
        background: #252540;
    }
    .match-info-label {
        font-size: 11px;
        color: #8a8a9e;
        padding: 2px 12px;
        margin-top: 6px;
    }
    .match-status {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
        min-width: 36px;
    }
    .match-status .status-icon {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    .status-icon.completed {
        background: #1bc5bd;
        color: #fff;
    }
    .status-icon.pending {
        background: #ffa800;
        color: #fff;
    }
    .status-icon.active {
        background: #3699ff;
        color: #fff;
    }
    .match-number {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        padding: 0 14px;
        min-width: 50px;
        border-right: 1px solid rgba(255,255,255,0.08);
    }
    .match-players {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 8px 14px;
        min-width: 0;
    }
    .player-row {
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.4;
    }
    .player-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .player-dot.red { background: #f64e60; }
    .player-dot.blue { background: #3699ff; }
    .player-name {
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
    }
    .club-name {
        font-size: 12px;
        color: #8a8a9e;
        white-space: nowrap;
    }
    .win-info {
        font-size: 14px;
        font-weight: 600;
        color: #1bc5bd;
        margin-left: 6px;
        white-space: nowrap;
    }
    .match-time {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 16px;
        min-width: 80px;
        font-size: 13px;
        color: #b5b5c3;
        white-space: nowrap;
        border-left: 1px solid rgba(255,255,255,0.08);
    }
    .match-time.finished {
        color: #1bc5bd;
        font-weight: 600;
    }
    .match-edit-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
    }
    .match-section-header {
        color: #fff;
        margin-bottom: 10px;
        margin-top: 20px;
    }
    .match-total-row {
        display: flex;
        align-items: center;
        background: #15152a;
        border-radius: 8px;
        padding: 10px 16px;
        margin-top: 8px;
        color: #b5b5c3;
        font-size: 13px;
        justify-content: space-between;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <!-- Dynamic Match List -->
            <div id="dynamic-day-matches"></div>
        </div>
        <div class="col-lg-4 col-md-12">
            <!-- Search Box -->
            <div class="mb-4 float-end">
                <h5>Search</h5>
                <div class="mb-2">
                    <select class="form-control selectpicker" id="search-day" name="type">
                        @forelse(@$configViewDict['days'] as $day)
                            <option value="{{ $day->id }}">Day {{ $day->item_no }}</option>
                        @empty
                        @endforelse
                    </select>
                    <div class="error-here"></div>
                </div>
                <div class="mb-2">
                    <select class="form-control selectpicker" id="search-mat" name="type">
                    </select>
                    <div class="error-here"></div>
                </div>
                <button id="search-button" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </div>
</div>