@extends('layouts.client')
@section('title', 'Liên Hệ')
@section('breadcrumb', 'Liên Hệ')
@section('content')

<!-- Contact Section Begin -->
<section class="contact spad">
    <div class="container">
        <div class="row">
            <!-- Contact Form Column -->
            <div class="col-lg-8 col-md-8">
                <div class="contact-form">
                    <div class="contact__form__title text-left" style="margin-bottom: 25px;">
                        <h2>Để lại tin nhắn</h2>
                    </div>
                    
                    <form action="{{ route('contact.post') }}" method="POST" id="contactForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <input type="text" name="name" placeholder="Họ và tên của bạn" required>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                 <input type="text" name="phone" placeholder="Số điện thoại" required>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <input type="email" name="email" placeholder="Email của bạn" required>
                            </div>
                            <div class="col-lg-12 text-center">
                                <textarea name="message" placeholder="Nội dung tin nhắn" required></textarea>
                                <button type="submit" class="site-btn">GỬI TIN NHẮN</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Info Column -->
            <div class="col-lg-4 col-md-4">
                <div class="contact__form__title text-left" style="margin-bottom: 25px;">
                    <h2>Thông tin liên hệ</h2>
                </div>
                <div style="margin-bottom: 30px;">
                    <p style="font-size: 16px; color: #666; margin-bottom: 20px;">Chào mừng bạn đến với Xanh Organic. Hãy liên hệ với chúng tôi nếu bạn cần bất kỳ sự trợ giúp nào.</p>
                    <div style="margin-bottom: 15px;">
                        <span class="icon_phone"></span>
                        <span style="font-size: 16px; color: #333;">Điện thoại: {{ config('app.phone', '0123 456 789') }}</span>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <span class="icon_mail_alt"></span>
                        <span style="font-size: 16px; color: #333;">Email: {{ config('app.email', 'organic@xanhorganic.com') }}</span>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <span class="icon_pin_alt"></span>
                        <span style="font-size: 16px; color: #333;">Địa chỉ: {{ config('app.address', '123 Đường Xanh, Q.1, TP.HCM') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Contact Section End -->

<!-- Map Begin -->
<div class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.4943078086524!2d106.69746731533459!3d10.77264926226956!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f4b3330bcc7%3A0xc1fd2781577bff14!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBLaG9hIEjhu41jIFThu7Luong4TCBUUi4gSMOU!5e0!3m2!1svi!2s!4v1234567890123!5m2!1svi!2s" 
            height="500" style="border:0;" allowfullscreen="50%" loading="lazy"></iframe>
</div>
<!-- Map End -->

@endsection


