@extends('layouts.app')

@section('title', 'Bosh sahifa')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="display-4 mb-3">
                    <i class="fas fa-chart-line text-primary"></i>
                </div>
                <h1 class="display-5 fw-bold mb-3">Mehnat Bozori Talabini Aniqlash</h1>
                <p class="lead text-muted">
                    Vazirlar Mahkamasi huzuridagi Makroiqtisodiy tadqiqotlar instituti tomonidan 
                    o'tkaziladigan korxonalar orasida so'rovnoma
                </p>
            </div>

            <div class="card">
                <div class="card-body p-5">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="mb-3">So'rovnomaning maqsadi:</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Bugun yetishmayotgan kadrlarni aniqlash
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Kelajakda talab oshadigan kadrlarni prognoz qilish
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Xodimlar soni o'zgarishini kuzatish
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Ta'lim siyosatini shakllantirishga yordam berish
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-center">
                            <h4 class="mb-3">So'rovnomani boshlash</h4>
                            <a href="{{ route('survey.step1') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-clipboard-list me-2"></i>
                                So'rovnomani boshlash
                            </a>
                            <p class="text-muted mt-3">
                                So'rovnomani to'ldirish 5-10 daqiqa vaqt oladi
                            </p>
                            <p class="small text-muted">
                                Hech qanday ro'yxatdan o'tish yoki kirish talab etilmaydi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection