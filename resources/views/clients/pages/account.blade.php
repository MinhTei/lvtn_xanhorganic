@extends('layouts.client')

@section('title', 'Tài khoản')
@section('breadcrumb', 'Tài khoản')

@section('content')
<section class="account spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <aside class="account-side">
                    <nav class="account-nav">
                        <a href="#profile" class="active">
                            <i class="fa fa-user-o"></i> Thông tin cá nhân
                        </a>
                        <a href="#orders">
                            <i class="fa fa-list-alt"></i> Lịch sử đơn hàng
                        </a>
                        <a href="#wishlist">
                            <i class="fa fa-heart-o"></i> Sản phẩm yêu thích
                        </a>
                        <a href="#addresses">
                            <i class="fa fa-map-marker"></i> Địa chỉ đã lưu
                        </a>
                        <!-- <a href="#settings">
                            <i class="fa fa-cog"></i> Cài đặt
                        </a> -->
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="logout">
                                <i class="fa fa-sign-out"></i> Đăng xuất
                            </button>
                        </form>
                    </nav>
                </aside>
            </div>

            <div class="col-lg-9 col-md-8">
                {{-- Hồ sơ --}}
                <div id="profile" class="account-panel">
                    <h2 class="account-title">Hồ Sơ Của Tôi</h2>
                    <form id="update-account" method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="account-field">
                                    <label for="name">Họ và tên</label>
                                    <input type="text" id="name" name="name" placeholder="Nhập họ và tên" value="{{ Auth::user()->name }}" required>
                                    @error('name')<p class="text-danger small">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="account-field">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="number" id="phone" name="phone" placeholder="Nhập số điện thoại" value="{{ Auth::user()->phone }}" required>
                                    @error('phone')<p class="text-danger small">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="account-field">
                                    <label for="email">Email đăng nhập</label>
                                    <input type="email" id="email" value="{{ Auth::user()->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="account-field">
                                    <label for="address">Địa chỉ mặc định</label>
                                    @php
                                        $defaultAddress = isset($addresses) ? $addresses->where('is_default', 1)->first() : null;
                                        if ($defaultAddress) {
                                            $addressString = $defaultAddress->street_address . ', ' . $defaultAddress->ward . ', ' . $defaultAddress->district . ', ' . $defaultAddress->province;
                                        } elseif (Auth::user()->address) {
                                            $addressString = Auth::user()->address;
                                        } else {
                                            $addressString = 'Chưa có địa chỉ nào';
                                        }
                                    @endphp
                                    <input type="text" id="address" value="{{ $addressString }}" readonly>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="site-btn mt-3">LƯU THAY ĐỔI</button>
                    </form>
                </div>

                {{-- Đơn hàng --}}
                <div id="orders" class="account-panel">
                    <div class="account-head">
                        <h2 class="account-title mb-0">Lịch sử đơn hàng</h2>
                    </div>
                    <div class="order-list">
                        @forelse($orders as $order)
                            @php
                                $statusColor = $statusColors[$order->status] ?? '#6b7280';
                                $statusLabel = $statusLabels[$order->status] ?? $order->status;
                            @endphp
                            <div class="order-card">
                                <div class="order-card-top">
                                    <div>
                                        <h5>{{ $order->order_code }}</h5>
                                        <p>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <span class="status-badge" style="color: {{ $statusColor }}; background: {{ $statusColor }}22;">{{ $statusLabel }}</span>
                                </div>
                                <div class="order-card-bot">
                                    <div>
                                        <p>Tổng tiền:</p>
                                        <strong>{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                                    </div>
                                    <a href="{{ route('account.order.detail', $order) }}" class="site-btn">Xem chi tiết</a>
                                </div>
                            </div>
                        @empty
                            <div class="account-empty">
                                <i class="fa fa-inbox"></i>
                                <p>Bạn chưa có đơn hàng nào.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Yêu thích --}}
                <div id="wishlist" class="account-panel">
                    <div class="account-head">
                        <h2 class="account-title mb-0">Sản phẩm yêu thích</h2>
                        @if($wishlistItems->isNotEmpty())
                            <button type="button" class="site-btn" id="btn-wishlist-add-all">Thêm tất cả vào giỏ</button>
                        @endif
                    </div>
                    <div id="account-wishlist-list" class="mt-3">
                        @include('clients.partials.wishlist_items', ['wishlistItems' => $wishlistItems])
                    </div>
                </div>

                {{-- Địa chỉ --}}
                <div id="addresses" class="account-panel">
                    <div id="address-list-view">
                        <div class="account-head">
                            <h2 class="account-title mb-0">Địa chỉ của tôi</h2>
                            <button type="button" class="btn btn-success" id="btn-address" data-toggle="modal" data-target="#addressModal">+ Thêm địa chỉ mới</button>
                        </div>

                        <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content account-modal">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Thêm địa chỉ mới</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="add-address" action="{{ route('account.address') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            @include('clients.partials.address_location')
                                            <div class="form-group">
                                                <label class="small text-muted">Địa chỉ cụ thể</label>
                                                <input type="text" name="street_address" class="form-control" placeholder="Số nhà, tên đường, ngõ ngách..." value="{{ old('street_address') }}" required>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" name="is_default" value="1" id="is_default" class="form-check-input">
                                                <label for="is_default" class="form-check-label small">Đặt làm địa chỉ mặc định</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-success">LƯU ĐỊA CHỈ</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if(isset($addresses) && $addresses->isEmpty())
                            <p class="text-center mt-3">Bạn chưa lưu địa chỉ nào.</p>
                        @else
                            <div class="row mt-3">
                                @foreach($addresses as $address)
                                    <div class="col-12 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h5 class="card-title m-0">Địa chỉ {{ $loop->iteration }}</h5>
                                                    <form action="{{ route('account.address.default', $address) }}" method="POST" class="d-inline m-0">
                                                        @csrf
                                                        <div class="form-check m-0">
                                                            <input class="form-check-input" type="checkbox" onchange="this.form.submit()" id="defaultCheck{{ $address->id }}" {{ $address->is_default ? 'checked disabled' : '' }}>
                                                            <label class="form-check-label small" for="defaultCheck{{ $address->id }}">Mặc định</label>
                                                        </div>
                                                    </form>
                                                </div>
                                                <p class="card-text text-muted mb-3">
                                                    {{ $address->street_address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                                </p>
                                                <div class="d-flex gap-2">
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary btn-edit-address" data-address-id="{{ $address->id }}">Cập nhật</a>
                                                    @if(!$address->is_default)
                                                        <form action="{{ route('account.address.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa địa chỉ này?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                                        </form>
                                                    @endif
                                                </div>

                                                <div class="address-edit mt-3 pt-3 border-top" id="edit-address-{{ $address->id }}">
                                                    <form action="{{ route('account.address.update', $address) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        @include('clients.partials.address_location', [
                                                            'province' => $address->province,
                                                            'district' => $address->district,
                                                            'ward' => $address->ward,
                                                        ])
                                                        <div class="form-group mb-2 mt-2">
                                                            <label class="small text-muted">Địa chỉ cụ thể</label>
                                                            <input type="text" name="street_address" class="form-control" value="{{ $address->street_address }}" required>
                                                        </div>
                                                        <div class="form-check mb-3">
                                                            <input type="checkbox" name="is_default" value="1" id="is_default_{{ $address->id }}" class="form-check-input" @checked($address->is_default)>
                                                            <label for="is_default_{{ $address->id }}" class="form-check-label small">Đặt làm địa chỉ mặc định</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-success btn-sm w-100">LƯU THAY ĐỔI</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Cài đặt --}}
                <!-- <div id="settings" class="account-panel">
                    <h2 class="account-title">Cài đặt tài khoản</h2>
                    <p class="account-hint mb-4">Thay đổi mật khẩu đăng nhập của bạn.</p>
                    <form id="change-password" method="POST" action="{{ route('account.change.password') }}">
                        @csrf
                        <div class="account-field">
                            <label for="current_password">Mật khẩu hiện tại</label>
                            <input type="password" id="current_password" name="current_password" required>
                            @error('current_password')<p class="text-danger small">{{ $message }}</p>@enderror
                        </div>
                        <div class="account-field">
                            <label for="new_password">Mật khẩu mới</label>
                            <input type="password" id="new_password" name="new_password" required>
                            @error('new_password')<p class="text-danger small">{{ $message }}</p>@enderror
                        </div>
                        <div class="account-field">
                            <label for="new_password_confirm">Xác nhận mật khẩu mới</label>
                            <input type="password" id="new_password_confirm" name="new_password_confirm" required>
                            @error('new_password_confirm')<p class="text-danger small">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="site-btn mt-3">ĐỔI MẬT KHẨU</button>
                    </form>
                </div> -->
            </div>
        </div>
    </div>
</section>
@endsection
