@extends('v1.layout')
@section('title', 'Transactions')

@section('content')
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    
                    Transactions
                    
                </h1>
            </div>
        </div>
        <!-- /.col-lg-12 -->
        <div class="container-outer-udf">
            <div class="container-inner-udf">
                <form class="form-inline" id="search-form" method="POST" role="form" >
                    From
                    <input type="date" class="form-control" name="start_date" rel="2">
                    to
                    <input type="date" class="form-control" name="end_date" rel="2">

                    <input type="submit" class="form-control btn btn-primary" value="Search" />
                </form>
                <table class="table table-bordered dataTable" id="transactions-table">
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
            $('#payments_transactions').addClass('active');
        });
    </script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="{{ URL::asset('vendor/datatables/buttons.server-side.js') }}"></script>
    
    <script type="text/javascript">
        (function(window, $) {
            window.LaravelDataTables = window.LaravelDataTables || {};
            window.LaravelDataTables["dataTableBuilder"] = $("#transactions-table").DataTable({
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
                        "name": "payment_details.created_at",
                        "data": "created_at",
                        "title": "Date",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "payment_details.payment_details_id",
                        "data": "payment_details_id",
                        "title": "Transaction ID",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "payment_source",
                        "data": "payment_source",
                        "title": "Source",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "payment_source_id",
                        "data": "payment_source_id",
                        "title": "Source ID",
                        "orderable": true,
                        "searchable": true
                    },
                    {
                        "name": "providers_name",
                        "data": "providers_name",
                        "title": "Channel",
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
                        "data": 'action',
                        "title": "Action",
                        "name": 'action',
                        "orderable": false,
                        "searchable": false
                    },
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