<?php
    
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Application\Helper;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
    
class RoleController extends Controller
{
    

    private $title='Roles';
    private $parent_title='Configuración Global';
    private $module='roles';

    public function index(Request $request)
    {
        $roles = Role::all();
        return view('admvisch.roles.index')
            ->with(['roles'=>$roles, 'title'=>$this->title, 'parent_title'=>$this->parent_title,'module'=>$this->module]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
  
        return view('admvisch.roles.enter')->with(['title'=>$this->title, 'parent_title'=>$this->parent_title, 'module'=>$this->module]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required'
        ]);
    
        $role = Role::create($request->all(),['guard_name'=>'web']);
        $role->permissions()->sync($request->permissions);
    
        session()->flash('error', 'success');
        return redirect()->route('roles');
                        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();
    
        return view('admvisch.roles.show',compact('role','rolePermissions'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
    
        return view('admvisch.roles.edit')->with(['role'=>$role, 'permission'=>$permission, 'rolePermissions'=>$rolePermissions, 'title'=>$this->title, 'parent_title'=>$this->parent_title, 'module'=>$this->module]);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = Helper::postValue('id');
        $this->validate($request, [
            'name' => 'required',
        ]);
    
       

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
    
        $role->permissions()->sync($request->permissions);

        session()->flash('error', 'success');
        return redirect()->route('roles')
                        ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::table("roles")->where('id',$id)->delete();

        session()->flash('error', 'success');
        return redirect()->route('roles')
                        ->with('success','Role deleted successfully');

                        
    }

}
