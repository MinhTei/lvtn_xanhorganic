@extends('layouts.admin')
@section('title', 'Gán quyền — ' . $role->name)

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header bg-white">Quyền của role: <code>{{ $role->name }}</code></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf @method('PUT')
            @foreach($permissions as $permission)
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" name="permissions[]"
                           value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                           @checked(in_array($permission->id, $assigned))>
                    <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                </div>
            @endforeach
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Lưu quyền</button>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Quay lại</a>
            </div>
        </form>
    </div>
</div>
@endsection
