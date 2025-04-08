<div class="mt-3">
    <div class="mb-3 d-none" id="control-action">
        <button id="save-btn" class="btn btn-success me-2">Save</button>
        <button id="cancel-btn" class="btn btn-secondary">Cancel</button>
    </div>

    <div class="row">
        <div class="col-md-2">
            <h5>Bracket Pool</h5>
            <div id="bracket-pool" class="dropzone p-2 border scroll-hidden"></div>
        </div>
        <div class="col-md-10 mat-area" id="schedule-container"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let originalAssignments = null;
        let isEditing = false
        const custom_data = window.custom_data || {
            bracketPool: Array.from({
                length: 32
            }, (_, i) => ({
                id: `${i + 1}`,
                name: `Bracket ${i + 1}`
            })),
            schedule: [{
                    day: 1,
                    mats: [{
                            mat: 1,
                            brackets: ['1', '2', '3']
                        },
                        {
                            mat: 2,
                            brackets: []
                        },
                        {
                            mat: 3,
                            brackets: []
                        },
                        {
                            mat: 4,
                            brackets: []
                        },
                        {
                            mat: 5,
                            brackets: []
                        },
                        {
                            mat: 6,
                            brackets: []
                        },
                        {
                            mat: 7,
                            brackets: []
                        },
                        {
                            mat: 8,
                            brackets: []
                        }
                    ]
                },
                {
                    day: 2,
                    mats: [{
                            mat: 1,
                            brackets: ['4', '5', '6']
                        },
                        {
                            mat: 2,
                            brackets: []
                        }
                    ]
                }
            ]
        };

        const matAssignments = {};
        const bracketLocations = {};

        function renderBrackets(container, bracketIds) {
            bracketIds.forEach(id => {
                const bracket = custom_data.bracketPool.find(b => b.id == id);
                if (bracket) {
                    const div = document.createElement('div');
                    div.className = 'bracket';
                    div.draggable = true;
                    div.dataset.id = bracket.id;
                    div.textContent = bracket.name;
                    container.appendChild(div);
                }
            });
        }

        function setupDragAndDrop() {
            document.querySelectorAll('.bracket').forEach(bracket => {
                bracket.addEventListener('dragstart', e => {
                    e.dataTransfer.setData('bracketId', bracket.dataset.id);
                    bracket.classList.add("dragging");
                });

                bracket.addEventListener('dragend', () => {
                    bracket.classList.remove("dragging");
                });
            });

            document.querySelectorAll('.dropzone').forEach(zone => {
                zone.addEventListener('dragover', e => {
                    e.preventDefault();
                    zone.classList.add('bg-light');
                });

                zone.addEventListener('dragleave', () => {
                    zone.classList.remove('bg-light');
                });

                zone.addEventListener('drop', e => {
                    e.preventDefault();
                    this.isEditing = true;
                    zone.classList.remove('bg-light');

                    const bracketId = e.dataTransfer.getData('bracketId');
                    const bracket = document.querySelector(`.bracket[data-id="${bracketId}"]`);
                    const control = document.querySelector('#control-action');
                    control.className = "mb-3"
                    if (!bracket) return;

                    const isPool = zone.id === "bracket-pool";

                    if (bracketLocations[bracketId]) {
                        const oldKey = bracketLocations[bracketId];
                        const oldZone = document.querySelector(
                            `.dropzone[data-key="${oldKey}"]`);
                        if (oldZone) {
                            oldZone.querySelector(`.bracket[data-id="${bracketId}"]`)?.remove();
                        }
                        matAssignments[oldKey] = matAssignments[oldKey].filter(id => id !==
                            bracketId);
                        delete bracketLocations[bracketId];
                    } else {
                        document.getElementById('bracket-pool')
                            .querySelector(`.bracket[data-id="${bracketId}"]`)?.remove();
                    }

                    if (isPool) {
                        document.getElementById('bracket-pool').appendChild(bracket);
                        return;
                    }

                    const day = zone.dataset.day;
                    const mat = zone.dataset.mat;
                    const key = `${day}_${mat}`;

                    // Reordering support
                    const afterElement = getDragAfterElement(zone, e.clientY);
                    if (afterElement == null) {
                        zone.appendChild(bracket);
                    } else {
                        zone.insertBefore(bracket, afterElement);
                    }

                    matAssignments[key] = Array.from(zone.querySelectorAll('.bracket'))
                        .map(el => el.dataset.id);
                    bracketLocations[bracketId] = key;
                });
            });
        }

        function getDragAfterElement(container, y) {
            const draggableElements = [...container.querySelectorAll('.bracket:not(.dragging)')];
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return {
                        offset: offset,
                        element: child
                    };
                } else {
                    return closest;
                }
            }, {
                offset: Number.NEGATIVE_INFINITY
            }).element;
        }

        function render() {

            const control = document.querySelector('#control-action');
            control.className = "mb-3 d-none"
            const pool = document.getElementById('bracket-pool');
            const scheduleContainer = document.getElementById('schedule-container');

            pool.innerHTML = "";
            scheduleContainer.innerHTML = "";

            const allAssigned = new Set();
            Object.keys(matAssignments).forEach(key => delete matAssignments[key]);
            Object.keys(bracketLocations).forEach(key => delete bracketLocations[key]);

            custom_data.schedule.forEach(day => {
                const dayLabel = document.createElement('div');
                dayLabel.className = "day-label";
                dayLabel.innerText = `Day ${day.day}`;
                scheduleContainer.appendChild(dayLabel);

                const scrollWrapper = document.createElement('div');
                scrollWrapper.className = "mat-slider-wrapper mb-4 p-3";

                const scrollContent = document.createElement('div');
                scrollContent.className = "mat-slider";

                day.mats.forEach(mat => {
                    const col = document.createElement('div');
                    col.className = "col-md-3";

                    const drop = document.createElement('div');
                    const key = `${day.day}_${mat.mat}`;
                    drop.className = "dropzone p-2 border rounded mate-poll scroll-hidden";
                    drop.dataset.day = day.day;
                    drop.dataset.mat = mat.mat;
                    drop.dataset.key = key;

                    const title = document.createElement('h6');
                    title.innerText = `Mat ${mat.mat}`;
                    drop.appendChild(title);

                    renderBrackets(drop, mat.brackets);
                    matAssignments[key] = [...mat.brackets];
                    mat.brackets.forEach(bid => {
                        bracketLocations[bid] = key;
                        allAssigned.add(bid);
                    });

                    col.appendChild(drop);
                    scrollContent.appendChild(col);
                });

                scrollWrapper.appendChild(scrollContent);
                scheduleContainer.appendChild(dayLabel);
                scheduleContainer.appendChild(scrollWrapper);
            });

            const unusedBrackets = custom_data.bracketPool.filter(b => !allAssigned.has(b.id));
            renderBrackets(pool, unusedBrackets.map(b => b.id));

            setupDragAndDrop();
        }

        function convertMatAssignmentsToSchedule() {
            const newSchedule = [];

            for (const key in matAssignments) {
                const [dayStr, matStr] = key.split('_');
                const day = parseInt(dayStr);
                const mat = parseInt(matStr);
                let dayObj = newSchedule.find(d => d.day === day);
                if (!dayObj) {
                    dayObj = {
                        day,
                        mats: []
                    };
                    newSchedule.push(dayObj);
                }
                dayObj.mats.push({
                    mat,
                    brackets: [...matAssignments[key]]
                });
            }

            newSchedule.forEach(day => {
                day.mats.sort((a, b) => a.mat - b.mat);
            });

            newSchedule.sort((a, b) => a.day - b.day);
            return newSchedule;
        }

        document.getElementById('save-btn').addEventListener('click', () => {
            custom_data.schedule = JSON.parse(JSON.stringify(convertMatAssignmentsToSchedule()));
            originalAssignments = custom_data.schedule
            render();
            console.log("✅ Saved assignments:", originalAssignments);
        });

        document.getElementById('cancel-btn').addEventListener('click', () => {
            if (originalAssignments) {
                custom_data.schedule = JSON.parse(JSON.stringify(originalAssignments));
                render();
                console.log("⛔ Cancelled — reverted to last save.");
            }
        });

        render();
        originalAssignments = JSON.parse(JSON.stringify(custom_data.schedule));
    });
</script>
