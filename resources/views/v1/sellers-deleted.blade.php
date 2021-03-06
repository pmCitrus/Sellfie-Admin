@extends('v1.layout')
@section('title', 'Sellers')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    Sellers
                    
                    <div class="col-lg-3 pull-right">
                        <form>
                        <div class="form-group">
                            <select class="form-control" name="seller_status" id="seller_status">
                                <option value="sign_up" > Sign Up </option>
                                <option value="active" > Active </option>
                                <option value="deleted" selected> Deleted </option>
                                <option value="banned"> Banned </option>
                            </select>
                        </div>
                        </form>
                    </div>
                </h1>
            </div>
        </div>
        
        <div class="container-outer-udf">
            <div class="container-inner-udf">
                <form class="form-inline" style ="width: 500px;margin: 0 auto;" id="search-form" method="POST" role="form" >
                    From
                    <input type="date" class="form-control" name="start_date" rel="2">
                    to
                    <input type="date" class="form-control" name="end_date" rel="2">

                    <input type="submit" class="form-control btn btn-primary" value="Search" />
                </form>
                
                <br>
                
                <table class="table table-bordered" id="sellers-table">
                    <thead>
                        <tr>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
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
                         <form class="form-inline" id="ban_reason-form" action="{{route('sellers_ban', ['seller_status' => Request::segment(2)]) }}" method="POST">
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
            $('#nav_sellers').addClass('active');
            
            $("#seller_status").change(function(){
                var url = "{{ url('sellers') }}"+"/"+this.value;
                window.location.replace(url);
            });
            
            $(document.body).on('click', '#enable_row, #ban_row', function (e){
                var action_type = ($(this).attr('id') == 'enable_row') ? "enable" : "ban";
                var name        = $(this).parents('tr:first').find('td:first').text();
                
                if(confirm('Do you want to '+action_type+' seller?'))
                {
                    if(action_type == 'enable')
                    {
                        var url     = "{{ route('sellers', ['seller_status' => Request::segment(2)]) }}/"+name+"/"+action_type;
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
            window.LaravelDataTables    = window.LaravelDataTables || {};
            window.LaravelDataTables["dataTableBuilder"]    = $("#sellers-table").DataTable({
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
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "users.created_at",
                        "data": "created_at",
                        "title": "Created",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "username",
                        "data": "username",
                        "title": "Username",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "users.email",
                        "data": "email",
                        "title": "Email",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "first_name",
                        "data": "first_name",
                        "title": "First Name",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "profile_name",
                        "data": "profile_name",
                        "title": "Brand Name",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "products_created",
                        "data": "products_created",
                        "title": "Products Created",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "collect_links_created",
                        "data": "collect_links_created",
                        "title": "Collect Links Created",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "user_kycs.is_admin_approved",
                        "data": "is_admin_approved",
                        "title": "KYC Status",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "profile_user_name",
                        "data": "profile_user_name",
                        "title": "Profile URL",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "data": 'action',
                        "title": "Actions",
                        "name": 'action',
                        "orderable": false,
                        "searchable": false,
                        "render": function ( ) {
                                    return  ' <a id="edit_row"'
                                            + 'title="View or Edit Seller"'
                                            + 'class="btn btn-xs btn-primary">'
                                            + '<i class="glyphicon glyphicon-edit"></i>'
                                            + '</a>&nbsp;'
                                            + ' <a id="enable_row"'
                                            + 'title="Enable Seller"'
                                            + 'class="btn btn-xs btn-success">'
                                            + '<i class="glyphicon glyphicon-check"></i>'
                                            + '</a>&nbsp;'
                                            +'<a id="ban_row"'
                                            + 'title="Ban Seller"'
                                            + ' class="btn btn-xs btn-danger">'
                                            + '<i class="glyphicon glyphicon-ban-circle"></i>'
                                            + '</a>';
                                  }
                    }
                ],
                "dom": "Bfrtip",
                "buttons": ['csv', 'excel'],          
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