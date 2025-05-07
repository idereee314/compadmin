<div class="container">
    <div class="row">
        <div class="col-8">
            <!-- Dynamic Mat Tables -->
            <div id="dynamic-day-matches"></div>
        </div>
        <div class="col-4">
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
                        <option value="">-- Mate --</option>
                    </select>
                    <div class="error-here"></div>
                </div>
                <input type="number" class="form-control mb-2" placeholder="Age" id="search-age">
                <input type="number" class="form-control mb-2" placeholder="Gender" id="search-gender">
                <input type="number" class="form-control mb-2" placeholder="Weight" id="search-weight">
                <button id="search-button" class="btn btn-primary w-100">Search</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const matesByDay = @json($configViewDict['mate']);

        $('#search-day').on('changed.bs.select', function () {
            const selectedDayId = this.value;
            const mateSelect = document.getElementById('search-mat');

            // Clear previous options
            mateSelect.innerHTML = '<option value="">-- Mate --</option>';

            if (matesByDay[selectedDayId]) {
                matesByDay[selectedDayId].forEach(mate => {
                    const option = document.createElement('option');
                    option.value = mate.id;
                    option.text = 'Mate ' + mate.mate_no;
                    mateSelect.appendChild(option);
                });
            }
            $('#search-mat').selectpicker('refresh');
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
            let hours = date.getHours().toString().padStart(2, '0'); // 2-digit hours
            let minutes = date.getMinutes().toString().padStart(2, '0'); // 2-digit minutes
            return `${hours}:${minutes}`;
        }


        // Function to render match tables dynamically
        function renderMatches(data) {
            const container = document.getElementById('dynamic-day-matches');
            container.innerHTML = "";

            data.forEach(day => {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('col-12', 'mb-4');
                const dayHeading = document.createElement('h3');
                dayHeading.innerText = `Day ${day.item_no}`;
                dayDiv.appendChild(dayHeading);
                const starDate = new Date(day.start_date);

                day.mates.forEach(mat => {
                    const matDiv = document.createElement('div');
                    matDiv.classList.add('mb-5');
                    const matHeading = document.createElement('h4');
                    matHeading.innerText = `Mat ${mat.mate_no}`;
                    matDiv.appendChild(matHeading);

                    const table = document.createElement('table');
                    table.classList.add('table', 'table-bordered');
                    const thead = document.createElement('thead');
                    const headerRow = document.createElement('tr');
                    headerRow.innerHTML = `
                        <th>#</th>
                        <th>Bracket</th>
                        <th>Winner</th>
                        <th>Duration</th>
                        <th>Status</th>`;
                    thead.appendChild(headerRow);
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');
                    let matDuration = 0;
                    mat.matches.forEach(match => {
                        // Move the last two matches in the entry_weight_id group to the end
                        const weightGroupMatches = match.brackets.filter(bracket => bracket.entry_weight_id);
                        if (weightGroupMatches.length > 2) {
                            const lastTwoMatches = weightGroupMatches.slice(-2);
                            match.brackets = match.brackets.filter(bracket => !lastTwoMatches.includes(bracket));
                            match.brackets.push(...lastTwoMatches);
                        }

                        match.brackets.map((bracket, index) => {
                            const row = document.createElement('tr');
                            matDuration += (match.entry?.duration ?? 0);
                            starDate.setMinutes(starDate.getMinutes() +
                                matDuration); // adds 15 minutes
                            row.innerHTML = `
                                <td>${bracket.id + 1}</td>
                                <td>
                                    ${bracket?.reg_one?.member?.firstname ?? 'TBD'} ${bracket?.reg_one?.member?.lastname ?? ''}
                                    -
                                     ${bracket?.reg_two?.member?.firstname ?? 'TBD'} ${bracket?.reg_two?.member?.lastname ?? ''}
                                </td>
                                <td>
                                    ${bracket?.reg_win?.member?.firstname ?? 'TBD'} ${bracket?.reg_win?.member?.lastname ?? ''}
                                </td>
                                <td>
                                    ${formatToHHmm(starDate)}
                                </td>
                                <td><span class="badge bg-${bracket.status == 'P' ? 'warning' : bracket.status == 'A' ? 'primary' : 'success'} text-dark">${bracket.status}</span></td>
                                <td class="text-center pr-0">
                                    <a href="javascript:;" class="btn btn-icon btn-light btn-hover-primary btn-sm mx-3 edit-button" data-match-id="${bracket.id}">
                                        <span class="svg-icon svg-icon-md svg-icon-primary">
                                            <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Write.svg-->
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)" />
                                                    <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                </td>
                            `;
                            tbody.appendChild(row);
                        });
                    });
                    const row = document.createElement('tr');
                    row.innerHTML =
                        `<td></td><td></td><td></td><td>${matDuration ?? '0'}</td><td></td><td class="text-center pr-0"> </td>`;
                    tbody.appendChild(row);
                    table.appendChild(tbody);
                    matDiv.appendChild(table);
                    dayDiv.appendChild(matDiv);
                });

                container.appendChild(dayDiv);
            });
        }

        // Function to fetch matches
        function fetchMatches() {
            const params = {
                event_id: @json($eventConfig['event_id']), // Replace with the actual event ID
                day: searchInputs.day.val() || null,
                mat: searchInputs.mat.val() || null,
                age: searchInputs.age.val() || null,
                gender: searchInputs.gender.val() || null,
                weight: searchInputs.weight.val() || null,
            };
            console.log(params);

            $.ajax({
                url: '{{ route('event.config.search.matches', ['event_id' => $eventConfig['event_id']]) }}',
                method: 'GET',
                data: params,
                success: function(response) {
                    renderMatches(response);
                },
                error: function(error) {
                    console.error('Error fetching matches:', error);
                },
            });
        }

        // Attach event listeners
        $('#search-button').on('click', fetchMatches);

        // Initial fetch
        fetchMatches();

        $(document).off('click', '.edit-button').on('click', '.edit-button', function() {
            console.log('Edit Match ID:', $(this).data('match-id'));
            const matchId = $(this).data('match-id');
            const url = "{!! route('event.config.match.edit_status', ['match_id' => '__EVENT_ID__']) !!}".replace('__EVENT_ID__', matchId);
            $.get(url, function(data) {
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
                            $.ajax({
                                url: form.action,
                                type: form.method,
                                data: new FormData(form),
                                success: function(response) {
                                    if (response.status ==
                                        'success') {
                                        $('#eventConfigModal')
                                            .find(
                                                "#close")
                                            .trigger('click');
                                        $("#config_tabs").find(
                                                "li a.active")
                                            .trigger(
                                                'click');
                                        toastr.success(response
                                            .msg);
                                    } else {
                                        toastr.error(response
                                            .errors,
                                            response.msg, {
                                                "closeButton": true,
                                                "timeOut": "0",
                                                "extendedTimeOut": "0",
                                            });
                                    }
                                },
                                error: function(xhr, textStatus,
                                    error) {
                                    console.log(xhr.statusText);
                                    console.log(textStatus);
                                    console.log(error);
                                },
                                async: false,
                                processData: false,
                                contentType: false
                            });
                        },
                    });

                    $(this).off('shown.bs.modal');
                });
                $('#eventEntryModal').on('hidden.bs.modal', function() {
                    $('#eventEntryModal .modal-content').empty();
                });
            });
        });
    });
</script>
