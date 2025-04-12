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
                <input type="number" class="form-control mb-2" placeholder="Day" id="search-day">
                <input type="number" class="form-control mb-2" placeholder="Mat" id="search-mat">
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
        const searchInputs = {
            day: $('#search-day'),
            mat: $('#search-mat'),
            age: $('#search-age'),
            gender: $('#search-gender'),
            weight: $('#search-weight'),
        };

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
                        <th>Status</th>`;
                    thead.appendChild(headerRow);
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');
                    mat.matches.forEach(match => {
                        match.brackets.map((bracket, index) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${index + 1}</td>
                                <td>
                                    ${bracket?.reg_one?.member?.firstname ?? 'N/A'} ${bracket?.reg_one?.member?.lastname ?? 'N/A'}
                                    - 
                                    ${bracket?.reg_two?.member?.firstname ?? 'N/A'} ${bracket?.reg_two?.member?.lastname ?? ''}
                                </td>
                                <td><span class="badge bg-${match.status === 'P' ? 'warning' : match.status === 'A' ? 'primary' : 'success'} text-dark">${match.status}</span></td>
                            `;
                            tbody.appendChild(row);
                        });
                    });
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
    });
</script>
