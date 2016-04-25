@extends('v1.layout')
@section('title', 'Orders')

@section('content')
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">

                    Products

                    <div class="col-lg-3 pull-right">
                        <form>
                        <div class="form-group">
                            <select class="form-control" name="internal_status_code" id="internal_status_code">
                                <option value="all"> All </option>
                                @foreach($code_list as $codes)
                                    <option value='{{ $codes->internal_status_code }}' @if(Request::segment(2) == $codes->internal_status_code) selected @endif> {{ $codes->status_description }} </option>
                                @endforeach
                            </select>
                        </div>
                        </form>
                    </div>
                </h1>
            </div>
        </div>
        <!-- /.col-lg-12 -->
        
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
                
                <table class="table table-bordered" id="orders-table">
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
            $('#payments_orders').addClass('active');
            
            $("#internal_status_code").change(function(){
                var url = "{{ url('orders') }}"+"/"+this.value;
                window.location.replace(url);
            });
        });
    </script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
    
    <script type="text/javascript">
        (function(window, $) {
            window.LaravelDataTables = window.LaravelDataTables || {};
            window.LaravelDataTables["dataTableBuilder"] = $("#orders-table").DataTable({
                "serverSide": true,
                "processing": true,
                "pageLength":"15",
                "scrollX":true,
                "scrollY":'250',
                "ajax": {
                        "url": "",
                        "data": function (d) {
                            d.start_date    = $('input[name=start_date]').val();
                            d.end_date      = $('input[name=end_date]').val();
                        }
                },
                "columns": [
                    {
                        "name": "orders.orders_id",
                        "data": "orders_id",
                        "title": "Order ID",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "product_name",
                        "data": "product_name",
                        "title": "Product",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "users.first_name",
                        "data": "first_name",
                        "title": "Seller",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "users.username",
                        "data": "username",
                        "title": "Seller Mobile",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "orders.total_amount",
                        "data": "total_amount",
                        "title": "Amount",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "customers_name",
                        "data": "customers_name",
                        "title": "Customer Name",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "customers_contact_number",
                        "data": "customers_contact_number",
                        "title": "Customer Mobile",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "status_description",
                        "data": "status_description",
                        "title": "Status",
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
                            var action_data         = "";
                            var refund_show_codes   = ["ORD_PLACED", "ORD_SHIPPED", "ORD_COMPLETED", "ORD_DISPUTED"];
                            var cancel_show_codes   = ["ORD_PLACED"];
                            
                            //add one more condition for checking if the pg amount is settled or not
                            //only then these options are available
                            //show Amount not PG Settled yet if the pg amt not received
                            
                            if(refund_show_codes.indexOf(full.internal_status_code) >= 0)
                            {
                                action_data +=  ' <a id="refund_row"'
                                    + 'class="btn btn-xs btn-info">'
                                    + 'Refund'
                                    + '</a>&nbsp;';
                            }
                            
                            if(cancel_show_codes.indexOf(full.internal_status_code) >= 0)
                            {
                                action_data +=  '<a id="cancel_row"'
                                    + 'class="btn btn-xs btn-danger">'
                                    + 'Cancel'
                                    + '</a>';
                            }
                            return action_data;
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