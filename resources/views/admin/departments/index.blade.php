@extends('layouts.admin')
@section('title', 'Fellowships & Departments')
@section('content')
<div class="a-toolbar">
  <form method="GET"><input type="text" name="q" value="{{ $q }}" placeholder="Search" style="border:1px solid var(--line);border-radius:10px;padding:10px 14px"></form>
  <a class="btn btn-blue" style="margin-left:auto" href="{{ route('admin.departments.create') }}">+ Add fellowship or department</a>
</div>
<table class="a-table">
  <thead><tr><th>Name</th><th>Description</th><th>Phone</th><th></th></tr></thead>
  <tbody>
    @forelse ($departments as $d)
      <tr>
        <td>{{ $d->name }}</td><td>{{ str($d->note)->limit(60) }}</td><td>{{ $d->formattedPhone() }}</td>
        <td style="white-space:nowrap">
          <a class="btn btn-ghost" href="{{ route('admin.departments.edit', $d) }}">Edit</a>
          <button type="button" class="btn btn-ghost js-delete" style="color:#DC2626" data-action="{{ route('admin.departments.destroy', $d) }}" data-name="{{ $d->name }}">Delete</button>
        </td>
      </tr>
    @empty
      <tr><td colspan="4" style="text-align:center;color:var(--grey);padding:30px">None yet.</td></tr>
    @endforelse
  </tbody>
</table>
@include('admin.partials.delete-modal')
@endsection
