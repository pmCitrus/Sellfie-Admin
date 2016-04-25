@extends('v1.layout')
@section('title', 'Settlements')

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    
                    Settlements
                    
                    <div class="col-lg-3 pull-right">
                        <form>
                        <div class="form-group">
                            <select class="form-control" name="settlement_action" id="settlement_action">
                                <option value="upload"> Upload PG File </option>
                                <option value="download" selected> Download Settlement File </option>
                            </select>
                        </div>
                        </form>
                    </div>
                </h1>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
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
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th rowspan="1" colspan="1"> <input> </th>
                            <th rowspan="1" colspan="1"> <input> </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
	{
            $('#nav_settlements').addClass('active');
            
            $("#settlement_action").change(function(){
                var url = "{{ url('settlements') }}"+"/"+this.value;
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
                        "name": "settlement_files.created_at",
                        "data": "created_at",
                        "title": "Settlement Date",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "data": 'action',
                        "title": "Actions",
                        "name": 'action',
                        "orderable": false,
                        "searchable": false,
                        "render": function ( ) {
                                    return ' <a id="view_row"'
                                            + 'title="Delete Seller"'
                                            + 'class="btn btn-xs btn-warning">'
                                            + 'Download'
                                            + '</a>';
                                  }
                    }
                ]
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