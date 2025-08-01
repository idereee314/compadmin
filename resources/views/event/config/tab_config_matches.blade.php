<div class="mt-3">
    <div class="mb-3 d-flex justify-content-end">
        <button id="save-btn" class="btn btn-success me-2">Save</button>
        <button id="cancel-btn" class="btn btn-secondary me-2">Cancel</button>
        <button id="generate-btn" class="btn btn-primary">Generate</button>
    </div>

    <div class="row">
        <div class="col-md-4">
            <h5>Bracket Pool</h5>
            <div id="bracket-pool" class="dropzone list-group">
            </div>
        </div>
        <div class="col-md-8 mat-area" id="schedule-container"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let originalAssignments = null;
        let shortCute = {};
        let isEditing = false
        const custom_data = {
            bracketPool: [],
            schedule: []
        };

        const matAssignments = {};
        const bracketLocations = {};

        function renderBrackets(container, bracketData) {
            bracketData.forEach(bracket => {
                const bracketId =
                    `${bracket.entry_id}_${bracket.entry_belt_id}_${bracket.entry_age_id}_${bracket.entry_weight_id}`;
                const existingBracket = document.querySelector(`[data-id="${bracketId}"]`);
                if (existingBracket) {
                    return;
                }
                const treeNode = document.createElement('div');
                treeNode.className = 'tree-node mb-3 p-3 border rounded';

                // Add bracket details
                const details = document.createElement('p');
                details.innerHTML = `
                    ${bracket?.entry?.fullname} /
                    ${bracket?.age?.name}  /
                    ${bracket?.belt?.name} /
                    ${bracket?.weight?.weight} / ${bracket?.total} / ${bracket?.is_complete}
                `;
                treeNode.appendChild(details);

                // Make the tree node 
                //
                treeNode.draggable = !bracket?.is_complete;
                treeNode.dataset.id = bracketId;
                if(bracket?.is_complete){
                    treeNode.className += ' bg-secondary text-muted';
                } 

                // Append the tree node to the container
                container.appendChild(treeNode);
            });
        }

        function renderTree(container, treeData, depth = 1) {
            // Ensure treeData is an array
            const isArray = Array.isArray(treeData);
            if (isArray) {
                renderBrackets(container, treeData);
                return
            }

            Object.keys(treeData).forEach(key => {
                const node = treeData[key];
                const treeNode = document.createElement('div');
                treeNode.className = 'list-group-item';

                // Add label for the current node
                const label = document.createElement('div');
                label.className = 'fw-bold'; // Bootstrap class for bold text
                label.textContent = key;
                treeNode.appendChild(label);

                // If the depth is less than 3, render children as headers
                if (node && !isArray) {
                    const childContainer = document.createElement('div');
                    childContainer.className = 'list-group ms-3'; // Indent child nodes
                    renderTree(childContainer, node, depth + 1);
                    treeNode.appendChild(childContainer);
                }
                // Append the tree node to the container
                container.appendChild(treeNode);
            });
        }

        function setupDragAndDrop() {
            document.querySelectorAll('.tree-node').forEach(draggable => {
                draggable.addEventListener('dragstart', e => {
                    e.dataTransfer.setData('bracketKey', draggable.dataset.id);
                    draggable.classList.add("dragging");
                    showControlAction();
                });

                draggable.addEventListener('dragend', () => {
                    draggable.classList.remove("dragging");
                });
            });

            function removeFromBraket(bracketKey) {
                const key = bracketLocations[bracketKey];
                if (key) {
                    const index = matAssignments[key].indexOf(bracketKey);
                    if (index > -1) {
                        const refrence = matAssignments[key].splice(index, 1);
                        delete refrence;
                        console.log("Removed from matAssignments", matAssignments[key]);
                    }
                }
                delete bracketLocations[bracketKey];
            }

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

                    const bracketKey = e.dataTransfer.getData('bracketKey');
                    const bracket = document.querySelector(`[data-id="${bracketKey}"]`);
                    if (!bracket) return;
                    const mapKey = `${zone.dataset.day}_${zone.dataset.mat}`;

                    zone.appendChild(bracket);
                    const isPool = zone.id === "bracket-pool";
                    if (isPool) {
                        // Remove from matAssignments if dropped in the pool
                        removeFromBraket(bracketKey);
                        const pool = document.getElementById('bracket-pool');
                        pool.innerHTML = "";
                        bracket.remove();
                        renderTree(zone, custom_data.bracketPool);
                        setupDragAndDrop();
                        return
                    } else if (bracketLocations[bracketKey] != null) {
                        removeFromBraket(bracketKey);
                    } else {
                        bracketLocations[bracketKey] = mapKey;
                    }


                    // Update matAssignments
                    if (!matAssignments[mapKey]) {
                        matAssignments[mapKey] = [];
                    }
                    const [entry_id, entry_belt_id, entry_age_id, entry_weight_id] = bracketKey
                        .split('_').map(Number)
                    matAssignments[mapKey].push(bracketKey);

                    showControlAction();
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

        function hideControlAction() {
            const controlSave = document.querySelector('#save-btn');
            if (controlSave) {
                controlSave.className = "btn btn-success me-2 d-none";
            }
            const controlCancel = document.querySelector('#cancel-btn');
            if (controlCancel) {
                controlCancel.className = "btn btn-secondary me-2 d-none";
            }
        }

        function showControlAction() {
            const controlSave = document.querySelector('#save-btn');
            if (controlSave) {
                controlSave.className = "btn btn-success me-2";
            }
            const controlCancel = document.querySelector('#cancel-btn');
            if (controlCancel) {
                controlCancel.className = "btn btn-secondary me-2";
            }
        }

        function render() {
            custom_data['bracketPool'] = @json($bracketPool) || [];
            custom_data['schedule'] = @json($mateData) || [];
            // Extract array data from bracketPool
            shortCute = extractArrayFromObject(custom_data['bracketPool']);

            console.log(custom_data, shortCute);

            hideControlAction();

            const pool = document.getElementById('bracket-pool');
            const scheduleContainer = document.getElementById('schedule-container');

            // Clear existing content
            pool.innerHTML = "";
            scheduleContainer.innerHTML = "";

            const allAssigned = new Set();
            Object.keys(matAssignments).forEach(key => delete matAssignments[key]);
            Object.keys(bracketLocations).forEach(key => delete bracketLocations[key]);

            // Render schedule (days and mats)
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
                    col.className = "col-md-5 mat-col mb-4";

                    const drop = document.createElement('div');
                    const key = `${day.day_id}_${mat.mat_id}`;
                    drop.className = "dropzone p-2 border rounded mate-poll scroll-hidden";
                    drop.dataset.day = day.day_id;
                    drop.dataset.mat = mat.mat_id;
                    drop.dataset.key = key;

                    const title = document.createElement('h6');
                    title.innerText = `Mat ${mat.mat }`;
                    drop.appendChild(title);

                    matAssignments[key] = mat.brackets.map(b =>
                        `${b.entry_id}_${b.entry_belt_id}_${b.entry_age_id}_${b.entry_weight_id}`
                    )
                    const fromShortCut = matAssignments[key].map(b => {
                        const bracket = mat.brackets.find(m => {
                            return `${m.entry_id}_${m.entry_belt_id}_${m.entry_age_id}_${m.entry_weight_id}` === b
                        });
                        const pointer = shortCute[b]
                        pointer.is_complete = bracket.is_complete;
                        return pointer
                    });
                    renderBrackets(drop, fromShortCut);
                    mat.brackets.forEach(bid => {
                        const keys =
                            `${bid.entry_id}_${bid.entry_belt_id}_${bid.entry_age_id}_${bid.entry_weight_id}`
                        bracketLocations[keys] = key;
                        allAssigned.add(bid.bracket_id);
                    });

                    col.appendChild(drop);
                    scrollContent.appendChild(col);
                });

                scrollWrapper.appendChild(scrollContent);
                scheduleContainer.appendChild(dayLabel);
                scheduleContainer.appendChild(scrollWrapper);
            });

            // Render unassigned brackets in the pool
            renderTree(pool, custom_data.bracketPool);

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

                const matObj = {
                    mat,
                    brackets: matAssignments[key].map(bracketKey => {
                        // Extract entry_id, entry_belt_id, entry_age_id, and entry_weight_id from the bracketKey
                        const [entry_id, entry_belt_id, entry_age_id, entry_weight_id] = bracketKey
                            .split('_').map(Number)

                        return {
                            entry_id,
                            entry_belt_id,
                            entry_age_id,
                            entry_weight_id
                        };
                    })
                };

                dayObj.mats.push(matObj);
            }

            // Sort mats and days for consistency
            newSchedule.forEach(day => {
                day.mats.sort((a, b) => a.mat - b.mat);
            });

            newSchedule.sort((a, b) => a.day - b.day);

            return newSchedule;
        }

        function extractArrayFromObject(obj) {
            let result = {};
            Object.keys(obj).forEach(key => {
                if (Array.isArray(obj[key])) {
                    obj[key].forEach(item => {
                        const itemKey =
                            `${item.entry_id}_${item.entry_belt_id}_${item.entry_age_id}_${item.entry_weight_id}`;
                        if (!result[itemKey]) {
                            result[itemKey] = item;
                        }
                    });
                } else if (typeof obj[key] === 'object' && obj[key] !== null) {
                    const nestedResult = extractArrayFromObject(obj[key]);
                    Object.assign(result, nestedResult);
                }
            });
            return result;
        }

        document.getElementById('save-btn').addEventListener('click', () => {
            const schedule = convertMatAssignmentsToSchedule();
            const eventId = @json($eventConfig['event_id']);

            $.ajax({
                url: '{{ route('event.config.saveMateBracket', ['event_id' => ':event_id']) }}'
                    .replace(':event_id', eventId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    schedule: schedule,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $("#config_tabs").find(
                            "li a.active").trigger(
                            'click');
                        toastr.success(response.message);
                    } else {
                        toastr.error('Failed to save brackets.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    toastr.error('An error occurred while saving.');
                },
            });
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

        
    $("#generate-btn").on('click', function() {
        var eventId = @json($eventConfig['event_id']);
        console.log(eventId);
        $.get('{!! route('event.config.match.index') !!}/' + eventId, function(data) {
            $('#eventEntryModal').modal();
            $('#eventEntryModal').on('shown.bs.modal', function() {
                $('#eventEntryModal .modal-content').html(data);
                $('.selectpicker').selectpicker();

                $('#start_date').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    format: 'yyyy-mm-dd',
                    templates: {
                        leftArrow: '<i class="la la-angle-right"></i>',
                        rightArrow: '<i class="la la-angle-left"></i>'
                    }
                })

                $('#end_date').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    format: 'yyyy-mm-dd',
                    templates: {
                        leftArrow: '<i class="la la-angle-right"></i>',
                        rightArrow: '<i class="la la-angle-left"></i>'
                    }
                })

                $('#event-config-days-entries-form').validate({
                    rules: {},
                    messages: {},
                    submitHandler: function(form) {
                        if(bracketLocations && Object.keys(bracketLocations).length > 0 && !confirm("Are you sure you want to generate matches? This will reset all existing matches and days entries.")) {
                            return 0;
                        }

                        Swal.fire({
                            title: "Are you sure you want to generate matches? This will reset all existing matches and days entries.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Тийм",
                            cancelButtonText: 'Үгүй',
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: 'btn btn-secondary'
                            },
                        }).then(function(result) {
                            if (result.value) {
                                $.ajax({
                                    url: form.action,
                                    type: form.method,
                                    data: new FormData(form),
                                    success: function(response) {
                                        if (response.status == 'success') {
                                            $('#eventConfigModal').find(
                                                "#close").trigger('click');
                                            $("#config_tabs").find(
                                                "li a.active").trigger(
                                                'click');
                                            toastr.success(response.msg);
                                        } else {
                                            toastr.error(response.errors,
                                                response.msg, {
                                                    "closeButton": true,
                                                    "timeOut": "0",
                                                    "extendedTimeOut": "0",
                                                });
                                        }
                                    },
                                    error: function(xhr, textStatus, error) {
                                        console.log(xhr.statusText);
                                        console.log(textStatus);
                                        console.log(error);
                                    },
                                    async: false,
                                    processData: false,
                                    contentType: false
                                });
                            }
                        });
                    },
                });

                $(this).off('shown.bs.modal');
            });
            $('#eventEntryModal').on('hidden.bs.modal', function() {
                $('#eventEntryModal .modal-content').empty();
            });
        });

    })
    });

</script>
