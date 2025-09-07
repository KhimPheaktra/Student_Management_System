@extends('layouts.admin')

@section('title','Dashboard')
@section('content')
<h1>Dashboard</h1>

<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row g-2 justify-content-center">
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Teachers</div>
                            <h5 class="mb-0 mt-1 fw-bold">{{ $totalTeachers ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-chalkboard-teacher fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Students</div>
                            <h5 class="mb-0 mt-1 fw-bold">{{ $totalStudents ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-user-graduate fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Subjects</div>
                            <h5 class="mb-0 mt-1 fw-bold">{{ $totalSubjects ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-book fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card bg-danger text-white shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Employees</div>
                            <h5 class="mb-0 mt-1 fw-bold">{{ $totalEmployees ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-users fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small">Total Users</div>
                            <h5 class="mb-0 mt-1 fw-bold">{{ $totalUsers ?? 'N/A' }}</h5>
                        </div>
                        <i class="fas fa-user-friends fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- First row: Yearly Enrollments and Top Majors -->
<div class="row mt-5">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Yearly Enrollments</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Top Majors</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="topMajorsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second row: Students by Department -->
<div class="row">
    <div class="col-xl-12">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-chart-bar me-1"></i>
                Students by Department
            </div>
            <div class="card-body">
                <canvas id="departmentChart" width="100%" height="40"></canvas>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    // Chart for Students by Department (Keep this as is)
    var departmentCtx = document.getElementById("departmentChart");
    var departmentChart = new Chart(departmentCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($subjectLabels) !!},
            datasets: [{
                label: "Number of Students",
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1,
                data: {!! json_encode($subjectData) !!}
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Enrollment Chart - Updated for yearly data with tooltips and trend indicators
    const enrollmentData = @json($enrollmentData);
    var enrollmentCtx = document.getElementById("enrollmentChart");
    var enrollmentChart = new Chart(enrollmentCtx, {
        type: 'bar',
        data: {
            labels: enrollmentData.labels,
            datasets: [{
                label: "Yearly Enrollments",
                backgroundColor: "rgba(78, 115, 223, 0.8)",
                borderColor: "rgba(78, 115, 223, 1)",
                borderWidth: 1,
                data: enrollmentData.data
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
         plugins: {
            tooltip: {
                // backgroundColor: "rgb(255,255,255)",
                // bodyFontColor: "#858796",
                // titleMarginBottom: 10,
                // titleFontColor: '#6e707e',
                // titleFontSize: 14,
                // borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'nearest', // Changed from 'index' to 'nearest'
                caretPadding: 10,
                callbacks: {
                    label: function(context) {
                        var datasetLabel = context.dataset.label || '';
                        return datasetLabel + ': ' + context.parsed.y + ' students';
                    }
                }
            }
        }
        },
        plugins: [{
            afterDatasetsDraw: function(chart) {
                const ctx = chart.ctx;
                chart.data.datasets.forEach(function(dataset, datasetIndex) {
                    var meta = chart.getDatasetMeta(datasetIndex);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            if (index > 0) { // Skip the first bar since we need previous data for comparison
                                const data = dataset.data[index];
                                const prevData = dataset.data[index-1];

                                // Draw arrow
                                ctx.fillStyle = data > prevData ? "green" : (data < prevData ? "red" : "gray");

                                const x = element.x;
                                const y = element.y - 15; // Position above the bar

                                ctx.beginPath();
                                if (data > prevData) {
                                    // Up arrow
                                    ctx.moveTo(x, y);
                                    ctx.lineTo(x - 8, y + 8);
                                    ctx.lineTo(x + 8, y + 8);
                                } else if (data < prevData) {
                                    // Down arrow
                                    ctx.moveTo(x, y + 8);
                                    ctx.lineTo(x - 8, y);
                                    ctx.lineTo(x + 8, y);
                                } else {
                                    // Equal sign
                                    ctx.moveTo(x - 8, y + 2);
                                    ctx.lineTo(x + 8, y + 2);
                                    ctx.moveTo(x - 8, y + 6);
                                    ctx.lineTo(x + 8, y + 6);
                                }
                                ctx.closePath();
                                ctx.fill();

                                // Calculate percentage change
                                if (prevData > 0) {
                                    const percentChange = ((data - prevData) / prevData * 100).toFixed(1);
                                    const changeText = data === prevData ? 'No change' :
                                                                        (data > prevData ? '+' + percentChange + '%' : percentChange + '%');

                                    // Draw percentage text
                                    ctx.font = '10px Arial';
                                    ctx.textAlign = 'center';
                                    ctx.fillText(changeText, x, y - 5);
                                }
                            }
                        });
                    }
                });
            }
        }]
    });

    // Top Majors Chart
    const topMajorsData = @json($topMajors);
    var topMajorsCtx = document.getElementById("topMajorsChart");
    var topMajorsChart = new Chart(topMajorsCtx, {
        type: 'doughnut',
        data: {
            labels: topMajorsData.map(item => item.name),
            datasets: [{
                data: topMajorsData.map(item => item.count),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    // backgroundColor: "rgb(255,255,255)",
                    // bodyFontColor: "#858796",
                    // borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + ' students';
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            cutout: '80%',
            elements: {
                arc: {
                    borderWidth: 0
                }
            }
        },
    });

    // Initialize DataTable (if needed)
    $(document).ready(function () {
        if ($('#activitiesTable').length) {
            $('#activitiesTable').DataTable({
                responsive: true
            });
        }
    });
</script>
@endsection

@php
function getChartColor($index) {
    $colors = ['primary', 'success', 'info', 'warning', 'danger'];
    return $colors[$index % count($colors)];
}
@endphp

{{-- 
@extends('layouts.admin')

@section('title','Dashboard')
@section('content')

<h1>Dashboard</h1>


<div class="container-fluid px-4">
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">Total Teachers</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <h5>{{ $totalTeachers }}</h5>
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Total Students Card</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <h5>{{ $totalStudents }}</h5>
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Total Subjects</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <h5>{{ $totalSubjects }}</h5>
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Total Employees</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

@endsection --}}


{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
