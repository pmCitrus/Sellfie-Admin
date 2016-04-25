@extends('v1.layout')
@section('title', 'KYC New')

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    
                    KYC
                    
                    <div class="col-lg-3 pull-right">
                        <form>
                        <div class="form-group">
                            <select class="form-control" name="kyc_status" id="kyc_status">
                                <option value="new"> New </option>
                                <option value="approved"> Approved </option>
                                <option value="rejected" selected> Rejected </option>
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
                
                <table class="table table-bordered" id="kyc-table">
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
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
	{
            $('#nav_kyc').addClass('active');
            
            $("#kyc_status").change(function(){
                var url = "{{ url('kyc') }}"+"/"+this.value;
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
            window.LaravelDataTables["dataTableBuilder"]    = $("#kyc-table").DataTable({
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
                        "name": "users.first_name",
                        "data": "first_name",
                        "title": "Seller Name",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "profile_name",
                        "data": "profile_name",
                        "title": "Brand Name",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "user_kycs.rejected_at",
                        "data": "rejected_at",
                        "title": "Rejected Date",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "admins_email",
                        "data": "admins_email",
                        "title": "Moderator",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "users.block_status",
                        "data": "block_status",
                        "title": "Account Status",
                        "orderable": false,
                        "searchable": true,
                        "render": function (data, type, full) {
                            if(full.block_status == 'n')
                            {
                                return (full.deleted_at == null) ? 'Active' : 'Deleted';
                            }
                            else
                            {
                                return 'Banned';
                            }
                        }
                    },
                    {
                        "data": 'action',
                        "title": "Actions",
                        "name": 'action',
                        "orderable": false,
                        "searchable": false,
                        "render": function ( data, type, full ) {
                            return  ' <a name="'+full.kyc_ref_id+'" id="view_row"'
                                    + 'title="View"'
                                    + 'class="btn btn-xs btn-primary">'
                                    + '<i class="glyphicon glyphicon-search"></i>'
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