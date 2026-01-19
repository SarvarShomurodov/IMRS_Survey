@extends('layouts.app')

@section('title', 'Касб тафсилотлари')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Back button -->
                <div class="mb-3">
                    <a href="{{ route('admin.skills.statistics', ['type' => $type] + $filters) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Орқага қайтиш
                    </a>
                </div>

                <!-- Skill Info Card -->
                <div class="card mb-4">
                    <div class="card-header bg-{{ $type === 'missing' ? 'warning' : 'info' }}">
                        <h3 class="card-title text-white">
                            <i class="fas fa-{{ $type === 'missing' ? 'exclamation-triangle' : 'chart-line' }}"></i>
                            {{ $skill->name }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge bg-light text-dark">
                                {{ $type === 'missing' ? 'Етишмаётган касб' : 'Келажакдаги талаб' }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Гуруҳ коди:</strong> {{ $skill->group_code ?? 'N/A' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Ишчи тури:</strong> {{ $skill->worker_type ?? 'Умумий' }}
                            </div>
                            <div class="col-md-4">
                                <strong>Жами корхоналар:</strong> {{ $details->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regional Statistics -->
                @if ($regionStats->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fas fa-map-marker-alt"></i> Вилоятлар бўйича тақсимот
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($regionStats as $region)
                                    <div class="col-md-3 mb-3">
                                        <div class="card border-{{ $type === 'missing' ? 'warning' : 'info' }}">
                                            <div class="card-body text-center">
                                                <h5 class="card-title">{{ $region->region_name }}</h5>
                                                <p class="card-text">
                                                    <span
                                                        class="badge bg-{{ $type === 'missing' ? 'warning' : 'info' }} fs-6">
                                                        {{ $region->count }} жавоб
                                                    </span><br>
                                                    <small class="text-muted">{{ $region->companies_count }}
                                                        корхона</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif


                <!-- Detailed Table -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-table"></i> Батафсил маълумотлар
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($details->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="detailsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="width: 50px">#</th>
                                            <th>Корхона номи</th>
                                            <th style="width: 120px">Вилоят</th>
                                            <th style="width: 120px">Туман</th>
                                            <th style="width: 150px">Фаолият тури</th>
                                            <th style="width: 100px">Ходимлар сони</th>
                                            <th style="width: 120px">Ҳозирги ўзгариш</th>
                                            <th style="width: 120px">6 ойлик прогноз</th>
                                            <th style="width: 120px">Тренд</th>
                                            <th style="width: 150px">Таълим даражаси</th>
                                            <th style="width: 120px">Иш тажрибаси</th>
                                            <th style="width: 120px">Жинс талаби</th>
                                            <th style="width: 120px">Сана</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $index => $detail)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <strong class="text-primary">{{ $detail->company_name }}</strong>
                                                </td>
                                                <td>{{ $detail->region_name }}</td>
                                                <td>{{ $detail->district_name }}</td>
                                                <td>
                                                    <small>{{ $detail->activity_type }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-secondary">{{ number_format($detail->employee_count) }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $changeLabels = [
                                                            'oshdi' => ['text' => 'Ошди', 'class' => 'success'],
                                                            'ozgarmadi' => ['text' => 'Ўзгармади', 'class' => 'info'],
                                                            'kamaydi' => ['text' => 'Камайди', 'class' => 'warning'],
                                                        ];
                                                        $change = $changeLabels[$detail->headcount_change] ?? [
                                                            'text' => $detail->headcount_change,
                                                            'class' => 'secondary',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $change['class'] }}">{{ $change['text'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $sixChangeLabels = [
                                                            'oshdi' => ['text' => 'Ошади', 'class' => 'success'],
                                                            'ozgarmadi' => ['text' => 'Ўзгармайди', 'class' => 'info'],
                                                            'kamaydi' => ['text' => 'Камаяди', 'class' => 'warning'],
                                                        ];
                                                        $sixChange = $sixChangeLabels[$detail->headcount_six_change] ?? [
                                                            'text' => $detail->headcount_six_change ?? 'N/A',
                                                            'class' => 'secondary',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $sixChange['class'] }}">{{ $sixChange['text'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $current = $detail->headcount_change;
                                                        $forecast = $detail->headcount_six_change;
                                                        $trend = 'N/A';
                                                        $trendClass = 'secondary';
                                                        
                                                        if ($current && $forecast) {
                                                            if ($current === 'oshdi' && $forecast === 'oshdi') {
                                                                $trend = 'Доимий ўсиш';
                                                                $trendClass = 'success';
                                                            } elseif ($current === 'kamaydi' && $forecast === 'kamaydi') {
                                                                $trend = 'Доимий камайиш';
                                                                $trendClass = 'danger';
                                                            } elseif ($current === 'ozgarmadi' && $forecast === 'ozgarmadi') {
                                                                $trend = 'Барқарор';
                                                                $trendClass = 'info';
                                                            } elseif ($current === 'oshdi' && $forecast === 'kamaydi') {
                                                                $trend = 'Вақтинчалик';
                                                                $trendClass = 'warning';
                                                            } elseif ($current === 'kamaydi' && $forecast === 'oshdi') {
                                                                $trend = 'Тикланиш';
                                                                $trendClass = 'primary';
                                                            } else {
                                                                $trend = 'Ўтиш';
                                                                $trendClass = 'dark';
                                                            }
                                                        }
                                                    @endphp
                                                    <span class="badge bg-{{ $trendClass }}">{{ $trend }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $eduLabels = [
                                                            'ahmiyati_yok' => 'Аҳамияти йўқ',
                                                            'orta' => 'Ўрта (11 йиллик таълим)',
                                                            'umumiy_orta' => 'Ўрта махсус / профессионал коллеж (техникум, касб-ҳунар)',
                                                            'oliy' => 'Олий (бакалавр / магистр)',
                                                            'phd' => 'Олий илмий даража (PhD/ DcS)',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-light text-dark">
                                                        {{ $eduLabels[$detail->education_level] ?? $detail->education_level }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $expLabels = [
                                                            '0' => 'Йўқ',
                                                            '0-1' => '1 йил ёки ундан кам',
                                                            '1-2' => '1-2 йил',
                                                            '3-5' => '3-5 йил',
                                                            '6-9' => '6-9 йил',
                                                            '10+' => '10 йилдан ортиқ',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-light text-dark">
                                                        {{ $expLabels[$detail->experience_level] ?? $detail->experience_level }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $genderLabels = [
                                                            'erkak' => ['text' => 'Эркак', 'class' => 'primary'],
                                                            'ayol' => ['text' => 'Аёл', 'class' => 'danger'],
                                                            'farq_qilmaydi' => [
                                                                'text' => 'Фарқсиз',
                                                                'class' => 'secondary',
                                                            ],
                                                        ];
                                                        $gender = $genderLabels[$detail->gender_preference] ?? [
                                                            'text' => $detail->gender_preference,
                                                            'class' => 'secondary',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $gender['class'] }}">{{ $gender['text'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <small>{{ $detail->created_at->format('d.m.Y') }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Summary -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-{{ $type === 'missing' ? 'warning' : 'info' }}">
                                        <h5>
                                            <i class="fas fa-chart-bar"></i> Статистика хулосаси:
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <ul class="list-unstyled">
                                                    <li><strong>Жами корхоналар:</strong> {{ $details->count() }}</li>
                                                    <li><strong>Умумий ходимлар:</strong>
                                                        {{ number_format($details->sum('employee_count')) }}</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-4">
                                                <ul class="list-unstyled">
                                                    <li><strong>Энг кўп талаб қилган вилоят:</strong>
                                                        {{ $regionStats->first()->region_name ?? 'N/A' }}</li>
                                                    <li><strong>Ўртача корхона ҳажми:</strong>
                                                        {{ $details->count() > 0 ? number_format($details->avg('employee_count')) : 0 }}
                                                        ходим</li>
                                                </ul>
                                            </div>
                                            <div class="col-md-4">
                                                <ul class="list-unstyled">
                                                    @php
                                                        $growingCompanies = $details->where('headcount_change', 'oshdi')->count();
                                                        $stableCompanies = $details->where('headcount_change', 'ozgarmadi')->count();
                                                        $decliningCompanies = $details->where('headcount_change', 'kamaydi')->count();
                                                    @endphp
                                                    <li><strong>Ўсаётган корхоналар:</strong> {{ $growingCompanies }} та</li>
                                                    <li><strong>Барқарор корхоналар:</strong> {{ $stableCompanies }} та</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Батафсил маълумотлар топилмади</h4>
                                <p class="text-muted">Бу касб бўйича ҳеч қандай маълумот йўқ</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // DataTable инициализация
                if ($.fn.DataTable && $('#detailsTable').length) {
                    $('#detailsTable').DataTable({
                        "pageLength": 25,
                        "lengthMenu": [
                            [10, 25, 50, 100],
                            [10, 25, 50, 100]
                        ],
                        "order": [
                            [12, "desc"]
                        ], // Sana bo'yicha tartiblash (yangi index)
                        "responsive": true,
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Uzbek.json",
                            "search": "Қидириш:",
                            "lengthMenu": "Ҳар саҳифада _MENU_ та кўрсатиш",
                            "info": "_START_ дан _END_ гача (_TOTAL_ тадан)",
                            "paginate": {
                                "first": "Биринчи",
                                "last": "Охирги",
                                "next": "Кейинги",
                                "previous": "Олдинги"
                            }
                        },
                        "columnDefs": [{
                                "orderable": false,
                                "targets": [8, 9, 10, 11]
                            }, // Badge ustunlarni tartiblash o'chirish
                            {
                                "className": "text-center",
                                "targets": [0, 5, 6, 7, 8, 12]
                            }
                        ]
                    });
                }

                // Print uchun style
                window.addEventListener('beforeprint', function() {
                    $('.btn, .card-tools').hide();
                    $('table').removeClass('table-striped table-hover');
                });

                window.addEventListener('afterprint', function() {
                    $('.btn, .card-tools').show();
                    $('table').addClass('table-striped table-hover');
                });
            });
        </script>

        <style>
            .badge {
                font-size: 0.7rem;
                font-weight: 500;
            }

            .table th {
                font-size: 0.8rem;
                font-weight: 600;
                white-space: nowrap;
            }

            .table td {
                font-size: 0.8rem;
                vertical-align: middle;
            }

            @media (max-width: 768px) {
                .table-responsive {
                    font-size: 0.7rem;
                }

                .badge {
                    font-size: 0.6rem !important;
                }
            }

            @media print {

                .btn,
                .card-tools,
                .alert {
                    display: none !important;
                }

                .table {
                    font-size: 0.7rem;
                }

                .badge {
                    background-color: #f8f9fa !important;
                    color: #000 !important;
                    border: 1px solid #dee2e6 !important;
                }
            }
        </style>
    @endpush
@endsection