@extends('layouts.admin_layout')
@section('title', 'Admin Dashboard')
@section('content')
    <h1 class="main-text">Dashboard</h1>

    {{-- <!-- Key Metrics Cards -->
    <div class="row">
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Check-in</h4>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Check-out</h4>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Total In Hotel</h4>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Available Rooms</h4>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Occupied Rooms</h4>
                <h2>0</h2>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Revenue Today</h4>
                <h2>$0</h2>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Monthly Revenue</h4>
                <h2>$0</h2>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card p-3 text-center">
                <h4>Occupancy Rate</h4>
                <h2>0%</h2>
            </div>
        </div>
    </div> --}}

    <!-- Revenue & Occupancy Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card p-3">
                <h4>Revenue & Occupancy Trends</h4>
                <div class="d-flex justify-content-end mb-3">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary active revenue-filter"
                            data-period="weekly">Week</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary revenue-filter"
                            data-period="monthly">Month</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary revenue-filter"
                            data-period="yearly">Year</button>
                    </div>
                </div>
                <div style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Room Status and Occupancy -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h4>Room Status</h4>
                <div style="height: 250px;">
                    <canvas id="roomStatusChart"></canvas>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between">
                        <div><span class="badge bg-success">&nbsp;</span> Available</div>
                        <div><span class="badge bg-danger">&nbsp;</span> Occupied</div>
                        <div><span class="badge bg-warning">&nbsp;</span> Maintenance</div>
                        <div><span class="badge bg-info">&nbsp;</span> Reserved</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h4>Occupancy by Room Type</h4>
                <div style="height: 250px;">
                    <canvas id="roomTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <!-- Revenue by Channel & KPI Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h4>Revenue by Channel</h4>
                <div style="height: 250px;">
                    <canvas id="channelChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h4>Key Performance Indicators</h4>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Average Daily Rate (ADR)</td>
                            <td class="text-end">$0.00</td>
                        </tr>
                        <tr>
                            <td>RevPAR (Revenue Per Available Room)</td>
                            <td class="text-end">$0.00</td>
                        </tr>
                        <tr>
                            <td>Average Length of Stay</td>
                            <td class="text-end">0 days</td>
                        </tr>
                        <tr>
                            <td>Cancellation Rate</td>
                            <td class="text-end">0%</td>
                        </tr>
                        <tr>
                            <td>Repeat Guest Rate</td>
                            <td class="text-end">0%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Bookings & Upcoming Reservations -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3">
                <h4>Recent Bookings</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">No recent bookings</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h4>Upcoming Reservations</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Guest</th>
                            <th>Room Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">No upcoming reservations</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}

    <!-- Rooms Management -->
    {{-- <div class="card p-3">
        <h4>Rooms</h4>
        <p>No rooms available.</p>
        <a href="{{ route('admin.rooms') }}" class="btn btn-custom">Manage Rooms</a>
    </div> --}}

    <!-- Load Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue & Occupancy Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                            label: 'Revenue ($)',
                            data: [1200, 1900, 2100, 2500, 3200, 3800, 3100],
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            yAxisID: 'y',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Occupancy (%)',
                            data: [45, 52, 60, 65, 80, 95, 85],
                            borderColor: 'rgba(153, 102, 255, 1)',
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            yAxisID: 'y1',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            max: 100,
                            title: {
                                display: true,
                                text: 'Occupancy (%)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });

            // Room Status Chart
            const roomStatusCtx = document.getElementById('roomStatusChart').getContext('2d');
            const roomStatusChart = new Chart(roomStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Available', 'Occupied', 'Maintenance', 'Reserved'],
                    datasets: [{
                        data: [60, 30, 5, 5],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(220, 53, 69, 0.8)',
                            'rgba(255, 193, 7, 0.8)',
                            'rgba(23, 162, 184, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Room Type Occupancy Chart
            const roomTypeCtx = document.getElementById('roomTypeChart').getContext('2d');
            const roomTypeChart = new Chart(roomTypeCtx, {
                type: 'bar',
                data: {
                    labels: ['Standard', 'Deluxe', 'Suite', 'Executive', 'Presidential'],
                    datasets: [{
                            label: 'Total Rooms',
                            data: [20, 15, 10, 5, 2],
                            backgroundColor: 'rgba(108, 117, 125, 0.5)',
                            borderColor: 'rgba(108, 117, 125, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Occupied',
                            data: [15, 10, 5, 2, 1],
                            backgroundColor: 'rgba(0, 123, 255, 0.5)',
                            borderColor: 'rgba(0, 123, 255, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Rooms'
                            }
                        }
                    }
                }
            });

            // Channel Revenue Chart
            const channelCtx = document.getElementById('channelChart').getContext('2d');
            const channelChart = new Chart(channelCtx, {
                type: 'pie',
                data: {
                    labels: ['Direct Booking', 'OTA Partners', 'Travel Agents', 'Corporate', 'Other'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Revenue Filter Buttons
            document.querySelectorAll('.revenue-filter').forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    document.querySelectorAll('.revenue-filter').forEach(btn => {
                        btn.classList.remove('active');
                    });

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Update chart data based on selected period
                    const period = this.getAttribute('data-period');

                    // Sample data for different periods
                    let labels, revenueData, occupancyData;

                    if (period === 'weekly') {
                        labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                        revenueData = [1200, 1900, 2100, 2500, 3200, 3800, 3100];
                        occupancyData = [45, 52, 60, 65, 80, 95, 85];
                    } else if (period === 'monthly') {
                        labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                        revenueData = [12000, 15000, 18000, 20000];
                        occupancyData = [55, 65, 75, 70];
                    } else if (period === 'yearly') {
                        labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep',
                            'Oct', 'Nov', 'Dec'
                        ];
                        revenueData = [45000, 48000, 60000, 65000, 72000, 90000, 110000, 115000,
                            95000, 75000, 68000, 80000
                        ];
                        occupancyData = [50, 55, 65, 70, 75, 85, 95, 98, 85, 70, 65, 75];
                    }

                    // Update chart data
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = revenueData;
                    revenueChart.data.datasets[1].data = occupancyData;
                    revenueChart.update();
                });
            });
        });
    </script>
@endsection
