<div class="row">
    <div class="col-md-2">
        <h5>Bracket Pool</h5>
        <div id="bracket-pool" class="dropzone p-2 border scroll-hidden"></div>
    </div>
    <div class="col-md-10 mat-area" id="schedule-container"></div>
</div>


<script>
    const data = {
        bracketPool: [{
                id: "1",
                name: "Bracket 1"
            },
            {
                id: "2",
                name: "Bracket 2"
            },
            {
                id: "3",
                name: "Bracket 3"
            },
            {
                id: "4",
                name: "Bracket 4"
            },
            {
                id: '5',
                name: 'Bracket 5'
            },
            {
                id: '6',
                name: 'Bracket 6'
            },
            {
                id: '7',
                name: 'Bracket 7'
            },
            {
                id: '8',
                name: 'Bracket 8'
            },
            {
                id: '9',
                name: 'Bracket 9'
            },
            {
                id: '10',
                name: 'Bracket 10'
            },
            {
                id: '11',
                name: 'Bracket 11'
            },
            {
                id: '12',
                name: 'Bracket 12'
            },
            {
                id: '13',
                name: 'Bracket 13'
            },
            {
                id: '14',
                name: 'Bracket 14'
            },
            {
                id: '15',
                name: 'Bracket 15'
            },
            {
                id: '16',
                name: 'Bracket 16'
            },
            {
                id: '17',
                name: 'Bracket 17'
            },
            {
                id: '18',
                name: 'Bracket 18'
            },
            {
                id: '19',
                name: 'Bracket 19'
            },
            {
                id: '20',
                name: 'Bracket 20'
            },
            {
                id: '21',
                name: 'Bracket 21'
            },
            {
                id: '22',
                name: 'Bracket 22'
            },
            {
                id: '23',
                name: 'Bracket 23'
            },
            {
                id: '24',
                name: 'Bracket 24'
            },
            {
                id: '25',
                name: 'Bracket 25'
            },
            {
                id: '26',
                name: 'Bracket 26'
            },
            {
                id: '27',
                name: 'Bracket 27'
            },
            {
                id: '28',
                name: 'Bracket 28'
            },
            {
                id: '29',
                name: 'Bracket 29'
            },
            {
                id: '30',
                name: 'Bracket 30'
            },
            {
                id: '31',
                name: 'Bracket 31'
            },
            {
                id: '32',
                name: 'Bracket 32'
            },

        ],
        schedule: [{
                day: 1,
                mats: [{
                        mat: 1,
                        brackets: []
                    },
                    {
                        mat: 2,
                        brackets: []
                    }, {
                        mat: 3,
                        brackets: []
                    }, {
                        mat: 4,
                        brackets: []
                    }, {
                        mat: 5,
                        brackets: []
                    }, {
                        mat: 6,
                        brackets: []
                    }, {
                        mat: 7,
                        brackets: []
                    }, {
                        mat: 8,
                        brackets: []
                    }
                ]
            },
            {
                day: 2,
                mats: [{
                        mat: 1,
                        brackets: []
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
    const bracketLocations = {}; // Tracks current mat of each bracket

    function renderBrackets(container, bracketIds) {
        bracketIds.forEach(id => {
            const bracket = data.bracketPool.find(b => b.id === id);
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
        // Make all brackets draggable
        document.querySelectorAll('.bracket').forEach(bracket => {
            bracket.addEventListener('dragstart', e => {
                e.dataTransfer.setData('bracketId', bracket.dataset.id);
                bracket.classList.add("dragging");
            });
            bracket.addEventListener('dragend', () => {
                bracket.classList.remove("dragging");
            });
        });

        // Make mats dropzones
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
                zone.classList.remove('bg-light');

                const bracketId = e.dataTransfer.getData('bracketId');
                const bracket = document.querySelector(`.bracket[data-id="${bracketId}"]`);

                if (!bracket) return;

                const isPool = zone.id === "bracket-pool";

                // Remove from old mat (if any)
                if (bracketLocations[bracketId]) {
                    const oldKey = bracketLocations[bracketId];
                    const oldZone = document.querySelector(`.dropzone[data-key="${oldKey}"]`);
                    if (oldZone) {
                        oldZone.querySelector(`.bracket[data-id="${bracketId}"]`)?.remove();
                    }
                    matAssignments[oldKey] = matAssignments[oldKey].filter(id => id !== bracketId);
                    delete bracketLocations[bracketId];
                } else {
                    // Remove from bracket pool
                    document.getElementById('bracket-pool').querySelector(
                        `.bracket[data-id="${bracketId}"]`)?.remove();
                }

                // If dropped in pool: done.
                if (isPool) {
                    document.getElementById('bracket-pool').appendChild(bracket);
                    return;
                }

                // Add to new mat
                const day = zone.dataset.day;
                const mat = zone.dataset.mat;
                const key = `${day}_${mat}`;
                zone.appendChild(bracket);

                matAssignments[key] = matAssignments[key] || [];
                matAssignments[key].push(bracketId);
                bracketLocations[bracketId] = key;

                // Save logic here — for now, console.log
                console.log("Bracket moved →", {
                    bracketId,
                    assignedTo: {
                        day,
                        mat
                    },
                    matAssignments
                });
            });
        });
    }

    function render() {
        const pool = document.getElementById('bracket-pool');
        const scheduleContainer = document.getElementById('schedule-container');

        pool.innerHTML = "";
        scheduleContainer.innerHTML = "";

        const allAssigned = new Set();

        // Render mats
        data.schedule.forEach(day => {
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

        // Render unused (unassigned) brackets in pool
        const unusedBrackets = data.bracketPool.filter(b => !allAssigned.has(b.id));
        renderBrackets(pool, unusedBrackets.map(b => b.id));

        setupDragAndDrop();
    }

    render();
</script>
