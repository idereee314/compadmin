

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

<script>
    $(document).ready(function() {
        const disableEdit = @json($disableEdit ?? false);
        const matesByDay = @json($configViewDict['mate']);

        $('#search-day').on('changed.bs.select', function () {
            const selectedDayId = this.value;
            const mateSelect = document.getElementById('search-mat');
            mateSelect.innerHTML = '<option value="">-- Mat --</option>';

            if (matesByDay[selectedDayId]) {
                matesByDay[selectedDayId].forEach(mate => {
                    const option = document.createElement('option');
                    option.value = mate.id;
                    option.text = 'Mat ' + (mate.mate_no);
                    mateSelect.appendChild(option);
                });
            }
            $('#search-mat').selectpicker('refresh');
            $('#search-mat').prop('selectedIndex', 0);
        });

        $('#search-day, #search-mat').selectpicker();
        $('#search-day').prop('selectedIndex', 0);
        $('#search-day').trigger('changed.bs.select');
        const searchInputs = {
            day: $('#search-day'),
            mat: $('#search-mat'),
            age: $('#search-age'),
            gender: $('#search-gender'),
            weight: $('#search-weight'),
        };

        function formatToHHmm(date) {
            let hours = date.getHours().toString().padStart(2, '0');
            let minutes = date.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        function getMatchInfo(bracket) {
            const entry = bracket.reg_one?.entry?.name ?? '';
            const age = bracket.reg_one?.age ?? null;
            const belt = bracket.reg_one?.belt?.name ?? '';
            const weight = bracket.reg_one?.weight?.weight ?? '';

            let ageName = '';
            if (age) {
                if (age.start_age == null) {
                    ageName = '-' + age.end_age;
                } else if (age.end_age == null) {
                    ageName = age.start_age + '+';
                } else {
                    ageName = age.start_age + '-' + age.end_age;
                }
            }

            const parts = [entry, ageName, belt, weight ? '-' + weight + 'KG' : ''].filter(p => p);
            return parts.join(' / ');
        }

        function getWinMethodDisplay(bracket) {
            if (bracket.status !== 'C' || !bracket.reg_win_id) return '';
            const method = bracket.win_method || '';
            const endTime = bracket.end_time || '';
            let timeDisplay = '';
            if (endTime) {
                const parts = endTime.split(':');
                timeDisplay = parts[1] + ':' + (parts[2] || '00');
            }
            if (method && timeDisplay) {
                return `${method} - ${timeDisplay}`;
            }
            if (method) return method;
            if (timeDisplay) return timeDisplay;
            return '';
        }

        function renderMatches(data) {
            const container = document.getElementById('dynamic-day-matches');
            container.innerHTML = "";
            const eventConfig = @json($eventConfig) || {};
            
            data.forEach(day => {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('col-12', 'mb-4');
                const dayHeading = document.createElement('h3');
                dayHeading.classList.add('match-section-header');
                dayHeading.innerText = `Day ${day.day}`;
                dayDiv.appendChild(dayHeading);
                const starDate = new Date(day.start_date);
                let [cH, cM, cS] = [0,0,0];
                if(eventConfig.start_time){
                    [cH, cM, cS] = eventConfig.start_time.split(':')?.map(Number);
                }
                starDate.setHours(cH, cM, cS || 0, 0);

                day.mates.forEach(mat => {
                    const matDiv = document.createElement('div');
                    matDiv.classList.add('mb-5');
                    const matHeading = document.createElement('h4');
                    matHeading.classList.add('match-section-header');
                    matHeading.style.fontSize = '16px';
                    matHeading.innerText = `Mat ${mat.mate_no}`;
                    matDiv.appendChild(matHeading);
                    
                    let totalDuration = 0;
                    let duration = 0;
                    let index = 0;
                    const matchData = [];

                    mat.event_matches.map((bracket) => {
                        if(isItBYE(bracket)){
                            return;
                        }
                        index++;
                        matchData.push(bracket.id);
                        
                        starDate.setMinutes(starDate.getMinutes() + duration);
                        if(bracket.end_time){
                            const start = new Date(starDate.getTime());
                            const [hours, minutes, seconds] = bracket.end_time.split(':').map(Number);
                            const end = new Date(starDate.getTime());
                            end.setHours(hours, minutes, seconds || 0, 0);
                            const timeDiffMs = end.getTime() - start.getTime();
                            duration = timeDiffMs / (1000 * 60);
                        } else {
                            if ((!bracket?.reg_two_id || !bracket?.reg_one_id) && bracket.status === 'C') {
                                duration = 0;
                            } else {
                                duration = (bracket.duration ?? 0);
                            }
                        }
                        const endDate = new Date(starDate.getTime());
                        endDate.setMinutes(endDate.getMinutes() + duration);
                        totalDuration += duration;
                        
                        const isCompleted = bracket.status === 'C';
                        const isActive = bracket.status === 'A';
                        const matchInfo = getMatchInfo(bracket);

                        // Player names and clubs
                        let p1Name = bracket.reg_one?.member?.firstname ?? 'TBD';
                        const p1Last = bracket.reg_one?.member?.lastname ?? '';
                        let p2Name = bracket.reg_two?.member?.firstname ?? 'TBD';
                        const p2Last = bracket.reg_two?.member?.lastname ?? '';
                        if (p1Name === 'TBD' && isCompleted) p1Name = 'BYE';
                        if (p2Name === 'TBD' && isCompleted) p2Name = 'BYE';

                        const p1Club = bracket.reg_one?.academy?.name ?? '';
                        const p2Club = bracket.reg_two?.academy?.name ?? '';

                        // Determine winner
                        const p1IsWinner = isCompleted && bracket.reg_win_id && bracket.reg_win_id === bracket.reg_one_id;
                        const p2IsWinner = isCompleted && bracket.reg_win_id && bracket.reg_win_id === bracket.reg_two_id;
                        const winMethodText = getWinMethodDisplay(bracket);

                        // Build match info label
                        let matchInfoHtml = '';
                        if (matchInfo) {
                            matchInfoHtml = `<div class="match-info-label">${matchInfo}</div>`;
                        }
                        
                        // Status icon
                        let statusHtml = '';
                        if (isCompleted) {
                            statusHtml = `<div class="status-icon completed"><svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 6L5 9L10 3" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></div>`;
                        } else if (isActive) {
                            statusHtml = `<div class="status-icon active"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="4" fill="white"/></svg></div>`;
                        } else {
                            statusHtml = `<div class="status-icon pending"><svg width="10" height="10" viewBox="0 0 10 10" fill="none"><circle cx="5" cy="5" r="3" stroke="white" stroke-width="1.5" fill="none"/></svg></div>`;
                        }

                        // Win info display (after winner's club name)
                        const p1WinHtml = p1IsWinner && winMethodText ? `<span class="win-info">${winMethodText}</span>` : '';
                        const p2WinHtml = p2IsWinner && winMethodText ? `<span class="win-info">${winMethodText}</span>` : '';

                        // Time display
                        const timeDisplay = isCompleted
                            ? `<div class="match-time finished">Дууссан</div>`
                            : `<div class="match-time">${formatToHHmm(starDate)}</div>`;

                        // Edit button
                        let editHtml = '';
                        if (!disableEdit) {
                            editHtml = `<div class="match-edit-btn">
                                <a href="javascript:;" class="btn btn-icon btn-sm btn-light edit-button" data-match-id="${bracket.id}" style="width:28px;height:28px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                            </div>`;
                        }

                        // Build the row
                        const rowWrapper = document.createElement('div');
                        rowWrapper.innerHTML = `
                            ${matchInfoHtml}
                            <div class="match-row">
                                <div class="match-status">${statusHtml}</div>
                                <div class="match-number">${index}</div>
                                <div class="match-players">
                                    <div class="player-row">
                                        <span class="player-dot red"></span>
                                        <span class="player-name">${p1Name} ${p1Last}</span>
                                        <span class="club-name">${p1Club}</span>
                                        ${p1WinHtml}
                                    </div>
                                    <div class="player-row">
                                        <span class="player-dot blue"></span>
                                        <span class="player-name">${p2Name} ${p2Last}</span>
                                        <span class="club-name">${p2Club}</span>
                                        ${p2WinHtml}
                                    </div>
                                </div>
                                ${timeDisplay}
                                ${editHtml}
                            </div>
                        `;
                        matDiv.appendChild(rowWrapper);
                    });

                    localStorage.setItem(`${@json($eventConfig['event_id'])}_${day.day_id}_${mat.mate_id}`, JSON.stringify(matchData));

                    starDate.setMinutes(starDate.getMinutes() + duration);

                    // Total row
                    const totalRow = document.createElement('div');
                    totalRow.classList.add('match-total-row');
                    totalRow.innerHTML = `<span>Total matches: ${matchData.length}</span><span>End: ${formatToHHmm(starDate)} | Duration: ${formatMinutesToHHmm(totalDuration)}</span>`;
                    matDiv.appendChild(totalRow);

                    dayDiv.appendChild(matDiv);
                });

                container.appendChild(dayDiv);
            });
        }
        
        function isItBYE(bracket){
            if ((!bracket?.reg_two_id || !bracket?.reg_one_id) && bracket.status === 'C') {
                return true;
            }
            return false;
        }

        function formatMinutesToHHmm(minutes) {
            const hrs = Math.floor(minutes / 60);
            const mins = Math.round(minutes % 60);
            return `${hrs.toString().padStart(2, '')}h ${mins.toString().padStart(2, '0')}m`;
        }

        function handleMatchesData(data){
            const result = [];
            data.forEach(day => {
                const bracketDurations = {};
                day.mates.forEach(mat => {
                    mat.matches.forEach(bracket => {
                        bracketDurations[bracket.id] = bracket.entry?.duration ?? 0;
                    });
                });
                result.push({
                    day: day.day,
                    day_id: day.id,
                    start_date: day.start_date,
                    mates: day.mates.map(mat => ({
                        mate_no: mat.mate_no,
                        mate_id: mat.id,
                        event_matches: mat.event_matches.map(match => ({
                            ...match,
                            duration: bracketDurations[match.bracket_id] || 0
                        }))
                    }))
                });
            })

            return result;
        }

        function fetchMatches() {
            const params = {
                event_id: @json($eventConfig['event_id']),
                day: searchInputs.day.val() || null,
                mat: searchInputs.mat.val() || null,
                age: searchInputs.age?.val() || null,
                gender: searchInputs.gender?.val() || null,
                weight: searchInputs.weight?.val() || null,
            };

            $.ajax({
                url: '{{ ($disableEdit ?? false) ? route('event.public.schedule.matches', ['eventId' => $eventConfig['event_id']]) : route('event.config.search.matches', ['event_id' => $eventConfig['event_id']]) }}',
                method: 'GET',
                data: params,
                success: function(response) {
                    renderMatches(handleMatchesData(response));
                },
                error: function(error) {
                    console.error('Error fetching matches:', error);
                },
            });
        }

        $('#search-button').on('click', fetchMatches);
        fetchMatches();

        $(document).off('click', '.edit-button').on('click', '.edit-button', function() {
            const matchId = $(this).data('match-id');
            const url = "{!! route('event.config.match.edit_status', ['match_id' => '__EVENT_ID__']) !!}".replace('__EVENT_ID__', matchId);
            window.open(url, '_blank');
        });
    });
</script>
