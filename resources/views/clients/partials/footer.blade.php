<footer class="footer">
    <div class="container">
        <div class="row footer-main">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer__about">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ asset('assets/clients/img/xanhorganic.png') }}" alt="Xanh Organic">
                    </a>
                    <p class="footer-brand">Xanh Organic</p>
                    <p class="footer-tagline">Sống xanh sống khỏe</p>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="footer__widget">
                    <h6>Danh mục nổi bật</h6>
                    <ul>
                        <li><a href="{{ route('products') }}">Rau củ quả</a></li>
                        <li><a href="{{ route('products') }}">Trái cây</a></li>
                        <li><a href="{{ route('products') }}">Bơ trứng sữa</a></li>
                        <li><a href="{{ route('products') }}">Thịt & Hải sản</a></li>
                        <li><a href="{{ route('products') }}">Đồ khô</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer__widget">
                    <h6>Về Xanh Organic</h6>
                    <ul>
                        <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                        <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                        <li><a href="{{ route('contact') }}">Câu hỏi thường gặp</a></li>
                        <li><a href="{{ route('about') }}">Chính sách bảo mật</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="footer__widget">
                    <h6>Theo dõi</h6>
                    <div class="footer__widget__social">
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                        <a href="#"><i class="fa fa-youtube"></i></a>
                    </div>
                    <p class="footer-pay-label">Thanh toán</p>
                    <div class="footer-pay">
                        <img src="{{ asset('assets/clients/img/cod.png') }}" alt="COD">
                        <img src="{{ asset('assets/clients/img/vnpay.png') }}" alt="VNPay">
                    </div>
                </div>
            </div>
        </div>
        <div class="footer__copyright">
            <p>Copyright &copy; {{ date('Y') }} Xanh Organic. All rights reserved.</p>
        </div>
    </div>
</footer>
