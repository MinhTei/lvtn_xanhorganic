@extends('layouts.client')

@section('title', 'Tài khoản')
@section('breadcrumb', 'Tài khoản')

@section('content')


<!-- Account Section Begin -->
<section class="account-page spad">
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
                <aside class="account-sidebar-no-padding">
                    {{-- <div class="account-sidebar-profile">
                        <div class="account-sidebar-profile">
                            <label for="avatar">
                                <img  class="account-sidebar-avatar-circle" src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
                                    id="avatar-preview" 
                                    alt="Avatar"
                                >
                            </label>
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="d-none" form="update-account">
                        </div>
                        <h4 class="account-sidebar-name">{{ Auth::user()->name }}</h4>
                        <p class="account-sidebar-tier"></p>
                    </div> --}}
                    <div class="row">
                        <nav class="account-nav">
                            <a href="#profile" class="account-nav-link active">
                                <i class="fa fa-user-o"></i>
                                Thông tin cá nhân
                            </a>
                            <a href="#orders" class="account-nav-link">
                                <i class="fa fa-list-alt"></i>
                                Lịch sử đơn hàng
                            </a>
                            <a href="#addresses" class="account-nav-link">
                                <i class="fa fa-map-marker"></i>
                                Địa chỉ đã lưu
                            </a>
                            <a href="#settings" class="account-nav-link">
                                <i class="fa fa-cog"></i>
                                Cài đặt
                            </a>

                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="account-nav-link text-danger w-100 text-left border-0 bg-transparent">
                                    <i class="fa fa-sign-out"></i>
                                    Đăng xuất
                                </button>
                            </form>
                        </nav>
                    </div>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 col-md-8">

                <!-- Profile Section -->
                <div id="profile" class="account-section">
                    <h2 class="account-section-title">Hồ Sơ Của Tôi</h2>
                    <form id ="update-account" method="POST" action="{{route('account.update')}}" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="account-form-group">
                                    <label for="name">Họ và tên</label>
                                    <input type="text" id="name" name="name" class="account-input" placeholder="Nhập họ và tên" value="{{ Auth::user()->name }}" required>
                                    @error('name')<p class="text-danger small">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="account-form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="number" id="phone" name="phone" class="account-input" placeholder="Nhập số điện thoại" value="{{ Auth::user()->phone }}" required>
                                    @error('phone')<p class="text-danger small">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="account-form-group">
                                    <label for="email">Email đăng nhập</label>
                                    <input type="email" id="email" value="{{ Auth::user()->email }}" class="account-input" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="account-form-group">
                                    <label for="address">Địa chỉ mặc định</label>
                                    @php
                                        $defaultAddress = isset($addresses) ? $addresses->where('is_default', 1)->first() : null;
                                        $addressString = '';
                                        if ($defaultAddress) {
                                            $addressString = $defaultAddress->street_address . ', ' . $defaultAddress->ward . ', ' . $defaultAddress->district . ', ' . $defaultAddress->province;
                                        } elseif (Auth::user()->address) {
                                            $addressString = Auth::user()->address;
                                        } else {
                                            $addressString = 'Chưa có địa chỉ nào';
                                        }
                                    @endphp
                                    <input type="text" id="address" value="{{ $addressString }}" class="account-input" readonly>
                                </div>
                            </div>
                        </div>
                        
                        
                        

                        <button type="submit" class="btn-submit mt-3">LƯU THAY ĐỔI</button>
                    </form>
                </div>

                <!-- Orders Section -->
                <div id="orders" class="account-section account-section-clean" >
                    <div class="account-orders-header">
                        <h2 class="account-section-title" style="margin-bottom: 0;">Lịch sử đơn hàng</h2>
                    </div>

                    <div class="account-orders-list">
                        @forelse($orders as $order)
                            @php
                                $statusColor = $statusColors[$order->status] ?? '#6b7280';
                                $statusLabel = $statusLabels[$order->status] ?? $order->status;
                            @endphp
                            <div class="account-order-card">
                                <div class="account-order-card-header">
                                    <div>
                                        <h5 class="account-order-card-title">Đơn hàng #{{ $order->order_code }}</h5>
                                        <p class="account-order-card-date">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <span class="order-status-badge" style="background: {{ $statusColor }}15; color: {{ $statusColor }};">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>
                                <div class="account-order-card-footer">
                                    <div>
                                        <p class="account-order-total-label">Tổng tiền:</p>
                                        <h4 class="account-order-total-value">{{ number_format($order->total_amount, 0, ',', '.') }}₫</h4>
                                    </div>
                                    <div>
                                        <a href="{{ route('account.order.detail', $order) }}" class="site-btn order-btn-detail">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="account-empty-state" style="grid-column: 1 / -1;">
                                <i class="fa fa-inbox"></i>
                                <p>Bạn chưa có đơn hàng nào.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Addresses Section -->
                <div id="addresses" class="account-section " >

                    <div id="address-list-view">
                        <div class="account-orders-header">
                            <h2 class="account-section-title">Địa chỉ của tôi</h2>
                            <button type="button" class="btn btn-success" id="btn-address" data-toggle="modal" data-target="#addressModal"> + Thêm địa chỉ mới</button>
                        </div>
                        <!-- Modal Thêm Địa Chỉ -->
                        <div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="margin-top: 150px; border-radius: 10px;">
                                  <div class="modal-header">
                                    <p><h5 class="modal-title" id="btn-address">Thêm địa chỉ mới</h5></p>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: none; border: none; font-size: 1.5rem;">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body">
                                  <form id ="add-address"action="{{ route('account.address') }}" method="POST">
                                      @csrf
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
                                    <div class="card shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="card-title m-0">Địa chỉ {{ $loop->iteration }}</h5>
                                                <form action="{{ route('account.address.default', $address) }}" method="POST" class="d-inline m-0">
                                                    @csrf
                                                    <div class="form-check m-0">
                                                        <input class="form-check-input" type="checkbox" onchange="this.form.submit()" id="defaultCheck{{ $address->id }}" {{ $address->is_default ? 'checked disabled' : '' }}>
                                                        <label class="form-check-label small" style="cursor: pointer;" for="defaultCheck{{ $address->id }}">
                                                            Mặc định
                                                        </label>
                                                    </div>
                                                </form>
                                            </div>
                                            <p class="card-text text-muted mb-3">
                                                {{ $address->street_address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                            </p>
                                            <div class="d-flex gap-2">
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-primary btn-edit-address" data-address-id="{{ $address->id }}">
                                                    Cập nhật
                                                </a>
                                                @if(!$address->is_default) 
                                                    <form action="{{ route('account.address.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa địa chỉ này?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                                                    </form>
                                                @endif
                                            </div> 

                                            <div class="address-edit-form mt-3 pt-3 border-top" id="edit-address-{{ $address->id }}" style="display: none;">
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

                <!-- Settings Section -->
                <div id="settings" class="account-section account-section-clean" >
                    <h2 class="account-section-title">Cài đặt tài khoản</h2>
                    <p class="account-form-hint mb-4">Thay đổi mật khẩu đăng nhập của bạn.</p>

                    <form id="change-password" method="POST" action="{{route('account.change.password')}}" >
                        @csrf
                        
                        <div class="account-form-group">
                            <label for="current_password">Mật khẩu hiện tại</label>
                            <input type="password" id="current_password" name="current_password" class="account-input" required>
                            @error('current_password')<p class="text-danger small">{{ $message }}</p>@enderror
                        </div>
                        <div class="account-form-group">
                            <label for="new_password">Mật khẩu mới</label>
                            <input type="password" id="new_password" name="new_password" class="account-input" required>
                            @error('new_password')<p class="text-danger small">{{ $message }}</p>@enderror
                        </div>
                        <div class="account-form-group">
                            <label for="new_password_confirm">Xác nhận mật khẩu mới</label>
                            <input type="password" id="new_password_confirm" name="new_password_confirm" class="account-input" required>
                            @error('new_password_confirm')<p class="text-danger small">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="site-btn mt-3">ĐỔI MẬT KHẨU</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Account Section End -->


@endsection
