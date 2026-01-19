{{-- resources/views/survey/step2.blade.php --}}
@extends('layouts.app')

@section('title', 'Сўровнома - 2-босқич')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">
                            <b>Иш берувчилар орасида сўров</b>
                        </h3>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('survey.step2.process') }}" id="step2Form">
                            @csrf

                            @if ($errors->has('skills'))
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ $errors->first('skills') }}
                                </div>
                            @endif

                            <!-- Бугун етишмаётган кадрлар -->
                            <div class="mb-4">
                                <label class="form-label h5 mb-3">
                                    Бугунги кунда корхонангизда қайси кадрлар етишмайди?
                                    <span class="text-muted fst-italic">(Биттадан кўп жавобни танлаш мумкин.)</span>
                                </label>
                                <select class="form-control" name="missing_skills[]" id="missing_skills" multiple>
                                    {{-- Options will be loaded dynamically --}}
                                </select>
                            </div>

                            <!-- Келажакда талаб ошадиган кадрлар -->
                            <div class="mb-4" id="future_skills_section" style="display: none;">
                                <label class="form-label h5 mb-3">
                                    Кейинги 6 ойда қайси кадрларга талаб ошиши кутилмоқда?
                                    <span class="text-muted fst-italic">(Биттадан кўп жавобни танлаш мумкин.)</span>
                                </label>
                                <select class="form-control" name="future_demand_skills[]" id="future_demand_skills"
                                    multiple>
                                    {{-- Options will be loaded dynamically --}}
                                </select>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Эслатма:</strong> Ҳеч бўлмаганда битта кадр турини танлашингиз шарт.
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5">
                                <div class="text-danger small">* Мажбурий савол</div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('survey.step1') }}" class="btn btn-outline-secondary">
                                        Орқага
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="nextBtn" style="display: none;">
                                        <i class="fas fa-arrow-right me-2"></i>Кейинги босқич
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
            // Select2 конфигурацияси
            function initializeSkillSelect(selector, placeholder) {
                // Avval mavjud select2'ni destroy qilish
                if ($(selector).hasClass("select2-hidden-accessible")) {
                    $(selector).select2('destroy');
                }

                // Barcha option'larni tozalash
                $(selector).empty();

                $(selector).select2({
                    theme: 'bootstrap-5',
                    placeholder: placeholder,
                    allowClear: true,
                    multiple: true,
                    minimumInputLength: 2,
                    ajax: {
                        url: '/api/skills/search',
                        delay: 300,
                        data: function(params) {
                            return {
                                q: params.term,
                                limit: 20
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    templateResult: function(skill) {
                        if (skill.loading) {
                            return skill.text;
                        }

                        var $result = $(
                            '<div class="select2-result-skill">' +
                            '<div class="skill-name">' + skill.name + '</div>' +
                            '<div class="skill-meta text-muted small">' +
                            '</span>' +
                            '</div>' +
                            '</div>'
                        );
                        return $result;
                    },
                    templateSelection: function(skill) {
                        return skill.name || skill.text;
                    }
                });
            }

            // Initialize missing skills select
            initializeSkillSelect('#missing_skills', 'Етишмаётган кадрларни қидиринг...');

            // Initialize future demand skills select
            initializeSkillSelect('#future_demand_skills', 'Келажакда талаб ошадиган кадрларни қидиринг...');
            $('#future_skills_section').show();

            // Агар эски қийматлар мавжуд бўлса, уларни юклаш
            function loadExistingSelections() {
                var missingSkills = @json(old('missing_skills', $formData['missing_skills'] ?? []));
                var futureDemandSkills = @json(old('future_demand_skills', $formData['future_demand_skills'] ?? []));

                // Missing skills yuklash
                if (missingSkills && missingSkills.length > 0) {
                    $.post('/api/skills/by-ids', {
                            ids: missingSkills,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        })
                        .done(function(skills) {
                            // Avval select'ni tozalash
                            $('#missing_skills').empty();

                            // Yangi option'larni qo'shish
                            $.each(skills, function(i, skill) {
                                var newOption = new Option(skill.text, skill.id, true, true);
                                $('#missing_skills').append(newOption);
                            });

                            // Select2'ni yangilash
                            $('#missing_skills').trigger('change');
                        })
                        .fail(function() {
                            console.log('Missing skills yuklashda xatolik');
                        });
                }

                // Future demand skills yuklash
                if (futureDemandSkills && futureDemandSkills.length > 0) {
                    $.post('/api/skills/by-ids', {
                            ids: futureDemandSkills,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        })
                        .done(function(skills) {
                            // Avval select'ni tozalash
                            $('#future_demand_skills').empty();

                            // Yangi option'larni qo'shish
                            $.each(skills, function(i, skill) {
                                var newOption = new Option(skill.text, skill.id, true, true);
                                $('#future_demand_skills').append(newOption);
                            });

                            // Select2'ni yangilash
                            $('#future_demand_skills').trigger('change');
                        })
                        .fail(function() {
                            console.log('Future demand skills yuklashda xatolik');
                        });
                }

                // Dastlabki tekshiriш
                setTimeout(function() {
                    checkIfCanProceed();
                }, 500);
            }

            // Event listener'larni qo'shish
            $('#missing_skills').on('change', function() {
                checkIfCanProceed();
            });

            $('#future_demand_skills').on('change', function() {
                checkIfCanProceed();
            });

            // Keyingi bosqich tugmasini ko'rsatish/yashirish
            function checkIfCanProceed() {
                var missingSkills = $('#missing_skills').val();
                var futureSkills = $('#future_demand_skills').val();

                // IKKALA select ham to'ldirilgan bo'lishi kerak
                var hasBothSkills = (missingSkills && missingSkills.length > 0) &&
                    (futureSkills && futureSkills.length > 0);

                if (hasBothSkills) {
                    $('#nextBtn').show();
                } else {
                    $('#nextBtn').hide();
                }
            }

            // Form submission validation
            $('#step2Form').on('submit', function(e) {
                var missingSkills = $('#missing_skills').val();
                var futureDemandSkills = $('#future_demand_skills').val();

                if ((!missingSkills || missingSkills.length === 0) &&
                    (!futureDemandSkills || futureDemandSkills.length === 0)) {
                    e.preventDefault();
                    alert('Ҳеч бўлмаганда битта кадр турини танлашингиз шарт!');
                    return false;
                }

                var $btn = $('#nextBtn');
                $btn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Юборилмоқда...');
            });

            // Ma'lumotlarni yuklash
            loadExistingSelections();
        });
    </script>

<style>
    .select2-result-skill {
        padding: 4px 0;
    }

    .skill-name {
        font-weight: 500;
        color: #333;
    }

    .skill-meta {
        margin-top: 2px;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple {
        min-height: 45px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        background-color: #005172 !important;
        border-color: #005172 !important;
        color: white !important;
        font-size: 0.875rem;
        margin: 2px;
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid #005172 !important;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection--multiple {
        border-color: #005172;
        box-shadow: 0 0 0 0.25rem rgba(0, 81, 114, 0.25);
    }

    .form-label.h5 {
        font-weight: 700;
        color: #202124;
        font-size: 17px;
    }

    .form-label span {
        font-style: italic;
        font-weight: normal;
        color: #6c757d;
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
        color: #d93025 !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        padding: 4px 8px;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__placeholder {
        color: #6c757d;
    }

    /* Bootstrap SVG ni bekor qilish va oq X qo'yish */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove {
        background: none !important;
        text-indent: 0 !important;
        overflow: visible !important;
        width: auto !important;
        height: auto !important;
        padding: 0 4px !important;
        margin-left: -7px !important;
        border: none !important;
        color: white !important;
        font-size: 18px !important;
        font-weight: bold !important;
        line-height: 1 !important;
        cursor: pointer !important;
        display: inline-block !important;
    }

    /* X belgisini ko'rsatish */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove span {
        color: white !important;
        font-size: 25px !important;
        font-weight: bold !important;
        display: inline !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Hover effekt */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove:hover {
        background-color: rgba(255, 255, 255, 0.2) !important;
        border-radius: 3px !important;
        color: white !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove:hover span {
        color: white !important;
    }

    /* Alternativ: Agar yuqoridagi ishlamasa, oq SVG bilan almashtirish */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove.use-white-svg {
        background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23ffffff'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") 50% / .75rem auto no-repeat !important;
        width: .75rem !important;
        height: .75rem !important;
        padding: .25em !important;
        text-indent: 100% !important;
        overflow: hidden !important;
    }
</style>
@endsection
