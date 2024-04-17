<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    
    public function readAllTrucks(){
        // $trucks = Truck::all();
        $trucks = Truck::join('drivers', 'trucks.driver_id', '=', 'drivers.id')->select('trucks.*', 'drivers.driver_name as driver_name')->get();
        if(!$trucks){
            return response()->json("No Truck Was found");
        }
        else {
             return response()->json($trucks);
        }
    }

    
    public function createTruck(Request $request){
        $request->validate([
            "truck_name"=>"required",
            "driver_id"=>"required",
            "image_path"=>"image|mimes:jpeg,png,jpg|max:2048"
        ]);
    
        $filename = null;
        if($request->hasFile("image_path")){
            $filename = $request->file("image_path")->store("trucks", "public");
        }
    
        $truck = Truck::create([
            'truck_name'=>$request->truck_name,
            'driver_id'=>$request->driver_id,
            'image_path'=>$filename
        ]);
    
        return response()->json($truck);
    }
    
    
    public function readTruck($id){
        try{
            // $truck = Truck::findOrFail($id);
            $truck = Truck::join("drivers", "trucks.driver_id", "=", "drivers.id")->select("trucks.*", "drivers.driver_name as driver_name")->findOrFail($id);

            if($truck){
                return response()->json($truck);
            }
            else{
                return response()->json("No Truck Was Found With The ID: ",$id);
            }
        }
        catch(\Exception $e){
            return response()->json([
                        'error'=>'Truck Does not exist With Such and ID'
            ], 400);
        }
    }

    
    public function updateTruck($id, Request $request){
        try{
            $request->validate([
                "truck_name"=>"required",
                "driver_id"=>"required",
                "image_path"=>"image|mimes:jpeg,png,jpg|max:2048"
            ]);
    
            $truck = Truck::findOrFail($id);
    
            if($truck){
                $truck->truck_name = $request->truck_name;
                $truck->driver_id = $request->driver_id;
    
                if($request->hasFile("image_path")){
                    $filename = $request->file("image_path")->store("trucks", "public");
                    $truck->image_path = $filename;
                }
    
                $truck->save();
    
                return response()->json($truck);
            }
            else{
                return response()->json("No Truck Was Found With The ID: ",$id);
            }
        }
        catch(\Exception $e){
            return response()->json([
                'error'=>'Unable to update record!'
            ], 400);
        }
    }
    
    
    public function deleteTruck($id){
        try{
            $truck = Truck::findOrFail($id);
            if($truck){
                Truck::destroy($id);
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
