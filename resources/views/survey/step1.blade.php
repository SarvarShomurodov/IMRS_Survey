{{-- resources/views/survey/step1.blade.php --}}
@extends('layouts.app')

@section('title', 'So\'rovnoma - 1-bosqich')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0" style="font-size: 2.50rem;">
                            {{-- <i class="fas fa-building me-2"></i> --}}
                            <b>Иш берувчилар орасида сўров</b>
                        </h3>
                    </div>
                    <div class="card-body fs-6">
                        <p style="font-size: 18px">Вазирлар Маҳкамаси ҳузуридаги Макроиқтисодий ва ҳудудий тадқиқотлар
                            институти томонидан иш берувчилар орасида сўров ўтказилмоқда. Сўров натижалари асосида
                            республикадаги кадрларга бўлган талаб аниқланади ва уни кейинги 6 ойда ўзгариш тенденциялари
                            таҳлил қилинади. Ушбу натижалар ёшларни меҳнат бозорида талаб юқори бўлган касбларни танлашга
                            имконият яратади, бу эса ўз навбатида корхоналардаги кадрларга бўлган талабни қоплаш имкониятини
                            яратади.</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('survey.step1.process') }}" id="step1Form">
                            @csrf

                            <!-- Viloyat - Radio buttons (vertical list) -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Корхона/ташкилот қайси ҳудудда жойлашган?
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="ps-2">
                                    @foreach ($regions as $region)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input region-radio" type="radio" name="region_id"
                                                id="region_{{ $region->id }}" value="{{ $region->id }}"
                                                {{ old('region_id', $formData['region_id'] ?? '') == $region->id ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label fs-6" for="region_{{ $region->id }}">
                                                {{ $region->getName('ru') }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('region_id')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- YANGI - Tashkiliy-huquqiy shakl -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Корхонангизнинг ташкилий-ҳуқуқий шакли
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="ps-2">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="organizational_legal_form"
                                            id="org_davlat" value="davlat"
                                            {{ old('organizational_legal_form', $formData['organizational_legal_form'] ?? '') == 'davlat' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label fs-6" for="org_davlat">
                                            Давлат ташкилоти
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="organizational_legal_form"
                                            id="org_xususiy" value="xususiy"
                                            {{ old('organizational_legal_form', $formData['organizational_legal_form'] ?? '') == 'xususiy' ? 'checked' : '' }}>
                                        <label class="form-check-label fs-6" for="org_xususiy">
                                            Хусусий корхона
                                        </label>
                                    </div>
                                </div>
                                @error('organizational_legal_form')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Tuman - Dropdown (oldingiday qoladi) -->
                            <div class="mb-4" id="district-section"
                                style="{{ old('region_id', $formData['region_id'] ?? '') ? '' : 'display: none;' }}">
                                <label class="form-label h5 mb-3">
                                    Туманни танланг
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('district_id') is-invalid @enderror" name="district_id"
                                    id="district_id" required>
                                    <option value="">Туманни танланг</option>
                                </select>
                                @error('district_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Faoliyat turi - Radio buttons (vertical list) -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Корхона/ташкилот фаолияти тури.
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="ps-2">
                                    @foreach ($activityTypes as $activityType)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="activity_type_id"
                                                id="activity_{{ $activityType->id }}" value="{{ $activityType->id }}"
                                                {{ old('activity_type_id', $formData['activity_type_id'] ?? '') == $activityType->id ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label fs-6" for="activity_{{ $activityType->id }}">
                                                {{ $activityType->getName('ru') }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('activity_type_id')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Korxona nomi -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Корхона/ташкилот номи.
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                    name="company_name" value="{{ old('company_name', $formData['company_name'] ?? '') }}"
                                    placeholder="Мой ответ" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Xodimlar soni -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Корхона/ташкилотда жами нечта ходим мехнат қилади? (Жавобни сонда киритинг.)
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('employee_count') is-invalid @enderror"
                                    name="employee_count"
                                    value="{{ old('employee_count', $formData['employee_count'] ?? '') }}"
                                    placeholder="Мой ответ" min="1" max="100000" required>
                                @error('employee_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Xodimlar soni o'zgarishi - Radio buttons (vertical list) -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Охирги йилга нисбатан ходимлар сони қандай ўзгарди?
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="ps-2">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="headcount_change"
                                            id="oshdi" value="oshdi"
                                            {{ old('headcount_change', $formData['headcount_change'] ?? '') == 'oshdi' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label fs-6" for="oshdi">
                                            Ошди
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="headcount_change"
                                            id="ozgarmadi" value="ozgarmadi"
                                            {{ old('headcount_change', $formData['headcount_change'] ?? '') == 'ozgarmadi' ? 'checked' : '' }}>
                                        <label class="form-check-label fs-6" for="ozgarmadi">
                                            Ўзгармади
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="headcount_change"
                                            id="kamaydi" value="kamaydi"
                                            {{ old('headcount_change', $formData['headcount_change'] ?? '') == 'kamaydi' ? 'checked' : '' }}>
                                        <label class="form-check-label fs-6" for="kamaydi">
                                            Камайди
                                        </label>
                                    </div>
                                </div>
                                @error('headcount_change')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 6 oylik prognoz - Radio buttons (vertical list) -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Сизнингча кейинги 6 ойда ходимлар сони қандай ўзгаради?
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="ps-2">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="headcount_six_change"
                                            id="six_oshadi" value="oshdi"
                                            {{ old('headcount_six_change', $formData['headcount_six_change'] ?? '') == 'oshdi' ? 'checked' : '' }}
                                            required>
                                        <label class="form-check-label fs-6" for="six_oshadi">
                                            Ошади
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="headcount_six_change"
                                            id="six_ozgarmaydi" value="ozgarmadi"
                                            {{ old('headcount_six_change', $formData['headcount_six_change'] ?? '') == 'ozgarmadi' ? 'checked' : '' }}>
                                        <label class="form-check-label fs-6" for="six_ozgarmaydi">
                                            Ўзгармайди
                                        </label>
                                    </div>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="headcount_six_change"
                                            id="six_kamayadi" value="kamaydi"
                                            {{ old('headcount_six_change', $formData['headcount_six_change'] ?? '') == 'kamaydi' ? 'checked' : '' }}>
                                        <label class="form-check-label fs-6" for="six_kamayadi">
                                            Камаяди
                                        </label>
                                    </div>
                                </div>
                                @error('headcount_six_change')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Navigation buttons -->
                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div class="text-danger small">* Мажбурий савол</div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary" id="nextBtn">
                                        Кейинги
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Region radio button o'zgarganda districts yuklanishi
            $('.region-radio').change(function() {
                var regionId = $(this).val();
                var $districtSection = $('#district-section');
                var $districtSelect = $('#district_id');

                if (regionId) {
                    $districtSection.show();
                    $districtSelect.html('<option value="">Юкланмокда...</option>');

                    $.get('{{ route('survey.districts') }}', {
                            region_id: regionId
                        })
                        .done(function(data) {
                            var options = '<option value="">Туманни танланг</option>';
                            $.each(data, function(i, district) {
                                options += '<option value="' + district.id + '">' + district
                                    .name_ru + '</option>';
                            });
                            $districtSelect.html(options);

                            // Agar old value bo'lsa, tanlash
                            var initialDistrictId =
                                '{{ old('district_id', $formData['district_id'] ?? '') }}';
                            if (initialDistrictId) {
                                $districtSelect.val(initialDistrictId);
                            }
                        })
                        .fail(function() {
                            $districtSelect.html('<option value="">Хатолик юз берди</option>');
                            alert('Tumanlarni yuklashda xatolik yuz berdi');
                        });
                } else {
                    $districtSection.hide();
                }
            });

            // Sahifa yuklanganda region tanlangan bo'lsa
            var initialRegionId = $('input[name="region_id"]:checked').val();
            if (initialRegionId) {
                $('.region-radio:checked').trigger('change');
            }

            // Form validation
            $('#step1Form').on('submit', function(e) {
                var $btn = $('#nextBtn');
                $btn.prop('disabled', true).html('Сохранение...');
            });
        });
    </script>

    <style>
        /* Google Forms style adjustments */
        .form-check-input:checked {
            background-color: #01374f;
            border-color: #01374f;
        }

        .form-check-input:focus {
            border-color: #01374f;
            box-shadow: 0 0 0 0.25rem rgba(103, 58, 183, 0.25);
        }

        .form-check-label {
            cursor: pointer;
            line-height: 1.4;
        }

        .form-label.h5 {
            font-weight: 700;
            color: #202124;
            font-size: 17px;
        }

        .btn-primary {
            background-color: #01374f;
            border-color: #01374f;
        }

        .btn-primary:hover {
            background-color: #005072;
            border-color: #005072;
        }

        .text-danger {
            color: #d93025 !important;
        }
    </style>
@endsection
