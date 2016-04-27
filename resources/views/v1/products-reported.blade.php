@extends('v1.layout')
@section('title', 'Products')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    Products
                    
                    <div class="col-lg-3 pull-right">
                        <form>
                        <div class="form-group">
                            <select class="form-control" name="product_status" id="product_status">
                                <option value="active"> Active </option>
                                <option value="disabled"> Disabled </option>
                                <option value="reported" selected> Reported </option>
                            </select>
                        </div>
                        </form>
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
                
                <table class="table table-bordered" id="products-table">
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
            $('#nav_products').addClass('active');
            
            $("#product_status").change(function(){
                var url = "{{ url('products') }}"+"/"+this.value;
                window.location.replace(url);
            });
            
            $(document.body).on('click', '#ignore_row, #disable_row, #ban_row', function (e){
                var name    = $(this).parents('tr:first').find('td:first').text();
                var action_type;
                switch($(this).attr('id'))
                {
                    case 'ignore_row':
                        action_type = 'ignore';
                        break;
                        
                    case 'disable_row':
                        action_type = 'disable';
                        break;
                        
                    case 'ban_row':
                        action_type = 'ban';
                        break;
                        
                    default:
                        e.preventDefault();
                }
                
                if(confirm('Do you want to '+action_type+' product?'))
                {
                    var url     = "{{ route('products', ['product_status' => Request::segment(2)]) }}/"+name+"/"+action_type;
                    window.location.replace(url);
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
            window.LaravelDataTables["dataTableBuilder"]    = $("#products-table").DataTable({
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
                        "name": "products.products_id",
                        "data": "products_id",
                        "title": "ID",
                        "orderable": false,
                        "searchable": true,
                        "render": function (data) {
                                    return  '<a title="View Product" target="_blank" href="{{config("base_urls.product_share_url")}}'+ data +'">'+data+'</a>';
                                  }
                    },
                    {
                        "name": "product_name",
                        "data": "product_name",
                        "title": "Name",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "selling_price",
                        "data": "selling_price",
                        "title": "Price",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "reported.reported_customers_mobile",
                        "data": "reported_customers_mobile",
                        "title": "Customers Mobile",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "reported.reported_customers_name",
                        "data": "reported_customers_name",
                        "title": "Customers Name",
                        "orderable": false,
                        "searchable": true
                    },
                    {
                        "name": "reported_counter.report_count",
                        "data": "report_count",
                        "title": "Report Count",
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
                        "name": "first_name",
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
                        "name": "profile_user_name",
                        "data": "profile_user_name",
                        "title": "Profile URL",
                        "orderable": false,
                        "searchable": true,
                        "render": function ( data ) {
                                    if(data != null)
                                    {
                                        return  '<a title="View Seller" target="_blank" href="{{config("base_urls.profile_url")}}'+ data +'"> '+ data + ' </a>';
                                    }
                                    else
                                    {
                                        return '';
                                    }
                                  }
                    },
                    {
                        "name": "products.created_at",
                        "data": "created_at",
                        "title": "Created At",
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
                                    return  ' <a id="ignore_row"'
                                            + 'class="btn btn-xs btn-primary">'
                                            + 'Ignore'
                                            + '</a>&nbsp;'
                                            + '<a id="disable_row"'
                                            + 'class="btn btn-xs btn-warning">'
                                            + 'Disable'
                                            + '</a>&nbsp;'
                                            + '<a id="ban_row"'
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