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
                <input type="text" class="form-control mb-2" placeholder="Name" id="search-name">
                <input type="number" class="form-control mb-2" placeholder="Age" id="search-age">
                <input type="date" class="form-control" id="search-day">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const data1 = [{
            Day: '1',
            mats: [{
                    id: '1',
                    name: 'Mat1',
                    matches: [{
                            ro: 17969,
                            firstname_one: 'Тэмүүлэн',
                            lastname_one: 'Энхбаатар',
                            acname_one: 'Ральф Грэйси Монгол академи',
                            rt: 18081,
                            firstname_two: 'Цэнгэлсүрэн',
                            lastname_two: 'Баасанбавуу',
                            acname_two: 'Unique Jiu Jitsu Club',
                            status: 'Pending',
                            start_time: '10:00'
                        },
                        {
                            ro: 17970,
                            firstname_one: 'Мөнхбаяр',
                            lastname_one: 'Батжаргал',
                            acname_one: 'Академи A',
                            rt: 18082,
                            firstname_two: 'Ганбаяр',
                            lastname_two: 'Төмөр',
                            acname_two: 'Академи B',
                            status: 'Ongoing',
                            start_time: '11:00'
                        }
                    ]
                },
                {
                    id: '2',
                    name: 'Mat2',
                    matches: [{
                        ro: 17971,
                        firstname_one: 'Доржсүрэн',
                        lastname_one: 'Төмөр',
                        acname_one: 'Жиу Житсу',
                        rt: 18083,
                        firstname_two: 'Мөнхтөр',
                        lastname_two: 'Чимэд-Оргил',
                        acname_two: 'Жиу Житсу 2',
                        status: 'Completed',
                        start_time: '12:00'
                    }]
                }
            ]
        }];

        // Function to render match tables dynamically
        function renderMatches(data) {
            const container = document.getElementById('dynamic-day-matches');
            container.innerHTML = ""

            data.forEach(day => {
                // For each day, add a day section
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('col-12', 'mb-4');
                const dayHeading = document.createElement('h3');
                dayHeading.innerText = `Day ${day.Day}`;
                dayDiv.appendChild(dayHeading);

                day.mats.forEach(mat => {
                    // For each mat, create the mat section
                    const matDiv = document.createElement('div');
                    matDiv.classList.add('mb-5');
                    const matHeading = document.createElement('h4');
                    matHeading.innerText = mat.name;
                    matDiv.appendChild(matHeading);

                    // Create the table for each mat
                    const table = document.createElement('table');
                    table.classList.add('table', 'table-bordered');
                    const thead = document.createElement('thead');
                    const headerRow = document.createElement('tr');
                    headerRow.innerHTML = `
                    <th>Bracket</th>
                    <th>Order</th>
                    <th>Start Time</th>
                    <th>Status</th>
                `;
                    thead.appendChild(headerRow);
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');
                    mat.matches.forEach(match => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${match.firstname_one} ${match.lastname_one} - ${match.firstname_two} ${match.lastname_two}</td>
                        <td>${match.ro}</td>
                        <td>${match.start_time}</td>
                        <td><span class="badge bg-${match.status === 'Pending' ? 'warning' : match.status === 'Ongoing' ? 'primary' : 'success'} text-dark">${match.status}</span></td>
                    `;
                        tbody.appendChild(row);
                    });
                    table.appendChild(tbody);
                    matDiv.appendChild(table);
                    dayDiv.appendChild(matDiv);
                });

                container.appendChild(dayDiv);
            });
        }

        // Call the function to render the data
        renderMatches(data1);
    })
</script>
