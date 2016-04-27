<?php

namespace App\V1\Controllers;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use DB;
use Sentinel;

use App\DataTables\UsersDataTable;
use App\DepartmentUserMapping;
use App\DataTables\Scopes\CommonDateSearchScope;
use Log;

class AdminUserController extends Controller
{
    public function index(UsersDatatable $datatable, Request $request)
    {
        $query_columns  = [
                            'users.users_id',
                            'username',
                            'email',
                            'departments_name',
                            'users.created_at',
                            'users.deleted_at'
                            ];
        $export_columns = [
                            'users_id',
                            'username',
                            'email',
                            'departments_name',
                            'created_at',
                            'deleted_at'
                            ];
        $datatable->setQueryColumns($query_columns);
        $datatable->setExportColumns($export_columns);
        
        if($datatable->request()->get('start_date'))
        {
            $column_name    = 'users.created_at';
            $start_date     = $datatable->request()->get('start_date').' 00:00:00';
            $end_date       = $datatable->request()->get('end_date').' 23:59:59';
            $scope_search   = new CommonDateSearchScope($column_name, $start_date, $end_date);
            $datatable->addScope($scope_search);
        }
        
        return $datatable->render('v1.users');
    }
    
    public function storeView()
    {
        $data['department_data']    = DB::table('departments')
                                        ->where('is_active', 'y')
                                        ->select(['departments_id', 'departments_name'])
                                        ->get();
        
        $data['role_data']          = DB::table('roles')
                                        ->whereNotIn('slug', ['administrator', 'sellfie-users'])
                                        ->select(['id', 'name'])
                                        ->get();
        
        $data['permission_data']    = DB::table('permissions')
                                        ->where('is_active', 'y')
                                        ->select(['permissions_name'])
                                        ->get();
        
        return view('v1.users-create-view', $data);
    }
    
    public function store()
    {
        $request                            = Request::all();
        $create_data['login']               = $request['email'];
        $create_data['username']            = $request['username'];
        $create_data['password']            = config('cartalyst.sentinel.default_user_password');
        $create_data['first_name']          = $request['first_name'];
        $create_data['last_name']           = $request['last_name'];
        $create_data['registration_mode']   = 'web';
        $admin_user = Sentinel::register($create_data, true);
        
        if($request['permission_type'] == '16') //user is given custom permission (Subscriber role)
        {
            foreach($request['custom_permissions'] as $permissions)
            {
                $create_permissions_array[$permissions] = "true";
            }
            $admin_user->permissions = $create_permissions_array;
            $admin_user->save();
            
            $role   = Sentinel::findRoleBySlug('subscriber');
        }
        else
        {
            $role   = Sentinel::findRoleById($request['permission_type']);
        }
        
        $role->users()->attach($admin_user);
        
        $request['users_id']    = $admin_user->users_id;
        $department_data        = DepartmentUserMapping::create($request);
        
        return redirect('users');
    }
    
    public function updateView($id)
    {
        $users_data     = DB::table('users')
                        ->where('users_id', $id)
                        ->select(['email', 'username', 'first_name', 'last_name', 'permissions'])
                        ->get();
        $data['email']      = $users_data[0]->email;
        $data['username']   = $users_data[0]->username;
        $data['first_name'] = $users_data[0]->first_name;
        $data['last_name']  = $users_data[0]->last_name;
        
        $dept_data      = DB::table('department_user_mappings')
                        ->join('departments', 'departments.departments_id', '=', 'department_user_mappings.departments_id')
                        ->where('department_user_mappings.users_id', $id)
                        ->select(['department_user_mappings.departments_id', 'departments_name'])
                        ->get();
        $data['departments_id']     = $dept_data[0]->departments_id;
        $data['departments_name']   = $dept_data[0]->departments_name;
        
        $role_data      = DB::table('role_users')
                        ->join('roles', 'role_users.role_id', '=', 'roles.id')
                        ->where('role_users.user_id', $id)
                        ->select(['roles.id', 'roles.name'])
                        ->get();
        $data['role_id']            = $role_data[0]->id;
        $data['role_name']          = $role_data[0]->name;
        
        $data['department_data']    = DB::table('departments')
                                        ->where('is_active', 'y')
                                        ->select(['departments_id', 'departments_name'])
                                        ->get();
        
        $data['role_data']          = DB::table('roles')
                                        ->whereNotIn('slug', ['administrator', 'sellfie-users'])
                                        ->select(['id', 'name', 'permissions'])
                                        ->get();
        
        if($role_data[0]->name  == 'Subscriber')
        {
            $assigned_permissions           = json_decode($users_data[0]->permissions, true);
        }
        else
        {
            foreach($data['role_data'] as $assigned)
            {
                if($data['role_id'] == $assigned->id)
                {
                    $assigned_permissions   = json_decode($assigned->permissions, true);
                }
            }
        }
        $data['role_assigned_data']         = array_keys($assigned_permissions);
        
        $data['permission_data']    = DB::table('permissions')
                                        ->where('is_active', 'y')
                                        ->select(['permissions_name'])
                                        ->get();
        
        return view('v1.users-update-view', $data);
    }
    
