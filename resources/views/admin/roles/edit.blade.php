@extends('layouts.admin')
@section('title', 'Gán quyền — ' . $role->name)

@section('content')
    <div class="card" style="max-width:800px;">
        <div class="card-header bg-white">Quyền của role: <code>{{ $role->name }}</code></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf @method('PUT')
                @php
                    $modules = [];
                    foreach ($permissions as $permission) {
                        $parts = explode('_', $permission->name, 2);
                        if (count($parts) === 2) {
                            $action = $parts[0];
                            $module = $parts[1];
                            $modules[$module][$action] = $permission;
                        }
                    }
                @endphp

                <div class ="table-reponsive">
                    <table class ="table table-bordered table-role-permissions">
                        <thead>
                            <tr>
                                <th>Quyền</th>
                                <th>Thêm</th>
                                <th>Sửa</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                         @foreach($modules as $moduleName => $actions)
                            <tr>
                                <!--Quyền Quản lý chung (manage) -->
                                <td>
                                    @if(isset($actions['manage']))
                                        @php $quyenManage = $actions['manage']; @endphp
                                        <div class="form-check mb-0">
                                            <input type="checkbox" class="form-check-input" name="permissions[]"
                                                   value="{{ $quyenManage->id }}" id="q_{{ $quyenManage->id }}" 
                                                   @checked(in_array($quyenManage->id, $assigned))>
                                            <label class="form-check-label fw-bold" style="cursor:pointer;" for="q_{{ $quyenManage->id }}">
                                                {{ $quyenManage->name }}
                                            </label>
                                        </div>
                                    @else
                                        <!-- <span class="text-muted ">manage_{{ $moduleName }}</span> -->
                                    @endif
                                </td>
                                
                                <!--Quyền Thêm (add) -->
                                <td class="text-center align-middle">
                                    @if(isset($actions['add']))
                                        @php $quyenAdd = $actions['add']; @endphp
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                               value="{{ $quyenAdd->id }}" @checked(in_array($quyenAdd->id, $assigned))>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <!-- Quyền Sửa (edit) -->
                                <td class="text-center align-middle">
                                    @if(isset($actions['edit']))
                                        @php $quyenEdit = $actions['edit']; @endphp
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                               value="{{ $quyenEdit->id }}" @checked(in_array($quyenEdit->id, $assigned))>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <!--Quyền Xóa (delete) -->
                                <td class="text-center align-middle">
                                    @if(isset($actions['delete']))
                                        @php $quyenDelete = $actions['delete']; @endphp
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                               value="{{ $quyenDelete->id }}" @checked(in_array($quyenDelete->id, $assigned))>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Lưu quyền</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
@endsection