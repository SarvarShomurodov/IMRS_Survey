{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin qismi')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            {{-- <h2><i class="fas fa-chart-bar me-2"></i>Admin Dashboard</h2> --}}
            <div></div> {{-- Chap tomonni bo'sh qoldirdik --}}
            <div class="ms-auto">
                <a href="{{ route('admin.export') }}?{{ http_build_query($filters) }}" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Excel yuklash
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filtrlar</h6>
            </div>
            <div class="card-body">
                <form method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Viloyat</label>
                            <select class="form-select" name="region_id" id="filter_region_id">
                                <option value="">Barcha viloyatlar</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}"
                                        {{ $filters['region_id'] == $region->id ? 'selected' : '' }}>
                                        {{ $region->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tuman</label>
                            <select class="form-select" name="district_id" id="filter_district_id">
                                <option value="">Barcha tumanlar</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ $filters['district_id'] == $district->id ? 'selected' : '' }}>
                                        {{ $district->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Faoliyat turi</label>
                            <select class="form-select" name="activity_type_id">
                                <option value="">Barcha faoliyat turlari</option>
                                @foreach ($activityTypes as $activityType)
                                    <option value="{{ $activityType->id }}"
                                        {{ $filters['activity_type_id'] == $activityType->id ? 'selected' : '' }}>
                                        {{ $activityType->getName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                        <label class="form-label">Yil</label>
                        <select class="form-select" name="year">
                            <option value="" selected>Barcha yillar</option>
                            {{-- @for ($y = date('Y'); $y >= 2024; $y--)
                                <option value="{{ $y }}" {{ ($filters['year'] ?? '') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor --}}
                        </select>
                    </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                        <label class="form-label">Chorak</label>
                        <select class="form-select" name="quarter">
                            <option value="">Barcha choraklar</option>
                            @for ($q = 1; $q <= 4; $q++)
                                <option value="{{ $q }}" {{ $filters['quarter'] == $q ? 'selected' : '' }}>{{ $q }}-chorak</option>
                            @endfor
                        </select>
                    </div>
                        <div class="col-md-9 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>Filtrlarni qo'llash
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Tozalash
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="h2 mb-0">{{ number_format($totalResponses) }}</div>
                                <div>Jami so'rovnomalar</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clipboard-list fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="h2 mb-0">{{ number_format($totalCompanies) }}</div>
                                <div>Jami korxonalar</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-building fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="h2 mb-0">{{ number_format($totalEmployees) }}</div>
                                <div>Jami xodimlar</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="h2 mb-0">{{ $topMissingSkills->count() }}</div>
                                <div>Yetishmayotgan kadrlar</div>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Viloyatlar/Tumanlar bo'yicha taqsimot -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0" id="regionChartTitle">
                            @if ($filters['region_id'])
                                Tumanlar bo'yicha taqsimot
                            @else
                                Viloyatlar bo'yicha taqsimot
                            @endif
                        </h6>
                        <button class="btn btn-sm btn-outline-primary"
                            onclick="downloadChart('regionChart', 'Viloyatlar_boyicha_taqsimot')">
                            <i class="fas fa-download me-1"></i>Rasm yuklash
                        </button>
                    </div>
                    <div class="card-body" style="height: 500px;">
                        <canvas id="regionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Faoliyat turlari bo'yicha taqsimot -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Faoliyat turlari bo'yicha taqsimot</h6>
                        <button class="btn btn-sm btn-outline-primary"
                            onclick="downloadChart('activityChart', 'Faoliyat_turlari_boyicha_taqsimot')">
                            <i class="fas fa-download me-1"></i>Rasm yuklash
                        </button>
                    </div>
                    <div class="card-body" style="height: 500px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Xodimlar soni o'zgarishi</h6>
                        <button class="btn btn-sm btn-outline-primary"
                            onclick="downloadChart('headcountChart', 'Xodimlar_soni_ozgarishi')">
                            <i class="fas fa-download me-1"></i>Rasm yuklash
                        </button>
                    </div>
                    <div class="card-body" style="height: 400px;">
                        <canvas id="headcountChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Barcha grafiklarni birgalikda yuklash -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Grafiklar eksporti</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-success" onclick="downloadAllCharts()">
                                <i class="fas fa-download me-2"></i>Barcha grafiklarni yuklash
                            </button>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-outline-primary btn-sm w-100"
                                        onclick="downloadChart('regionChart', 'Viloyatlar_taqsimoti')">
                                        <i class="fas fa-map-marker-alt me-1"></i>Viloyatlar
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-outline-primary btn-sm w-100"
                                        onclick="downloadChart('activityChart', 'Faoliyat_turlari')">
                                        <i class="fas fa-briefcase me-1"></i>Faoliyat turlari
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <button class="btn btn-outline-primary btn-sm w-100"
                                        onclick="downloadChart('headcountChart', 'Xodimlar_ozgarishi')">
                                        <i class="fas fa-users me-1"></i>Xodimlar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- TOP-20 yetishmayotgan kadrlar -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">TOP-20 yetishmayotgan kadrlar</h6>
                        <a href="{{ route('admin.skills.statistics') }}?type=missing&{{ http_build_query($filters) }}"
                            class="btn btn-sm btn-outline-primary">
                            Batafsil
                        </a>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @foreach ($topMissingSkills->take(20) as $skill)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="fw-medium">
                                        @if(isset($skill->name))
                                            {{ Str::limit($skill->name, 50) }}
                                        @elseif(isset($skill->skill_name))
                                            {{ Str::limit($skill->skill_name, 50) }}
                                        @else
                                            {{ $skill->group_code ?? 'Noma\'lum' }}
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        {{ $skill->worker_type }} | {{ $skill->group_code }}
                                    </small>
                                </div>
                                <span class="badge bg-danger">{{ $skill->responses_count }}</span>
                            </div>
                            @if (!$loop->last)
                                <hr class="my-2">
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- TOP-20 kelajakda talab oshadigan kadrlar -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">TOP-20 kelajakda talab oshadigan kadrlar</h6>
                        <a href="{{ route('admin.skills.statistics') }}?type=future_demand&{{ http_build_query($filters) }}"
                            class="btn btn-sm btn-outline-primary">
                            Batafsil
                        </a>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @foreach ($topFutureDemandSkills->take(20) as $skill)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="fw-medium">
                                        @if(isset($skill->name))
                                            {{ Str::limit($skill->name, 50) }}
                                        @elseif(isset($skill->skill_name))
                                            {{ Str::limit($skill->skill_name, 50) }}
                                        @else
                                            {{ $skill->group_code ?? 'Noma\'lum' }}
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        {{ $skill->worker_type }} | {{ $skill->group_code }}
                                    </small>
                                </div>
                                <span class="badge bg-primary">{{ $skill->responses_count }}</span>
                            </div>
                            @if (!$loop->last)
                                <hr class="my-2">
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($performance) && app()->environment('local'))
<div style="position: fixed; bottom: 10px; right: 10px; background: #000; color: #fff; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 9999;">
    <strong>Performance:</strong><br>
    ⏱️ Total: {{ $performance['total_time'] }}ms<br>
    💾 Cache Hit: {{ $performance['cache_hit'] ? '✅ YES' : '❌ NO' }}<br>
    🗄️ DB Time: {{ $performance['db_time'] }}ms<br>
    📊 Memory: {{ $performance['memory_usage'] }}MB<br>
    🕐 Time: {{ $performance['timestamp'] }}
</div>
@endif
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let regionChart;
            let activityChart;
            let headcountChart;

            // Global function to download single chart
            window.downloadChart = function(chartId, filename) {
                const canvas = document.getElementById(chartId);
                if (!canvas) {
                    alert('Grafik topilmadi!');
                    return;
                }

                // Create a temporary canvas with white background
                const tempCanvas = document.createElement('canvas');
                const tempCtx = tempCanvas.getContext('2d');

                tempCanvas.width = canvas.width;
                tempCanvas.height = canvas.height;

                // Fill with white background
                tempCtx.fillStyle = '#ffffff';
                tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

                // Draw the chart on top
                tempCtx.drawImage(canvas, 0, 0);

                // Download
                const link = document.createElement('a');
                link.download = filename + '_' + new Date().toISOString().slice(0, 10) + '.png';
                link.href = tempCanvas.toDataURL('image/png', 1.0);
                link.click();
            };

            // Global function to download all charts as ZIP
            window.downloadAllCharts = function() {
                const zip = new JSZip();
                const charts = [{
                        id: 'regionChart',
                        name: 'Viloyatlar_taqsimoti'
                    },
                    {
                        id: 'activityChart',
                        name: 'Faoliyat_turlari_taqsimoti'
                    },
                    {
                        id: 'headcountChart',
                        name: 'Xodimlar_soni_ozgarishi'
                    }
                ];

                let completed = 0;
                const total = charts.length;

                charts.forEach((chart, index) => {
                    const canvas = document.getElementById(chart.id);
                    if (canvas) {
                        // Create white background
                        const tempCanvas = document.createElement('canvas');
                        const tempCtx = tempCanvas.getContext('2d');

                        tempCanvas.width = canvas.width;
                        tempCanvas.height = canvas.height;

                        tempCtx.fillStyle = '#ffffff';
                        tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
                        tempCtx.drawImage(canvas, 0, 0);

                        // Convert to blob and add to zip
                        tempCanvas.toBlob(function(blob) {
                            zip.file(chart.name + '.png', blob);
                            completed++;

                            if (completed === total) {
                                // Generate and download ZIP
                                zip.generateAsync({
                                    type: 'blob'
                                }).then(function(content) {
                                    const link = document.createElement('a');
                                    link.href = URL.createObjectURL(content);
                                    link.download = 'Dashboard_Grafiklar_' + new Date()
                                        .toISOString().slice(0, 10) + '.zip';
                                    link.click();
                                });
                            }
                        }, 'image/png', 1.0);
                    }
                });

                if (completed === 0) {
                    alert('Hech qanday grafik topilmadi!');
                }
            };

            // Region filter change
            document.getElementById('filter_region_id').addEventListener('change', function() {
                const regionId = this.value;
                const districtSelect = document.getElementById('filter_district_id');

                if (regionId) {
                    fetch('{{ route('admin.districts') }}?region_id=' + regionId)
                        .then(response => response.json())
                        .then(data => {
                            let options = '<option value="">Barcha tumanlar</option>';
                            data.forEach(district => {
                                options +=
                                    `<option value="${district.id}">${district.name_uz}</option>`;
                            });
                            districtSelect.innerHTML = options;
                        });
                } else {
                    districtSelect.innerHTML = '<option value="">Barcha tumanlar</option>';
                }
            });

            // Initialize charts
            initializeCharts();

            function initializeCharts() {

                // Viloyatlar/Tumanlar bo'yicha chart
                const isRegionSelected = {{ $filters['region_id'] ? 'true' : 'false' }};

                let chartData, chartLabels;
                if (isRegionSelected) {
                    const districtData = @json($responsesByDistrict ?? []);
                    chartLabels = districtData.map(item => item.district_name);
                    chartData = districtData.map(item => item.count);
                } else {
                    const regionData = @json($responsesByRegion);
                    chartLabels = regionData.map(item => item.region_name);
                    chartData = regionData.map(item => item.count);
                }

                // Region/District Chart
                const ctx = document.getElementById('regionChart').getContext('2d');

                if (regionChart) {
                    regionChart.destroy();
                }

                regionChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartLabels,
                        datasets: [{
                            data: chartData,
                            backgroundColor: isRegionSelected ? '#36A2EB' : '#FF6384',
                            borderColor: isRegionSelected ? '#2196F3' : '#E91E63',
                            borderWidth: 1,
                            barThickness: 25,
                            maxBarThickness: 30
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            y: {
                                ticks: {
                                    maxRotation: 0,
                                    callback: function(value, index) {
                                        const label = this.getLabelForValue(value);
                                        return label.length > 20 ? label.substring(0, 20) + '...' :
                                            label;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        layout: {
                            padding: {
                                left: 10,
                                right: 80,
                                top: 10,
                                bottom: 10
                            }
                        },
                        animation: {
                            onComplete: function() {
                                const ctx = this.ctx;
                                ctx.save();
                                ctx.font = 'bold 12px Arial';
                                ctx.textAlign = 'left';
                                ctx.textBaseline = 'middle';
                                ctx.fillStyle = 'white';

                                const meta = this.getDatasetMeta(0);
                                meta.data.forEach((bar, index) => {
                                    const data = chartData[index];
                                    if (data > 0) {
                                        const label = data.toLocaleString();

                                        // Background
                                        const bgColor = isRegionSelected ? '#2196F3' :
                                        '#E91E63';
                                        const textWidth = ctx.measureText(label).width;
                                        const padding = 8;

                                        ctx.fillStyle = bgColor;
                                        const rectX = bar.x + 5;
                                        const rectY = bar.y - 10;
                                        const rectWidth = textWidth + (padding * 2);
                                        const rectHeight = 20;

                                        ctx.fillRect(rectX, rectY, rectWidth, rectHeight);

                                        // Text
                                        ctx.fillStyle = 'white';
                                        ctx.fillText(label, rectX + padding, bar.y);
                                    }
                                });
                                ctx.restore();
                            }
                        }
                    }
                });

                // Xodimlar soni o'zgarishi chart
                const headcountData = @json($headcountChanges);
                const headcountLabels = Object.keys(headcountData);
                const headcountCounts = Object.values(headcountData);

                headcountChart = new Chart(document.getElementById('headcountChart'), {
                    type: 'bar',
                    data: {
                        labels: headcountLabels,
                        datasets: [{
                            data: headcountCounts,
                            backgroundColor: ['#28a745', '#17a2b8', '#ffc107'],
                            borderColor: ['#1e7e34', '#138496', '#e0a800'],
                            borderWidth: 1,
                            barThickness: 60,
                            maxBarThickness: 80
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        layout: {
                            padding: {
                                top: 40,
                                bottom: 10
                            }
                        },
                        animation: {
                            onComplete: function() {
                                const ctx = this.ctx;
                                ctx.save();
                                ctx.font = 'bold 12px Arial';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillStyle = 'white';

                                const meta = this.getDatasetMeta(0);
                                meta.data.forEach((bar, index) => {
                                    const data = headcountCounts[index];
                                    if (data > 0) {
                                        const label = data.toLocaleString();
                                        const bgColor = ['#28a745', '#17a2b8', '#ffc107'][
                                        index];

                                        // Background
                                        const textWidth = ctx.measureText(label).width;
                                        const padding = 8;

                                        ctx.fillStyle = bgColor;
                                        const rectX = bar.x - (textWidth + padding * 2) / 2;
                                        const rectY = bar.y - 25;
                                        const rectWidth = textWidth + (padding * 2);
                                        const rectHeight = 20;

                                        ctx.fillRect(rectX, rectY, rectWidth, rectHeight);

                                        // Text
                                        ctx.fillStyle = 'white';
                                        ctx.fillText(label, bar.x, bar.y - 15);
                                    }
                                });
                                ctx.restore();
                            }
                        }
                    }
                });

                // Faoliyat turlari chart
                const activityData = @json($responsesByActivity);
                const activityLabels = activityData.map(item => item.activity_name);
                const activityCounts = activityData.map(item => item.count);

                activityChart = new Chart(document.getElementById('activityChart'), {
                    type: 'bar',
                    data: {
                        labels: activityLabels,
                        datasets: [{
                            data: activityCounts,
                            backgroundColor: '#6366f1',
                            borderColor: '#4f46e5',
                            borderWidth: 1,
                            barThickness: 25,
                            maxBarThickness: 30
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            y: {
                                ticks: {
                                    maxRotation: 0,
                                    callback: function(value, index) {
                                        const label = this.getLabelForValue(value);
                                        return label.length > 25 ? label.substring(0, 25) + '...' :
                                            label;
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        layout: {
                            padding: {
                                left: 10,
                                right: 80,
                                top: 10,
                                bottom: 10
                            }
                        },
                        animation: {
                            onComplete: function() {
                                const ctx = this.ctx;
                                ctx.save();
                                ctx.font = 'bold 12px Arial';
                                ctx.textAlign = 'left';
                                ctx.textBaseline = 'middle';
                                ctx.fillStyle = 'white';

                                const meta = this.getDatasetMeta(0);
                                meta.data.forEach((bar, index) => {
                                    const data = activityCounts[index];
                                    if (data > 0) {
                                        const label = data.toLocaleString();

                                        // Background
                                        const textWidth = ctx.measureText(label).width;
                                        const padding = 8;

                                        ctx.fillStyle = '#4f46e5';
                                        const rectX = bar.x + 5;
                                        const rectY = bar.y - 10;
                                        const rectWidth = textWidth + (padding * 2);
                                        const rectHeight = 20;

                                        ctx.fillRect(rectX, rectY, rectWidth, rectHeight);

                                        // Text
                                        ctx.fillStyle = 'white';
                                        ctx.fillText(label, rectX + padding, bar.y);
                                    }
                                });
                                ctx.restore();
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
