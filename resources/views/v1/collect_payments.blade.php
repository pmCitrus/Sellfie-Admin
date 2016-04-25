@extends('v1.layout')
@section('title', 'Collects')

@section('content')
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">

                    Collects

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
                
                <table class="table table-bordered" id="collects-table">
                    <thead>
                        <tr>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <th> </th>
                            <!--<th> </th>-->
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
                            <!--<th rowspan="1" colspan="1"> <input> </th>-->
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
            $('#payments_collects').addClass('active');
            
            $("#internal_status_code").change(function(){
                var url = "{{ url('collect_payments') }}"+"/"+this.value;
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
            window.LaravelDataTables["dataTableBuilder"] = $("#collects-table").DataTable({
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
                        "name": "payment_details.payment_details_id",
                        "data": "payment_details_id",
                        "title": "Transaction ID",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "payment_link_title",
                        "data": "payment_link_title",
                        "title": "Collect Title",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "users.first_name",
                        "data": "first_name",
                        "title": "Seller",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "users.username",
                        "data": "username",
                        "title": "Seller Mobile",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "payment_details.total_amount",
                        "data": "total_amount",
                        "title": "Amount",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "customers_name",
                        "data": "customers_name",
                        "title": "Customer",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "customers_contact_number",
                        "data": "customers_contact_number",
                        "title": "Customer Mobile",
                        "orderable": true,
                        "searchable": true
                    },
//                    {
//                        "name": "settlement_status",
//                        "data": "settlement_status",
//                        "title": "Status",
//                        "orderable": true,
//                        "searchable": true
//                    },
                    {
                        "data": 'action',
                        "title": "Action",
                        "name": 'action',
                        "orderable": false,
                        "searchable": false
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