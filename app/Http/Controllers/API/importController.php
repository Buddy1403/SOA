<?php

namespace App\Http\Controllers\API;
use Exception;
use App\Models\User;
use App\Models\arena;
use App\Models\import;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Exports\importExport;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;

class ImportController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->query('status') == "null" ? null : 'done';
        $site = $request->query('site');
        $from = $request->query('dateFrom');
        $to = $request->query('dateTo');

        $soa = import::with(['BankDetails',
            'arenaDetails.BankDetails',
            'arenaDetails.EmailDetails',
            'arenaDetails.ContactDetails',
            'arenaDetails.UserTeam.userDetails.positionDetails'
        ])->whereNull('status')->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');


        if($request->has('per_page')) {
            $perPage = $request->input('per_page');
            return $soa->paginate($perPage);
        } else {
            return $soa->get();
        }

        // if($request->has('per_page')) {

        //     $perPage = $request->input('per_page');

        //     if($request->has('site') && $site !== 'all') {
        //         if($request->has('dateFrom') && $request->has('dateTo')) {
        //             return $soa->where('refNo','like', '_'.$site.'%')->whereBetween('date_of_soa',[$from, $to])->paginate($perPage);
        //         }else{
        //             return $soa->where('refNo','like', '_'.$site.'%')->paginate($perPage);
        //         }
        //     }
        //     else if ($request->has('site')  && $site == 'all') {
        //         if($request->has('dateFrom') && $request->has('dateTo')) {
        //             return $soa->whereBetween('date_of_soa',[$from, $to])->paginate($perPage);
        //         }else {
        //             return $soa->paginate($perPage);
        //         }
        //     }
        //     else {
        //         return $soa->paginate($perPage);
        //     }
        // } else {
        //     return $soa->get();
        // }

    }

    // public function importDateRange(Request $request, $from, $to){
    //     $status = $request->query('status') == "null" ? null : 'done';
    //     $soaDateRange = import::with(['BankDetails',
    //     'arenaDetails.BankDetails',
    //     'arenaDetails.EmailDetails',
    //     'arenaDetails.ContactDetails',
    //     'arenaDetails.UserTeam.userDetails.positionDetails'
    //     ])->where('status', $status)->whereBetween('date_of_soa',[$from, $to])->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');

    //      if($request->has('per_page')) {
    //         $perPage = $request->input('per_page');
    //         return $soaDateRange->paginate($perPage);
    //     } else {
    //         return $soaDateRange->get();
    //     }
    // }

    //converted
    public function withstatus(Request $request)
    {
        $status = $request->query('status') == "null" ? null : 'done';
        $site = $request->query('site');
        $from = $request->query('dateFrom');
        $to = $request->query('dateTo');

        $soa = import::with(['BankDetails',
            'arenaDetails.BankDetails',
            'arenaDetails.EmailDetails',
            'arenaDetails.ContactDetails',
            'arenaDetails.UserTeam.userDetails.positionDetails'
    ])->whereNotNull('status')->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');

    if($request->has('per_page')) {

        $perPage = $request->input('per_page');

        if($request->has('site') && $site !== 'all') {
            if($request->has('dateFrom') && $request->has('dateTo')) {
                return $soa->where('refNo','like', '_'.$site.'%')->whereBetween('date_of_soa',[$from, $to])->paginate($perPage);
            }else {
                return $soa->where('refNo','like', '_'.$site.'%')->paginate($perPage);
            }

        } else if ($request->has('site')  && $site == 'all') {
            if($request->has('dateFrom') && $request->has('dateTo')) {
                return $soa->whereBetween('date_of_soa',[$from, $to])->paginate($perPage);
            }else {
                return $soa->paginate($perPage);
            }
        } else {
            return $soa->get();
        }
    } else {
        return $soa->get();
    }

    }

    public function searchSoa(Request $request) {

        $status = $request->query('status') == "null" ? null : 'done';
        $site = $request->query('site');
        $from = $request->query('dateFrom');
        $to = $request->query('dateTo');
        $soaSearch = import::with(['BankDetails',
                'arenaDetails.BankDetails',
                'arenaDetails.EmailDetails',
                'arenaDetails.ContactDetails',
                'arenaDetails.UserTeam.userDetails.positionDetails'
        ])->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');

        $jcapFilter = DB::table('imports')
                    ->join('emails','imports.areaCode', '=', 'emails.area_code')
                    ->select('imports.*')
                    ->orderBy('imports.areaCode', 'ASC');

        $jcapFilter = import::with(['BankDetails',
                'arenaDetails.BankDetails',
                'arenaDetails.EmailDetails',
                'arenaDetails.ContactDetails',
                'arenaDetails.UserTeam.userDetails.positionDetails'
        ])
        ->join('emails','imports.areaCode', '=', 'emails.area_code')
        ->where('email', 'LIKE', '%dea.greatpeak@gmail.com%')
        ->where('import.status', '=', 'NULL')
        ->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');

        // $emailSearch = Email::orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');

        if (($request->query('search') != null && $request->query('search') != "null") && ($from === "undefined" && $to === "Invalid date")){
            if($request->query('search') === "jcap" || $request->query('search') === "JCAP"){
                if($request->has('per_page')) {
                    // dd('hehe');
                    $perPage = $request->input('per_page');
                    if($request->has('site') && $site !== 'all') {
                        return $jcapFilter->where('refNo','like', '_'.$site.'%')
                                        ->paginate($perPage);
                    }elseif($request->has('site')  && $site == 'all'){
                        return $jcapFilter->paginate($perPage);
                    }else{
                        return $soaSearch->paginate($perPage);
                    }
                }else {
                    return $soaSearch->get();
                }
            }else{
                if($request->has('per_page')) {
                    // dd('haha');
                    $perPage = $request->input('per_page');
                    if($request->has('site') && $site !== 'all') {
                        return $soaSearch->where('refNo','like', '_'.$site.'%')->Where('arena_name','like', '%'.$request->query('search').'%')
                                        ->paginate($perPage);
                    }elseif($request->has('site')  && $site == 'all'){
                        return $soaSearch->Where('arena_name','like', '%'.$request->query('search').'%')
                                        ->orWhere('areaCode','like', '%'.$request->query('search').'%')->paginate($perPage);
                    }else{
                        return $soaSearch->paginate($perPage);
                    }
                }else {
                    return $soaSearch->get();
                }
            }
        }
        elseif((is_null($request->query('search')) == true || $request->query('search') == "null") && ($from !== "undefined" && $to !== "Invalid date")){
            if($request->has('per_page')) {
                $perPage = $request->input('per_page');
                if($request->has('site') && $site !== 'all') {
                    if($request->has('dateFrom') && $request->has('dateTo')){
                        return $soaSearch->where('refNo','like', '_'.$site.'%')->whereBetween('date_of_soa',[$from, $to])->paginate($perPage);
                    }else{
                        return $soaSearch->paginate($perPage);
                    }
                }elseif($request->has('site')  && $site == 'all'){
                    if($request->has('dateFrom') && $request->has('dateTo')){
                        return $soaSearch->whereBetween('date_of_soa',[$from, $to])->paginate($perPage);
                    }else{
                        return $soaSearch->paginate($perPage);
                    }
                }else{

                }
            }else {
                return $soaSearch->get();
            }
        }
        elseif(($request->query('search') != null && $request->query('search') != "null") && ($from !== "undefined" && $to !== "Invalid date")){
            if($request->has('per_page')) {
                $perPage = $request->input('per_page');
                return $soaSearch->Where('arena_name','like', '%'.$request->query('search').'%')
                                 ->whereBetween('date_of_soa',[$from, $to])
                                 ->orWhere('areaCode','like', '%'.$request->query('search').'%')
                                 ->whereBetween('date_of_soa',[$from, $to])->paginate($perPage);
            }else {
                return $soaSearch->get();
            }
        }else{

            // if($request->has('per_page')) {
            //     $perPage = $request->input('per_page');
            //     return $soaSearch->paginate($perPage);
            // } else {
            //     return $soaSearch->get();
            // }

            if($request->has('per_page')) {
                $perPage = $request->input('per_page');
                if($request->has('site') && $site !== 'all') {
                    return $soaSearch->where('refNo','like', '_'.$site.'%')->whereNull('status')->paginate($perPage);
                } else if ($request->has('site')  && $site == 'all') {
                    return $soaSearch->whereNull('status')->paginate($perPage);
                } else {
                    return $soaSearch->get();
                }
            } else {
                return $soaSearch->get();
            }
        }


    }


    public function filterNoArena(Request $request) {
        $soaNoArena = import::with(['BankDetails',
        'arenaDetails.BankDetails',
        'arenaDetails.EmailDetails',
        'arenaDetails.ContactDetails',
        'arenaDetails.UserTeam.userDetails.positionDetails'
        ])->whereDoesntHave('arenaDetails')->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');


        // dd($soa[6]->arenaDetails()->exists());

        if($request->has('per_page')) {
            $perPage = $request->input('per_page');

            if($request->has('from') && $request->has('to')) {
                return $soaNoArena->whereBetween('date_of_soa',[$request->query('from'), $request->query('to')])->paginate($perPage);
            } else {
                return $soaNoArena->paginate($perPage);
            }


        } else {
            return $soaNoArena->get();
        }

    //    return $soa;
    }

    public function filterSite(Request $request) {
        $soaSite = import::with(['BankDetails',
            'arenaDetails.BankDetails',
            'arenaDetails.EmailDetails',
            'arenaDetails.ContactDetails',
            'arenaDetails.UserTeam.userDetails.positionDetails'
    ]);
        $perPage = $request->query('per_page');
        $site = $request->query('site');
        if($request->has('site') && $site !== 'all') {
            if($request->has('from') && $request->has('to')) {
                return $soaSite->whereBetween('date_of_soa',[$request->query('from'), $request->query('to')])->where('refNo','like', 'S'.$site.'%')
                    ->orWhere('refNo','like', 'R'.$site.'%')
                    ->paginate($perPage);
            }else {
                return $soaSite->where('refNo','like', 'S'.$site.'%')
                    ->orWhere('refNo','like', 'R'.$site.'%')
                    ->paginate($perPage);
            }

        } else {
            return $soaSite->paginate($perPage);
        }

    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SavePrimaryBank($id)
    {
        $arena = arena::findOrFail($id);

        if($arena->bank_id == null){
                $bankOwn = BankAccount::where('area_code',$id)->first();
                $arena->bank_id = $bankOwn->id;
                $arena->update();
        }

        return BankAccount::where('id',$arena->bank_id)->first();
    }
    public function bankaccountfilter($id)
    {
        return BankAccount::where('area_code',$id)->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());

        $import = import::upsert($request->all(),['codeEvent']);

        return  $import;


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bankdetialsfilter($id)
    {
        return BankAccount::where('area_code',$id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function arenastatus(Request $request)
    {
        // dump($arena_name);
        // $import = import::where('areaCode',$areaCode)->update([
        //     'status' => 'done',
        // ]);

        $import = import::upsert( $request->all(),['codeEvent']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function countdata()
    {
        $arena = arena::count();
        $user = User::count();
        $import = Import::whereNull('status')->count();

        return Response()->json([
            'arena' => $arena,
            'user' => $user,
            'import' => $import
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {



    }

    public function depositedata(){
//         ->whereNotNull('status')
// ->whereNotNull('status')
        $deposit =  import::with(['BankDetails','arenaDetails.BankDetails'])->where('group','Deposit')->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC')->get();
        $reflenish =  import::with(['BankDetails','arenaDetails.BankDetails'])->where('group','Replenish')->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC')->get();
        return Response()->json([
            'rf' => $reflenish,
            'dp' => $deposit
        ]);
    }

    public function summaryReport(Request $request) {
        $group = $request->query('group');

        $soaSummaryReport = import::with(['BankDetails','arenaDetails.BankDetails'])->where('group', $group);

        if($request->has('per_page')) {
            return $soaSummaryReport->select('date_of_soa')->distinct()->orderBy('date_of_soa', 'DESC')->paginate($request->input('per_page'));
        } else {
            return $soaSummaryReport->select('date_of_soa')->distinct()->orderBy('date_of_soa', 'DESC')->get();
        }
    }




    public function summaryDateRange(Request $request, $from, $to){
        $group = $request->query('group');
        $summaryDateRange = import::with(['BankDetails','arenaDetails.BankDetails'])->where('group', ucfirst($group))->whereBetween('date_of_soa',[$from, $to])->orderBy('date_of_soa', 'DESC')->orderBy('areaCode', 'ASC');

         if($request->has('per_page')) {
            $perPage = $request->input('per_page');
            return $summaryDateRange->paginate($perPage);
        } else {
            return $summaryDateRange->get();
        }
    }





    public function ConvertToExcel($group,$data){
        // ->whereNotNull('status')
        // dd($group);

       return import::where('group',ucfirst($group))
                        ->where('date_of_soa',$data)
                        ->get();
    }

    public function Clearfilterbydate(request $request){

        $status = $request->tab == 'converted' ? 'done' : null;

        $delete = import::whereBetween('date_of_soa',[$request->from, $request->to])
                        ->where('status',$status)
                        ->delete();
        return $delete;
    }

    public function deleteSpecificSoa($id){
        $delete = import::whereId($id)->delete();

        return $delete;
    }


}
