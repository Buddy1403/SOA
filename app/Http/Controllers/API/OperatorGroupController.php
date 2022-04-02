<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ArenaHasGroup;
use App\Models\OperatorGroups;
use App\Http\Controllers\Controller;

class OperatorGroupController extends Controller
{
    public function index(){
        return response()->json(OperatorGroups::with(['Hasgroup'])->latest()->get());
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255',
        ]);

        $team = OperatorGroups::create([
            'name' => $request['name'],
        ]);



    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        // $this->arena($id,'update',$request->team);

        $group = OperatorGroups::findOrFail($id)->update([
            'name' => $request->name,
        ]);
    }

    public function destroy($id)
    {

        $group =  OperatorGroups::findOrFail($id);
        $hasarena =  ArenaHasGroup::where('group_id',$group->id);

        $hasarena->delete();
        $group->delete();
    }

    public function getselectedGroup($id){

        return response()->json(OperatorGroups::with(['Hasgroup'])->whereId($id)->get());
    }

    public function Deletegroupsselectedarena($id){

        $group =  ArenaHasGroup::findOrFail($id);
        $group->delete();
    }

    public function Addtogroup(request $request){

        foreach($request->data as $area_code){
            $addarena = ArenaHasGroup::updateOrCreate([
                'area_code' => $area_code['areaCode'],
                'group_id' => $request->id
            ]);

        }
    }

}
