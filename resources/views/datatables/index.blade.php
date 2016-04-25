@extends('layouts.master')

@section('content')
    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Updated At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>
@stop

@push('scripts')
<script>
$(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('datatables.data') !!}',
        columns: [
            { data: 'users_id', name: 'users_id' },
            { data: 'email', name: 'email' },
            { data: 'username', name: 'username' },
            { data: 'created_at', name: 'created_at' }
        ]
    });
});
</script>
@endpush