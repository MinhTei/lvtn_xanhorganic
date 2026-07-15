@extends('layouts.client')

@section('title', 'Giới thiệu')
@section('breadcrumb', 'Giới thiệu')

@section('content')



<!-- Story Section Begin -->
<section class="about-story spad">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="about-story__pic">
                    <img src="{{ asset('assets/clients/img/about/about.png') }}" alt="Giới thiệu Xanh Organic">
                </div>
            </div>
            <div class="col-lg-7 col-md-6">
                <div class="about-story__text">
                    <span class="sub-title">CÂU CHUYỆN CỦA CHÚNG TÔI</span>
                    <h2>Từ mảnh đất nhỏ đến bàn ăn của triệu gia đình</h2>
                    <p>Xanh Organic ra đời năm 2019 từ một ước mơ giản dị: mang những bó rau sạch, những quả cà chua đỏ mọng được trồng bằng đôi tay và tâm huyết của nông dân Việt đến với từng bữa cơm gia đình.</p>
                    <p>Người sáng lập - vốn là một kỹ sư nông nghiệp - nhận ra rào cản từ việc lạm dụng hóa chất trong canh tác. Xanh Organic bắt đầu chuỗi kết nối đó.</p>
                    <div class="quote-box">
                        <p>"Chúng tôi không chỉ bán rau — chúng tôi chuyển tải sự tận hưởng từ đất đai đến bữa ăn của bạn."</p>
                    </div>
                    <p>Sau 5 năm, chúng tôi đã hợp tác với hơn 50 nông trại đạt chuẩn VietGAP và hữu cơ quốc tế, phục vụ hơn 10.000 khách hàng tại TP.HCM và các tỉnh lân cận.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Story Section End -->


<!-- Core Values Section Begin -->
<section class="core-values spad">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-12">
                <span class="sub-title">GIÁ TRỊ CỐT LÕI</span>
                <h2>Những Cam Kết Chúng Tôi Giữ Mỗi Ngày</h2>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fa fa-handshake-o"></i></div>
                    <h4>Minh Bạch Hoàn Toàn</h4>
                    <p>Mọi sản phẩm đều có mã QR truy xuất nguồn gốc - bạn biết rau của mình đến từ đâu, do ai trồng.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fa fa-globe"></i></div>
                    <h4>Trách Nhiệm Với Môi Trường</h4>
                    <p>Bao bì phân hủy sinh học, giảm thiểu carbon, hỗ trợ nông nghiệp tái sinh và bảo vệ đất đai.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="value-card">
                    <div class="value-icon"><i class="fa fa-users"></i></div>
                    <h4>Đồng Hành Với Nông Dân</h4>
                    <p>Chúng tôi cam kết giá thu mua công bằng, đào tạo kỹ thuật hữu cơ và đảm bảo đầu ra ổn định cho nông dân.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Core Values Section End -->

<!-- CTA Section Begin -->
<section class="about-cta spad">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2>Sẵn Sàng Trải Nghiệm Thực Phẩm Sạch?</h2>
                <p>Tham gia cùng hơn 10.000 gia đình tin tưởng Xanh Organic để bảo vệ sức khỏe qua từng bữa ăn mỗi ngày.</p>
                <div class="cta-buttons">
                    <a href="{{ route('home') }}" class="btn-buy">Mua Sắm Ngay</a>
                    <a href="{{ route('contact') }}" class="btn-contact">Liên Hệ Chúng Tôi</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- CTA Section End -->

@endsection