    public function update()
    {
        echo "<pre>";
        $request    = Request::all();
        
        if(($request['old_first_name'] != $request['first_name']) || ($request['old_last_name'] != $request['last_name']) ||
            ($request['old_username'] != $request['username']) || ($request['old_email'] != $request['email']))
        {
            $update_array               = null;
            $update_array['first_name'] = $request['first_name'];
            $update_array['last_name']  = $request['last_name'];
            $update_array['username']   = $request['username'];
            $update_array['email']      = $request['email'];
            $update_array['updated_at'] = date('Y-m-d H:i:s');
            DB::table('users')
                ->where('users_id', $request['users_id'])
                ->update($update_array);
        }
        
        if($request['old_departments_id'] != $request['departments_id'])
        {
            $update_array                   = null;
            $update_array['departments_id'] = $request['departments_id'];
            $update_array['updated_at']     = date('Y-m-d H:i:s');
            DB::table('department_user_mappings')
                ->where('users_id', $request['users_id'])
                ->update($update_array);
        }
        
        if($request['old_role_id'] != $request['permission_type'])
        {
            $update_array               = null;
            $update_array['role_id']    = $request['permission_type'];
            $update_array['updated_at'] = date('Y-m-d H:i:s');
            DB::table('role_users')
                ->where('user_id', $request['users_id'])
                ->update($update_array);
            
            $update_array               = null;
            $update_array['updated_at'] = date('Y-m-d H:i:s');
            if($request['old_permission_type'] == 'Subscriber')
            {
                $update_array['permissions']    = NULL;
                DB::table('users')
                    ->where('users_id', $request['users_id'])
                    ->update($update_array);
            }
            
            if($request['permission_type']  == '16') //has Subscriber role
            {
                foreach($request['custom_permissions'] as $permissions)
                {
                    $create_permissions_array[$permissions] = true;
                }
                $update_array['permissions']    = json_encode($create_permissions_array);
                DB::table('users')
                    ->where('users_id', $request['users_id'])
                    ->update($update_array);
            }
        }
        else if(($request['old_role_id'] == '16') && ($request['permission_type']== '16'))
        {
            foreach($request['custom_permissions'] as $permissions)
            {
                $create_permissions_array[$permissions] = true;
            }
            $update_array['permissions']    = json_encode($create_permissions_array);
            DB::table('users')
                ->where('users_id', $request['users_id'])
                ->update($update_array);
        }
        
        $url    = route('users_view', ['id' => $request['users_id']]);
        return redirect($url);
    }
    
    public function enable($id)
    {
        $update_array['deleted_at'] = NULL;
        $update_array['updated_at'] = date('Y-m-d H:i:s');
        
        $updated    = DB::table('users')
                        ->where('users_id', $id)
                        ->update($update_array);
        
        return redirect('users');
    }
    
    public function delete($id)
    {
        $update_array['deleted_at'] = date('Y-m-d H:i:s');
        $update_array['updated_at'] = date('Y-m-d H:i:s');
        
        $updated    = DB::table('users')
                        ->where('users_id', $id)
                        ->update($update_array);
        
        return redirect('users');
    }
    
    public function ban()
    {
        $request    = Request::all();
        $update_array['block_status']   = 'y';
        $update_array['deleted_at']     = date('Y-m-d H:i:s');
        $update_array['updated_at']     = date('Y-m-d H:i:s');
        
        $updated    = DB::table('users')
                        ->where('users_id', $request['users_id'])
                        ->update($update_array);
        
        if($updated)
        {
            $insert_data['users_id']    = $request['users_id'];
            $insert_data['block_reason']= $request['block_reason'];
            $insert_data['blocked_by']  = Sentinel::getUser()->users_id;
            $insert_data['created_at']  = date('Y-m-d H:i:s');
            $insert_data['updated_at']  = date('Y-m-d H:i:s');
            DB::table('user_block_details')->insert($insert_data);
        }
        
        return redirect('users');
    }
}
