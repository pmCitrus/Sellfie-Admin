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
                            <th rowspan="1" colspan="1"> <input> </th>
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
            
            $(document.body).on('click', '#enable_row, #delete_row, #ban_row', function (e){
                var action_type = ($(this).attr('id') === 'delete_row') ? "delete" : "ban";
                var name        = $(this).parents('tr:first').find('td:first').text();
                var action_type;
                
                switch($(this).attr('id'))
                {
                    case 'enable_row':
                        action_type = 'enable';
                        break;
                        
                    case 'delete_row':
                        action_type = 'disable';
                        break;
                        
                    case 'ban_row':
                        action_type = 'ban';
                        break;
                        
                    default:
                        e.preventDefault();
                }
                
                if(confirm('Do you want to '+action_type+' moderator?'))
                {
                    if(action_type === 'ban')
                    {
                        $('#ban_users_id').val(name);
                        $('#myModal').modal('show');
                        e.preventDefault();
                    }
                    else
                    {
                        var url = "{{ route('users') }}/"+name+"/"+action_type;
                        window.location.replace(url);
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
                "ajax": {
                        "url": "",
                        "data": function (d) {
                            d.start_date    = $('input[name=start_date]').val();
                            d.end_date      = $('input[name=end_date]').val();
                        }
                },
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
                                var enable_or_disable_action;
                                if(full.deleted_at == null)
                                {
                                    enable_or_disable_action = '<a id="delete_row"'
                                            + 'title="Disable Moderator"'
                                            + 'class="btn btn-xs btn-warning">'
                                            + 'Disable'
                                            + '</a>&nbsp;';
                                }
                                else if(full.deleted_at != '')
                                {
                                    enable_or_disable_action = '<a id="enable_row"'
                                            + 'title="Enable Moderator"'
                                            + 'class="btn btn-xs btn-success">'
                                            + 'Enable'
                                            + '</a>&nbsp;';
                                }
                                
                                return  ' <a id="edit_row"'
                                        + 'title="Edit Details"'
                                        + 'class="btn btn-xs btn-primary">'
                                        + 'Edit'
                                        + '</a>&nbsp;'
                                        + enable_or_disable_action
                                        +'<a id="ban_row"'
                                        + 'title="Ban Moderator"'
                                        + 'class="btn btn-xs btn-danger">'
                                        + 'Ban'
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
            
            $('#search-form').on('submit', function(e) {
                var start_date  = $('input[name=start_date]').val();
                var end_date    = $('input[name=end_date]').val();
                if((start_date == '') || (end_date == ''))
                {
                    alert('Kindly select both the dates.');
                }
                else
                {
                    if(start_date > end_date)
                    {
                        alert('Kindly select correct date range.');
                    }
                    else
                    {
                        window.LaravelDataTables["dataTableBuilder"].draw();
                    }
                }
                e.preventDefault();
            });
        })(window, jQuery);
    </script>
@endpush