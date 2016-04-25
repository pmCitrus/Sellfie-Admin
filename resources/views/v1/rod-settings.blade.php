@extends('v1.layout')
@section('title', 'ROD Settings')

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    
                    ROD Settings
                    
                </h1>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <form role="form" id='product_rod_change' action='{{route('rod_settings') }}' method='post'>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="rod_type" value='product'>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> Current Default</span>
                            <input type="text" class="form-control" placeholder="{{ $product_rod_days }}" disabled>
                        </div>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> New Default</span>
                            <input type="number" class="form-control" name='rod_days' id='product_rod_days' required autofocus min='1' max='30' >
                        </div>

                        <div class="form-group input-group-btn">
                            <input type='submit' class='btn btn-primary form-control' value='Save Changes'>
                        </div>
                    </form>    
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">PRODUCT</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <form role="form" id='collect_link_rod_change' action='{{route('rod_settings') }}' method='post'>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="rod_type" value='collect_link'>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> Current Default</span>
                            <input type="text" class="form-control" placeholder="{{ $collect_link_rod_days }}" disabled>
                        </div>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> New Default</span>
                            <input type="number" class="form-control" name='rod_days' id='collect_link_rod_days' required min='1' max='30'>
                        </div>

                        <div class="form-group input-group-btn">
                            <input type='submit' class='btn btn-success form-control' value='Save Changes'>
                        </div>
                    </form>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">COLLECT LINKS</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
	{
            $('#payments_rods').addClass('active');
            
            $("#product_rod_change").submit(function(e){
                var prod_rod_value  = $("#product_rod_days").val();
                if(prod_rod_value == {{ $product_rod_days }})
                {
                    alert('Please choose a different default value');
                    e.preventDefault();
                }
                else
                {
                    if(confirm('Do you want to change the product rod ?') == false)
                    {
                        e.preventDefault();
                    }
                }
            });
            
            $("#collect_link_rod_change").submit(function(e){
                var collect_link_rod_value  = $("#collect_link_rod_days").val();
                if(collect_link_rod_value == {{ $collect_link_rod_days }})
                {
                    alert('Please choose a different default value');
                    e.preventDefault();
                }
                else
                {
                    if(confirm('Do you want to change the collect link rod ?') == false)
                    {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush