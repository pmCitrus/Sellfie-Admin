@extends('v1.layout')
@section('title', 'Dashboard')

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    
                    Dashboard
                    
                    <div class="col-lg-3 pull-right">
                        <form>
                        <div class="form-group">
                            <select class="form-control" name="time_period" id="time_period">
                                <option value="daily" @if((Request::segment(2) == null) || (Request::segment(2) == 'daily')) selected @endif > Daily </option>
                                <option value="weekly" @if(Request::segment(2) == 'weekly') selected @endif > Weekly </option>
                                <option value="monthly" @if(Request::segment(2) == 'monthly') selected @endif> Monthly </option>
                                <option value="quarterly" @if(Request::segment(2) == 'quarterly') selected @endif> Quarterly </option>
                                <option value="half_yearly" @if(Request::segment(2) == 'half_yearly') selected @endif> Half Yearly </option>
                                <option value="custom" @if((Request::segment(2) == 'custom') || isset($is_custom)) selected @endif> Custom </option>
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
    <form action="{{route('dashboard_custom')}}" class="form-inline" style="width: 500px;margin: 0 auto; display:none;" id="search-form" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        From
        <input type="date" class="form-control" name="start_date" value="{{ $start_date }}" rel="2">
        to
        <input type="date" class="form-control" name="end_date" value="{{ $end_date }}" rel="2">
        
        <input type="submit" class="form-control btn btn-primary" value="Search" />
    </form>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_active_sellers }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Active Sellers</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_sellers }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Total Sellers</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-camera fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_products_created }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Products</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-list-alt fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_collect_links_created }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Collect links</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- /.row -->
    
    <!-- /.row -->
    <div class="page-header"></div>
    <!-- ./row -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-shopping-cart fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_orders_placed }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Orders Placed</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-barcode fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_orders_completed }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Orders Completed</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-credit-card fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_collect_payments }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Collect Payments</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-bar-chart-o fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $total_sellfie_commission }}</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">Sellfie Commission</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- ./row -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
	{
            $('#nav_dashboard').addClass('active');
            
            $("#time_period").change(function(){
                if(this.value == 'custom')
                {
                    $("#search-form").toggle();
                }
                else
                {
                    var url = "{{ route('dashboard') }}"+"/"+this.value;
                    window.location.replace(url);
                }
            });
            
            $('#search-form').on('submit', function(e) {
                var start_date  = $('input[name=start_date]').val();
                var end_date    = $('input[name=end_date]').val();
                if((start_date == '') || (end_date == ''))
                {
                    alert('Kindly select both the dates.');
                    e.preventDefault();
                }
                else if(start_date > end_date)
                {
                    alert('Kindly select correct date range.');
                    e.preventDefault();
                }
                
            });
            
            if($("#time_period").val() == 'custom')
            {
                $("#search-form").toggle();
            }
        });
    </script>
@endpush