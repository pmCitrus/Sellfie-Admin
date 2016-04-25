@extends('v1.layout')
@section('title', 'Users')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    Admin Users
                    
                    <div class="col-lg-3 pull-right">
                        <a href="{{ route('users_new_view') }}" class="btn btn-primary">
                            <i class="glyphicon glyphicon-plus"> </i>
                            Add New Moderator
                        </a>
                    </div>
                </h1>
            </div>
        </div>
        
        <div class="container-outer-udf">
            <div class="container-inner-udf">
                <form class="form-inline" style ="width: 500px;margin: 0 auto;" id="search-form" method="POST">
                    From
                    <input type="date" class="form-control" name="start_date" rel="2">
                    to
                    <input type="date" class="form-control" name="end_date" rel="2">

                    <input type="submit" class="form-control btn btn-primary" value="Search" />
                </form>
                
                <br>
                
                <table class="table table-bordered" id="users-table">
                    <thead>
                        <tr>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1"> <input> </th>
                            <th rowspan="1" colspan="1"> <input> </th>
                            <th rowspan="1" colspan="1"> <input> </th>
                            <th rowspan="1" colspan="1"> <input> </th>
                            <th rowspan="1" colspan="1"> <input> </th>
                            <th rowspan="1" colspan="1"> <input disabled> </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                         <form class="form-inline" id="ban_reason-form" action="{{route('users_ban') }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="users_id" id="ban_users_id" value="">
                            Enter ban reason
                            <input class='form-control' type="text" name="block_reason" maxlength='100' required>
                            <input type="submit" class="form-control btn btn-danger" value="Ban" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
	{
            $('#nav_users').addClass('active');
            
            $(document.body).on('click', '#edit_row', function (e){
                var name    = $(this).parents('tr:first').find('td:first').text();
                var url     = "{{ route('users') }}/"+name+"/view";
                window.location.replace(url);
            });
            
            $(document.body).on('click', '#delete_row, #ban_row', function (e){
                var action_type = ($(this).attr('id') === 'delete_row') ? "delete" : "ban";
                var name        = $(this).parents('tr:first').find('td:first').text();
                
                if(confirm('Do you want to '+action_type+' moderator?'))
                {
                    if(action_type === 'delete')
                    {
                        var url = "{{ route('users') }}/"+name+"/"+action_type;
                        window.location.replace(url);
                    }
                    else
                    {
                        $('#ban_users_id').val(name);
                        $('#myModal').modal('show');
                        e.preventDefault();
                    }
                }
                else
                {
                    e.preventDefault();
                }
            });
        });
    </script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
    
    <script type="text/javascript">
        (function(window, $) {
            window.LaravelDataTables = window.LaravelDataTables || {};
            window.LaravelDataTables["dataTableBuilder"] = $("#users-table").DataTable({
                "serverSide": true,
                "processing": true,
                "pageLength": "15",
                "scrollX": true,
                "scrollY": '250',
                "ajax": "",
                "columns": [
                    {
                        "name": "users.users_id",
                        "data": "users_id",
                        "title": "ID",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "username",
                        "data": "username",
                        "title": "Username",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "email",
                        "data": "email",
                        "title": "Email",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "departments_name",
                        "data": "departments_name",
                        "title": "Department",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "users.created_at",
                        "data": "created_at",
                        "title": "Created At",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "data": 'action',
                        "title": "Action",
                        "name": 'action',
                        "orderable": false,
                        "searchable": false,
                        "render": function ( data, type, full ) {
                                    return  ' <a id="edit_row"'
                                            + 'title="Edit Details"'
                                            + 'class="btn btn-xs btn-primary">'
                                            + '<i class="glyphicon glyphicon-edit"></i>'
                                            + '</a>&nbsp;'
                                            + '<a id="delete_row"'
                                            + 'title="Delete Moderator"'
                                            + 'class="btn btn-xs btn-warning">'
                                            + '<i class="glyphicon glyphicon-remove-sign"></i>'
                                            + '</a>&nbsp;'
                                            +'<a id="ban_row"'
                                            + 'title="Ban Moderator"'
                                            + ' class="btn btn-xs btn-danger">'
                                            + '<i class="glyphicon glyphicon-ban-circle"></i>'
                                            + '</a>';
                                  }
                    }
                ],
                "dom": "Bfrtip",
                "buttons": ["csv", "excel"],          
                initComplete: function () {
                    this.api().columns().every(function () {
                        $('.dataTables_scrollBody thead tr').addClass('hidden');
                        var column = this;
                        var input = document.createElement("input");
                        $(input).appendTo($(column.footer()).empty()).on('keyup', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    });
                }
            });
        })(window, jQuery);
    </script>
@endpush