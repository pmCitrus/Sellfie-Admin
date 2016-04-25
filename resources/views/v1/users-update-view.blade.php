@extends('v1.layout')
@section('title', 'Moderators')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <h1 class="page-header">
                    View / Edit Moderator
                    
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
        <div class="col-lg-6 col-md-6">
            <div class="panel">
                <form role="form" id="user-create-form" action='{{route('users_update') }}' method='post'>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="users_id" value="{{ Request::segment(2) }}">
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> First Name</span>
                        <input type="text" class="form-control" name="old_first_name" value='{{ $first_name }}' readonly="readonly">
                        <input type="text" class="form-control" name='first_name' value='{{ $first_name }}' required autofocus>
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Last Name</span>
                        <input type="text" class="form-control" name="old_last_name" value='{{ $last_name }}' readonly="readonly">
                        <input type="text" class="form-control" name='last_name' value='{{ $last_name }}' required>
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Email (login) </span>
                        <input type="text" class="form-control" name="old_email" value='{{ $email }}' readonly="readonly">
                        <input type="email" class="form-control" name='email' value='{{ $email }}' required >
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Username</span>
                        <input type="text" class="form-control" name="old_username" value='{{ $username }}' readonly="readonly">
                        <input type="text" class="form-control" name='username' value='{{ $username }}' required >
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Department</span>
                        <input type="hidden" class="form-control" name="old_departments_id" value='{{ $departments_id }}' readonly="readonly">
                        <input type="text" class="form-control" name="old_departments_name" value='{{ $departments_name }}' readonly="readonly">
                        <select class="form-control" name='departments_id' required>
                            <option value=""> Select </option>
                            
                            @foreach($department_data as $dept_data)
                                <option value="{{ $dept_data->departments_id }}" @if($dept_data->departments_id == $departments_id) selected @endif> {{ $dept_data->departments_name }} </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Permission Assigned</span>
                        @foreach($role_assigned_data as $roles_assigned_data)
                            <input type="text" class="form-control" value='{{ $roles_assigned_data }}' disabled>
                        @endforeach
                    </div>
                    
                    <div class="form-group input-group">
                        <span class="input-group-addon"> Roles</span>
                        <input type="hidden" class="form-control" name="old_role_id" value='{{ $role_id }}' readonly="readonly">
                        <input type="text" class="form-control" name="old_permission_type" value='{{ $role_name }}' readonly="readonly">
                        <select class="form-control" name='permission_type' id="permission_type" required>
                            <option value=""> Select </option>
                            
                            @foreach($role_data as $roles_data) 
                                <option value="{{ $roles_data->id }}" @if($roles_data->id == $role_id) selected @endif> {{ $roles_data->name }} </option>
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
                        <input type='submit' class='btn btn-primary form-control' value='Save Changes'>
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