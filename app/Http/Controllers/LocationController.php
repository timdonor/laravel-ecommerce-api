<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'street'=> 'required',
            'building'=> 'required',
            'area'=> 'required'
        ]);

        Location::create([
            'street'=> $request->street,
            'building'=> $request->building,
            'area'=> $request->area,
            'user_id'=> JWTAuth::id()
        ]);

        return response()->json('Location added', 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'street'=> 'required',
            'building'=> 'required',
            'area'=> 'required'
        ]);

        $location = Location::find($id);

        if($location){
            $location->street = $request->street;
            $location->building = $request->building;
            $location->area = $request->area;
            $location->save();

            return response()->json("Location updated");
        }else{
            return response()->json("location not found");
        }
    }

    public function destroy($id)
    {
        $location = Location::find($id);
        if($location) {
            $location->delete();
            return response()->json('Location Deleted');
        } else {
            return response()->json('Location not found');
        }
    }
}
