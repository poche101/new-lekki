@extends('layouts.admin')
@section('title', 'Roles and permissions')
@section('content')
<form method="POST" action="{{ route('admin.roles.update') }}">
@csrf @method('PUT')
<table class="a-table">
  <thead><tr><th>Permission</th>@foreach ($labels as $key => $label)@if ($key !== 'super_admin')<th>{{ $label }}</th>@endif @endforeach</tr></thead>
  <tbody>
  @foreach ($permKeys as $permKey => $permLabel)
    <tr>
      <td>{{ $permLabel }}</td>
      @foreach ($labels as $roleKey => $roleLabel)
        @continue($roleKey === 'super_admin')
        {{-- hidden marker ensures this role's key exists even if every box below is unchecked --}}
        <td style="text-align:center">
          <input type="hidden" name="perms[{{ $roleKey }}][]" value="">
          <input type="checkbox" name="perms[{{ $roleKey }}][]" value="{{ $permKey }}" {{ in_array($permKey, $permissions[$roleKey] ?? []) ? 'checked' : '' }}>
        </td>
      @endforeach
    </tr>
  @endforeach
  </tbody>
</table>
<button class="btn btn-blue" style="margin-top:16px" type="submit">Save</button>
</form>
@endsection
