@extends('layouts.admin')
@section('title', 'Thêm role')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header bg-white">Thêm role mới</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên role *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                       placeholder="vd: warehouse" required>
                <div class="form-text">Chữ thường, không dấu cách (dùng _ hoặc -).</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Quyền</label>
                @php
                    $danhSachModule = [];
                    foreach($permissions as $quyen) {
                        $parts = explode('_', $quyen->name, 2);
                        if(count($parts) === 2) {
                            $hanhDong = $parts[0]; // manage, add, edit, delete
                            $tenModule = $parts[1]; // users, products...
                            $danhSachModule[$tenModule][$hanhDong] = $quyen;
                        }
                    }
                @endphp

                <div class="table-responsive">
                    <table class="table table-bordered mb-0 table-role-permissions">
                        <thead class="table-light">
                            <tr>
                                <th>Quản lý (Full)</th>
                                <th class="text-center">Thêm</th>
                                <th class="text-center">Sửa</th>
                                <th class="text-center">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($danhSachModule as $tenModule => $cacQuyen)
                                <tr>
                                    <!-- Quyền Quản lý chung -->
                                    <td>
                                        @if(isset($cacQuyen['manage']))
                                            @php $quyenManage = $cacQuyen['manage']; @endphp
                                            <div class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input" name="permissions[]"
                                                       value="{{ $quyenManage->id }}" id="q_{{ $quyenManage->id }}" 
                                                       @checked(collect(old('permissions', []))->contains($quyenManage->id))>
                                                <label class="form-check-label fw-bold" style="cursor:pointer;" for="q_{{ $quyenManage->id }}">
                                                    {{ $quyenManage->name }}
                                                </label>
                                            </div>
                                        @else
                                            <span class="text-muted fw-bold">manage_{{ $tenModule }}</span>
                                        @endif
                                    </td>
                                    
                                    <!-- Quyền Thêm (add) -->
                                    <td class="text-center align-middle">
                                        @if(isset($cacQuyen['add']))
                                            @php $quyenAdd = $cacQuyen['add']; @endphp
                                            <input type="checkbox" class="form-check-input" name="permissions[]"
                                                   value="{{ $quyenAdd->id }}" @checked(collect(old('permissions', []))->contains($quyenAdd->id))>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <!-- Quyền Sửa (edit) -->
                                    <td class="text-center align-middle">
                                        @if(isset($cacQuyen['edit']))
                                            @php $quyenEdit = $cacQuyen['edit']; @endphp
                                            <input type="checkbox" class="form-check-input" name="permissions[]"
                                                   value="{{ $quyenEdit->id }}" @checked(collect(old('permissions', []))->contains($quyenEdit->id))>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <!--  Quyền Xóa (delete) -->
                                    <td class="text-center align-middle">
                                        @if(isset($cacQuyen['delete']))
                                            @php $quyenDelete = $cacQuyen['delete']; @endphp
                                            <input type="checkbox" class="form-check-input" name="permissions[]"
                                                   value="{{ $quyenDelete->id }}" @checked(collect(old('permissions', []))->contains($quyenDelete->id))>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </form>
    </div>
</div>

@endsection
