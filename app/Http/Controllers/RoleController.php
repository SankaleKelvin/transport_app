<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function createRole(Request $request){
        $request->validate([
            "name"=>"required"
        ]);

        $role = Role::create([
            'name'=>$request->name,
            'description'=>$request->description
        ]);

        return response()->json($role);
    }

    public function readAllRole(){
        $roles = Role::all();
        if(!$roles){
            return response()->json("No Role Was found");
        }
        else {
             return response()->json($roles);
        }
    }

    public function readRole($id){
        try{
            $role = Role::findOrFail($id);

            if($role){
                return response()->json($role);
            }
            else{
                return response()->json("No Role Was Found With The ID: ",$id);
            }
        }
        catch(\Exception $e){
            return response()->json([
                        'error'=>'Role Does not exist With Such and ID'
            ], 400);
        }
    }

    public function updateRole($id, Request $request){
        try{
            $request ->validate([
                "name"=>"required"
            ]);
            $role = Role::findOrFail($id);

            if($role){
                $role->name = $request->name ;
                $role->description = $request->description ; 
                $role->save();

                return response()->json($role);
            }
            else{
                return response()->json("No Role Was Found With The ID: ",$id);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'error'=>'Unable to update record!'
                ], 400);
        }
    }

    public function deleteRole($id){
        try{
            $role = Role::findOrFail($id);
            if($role){
                Role::destroy($id);
                return response()->json("Record Has been Successfully Deleted");
            }
            else{
                return response()->json("Record Does not exist with the ID: ", $id);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'error'=>'Record Not Deleted!'
                ], 400);
        }
    }
    
}
