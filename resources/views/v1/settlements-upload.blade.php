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
                                <option value="upload" selected> Upload PG File </option>
                                <option value="download"> Download Settlement File </option>
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
        <div class="col-lg-6 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form role="form" action='{{route('settlements_upload') }}' method='post' enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        
                        <div class="form-group input-group">
                            <span class="input-group-addon"> PG File </span>
                            <input type="file" class="form-control" name='pg_file' id='pg_file' required />
                        </div>

                        <div class="form-group input-group-btn">
                            <input type='submit' class='btn btn-primary form-control' value='Upload'>
                        </div>
                    </form>    
                </div>
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
@endpush