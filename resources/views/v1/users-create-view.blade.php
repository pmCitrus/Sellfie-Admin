@extends('v1.layout')
@section('title', 'Moderators')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    Create Moderators
                    
                    <div class="col-lg-2 pull-right">
                        <a href="{{ route('users') }}" class="btn btn-primary">
                            <i class="glyphicon glyphicon-arrow-left"> </i>
                            Back
                        </a>
                    </div>
                </h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5 col-md-6">
            <div class="panel">
                <form role="form" id="user-create-form" action='{{route('users_new') }}' method='post'>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group input-group">
                        <span class="input-group-addon"> First Name</span>
                        <input type="text" class="form-control" name='first_name' required autofocus>
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Last Name</span>
                        <input type="text" class="form-control" name='last_name' required>
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Email (login) </span>
                        <input type="email" class="form-control" name='email' required >
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Username</span>
                        <input type="text" class="form-control" name='username' required >
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Department</span>
                        <select class="form-control" name='departments_id' required>
                            <option value=""> Select </option>
                            
                            @foreach($department_data as $dept_data)
                                <option value="{{ $dept_data->departments_id }}"> {{ $dept_data->departments_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Roles</span>
                        <select class="form-control" name='permission_type' id="permission_type" required>
                            <option value=""> Select </option>
                            
                            @foreach($role_data as $roles_data)
                                <option value="{{ $roles_data->id }}"> {{ $roles_data->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group" id="custom_permissions" style="display: none">
                        <label class="pull-right">Choose Permissions</label>
                        @foreach($permission_data as $per_data)
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="custom_permissions[]" value="{{ $per_data->permissions_name }}"> {{ $per_data->permissions_name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group input-group-btn">
                        <input type='submit' class='btn btn-primary form-control' value='Create Now'>
                    </div>
                    
                    <div id="error_messages"> </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function()
	{
            $('#nav_users').addClass('active');
            
            $('#permission_type').change(function (e) {
                if(this.options[this.selectedIndex].text == 'Subscriber')
                {
                    $('#custom_permissions').show();
                }
                else
                {
                    $('#custom_permissions').hide();
                    
                    if(this.options[this.selectedIndex].text == 'Master Moderator')
                    {
                        if(confirm("Are you sure you want to assign master admin role to the moderator?"))
                        {
                            return true;
                        }
                        else
                        {
                            $('#permission_type').val("");
                        }
                    }
                }
            });
        });
    </script>
@endpush