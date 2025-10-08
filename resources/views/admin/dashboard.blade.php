@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
  <h1 class="main-text">Dashboard</h1>

  <!-- Key Metrics Cards -->
  <div class="row" id="metrics-row">
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Check-in</h4>
        <h2 id="check-in">0</h2>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Check-out</h4>
        <h2 id="check-out">0</h2>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Total In Hotel</h4>
        <h2 id="total-in-hotel">0</h2>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Available Rooms</h4>
        <h2 id="available-rooms">0</h2>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Occupied Rooms</h4>
        <h2 id="occupied-rooms">0</h2>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Revenue Today</h4>
        <h2 id="revenue-today">₱0</h2>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Monthly Revenue</h4>
        <h2 id="monthly-revenue">₱0</h2>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
      <div class="card p-3 text-center">
        <h4>Occupancy Rate</h4>
        <h2 id="occupancy-rate">0%</h2>
      </div>
    </div>
  </div>

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
        <canvas id="revenueChart" height="300"></canvas>
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

  <!-- Modified: Added Chart.js CDN and improved chart rendering -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let currentOccupancyData = [];

      // Fetch metrics and chart data on page load
      fetch('{{ route('admin.dashboard-data') }}?period=weekly', {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          }
        })
        .then(res => res.json())
        .then(data => {
          // Debug logs to check data structure
          console.log('Metrics:', data.metrics);
          console.log('Chart Data:', data.chartData);

          // Update metrics row
          document.getElementById('check-in').textContent = data.metrics.checkIn ?? 0;
          document.getElementById('check-out').textContent = data.metrics.checkOut ?? 0;
          document.getElementById('total-in-hotel').textContent = data.metrics.totalInHotel ?? 0;
          document.getElementById('available-rooms').textContent = data.metrics.availableRooms ?? 0;
          document.getElementById('occupied-rooms').textContent = data.metrics.occupiedRooms ?? 0;
          document.getElementById('revenue-today').textContent = '₱' + (data.metrics.revenueToday ?? 0)
            .toLocaleString();
          document.getElementById('monthly-revenue').textContent = '₱' + (data.metrics.monthlyRevenue ?? 0)
            .toLocaleString();
          document.getElementById('occupancy-rate').textContent = (data.metrics.occupancyRate ?? 0).toFixed(2) +
            '%';

          // Defensive check for chartData occupancyData format
          if (Array.isArray(data.chartData.occupancyData) && data.chartData.occupancyData.length > 0 && typeof data
            .chartData.occupancyData[0] === 'object') {
            currentOccupancyData = data.chartData.occupancyData;
            revenueChart.data.labels = data.chartData.labels;
            revenueChart.data.datasets[0].data = data.chartData.revenueData;
            revenueChart.data.datasets[0].tooltipData = data.chartData.tooltipData;
            revenueChart.data.datasets[1].data = data.chartData.occupancyData.map(d => d.percentage);
          } else {
            // Fallback for old format or empty data
            currentOccupancyData = data.chartData.occupancyData.map(p => ({
              percentage: p,
              count: 0
            }));
            revenueChart.data.labels = data.chartData.labels;
            revenueChart.data.datasets[0].data = data.chartData.revenueData;
            revenueChart.data.datasets[0].tooltipData = data.chartData.tooltipData;
            revenueChart.data.datasets[1].data = data.chartData.occupancyData;
          }
          revenueChart.update();
        })
        .catch(error => console.error('Error:', error));

      // Initialize chart (assuming Chart.js is included)
      const ctx = document.getElementById('revenueChart').getContext('2d');
      const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: [],
          datasets: [{
            label: 'Revenue Trend (%)',
            data: [],
            borderColor: '#EFBF04',
            fill: false,
            yAxisID: 'y'
          }, {
            label: 'Occupancy Rate (%)',
            data: [],
            borderColor: '#000',
            fill: false,
            yAxisID: 'y1'
          }]
        },
        options: {
          scales: {
            y: {
              type: 'linear',
              position: 'left',
              title: {
                display: true,
                text: 'Trend (%)'
              },
              ticks: {
                callback: function(value) {
                  return value + '%';
                }
              },
              beginAtZero: false
            },
            y1: {
              type: 'linear',
              position: 'right',
              title: {
                display: true,
                text: 'Occupancy (%)'
              },
              ticks: {
                callback: function(value) {
                  return value + '%';
                }
              },
              grid: {
                drawOnChartArea: false
              },
              beginAtZero: true
            }
          },
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  if (context.datasetIndex === 0) {
                    const tooltipData = context.dataset.tooltipData || [];
                    const revenueValue = tooltipData[context.dataIndex] !== undefined ? tooltipData[context
                      .dataIndex] : context.parsed.y;
                    return 'Percentile: ' + context.parsed.y.toFixed(2) + '%\nRevenue: ₱' + revenueValue
                      .toLocaleString();
                  } else {
                    const d = currentOccupancyData[context.dataIndex];
                    return 'Occupancy: ' + d.percentage + '% (' + d.count + ')';
                  }
                }
              }
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

          fetch('{{ route('admin.dashboard-data') }}?period=' + period, {
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
              }
            })
            .then(res => res.json())
            .then(data => {
              currentOccupancyData = data.chartData.occupancyData;
              revenueChart.data.labels = data.chartData.labels;
              revenueChart.data.datasets[0].data = data.chartData.revenueData;
              revenueChart.data.datasets[0].tooltipData = data.chartData.tooltipData;
              revenueChart.data.datasets[1].data = data.chartData.occupancyData.map(d => d.percentage);
              revenueChart.update();
            })
            .catch(error => console.error('Error:', error));
        });
      });
    });
  </script>
@endsection
