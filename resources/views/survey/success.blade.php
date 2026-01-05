@extends('layouts.app')

@section('title', 'So\'rovnoma muvaffaqiyatli yuborildi')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h2 class="text-success mb-3">Ishtirokingiz uchun rahmat!</h2>
                    <p class="lead mb-4">
                        So'rovnoma muvaffaqiyatli yuborildi va saqlandi.
                    </p>
                    
                    @if($latestResponseData)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">So'rovnoma ma'lumotlari</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <p><strong>Korxona:</strong><br>{{ $latestResponseData['company_name'] }}</p>
                                        <p><strong>Joylashuv:</strong><br>{{ $latestResponseData['region_name'] }}, {{ $latestResponseData['district_name'] }}</p>
                                        <p><strong>Faoliyat turi:</strong><br>{{ $latestResponseData['activity_type_name'] }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Xodimlar soni:</strong> {{ number_format($latestResponseData['employee_count']) }} kishi</p>
                                        <p><strong>Yuborilgan sana:</strong><br>{{ $latestResponseData['created_at']->format('d.m.Y H:i') }}</p>
                                        <p><strong>Davr:</strong> {{ $latestResponseData['period_text'] }}</p>
                                    </div>
                                </div>
                                
                                {{-- @if($latestResponseData['missing_skills_count'] > 0)
                                    <div class="mb-3">
                                        <h6 class="text-danger">Yetishmayotgan kadrlar: {{ $latestResponseData['missing_skills_count'] }} ta</h6>
                                    </div>
                                @endif
                                
                                @if($latestResponseData['future_demand_skills_count'] > 0)
                                    <div class="mb-3">
                                        <h6 class="text-primary">Kelajakda talab oshadigan kadrlar: {{ $latestResponseData['future_demand_skills_count'] }} ta</h6>
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    @endif
                    
                    
                    
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('home') }}" class="btn" style="background-color: #01374f; color: white;">
                            <i class="fas fa-home me-2"></i>Bosh sahifa
                        </a>
                        {{-- <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-bar me-2"></i>Natijalarni ko'rish
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection