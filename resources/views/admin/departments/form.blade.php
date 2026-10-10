@extends('layouts.admin')
@section('title', $department->exists ? 'Edit' : 'Add fellowship or department')
@section('content')
<form method="POST" action="{{ $department->exists ? route('admin.departments.update', $department) : route('admin.departments.store') }}" class="card" style="max-width:560px">
  @csrf @if ($department->exists) @method('PUT') @endif
  <div class="field" style="margin-bottom:14px"><label>Name</label><input type="text" name="name" value="{{ old('name', $department->name) }}" required></div>
  <div class="field" style="margin-bottom:14px"><label>Description</label><textarea name="note">{{ old('note', $department->note) }}</textarea></div>
  <div class="field" style="margin-bottom:18px"><label>Phone number</label><input type="text" name="phone" value="{{ old('phone', $department->phone) }}" placeholder="+2348030000000" required></div>
  <button class="btn btn-blue" type="submit">Save</button>
  <a class="btn btn-ghost" href="{{ route('admin.departments.index') }}">Cancel</a>
</form>
@endsection
