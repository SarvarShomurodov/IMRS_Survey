{{-- resources/views/survey/step3.blade.php --}}
@extends('layouts.app')

@section('title', 'Сўровнома - 3-босқич')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">
                            <b>Иш берувчилар орасида сўров - Қўшимча маълумотлар</b>
                        </h3>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('survey.step3.process') }}" id="step3Form">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Етишмаётган кадрлар учун саволлар -->
                            @if (!empty($missingSkills) && $missingSkills->count() > 0)
                                <div class="mb-5">
                                    <h4 style="color: #174A7E" class="mb-4">
                                        <i class="fas fa-user-times me-2"></i>
                                        Бугунги кунда етишмаётган кадрлар
                                    </h4>

                                    @foreach ($missingSkills as $index => $skill)
                                        <div class="card mb-4 border-start border-4"
                                            style="border-color: #174A7E !important;">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0" style="color: #174A7E !important;">
                                                    {{ $skill->getName() }}
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- Таълим даражаси -->
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold-my">
                                                            Ушбу касб учун талаб қилинган <b>минимал таълим даражасини</b>
                                                            белгиланг. *
                                                        </label>
                                                        <select class="form-select"
                                                            name="missing_skill_{{ $skill->id }}_education" required>
                                                            <option value="">Танланг...</option>
                                                            <option value="ahmiyati_yok"
                                                                {{ old("missing_skill_{$skill->id}_education", $formData["missing_skill_{$skill->id}_education"] ?? '') == 'ahmiyati_yok' ? 'selected' : '' }}>
                                                                Аҳамияти йўқ
                                                            </option>
                                                            <option value="orta"
                                                                {{ old("missing_skill_{$skill->id}_education", $formData["missing_skill_{$skill->id}_education"] ?? '') == 'orta' ? 'selected' : '' }}>
                                                                Ўрта (11 йиллик таълим)
                                                            </option>
                                                            <option value="umumiy_orta"
                                                                {{ old("missing_skill_{$skill->id}_education", $formData["missing_skill_{$skill->id}_education"] ?? '') == 'umumiy_orta' ? 'selected' : '' }}>
                                                                Ўрта махсус / профессионал коллеж (техникум, касб-ҳунар)
                                                            </option>
                                                            <option value="oliy"
                                                                {{ old("missing_skill_{$skill->id}_education", $formData["missing_skill_{$skill->id}_education"] ?? '') == 'oliy' ? 'selected' : '' }}>
                                                                Олий (бакалавр / магистр)
                                                            </option>
                                                            <option value="phd"
                                                                {{ old("missing_skill_{$skill->id}_education", $formData["missing_skill_{$skill->id}_education"] ?? '') == 'phd' ? 'selected' : '' }}>
                                                                Олий илмий даража (PhD/ DcS)
                                                        </select>
                                                    </div>

                                                    <!-- Иш тажрибаси -->
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold-my">
                                                            Ушбу касб учун талаб қилинган <b>минимал иш тажрибасини</b>
                                                            белгиланг. *
                                                        </label>
                                                        <select class="form-select"
                                                            name="missing_skill_{{ $skill->id }}_experience" required>
                                                            <option value="">Танланг...</option>
                                                            <option value="0"
                                                                {{ old("missing_skill_{$skill->id}_experience", $formData["missing_skill_{$skill->id}_experience"] ?? '') == '0' ? 'selected' : '' }}>
                                                                Тажриба талаб қилинмайди
                                                            </option>
                                                            <option value="0-1"
                                                                {{ old("missing_skill_{$skill->id}_experience", $formData["missing_skill_{$skill->id}_experience"] ?? '') == '0-1' ? 'selected' : '' }}>
                                                                1 йил ёки ундан кам
                                                            </option>
                                                            <option value="1-2"
                                                                {{ old("missing_skill_{$skill->id}_experience", $formData["missing_skill_{$skill->id}_experience"] ?? '') == '1-2' ? 'selected' : '' }}>
                                                                1 - 2 йил
                                                            </option>
                                                            <option value="3-5"
                                                                {{ old("missing_skill_{$skill->id}_experience", $formData["missing_skill_{$skill->id}_experience"] ?? '') == '3-5' ? 'selected' : '' }}>
                                                                3 - 5 йил
                                                            </option>
                                                            <option value="6-9"
                                                                {{ old("missing_skill_{$skill->id}_experience", $formData["missing_skill_{$skill->id}_experience"] ?? '') == '6-9' ? 'selected' : '' }}>
                                                                6 - 9 йил
                                                            </option>
                                                            <option value="10+"
                                                                {{ old("missing_skill_{$skill->id}_experience", $formData["missing_skill_{$skill->id}_experience"] ?? '') == '10+' ? 'selected' : '' }}>
                                                                10 йилдан ортиқ
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <!-- Жинс -->
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold-my">
                                                            Ушбу касб учун <b>жинс бўйича талаб</b> (агар мавжуд бўлса). *
                                                        </label>
                                                        <select class="form-select"
                                                            name="missing_skill_{{ $skill->id }}_gender" required>
                                                            <option value="">Танланг...</option>
                                                            <option value="erkak"
                                                                {{ old("missing_skill_{$skill->id}_gender", $formData["missing_skill_{$skill->id}_gender"] ?? '') == 'erkak' ? 'selected' : '' }}>
                                                                Эркак
                                                            </option>
                                                            <option value="ayol"
                                                                {{ old("missing_skill_{$skill->id}_gender", $formData["missing_skill_{$skill->id}_gender"] ?? '') == 'ayol' ? 'selected' : '' }}>
                                                                Аёл
                                                            </option>
                                                            <option value="farq_qilmaydi"
                                                                {{ old("missing_skill_{$skill->id}_gender", $formData["missing_skill_{$skill->id}_gender"] ?? '') == 'farq_qilmaydi' ? 'selected' : '' }}>
                                                                Аҳамияти йўқ
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <!-- AJRATUVCHI - faqat ikkala bo'lim ham mavjud bo'lsa ko'rsatiladi -->
                            @if (
                                !empty($missingSkills) &&
                                    $missingSkills->count() > 0 &&
                                    !empty($futureDemandSkills) &&
                                    $futureDemandSkills->count() > 0)
                                <div class="text-center my-5">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <hr class="flex-grow-1" style="border-color: #dee2e6;">
                                        <span class="mx-3 text-muted">
                                            <i class="fas fa-chevron-down fa-lg"></i>
                                        </span>
                                        <hr class="flex-grow-1" style="border-color: #dee2e6;">
                                    </div>
                                </div>
                            @endif
                            <!-- Келажакда талаб ошадиган кадрлар учун саволлар -->
                            @if (!empty($futureDemandSkills) && $futureDemandSkills->count() > 0)
                                <div class="mb-5">
                                    <h4 class="mb-4" style="color: #196765">
                                        <i class="fas fa-chart-line me-2"></i>
                                        Келажакда талаб ошадиган кадрлар
                                    </h4>

                                    @foreach ($futureDemandSkills as $index => $skill)
                                        <div class="card mb-4 border-start border-4"
                                            style="border-color: #196765 !important;">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0" style="color: #196765 !important;">
                                                    {{ $skill->getName() }}
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- Таълим даражаси -->
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold-my">
                                                            Ушбу касб учун талаб қилинган <b>минимал таълим даражасини</b>
                                                            белгиланг. *
                                                        </label>
                                                        <select class="form-select"
                                                            name="future_skill_{{ $skill->id }}_education" required>
                                                            <option value="">Танланг...</option>
                                                            <option value="ahmiyati_yok"
                                                                {{ old("future_skill_{$skill->id}_education", $formData["future_skill_{$skill->id}_education"] ?? '') == 'ahmiyati_yok' ? 'selected' : '' }}>
                                                                Аҳамияти йўқ
                                                            </option>
                                                            <option value="orta"
                                                                {{ old("future_skill_{$skill->id}_education", $formData["future_skill_{$skill->id}_education"] ?? '') == 'orta' ? 'selected' : '' }}>
                                                                Ўрта (11 йиллик таълим)
                                                            </option>
                                                            <option value="umumiy_orta"
                                                                {{ old("future_skill_{$skill->id}_education", $formData["future_skill_{$skill->id}_education"] ?? '') == 'umumiy_orta' ? 'selected' : '' }}>
                                                                Ўрта махсус / профессионал коллеж (техникум, касб-ҳунар)
                                                            </option>
                                                            <option value="oliy"
                                                                {{ old("future_skill_{$skill->id}_education", $formData["future_skill_{$skill->id}_education"] ?? '') == 'oliy' ? 'selected' : '' }}>
                                                                Олий (бакалавр / магистр)
                                                            </option>
                                                            <option value="phd"
                                                                {{ old("future_skill_{$skill->id}_education", $formData["future_skill_{$skill->id}_education"] ?? '') == 'phd' ? 'selected' : '' }}>
                                                                Олий илмий даража (PhD/ DcS)
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <!-- Иш тажрибаси -->
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold-my">
                                                            Ушбу касб учун талаб қилинган <b>минимал иш тажрибасини</b>
                                                            белгиланг. *
                                                        </label>
                                                        <select class="form-select"
                                                            name="future_skill_{{ $skill->id }}_experience" required>
                                                            <option value="">Танланг...</option>
                                                            <option value="0"
                                                                {{ old("future_skill_{$skill->id}_experience", $formData["future_skill_{$skill->id}_experience"] ?? '') == '0' ? 'selected' : '' }}>
                                                                Тажриба талаб қилинмайди
                                                            </option>
                                                            <option value="0-1"
                                                                {{ old("future_skill_{$skill->id}_experience", $formData["future_skill_{$skill->id}_experience"] ?? '') == '0-1' ? 'selected' : '' }}>
                                                                1 йил ёки ундан кам
                                                            </option>
                                                            <option value="1-2"
                                                                {{ old("future_skill_{$skill->id}_experience", $formData["future_skill_{$skill->id}_experience"] ?? '') == '1-2' ? 'selected' : '' }}>
                                                                1-2 йил
                                                            </option>
                                                            <option value="3-5"
                                                                {{ old("future_skill_{$skill->id}_experience", $formData["future_skill_{$skill->id}_experience"] ?? '') == '3-5' ? 'selected' : '' }}>
                                                                3-5 йил
                                                            </option>
                                                            <option value="6-9"
                                                                {{ old("future_skill_{$skill->id}_experience", $formData["future_skill_{$skill->id}_experience"] ?? '') == '6-9' ? 'selected' : '' }}>
                                                                6-9 йил
                                                            </option>
                                                            <option value="10+"
                                                                {{ old("future_skill_{$skill->id}_experience", $formData["future_skill_{$skill->id}_experience"] ?? '') == '10+' ? 'selected' : '' }}>
                                                                10 йилдан ортиқ
                                                            </option>
                                                        </select>
                                                    </div>

                                                    <!-- Жинс -->
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label fw-bold-my">
                                                            Ушбу касб учун <b>жинс бўйича талаб</b> (агар мавжуд бўлса). *
                                                        </label>
                                                        <select class="form-select"
                                                            name="future_skill_{{ $skill->id }}_gender" required>
                                                            <option value="">Танланг...</option>
                                                            <option value="erkak"
                                                                {{ old("future_skill_{$skill->id}_gender", $formData["future_skill_{$skill->id}_gender"] ?? '') == 'erkak' ? 'selected' : '' }}>
                                                                Эркак
                                                            </option>
                                                            <option value="ayol"
                                                                {{ old("future_skill_{$skill->id}_gender", $formData["future_skill_{$skill->id}_gender"] ?? '') == 'ayol' ? 'selected' : '' }}>
                                                                Аёл
                                                            </option>
                                                            <option value="farq_qilmaydi"
                                                                {{ old("future_skill_{$skill->id}_gender", $formData["future_skill_{$skill->id}_gender"] ?? '') == 'farq_qilmaydi' ? 'selected' : '' }}>
                                                                Аҳамияти йўқ
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Эслатма:</strong> Барча саволларга жавоб бериш мажбурий. Бу маълумотлар меҳнат
                                бозорини таҳлил қилиш учун ишлатилади.
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div class="text-danger small">* Мажбурий саволлар</div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('survey.step2') }}" class="btn btn-outline-secondary">
                                        Орқага
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i>Сўровномани юбориш
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
            // Form yuborish
            $('#step3Form').on('submit', function(e) {
                // Tasdiqlash dialogi
                if (!confirm(
                        'Маълумотларни юборишга аминмисиз? Юборилган маълумотларни кейинчалик ўзгартириб бўлмайди.'
                        )) {
                    e.preventDefault();
                    return false;
                }

                var $btn = $('#submitBtn');
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Юборилмоқда...'
                );

                // Kutish ogohlantirishi
                setTimeout(function() {
                    if ($btn.prop('disabled')) {
                        alert('Жараён узоқ вақт олмоқда. Илтимос, кутиб туринг...');
                    }
                }, 8000); // 8 секунд
            });

            // Barcha required fieldlarni tekshirish
            function validateForm() {
                var isValid = true;
                $('select[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                return isValid;
            }

            // Real-time validation
            $('select[required]').on('change', function() {
                if ($(this).val()) {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(this).removeClass('is-valid').addClass('is-invalid');
                }
            });
        });
    </script>

    <style>
        .form-label.fw-bold-my {
            color: #202124;
            font-size: 16px;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .btn-primary {
            background-color: #01374f;
            border-color: #01374f;
        }

        .btn-primary:hover {
            background-color: #005172;
            border-color: #005172;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .form-select:focus {
            border-color: #005172;
            box-shadow: 0 0 0 0.25rem rgba(0, 81, 114, 0.25);
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .is-valid {
            border-color: #28a745;
        }

        .card-header h5 {
            font-weight: 600;
        }
    </style>
@endsection
