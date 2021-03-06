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
                                <option value="deleted" > Deleted </option>
                                <option value="banned" selected> Banned </option>
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
                        "name": "users.username",
                        "data": "username",
                        "title": "Username",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "users.first_name",
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
                        "name": "admins_email",
                        "data": "admins_email",
                        "title": "Moderator",
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
                        "name": "orders_completed",
                        "data": "orders_completed",
                        "title": "Orders Completed",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "block_reason",
                        "data": "block_reason",
                        "title": "Reason",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "users.deleted_at",
                        "data": "deleted_at",
                        "title": "Deleted On",
                        "orderable": true,
                        "searchable": true
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