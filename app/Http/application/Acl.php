<?php
namespace Application;

use Illuminate\Database\Capsule\Manager as Capsule;
use Models\User;

class Acl extends Database {

    private $_id;
    private $_rol;
    private $_permissions;
    
    public function __construct($id = false)
    {
        parent::__construct();

        if($id) {
            $this->_id = (int) $id;
        } else {
            if(Session::get("id")) {
                $this->_id = Session::get("id");
            } else {
                $this->_id = 1;
            }
        }

        $this->_roles_id = $this->getRolId();
        $this->_rol = Helper::transformUpperTextToLowerText(Helper::friendlyUrl($this->getRolName()));
        $this->_permissions = $this->getPermissionsRol();
        $this->compileAcl();
    }
    
    public function compileAcl()
    {
        $this->_permissions = array_merge($this->_permissions);
    }

    public function getRolId()
    {
        $roles = Capsule::select("SELECT roles.id FROM users INNER JOIN roles ON users.roles_id = roles.id WHERE users.id = :id", [
            ':id' => $this->_id
        ]);

        if(count($roles) > 0){
            $rol = current($roles);
            return $rol->id;
        }
        return false;
    }

    public function getRolName()
    {
        $roles = Capsule::select("SELECT roles.description FROM users INNER JOIN roles ON users.roles_id = roles.id WHERE users.id = :id", [
            ':id' => $this->_id
        ]);

        if(count($roles) > 0){
            $rol = current($roles);
            return $rol->description;
        }
        return false;
    }
    
    public function getPermissionsRol()
    {
        $permissions = Capsule::select("SELECT roles_permissions.permission, roles_permissions.actions FROM roles_permissions INNER JOIN roles ON roles_permissions.roles_id = roles.id WHERE roles.id = :roles_id", [
            ':roles_id' => $this->_roles_id
        ]);

        $data = array();

        foreach ($permissions AS $permission) {

            $actions = explode('|', $permission->actions);

            $data[$permission->permission] = array(
                "rol" => $this->_rol,
                "permission" => $permission->permission,
                "actions" => $actions,
            );
        }
        
        return $data;
    }
    
    public function permissionGlobal($key, $action = false)
    {
        if(array_key_exists($key, $this->_permissions)){
            if($action != false){
                if(in_array($action , $this->_permissions[$key]['actions'])){
                    return true;
                }
            }
            return true;
        }
        return false;
    }
    
    public function accessGlobal($key, $action = false)
    {
        if($this->permissionGlobal($key, $action)) {
            return;
        }

        header("location:" . BASE_URL . URL_FRIENDLY_BASE . "error/access");
        exit;
    }

    public function permissionMenu($key, $action = false)
    {
        if(array_key_exists($key, $this->_permissions)){
            if($action != false) {
                if (in_array($action, $this->_permissions[$key]['actions'])) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function accessMenu($key, $action = false)
    {
        return $this->permissionMenu($key, $action);
    }
    
}
?>
