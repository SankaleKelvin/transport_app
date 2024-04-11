<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function createDriver(Request $request){
        $request->validate([
            "driver_name"=>"required",
            "email"=>"required"
        ]);

        $driver = Driver::create([
            'driver_name'=>$request->driver_name,
            'email'=>$request->email
        ]);

        return response()->json($driver);
    }

    public function readAllDrivers(){
        $drivers = Driver::all();
        if(!$drivers){
            return response()->json("No Driver Was found");
        }
        else {
             return response()->json($drivers);
        }
    }

    public function readDriver($id){
        try{
            $driver = Driver::findOrFail($id);

            if($driver){
                return response()->json($driver);
            }
            else{
                return response()->json("No Driver Was Found With The ID: ",$id);
            }
        }
        catch(\Exception $e){
            return response()->json([
                        'error'=>'Driver Does not exist With Such and ID'
            ], 400);
        }
    }

    public function updateDriver($id, Request $request){
        try{
            $request ->validate([
                "driver_name"=>"required",
                "email"=>"required",
            ]);
            $driver = Driver::findOrFail($id);

            if($driver){
                $driver->driver_name = $request->driver_name ;
                $driver->email = $request->email ; 
                $driver->save();

                return response()->json($driver);
            }
            else{
                return response()->json("No Driver Was Found With The ID: ",$id);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'error'=>'Unable to update record!'
                ], 400);
        }
    }

    public function deleteDriver($id){
        try{
            $driver = Driver::findOrFail($id);
            if($driver){
                Driver::destroy($id);
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
