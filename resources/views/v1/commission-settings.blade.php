@extends('v1.layout')
@section('title', 'Commission Settings')

@section('content')
    <!-- /.row -->
    <div class="row">
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    
                    Commission Settings
                    
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
                    <form role="form" id='percentage_commission' action='{{route('commission_settings') }}' method='post'>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="commission_type" value='percentage'>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> Current Default</span>
                            <input type="text" class="form-control" placeholder="{{ $percentage_commission }}" disabled>
                        </div>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> New Default</span>
                            <input type="number" class="form-control" name='commission_value' id='perc_commission' required autofocus min='0' max='100' step="any" >
                        </div>

                        <div class="form-group input-group-btn">
                            <input type='submit' class='btn btn-primary form-control' value='Save Changes'>
                        </div>
                    </form>    
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">PERCENTAGE</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <form role="form" id='fixed_commission' action='{{route('commission_settings') }}' method='post'>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="commission_type" value='fixed'>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> Current Default</span>
                            <input type="text" class="form-control" placeholder="{{ $fixed_commission }}" disabled>
                        </div>

                        <div class="form-group input-group">
                            <span class="input-group-addon"> New Default</span>
                            <input type="number" class="form-control" name='commission_value' id='fix_commission' required min='0' max='100' step="any">
                        </div>

                        <div class="form-group input-group-btn">
                            <input type='submit' class='btn btn-success form-control' value='Save Changes'>
                        </div>
                    </form>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-right">FIXED ( in INR )</span>
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
            $('#payments_commissions').addClass('active');
            
            $("#percentage_commission").submit(function(e){
                var perc_commission_value  = $("#perc_commission").val();
                if(perc_commission_value == {{ $percentage_commission }})
                {
                    alert('Please choose a different default value');
                    e.preventDefault();
                }
                else
                {
                    if(confirm('Do you want to change the percentage commission ?') == false)
                    {
                        e.preventDefault();
                    }
                }
            });
            
            $("#fixed_commission").submit(function(e){
                var fix_commission_value  = $("#fix_commission").val();
                if(fix_commission_value == {{ $fixed_commission }})
                {
                    alert('Please choose a different default value');
                    e.preventDefault();
                }
                else
                {
                    if(confirm('Do you want to change the fixed commission ?') == false)
                    {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush