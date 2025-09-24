@extends('layouts.admin')
@section('title', 'Front Desk')
@section('content')
    <h1 class="main-text">Front Desk</h1>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active">Check-in</button>
                    <button type="button" class="btn btn-outline-primary">Check-out</button>
                    <button type="button" class="btn btn-outline-primary">Due Out</button>
                    <button type="button" class="btn btn-outline-primary">Checked In</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3 mt-3">
                <h4>Today's Check-ins</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Name</th>
                            <th>Room Number</th>
                            <th>Total Amount</th>
                            <th>Amount Paid</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6">No check-ins today.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3 mt-3">
                <h4>Booking Calendar</h4>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">May 2025</h5>
                    <div>
                        <button class="btn btn-secondary btn-sm" id="today-btn">today</button>
                        <button class="btn btn-light btn-sm" id="prev-month">&laquo;</button>
                        <button class="btn btn-light btn-sm" id="next-month">&raquo;</button>
                    </div>
                </div>

                <table class="table table-bordered calendar-table">
                    <thead>
                        <tr>
                            <th class="text-center">Sun</th>
                            <th class="text-center">Mon</th>
                            <th class="text-center">Tue</th>
                            <th class="text-center">Wed</th>
                            <th class="text-center">Thu</th>
                            <th class="text-center">Fri</th>
                            <th class="text-center">Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- Calendar data will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();

            const monthNames = ["January", "February", "March", "April", "May", "June",
                               "July", "August", "September", "October", "November", "December"];

            const todayBtn = document.getElementById('today-btn');
            const prevMonthBtn = document.getElementById('prev-month');
            const nextMonthBtn = document.getElementById('next-month');

            // Event listeners
            todayBtn.addEventListener('click', () => {
                currentMonth = today.getMonth();
                currentYear = today.getFullYear();
                renderCalendar(currentMonth, currentYear);
            });

            prevMonthBtn.addEventListener('click', () => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            });

            nextMonthBtn.addEventListener('click', () => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentMonth, currentYear);
            });

            function renderCalendar(month, year) {
                // Update header
                document.querySelector('h5').textContent = `${monthNames[month]} ${year}`;

                const calendarBody = document.getElementById('calendar-body');
                calendarBody.innerHTML = '';

                // First day of the month
                const firstDay = new Date(year, month, 1).getDay();

                // Last day of the month
                const lastDay = new Date(year, month + 1, 0).getDate();

                // Previous month's last date
                const prevMonthLastDay = new Date(year, month, 0).getDate();

                let date = 1;
                let nextMonthDate = 1;

                // Creating the calendar grid
                for (let i = 0; i < 6; i++) {
                    const row = document.createElement('tr');
                    row.style.height = '100px'; // Set row height to create space for events

                    for (let j = 0; j < 7; j++) {
                        const cell = document.createElement('td');
                        cell.style.verticalAlign = 'top';
                        cell.style.padding = '5px';

                        if (i === 0 && j < firstDay) {
                            // Previous month dates
                            const prevDate = prevMonthLastDay - (firstDay - j - 1);
                            const linkPrev = document.createElement('a');
                            linkPrev.href = '#';
                            linkPrev.className = 'text-muted small';
                            linkPrev.textContent = prevDate;
                            cell.appendChild(linkPrev);
                        } else if (date > lastDay) {
                            // Next month dates
                            const linkNext = document.createElement('a');
                            linkNext.href = '#';
                            linkNext.className = 'text-muted small';
                            linkNext.textContent = nextMonthDate;
                            cell.appendChild(linkNext);
                            nextMonthDate++;
                        } else {
                            // Current month dates
                            const link = document.createElement('a');
                            link.href = '#';
                            link.className = 'text-primary';

                            // Check if it's today's date
                            if (date === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                                link.className = 'text-primary font-weight-bold';
                                cell.style.backgroundColor = '#f8f9fa';
                            }

                            link.textContent = date;
                            cell.appendChild(link);

                            // Space for events
                            const eventSpace = document.createElement('div');
                            eventSpace.className = 'mt-1';

                            // Add mock events for demo (optional)
                            // Uncomment to add sample events
                            /*
                            if (date === 15) {
                                const event = document.createElement('div');
                                event.className = 'bg-primary text-white small p-1 rounded';
                                event.textContent = 'Check-in: Room 101';
                                eventSpace.appendChild(event);
                            }
                            */

                            cell.appendChild(eventSpace);
                            date++;
                        }

                        row.appendChild(cell);
                    }

                    calendarBody.appendChild(row);

                    // Stop creating rows if we've reached the end of the month
                    if (date > lastDay) {
                        break;
                    }
                }
            }

            // Initial render
            renderCalendar(currentMonth, currentYear);
        });
    </script>

    <style>
        .calendar-table th {
            background-color: #f8f9fa;
            font-weight: normal;
            padding: 8px;
        }

        .calendar-table td {
            width: 14.28%;
            height: 100px;
            overflow: hidden;
        }

        .calendar-table a {
            text-decoration: none;
        }

        #today-btn {
            border-radius: 4px;
            font-size: 0.8rem;
            padding: 4px 8px;
            background-color: #6c757d;
            color: white;
        }

        #prev-month, #next-month {
            width: 30px;
            height: 30px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ced4da;
        }
    </style>
@endsection
