@extends('layouts.frontend')

@section('title', 'ติดต่อเรา')
@section('description', 'ติดต่อทีมพัฒนาเพื่อสอบถามข้อมูลหรือขอความช่วยเหลือ')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
    <div class="container mt-3">
        <div class="frontend-alert frontend-alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="frontend-alert frontend-alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif

<!-- Hero Section -->
<section class="frontend-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">ติดต่อเรา</h1>
                <p class="lead mb-4">
                    เราพร้อมให้บริการและตอบคำถามทุกข้อสงสัย
                    ติดต่อเราได้หลายช่องทาง
                </p>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://placehold.co/500x300/667eea/ffffff?text=Contact+Us" 
                     class="img-fluid rounded-3 shadow-lg" alt="Contact Us">
            </div>
        </div>
    </div>
</section>

<!-- Contact Form & Info -->
<section class="frontend-section">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="card frontend-card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-envelope me-2"></i>ส่งข้อความถึงเรา</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('contact.send') }}" method="POST" class="frontend-contact-form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">ชื่อ-นามสกุล *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">อีเมล *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">หัวข้อ *</label>
                                    <select class="form-select @error('subject') is-invalid @enderror" 
                                            id="subject" name="subject" required>
                                        <option value="">เลือกหัวข้อ</option>
                                        <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>คำถามทั่วไป</option>
                                        <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>ขอความช่วยเหลือ</option>
                                        <option value="sales" {{ old('subject') == 'sales' ? 'selected' : '' }}>สอบถามราคา</option>
                                        <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>ปัญหาทางเทคนิค</option>
                                        <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>อื่นๆ</option>
                                    </select>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <label for="message" class="form-label">ข้อความ *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12">
                                    <button type="submit" class="frontend-btn-primary btn-lg">
                                        <i class="fas fa-paper-plane"></i>
                                        ส่งข้อความ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="card h-100 frontend-card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>ข้อมูลติดต่อ</h4>
                    </div>
                    <div class="card-body p-4 frontend-contact-info">
                        <div class="mb-4">
                            <h6><i class="fas fa-map-marker-alt text-primary me-2"></i>ที่อยู่</h6>
                            <p class="text-muted mb-0">
                                123 ถนนสุขุมวิท<br>
                                แขวงคลองตัน เขตวัฒนา<br>
                                กรุงเทพมหานคร 10110
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h6><i class="fas fa-phone text-primary me-2"></i>โทรศัพท์</h6>
                            <p class="text-muted mb-0">
                                <a href="tel:+6621234567" class="text-decoration-none">+66 2-123-4567</a><br>
                                <a href="tel:+66812345678" class="text-decoration-none">+66 81-234-5678</a>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h6><i class="fas fa-envelope text-primary me-2"></i>อีเมล</h6>
                            <p class="text-muted mb-0">
                                <a href="mailto:info@example.com" class="text-decoration-none">info@example.com</a><br>
                                <a href="mailto:support@example.com" class="text-decoration-none">support@example.com</a>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h6><i class="fas fa-clock text-primary me-2"></i>เวลาทำการ</h6>
                            <p class="text-muted mb-0">
                                จันทร์ - ศุกร์: 08:00 - 18:00<br>
                                เสาร์: 09:00 - 17:00<br>
                                อาทิตย์: ปิดทำการ
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <h6><i class="fas fa-share-alt text-primary me-2"></i>ติดตามเรา</h6>
                            <div class="social-links">
                                <a href="#" class="frontend-social-btn facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="#" class="frontend-social-btn twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="frontend-social-btn instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="frontend-social-btn linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="frontend-section-alt">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="h1 mb-3">คำถามที่พบบ่อย</h2>
                <p class="lead text-muted">คำตอบสำหรับคำถามที่ลูกค้าสอบถามบ่อย</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="accordion frontend-accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ระบบมีความปลอดภัยแค่ไหน?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                ระบบของเราใช้เทคโนโลยีการเข้ารหัสระดับสูง และมีการสำรองข้อมูลอัตโนมัติ
                                เพื่อความปลอดภัยของข้อมูลลูกค้า
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                สามารถใช้งานบนมือถือได้หรือไม่?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                ได้ครับ ระบบของเราออกแบบให้รองรับการใช้งานบนทุกอุปกรณ์
                                รวมถึงสมาร์ทโฟนและแท็บเล็ต
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                มีการสนับสนุนลูกค้าหรือไม่?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                เรามีทีมสนับสนุนลูกค้าที่พร้อมให้บริการตลอด 24 ชั่วโมง
                                ตอบคำถามและแก้ไขปัญหาอย่างรวดเร็ว
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                ราคาแพ็คเกจเป็นอย่างไร?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                เรามีแพ็คเกจที่หลากหลาย เหมาะสำหรับทุกขนาดธุรกิจ
                                ติดต่อเราเพื่อขอข้อมูลราคาที่เหมาะสมกับความต้องการ
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        SwalHelper.error('กรุณากรอกข้อมูลให้ครบถ้วน');
    }
});

// Phone number formatting
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        if (value.startsWith('0')) {
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        } else if (value.startsWith('66')) {
            value = value.replace(/(\d{2})(\d{2})(\d{3})(\d{4})/, '+$1 $2-$3-$4');
        }
    }
    e.target.value = value;
});
</script>
@endpush
