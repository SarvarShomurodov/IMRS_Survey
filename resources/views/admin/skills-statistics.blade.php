@extends('layouts.app')

@section('title', 'Kasblar Statistikasi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            @if ($type === 'missing')
                                <i class="fas fa-exclamation-triangle text-warning"></i> Бугунги кунда етишмаётган кадрлар
                            @else
                                <i class="fas fa-chart-line text-info"></i> Келажакда талаб ошадиган кадрлар
                            @endif
                        </h3>

                        <div class="card-tools">
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.skills.statistics', ['type' => 'missing'] + request()->except('type')) }}"
                                    class="btn btn-sm {{ $type === 'missing' ? 'btn-warning' : 'btn-outline-warning' }}">
                                    Етишмаётган кадрлар
                                </a>
                                <a href="{{ route('admin.skills.statistics', ['type' => 'future_demand'] + request()->except('type')) }}"
                                    class="btn btn-sm {{ $type === 'future_demand' ? 'btn-info' : 'btn-outline-info' }}">
                                    Келажакдаги талаб
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Filter Form -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">
                                            <i class="fas fa-filter"></i> Филтрлар
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form method="GET" id="filterForm">
                                            <input type="hidden" name="type" value="{{ $type }}">

                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label for="region_id" class="form-label">Вилоят:</label>
                                                    <select name="region_id" id="region_id" class="form-select">
                                                        <option value="">Барча вилоятлар</option>
                                                        @foreach ($regions as $region)
                                                            <option value="{{ $region->id }}"
                                                                {{ $filters['region_id'] == $region->id ? 'selected' : '' }}>
                                                                {{ $region->name_uz }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="district_id" class="form-label">Туман:</label>
                                                    <select name="district_id" id="district_id" class="form-select">
                                                        <option value="">Барча туманлар</option>
                                                        @foreach ($districts as $district)
                                                            <option value="{{ $district->id }}"
                                                                {{ $filters['district_id'] == $district->id ? 'selected' : '' }}>
                                                                {{ $district->name_uz }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-3 mb-3">
                                                    <label for="activity_type_id" class="form-label">Фаолият тури:</label>
                                                    <select name="activity_type_id" id="activity_type_id"
                                                        class="form-select">
                                                        <option value="">Барча фаолият турлари</option>
                                                        @foreach ($activityTypes as $activityType)
                                                            <option value="{{ $activityType->id }}"
                                                                {{ $filters['activity_type_id'] == $activityType->id ? 'selected' : '' }}>
                                                                {{ $activityType->name_uz }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-2 mb-3">
                                                    <label for="year" class="form-label">Йил:</label>
                                                    <select name="year" id="year" class="form-select">
                                                        @for ($y = date('Y'); $y >= 2020; $y--)
                                                            <option value="{{ $y }}"
                                                                {{ $filters['year'] == $y ? 'selected' : '' }}>
                                                                {{ $y }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                <div class="col-md-1 mb-3">
                                                    <label for="limit" class="form-label">Лимит:</label>
                                                    <select name="limit" id="limit" class="form-select">
                                                        <option value="25"
                                                            {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                                                        <option value="50"
                                                            {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                                                        <option value="100"
                                                            {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
                                                        <option value="200"
                                                            {{ request('limit') == 200 ? 'selected' : '' }}>200</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-primary me-2">
                                                        <i class="fas fa-search"></i> Қидириш
                                                    </button>
                                                    <a href="{{ route('admin.skills.statistics', ['type' => $type]) }}"
                                                        class="btn btn-secondary me-2">
                                                        <i class="fas fa-undo"></i> Тозалаш
                                                    </a>

                                                    <!-- Export tugmalari -->
                                                    @if ($skills->count() > 0)
                                                        <div class="btn-group" role="group">
                                                            <button type="button" class="btn btn-success dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                <i class="fas fa-download"></i> Экспорт
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.skills.export', array_merge(request()->all(), ['format' => 'excel', 'detail' => 'detailed'])) }}">
                                                                        <i class="fas fa-file-excel text-success"></i> Excel
                                                                        (батафсил)
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.skills.export', array_merge(request()->all(), ['format' => 'csv', 'detail' => 'detailed'])) }}">
                                                                        <i class="fas fa-file-csv text-info"></i> CSV
                                                                        (батафсил)
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Table -->
                        <div class="row">
                            <div class="col-12">
                                @if ($skills->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover" id="skillsTable">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th style="width: 50px">#</th>
                                                    <th>Касб номи</th>
                                                    <th style="width: 150px">Гуруҳ коди</th>
                                                    <th style="width: 120px">Ишчи тури</th>
                                                    <th style="width: 100px">Жавоблар сони</th>
                                                    <th style="width: 120px">Умумий талаб</th>
                                                    <th style="width: 200px">Таълим талаблари</th>
                                                    <th style="width: 200px">Тажриба талаблари</th>
                                                    <th style="width: 150px">Жинс талаблари</th>
                                                    <th style="width: 80px">Амал</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($skills as $index => $skill)
                                                    <tr>
                                                        <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                                        <td>
                                                            <strong class="text-primary">{{ $skill->skill_name }}</strong>
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge bg-secondary">{{ $skill->group_code ?? 'N/A' }}</span>
                                                        </td>
                                                        <td>
                                                            <small
                                                                class="text-muted">{{ $skill->worker_type ?? 'Умумий' }}</small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-{{ $type === 'missing' ? 'warning' : 'info' }} fs-6">
                                                                {{ number_format($skill->responses_count) }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="fw-bold text-success">
                                                                {{ number_format($skill->total_required ?? ($skill->total_expected ?? 0)) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="education-stats">
                                                                @if ($skill->edu_ahmiyati_yok > 0)
                                                                    <span class="badge bg-light text-dark me-1">Аҳамияти
                                                                        йўқ: {{ $skill->edu_ahmiyati_yok }}</span>
                                                                @endif
                                                                @if ($skill->edu_orta > 0)
                                                                    <span class="badge bg-light text-dark me-1">Ўрта (11 йиллик таълим):
                                                                        {{ $skill->edu_orta }}</span>
                                                                @endif
                                                                @if ($skill->edu_umumiy_orta > 0)
                                                                    <span class="badge bg-light text-dark me-1">Ўрта махсус / профессионал коллеж (техникум, касб-ҳунар): 
                                                                        {{ $skill->edu_umumiy_orta }}</span>
                                                                @endif
                                                                @if ($skill->edu_oliy > 0)
                                                                    <span class="badge bg-light text-dark">Олий (бакалавр / магистр):
                                                                        {{ $skill->edu_oliy }}</span>
                                                                @endif
                                                                @if ($skill->edu_phd > 0)
                                                                    <span class="badge bg-light text-dark">Олий илмий даража (PhD/ DcS):
                                                                        {{ $skill->edu_phd }}</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="experience-stats">
                                                                @if ($skill->exp_0 > 0)
                                                                    <span class="badge bg-light text-dark me-1">Тажриба талаб қилинмайди:
                                                                        {{ $skill->exp_0 }}</span>
                                                                @endif
                                                                @if ($skill->exp_0_1 > 0)
                                                                    <span class="badge bg-light text-dark me-1">1 йил ёки ундан кам:
                                                                        {{ $skill->exp_0_1 }}</span>
                                                                @endif
                                                                @if ($skill->exp_1_2 > 0)
                                                                    <span class="badge bg-light text-dark me-1">1-2 йил:
                                                                        {{ $skill->exp_1_2 }}</span>
                                                                @endif
                                                                @if ($skill->exp_3_5 > 0)
                                                                    <span class="badge bg-light text-dark me-1">3-5 йил:
                                                                        {{ $skill->exp_3_5 }}</span>
                                                                @endif
                                                                @if ($skill->exp_6_9 > 0)
                                                                    <span class="badge bg-light text-dark me-1">6-9 йил:
                                                                        {{ $skill->exp_6_9 }}</span>    
                                                                @endif
                                                                @if ($skill->exp_10_plus > 0)
                                                                    <span class="badge bg-light text-dark me-1">10 йилдан ортиқ:
                                                                        {{ $skill->exp_10_plus }}</span>    
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="gender-stats">
                                                                @if ($skill->gender_male > 0)
                                                                    <span class="badge bg-primary me-1">Эркак:
                                                                        {{ $skill->gender_male }}</span>
                                                                @endif
                                                                @if ($skill->gender_female > 0)
                                                                    <span class="badge bg-danger me-1">Аёл:
                                                                        {{ $skill->gender_female }}</span>
                                                                @endif
                                                                @if ($skill->gender_any > 0)
                                                                    <span class="badge bg-secondary">Фарқсиз:
                                                                        {{ $skill->gender_any }}</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('admin.skill.detail', $skill->skill_id) }}?type={{ $type }}&{{ http_build_query($filters) }}"
                                                                class="btn btn-sm btn-outline-primary" title="Батафсил">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
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
                                                    <i class="fas fa-info-circle"></i> Хулоса:
                                                </h5>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-0">
                                                            @if ($type === 'missing')
                                                                Жами <strong>{{ $skills->count() }}</strong> та етишмаётган
                                                                касб аниқланди.
                                                                Энг кўп талаб қилинган:
                                                                <strong>{{ $skills->first()->skill_name ?? 'N/A' }}</strong>
                                                                ({{ $skills->first()->responses_count ?? 0 }} та жавоб)
                                                            @else
                                                                Жами <strong>{{ $skills->count() }}</strong> та келажакда
                                                                талаб ошадиган касб.
                                                                Энг кўп ўсиш кутилган:
                                                                <strong>{{ $skills->first()->skill_name ?? 'N/A' }}</strong>
                                                                ({{ $skills->first()->responses_count ?? 0 }} та жавоб)
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-0">
                                                            Умумий талаб:
                                                            <strong>{{ number_format($skills->sum($type === 'missing' ? 'total_required' : 'total_expected')) }}</strong>
                                                            киши
                                                        </p>
                                                        <small class="text-muted">
                                                            Фильтрлар:
                                                            @if ($filters['region_id'])
                                                                Вилоят:
                                                                {{ $regions->firstWhere('id', $filters['region_id'])->name_uz ?? 'N/A' }}
                                                            @endif
                                                            @if ($filters['district_id'])
                                                                , Туман:
                                                                {{ $districts->firstWhere('id', $filters['district_id'])->name_uz ?? 'N/A' }}
                                                            @endif
                                                            @if ($filters['activity_type_id'])
                                                                , Фаолият:
                                                                {{ $activityTypes->firstWhere('id', $filters['activity_type_id'])->name_uz ?? 'N/A' }}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">Маълумотлар топилмади</h4>
                                        <p class="text-muted">Бошқа фильтр параметрларини танлаб кўринг</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Viloyat o'zgarganda tumanlarni yangilash
                $('#region_id').change(function() {
                    const regionId = $(this).val();
                    const districtSelect = $('#district_id');

                    districtSelect.empty().append('<option value="">Барча туманлар</option>');

                    if (regionId) {
                        $.get('/admin/districts', {
                                region_id: regionId
                            })
                            .done(function(data) {
                                $.each(data, function(index, district) {
                                    districtSelect.append(
                                        `<option value="${district.id}">${district.name_uz}</option>`
                                        );
                                });
                            })
                            .fail(function() {
                                console.error('Туманларни юклашда хатолик');
                            });
                    }
                });

                // DataTable agar mavjud bo'lsa
                if ($.fn.DataTable && $('#skillsTable').length) {
                    $('#skillsTable').DataTable({
                        "paging": false,
                        "searching": false,
                        "ordering": true,
                        "info": false,
                        "responsive": true,
                        "columnDefs": [{
                            "orderable": false,
                            "targets": [6, 7, 8, 9]
                        }],
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Uzbek.json"
                        }
                    });
                }

                // Print media uchun
                window.addEventListener('beforeprint', function() {
                    $('.btn, .card-tools, .dropdown').hide();
                });

                window.addEventListener('afterprint', function() {
                    $('.btn, .card-tools, .dropdown').show();
                });
            });
        </script>

        <style>
            .education-stats .badge,
            .experience-stats .badge,
            .gender-stats .badge {
                font-size: 0.75rem;
                margin-bottom: 2px;
                display: inline-block;
            }

            .table th {
                font-size: 0.875rem;
                font-weight: 600;
            }

            .table td {
                font-size: 0.875rem;
                vertical-align: middle;
            }

            @media (max-width: 768px) {
                .table-responsive {
                    font-size: 0.8rem;
                }

                .badge {
                    font-size: 0.7rem !important;
                }
            }

            @media print {

                .btn,
                .card-tools,
                .dropdown,
                .alert {
                    display: none !important;
                }

                .table {
                    font-size: 0.8rem;
                }
            }
        </style>
    @endpush
@endsection
