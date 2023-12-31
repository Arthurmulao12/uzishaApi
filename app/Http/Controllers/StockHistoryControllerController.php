<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DepositsUsers;
use App\Models\DepositController;
use Illuminate\Support\Facades\DB;
use App\Models\StockHistoryController;
use App\Http\Requests\StoreStockHistoryControllerRequest;
use App\Http\Requests\UpdateStockHistoryControllerRequest;
use App\Models\DepositServices;
use App\Models\ServicesController;

class StockHistoryControllerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enterprise)
    {
        $list=collect(StockHistoryController::where('enterprise_id','=',$enterprise)->get());
        $list_data=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $list_data;
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStockHistoryControllerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStockHistoryControllerRequest $request)
    {
        $response=['message'=>'fail','data'=>[]];

        if(isset($request->depot_id) && ($request->depot_id)>=1){
           
        }else{
            //looking for it deposit
            $deposit=DepositsUsers::where('user_id','=',$request['user_id'])->get()[0];
            $request['depot_id']=$deposit->deposit_id;
        }
       
        $stockbefore=DepositServices::where('deposit_id','=',$request['depot_id'])->where('service_id','=',$request['service_id'])->get();
        if (count($stockbefore)>0) {
            $request['quantity_before']=$stockbefore[0]->available_qte;
        } else {
            //affect service to the deposit with the qty sent
            DepositServices::create([
                'deposit_id'=>$request['depot_id'],
                'service_id'=>$request['service_id'],
                'available_qte'=>0
            ]);
        }
        
       

        if($request['type']=='entry'){
            DB::update('update deposit_services set available_qte = available_qte + ? where service_id = ? and deposit_id = ?',[$request['quantity'],$request['service_id'],$request['depot_id']]);
            return $this->show(StockHistoryController::create($request->all()));
        }else if($request['type']=='withdraw'){

            if($request['quantity_before']>=$request['quantity']){
                DB::update('update deposit_services set available_qte = available_qte - ? where service_id = ? and deposit_id = ?',[$request['quantity'],$request['service_id'],$request['depot_id']]);
                return $this->show(StockHistoryController::create($request->all()));
            }
           
        }else{
            return $response;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StockHistoryController  $stockHistoryController
     * @return \Illuminate\Http\Response
     */
    public function show(StockHistoryController $stockHistoryController)
    {
        return StockHistoryController::leftjoin('deposit_controllers as D','stock_history_controllers.depot_id','=','D.id')
        ->leftjoin('services_controllers as S','stock_history_controllers.service_id','=','S.id')
        ->leftjoin('unit_of_measure_controllers as UOM','S.uom_id','=','UOM.id')
        ->leftjoin('users as U','stock_history_controllers.user_id','=','U.id')
        ->where('stock_history_controllers.id','=',$stockHistoryController['id'])->get(['stock_history_controllers.*','S.name as service_name','UOM.symbol as uom_symbol','D.name as deposit_name','U.user_name as done_by_name'])[0];
    }

    /**
     * get all story by service id
     */

     public function getbyservice($serviceid){

        return StockHistoryController::leftjoin('deposit_controllers as D','stock_history_controllers.depot_id','=','D.id')
        ->leftjoin('services_controllers as S','stock_history_controllers.service_id','=','S.id')
        ->leftjoin('users as U','stock_history_controllers.user_id','=','U.id')
        ->where('stock_history_controllers.service_id','=',$serviceid)
        ->orderby('stock_history_controllers.created_at','desc')
        ->get(['stock_history_controllers.*','S.name as service_name','D.name as deposit_name','U.user_name as done_by_name']);
     }

     /**
      * get all story by multiple services and periodic 
      * 
     */
     public function multipleservices(Request $request){
        $datatosend=[];
        //find the user
        if(isset($request->user_id) && !empty($request->user_id) && $request->user_id>0){
            $user = $this->getinfosuser($request->user_id);
            if($user){
                if($user['user_type']=='super_admin'){
                    foreach ($request['services'] as $key => $service) {

                        $list=collect(StockHistoryController::where('service_id','=',$service['service']['id'])
                        ->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
                        ->orderby('created_at','desc')
                        ->get());
                        $list_data=$list->map(function ($item,$key){
                            return $this->show($item);
                        });
                        array_push($datatosend,$list_data);
                    }
                }
            }
        }
        return $datatosend;
     }

     public function getbyuser(Request $request){
        $grouped_data=$this->getbyusergrouped($request);
        $list_data=[];
        $user=$this->getinfosuser($request['user_id']);
        $enterprise=$this->getEse($user['id']);
        if(empty($request->from) && empty($request->to)){
            $request['from']= date('Y-m-d');
            $request['to']=date('Y-m-d');
        }

        if ($user['user_type']=='super_admin') {
            $deposits=DepositController::where('enterprise_id','=',$enterprise['id'])->get();
            foreach ($deposits as $deposit) {
                $list=collect(StockHistoryController::where('depot_id','=',$deposit['id'])
                ->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
                ->orderby('created_at','asc')
                ->get());
                foreach ($list as $item) {
                    array_push($list_data,$this->show($item));
                }
            }
        } else {
            $deposits=DepositsUsers::where('user_id','=',$request->user_id)->get();
            foreach ($deposits as $deposit) {
                $list=collect(StockHistoryController::where('depot_id','=',$deposit->deposit_id)
                ->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
                ->orderby('created_at','desc')
                ->get());
                foreach ($list as $item) {
                    array_push($list_data,$this->show($item));
                }
             }
        }
        
        return ['ungrouped'=>$list_data,'grouped'=>$grouped_data,'services_group'=>[],'from'=>$request['from'],'to'=>$request['to'],'tabular'=>$this->newReportStockHistory($request)];
     }
     
     public function getbyusergrouped(Request $request){
        $list_data=[];
        $user=$this->getinfosuser($request['user_id']);
        $enterprise=$this->getEse($user['id']);
        if(empty($request->from) && empty($request->to)){
            $request['from']= date('Y-m-d');
            $request['to']=date('Y-m-d');
        }

        if ($user['user_type']=='super_admin') {
            $deposits=DepositController::where('enterprise_id','=',$enterprise['id'])->get();
            foreach ($deposits as $deposit) {
                $depositArray=['deposit'=>$deposit,'articles'=>[]];

                $articles= DB::table('stock_history_controllers')
                    ->leftjoin('services_controllers as S','stock_history_controllers.service_id','=','S.id')
                    ->leftjoin('unit_of_measure_controllers as UOM','S.uom_id','=','UOM.id')
                    ->where('stock_history_controllers.depot_id','=',$deposit['id'])
                    ->whereBetween('stock_history_controllers.created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
                    ->select('stock_history_controllers.service_id','S.name','UOM.symbol',DB::raw('sum(stock_history_controllers.quantity) as quantity_total'))
                    ->groupBy('stock_history_controllers.service_id','S.name','UOM.symbol')
                    ->get();
                    foreach ($articles as $key => $value) {
                        array_push($depositArray['articles'],$value);
                    }
                    array_push($list_data,$depositArray);
            }
        } else {
            $deposits=DepositsUsers::join('deposit_controllers as D','deposits_users.deposit_id','=','D.id')->where('deposits_users.user_id','=',$request->user_id)->get('D.*');
            foreach ($deposits as $deposit) {
                $depositArray=['deposit'=>$deposit,'articles'=>[]];

                $articles= DB::table('stock_history_controllers')
                    ->leftjoin('services_controllers as S','stock_history_controllers.service_id','=','S.id')
                    ->leftjoin('unit_of_measure_controllers as UOM','S.uom_id','=','UOM.id')
                    ->where('stock_history_controllers.depot_id','=',$deposit['id'])
                    ->whereBetween('stock_history_controllers.created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
                    ->select('stock_history_controllers.service_id','S.name','UOM.symbol',DB::raw('sum(stock_history_controllers.quantity) as quantity_total'))
                    ->groupBy('stock_history_controllers.service_id','S.name','UOM.symbol')
                    ->get();
                    foreach ($articles as $key => $value) {
                        array_push($depositArray['articles'],$value);
                    }
                    array_push($list_data,$depositArray);
             }
        }
        
        return $list_data;
     } 
     
     public function newReportStockHistory(Request $request){
        $list_data=[];
        $serviceCtrl= new ServicesControllerController();
        $user=$this->getinfosuser($request['user_id']);
        $enterprise=$this->getEse($user['id']);
        if(empty($request->from) && empty($request->to)){
            $request['from']= date('Y-m-d');
            $request['to']=date('Y-m-d');
        }

        if ($user['user_type']=='super_admin') {
            $services=StockHistoryController::where('type','=','entry')
            ->where('enterprise_id','=',$enterprise['id'])
            ->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
            ->select('service_id')
            ->groupBy('service_id')
            ->get();
            foreach ($services as $service) {
                $entries=StockHistoryController::select(DB::raw('sum(quantity) as totalEntries'))->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])->where('service_id','=',$service['service_id'])->where('type','=','entry')->get('totalEntries')->first();
                $withdraw=StockHistoryController::select(DB::raw('sum(quantity) as totalWithdraw'))->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])->where('service_id','=',$service['service_id'])->where('type','=','withdraw')->get('totalWithdraw')->first();
                $before=StockHistoryController::select(DB::raw('sum(quantity_before) as totalBefore'))->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])->where('service_id','=',$service['service_id'])->get('totalBefore')->first();
                $service['totalEntries']=$entries['totalEntries'];
                $service['totalWithdraw']=$withdraw['totalWithdraw'];
                $service['sold']=$entries['totalEntries']-$withdraw['totalWithdraw'];
                $service['totalBefore']=$before['totalBefore'];
                $service['service']=$serviceCtrl->show(ServicesController::find($service['service_id']))['service'];
                array_push($list_data,$service);
            }
          
        } else {
            $deposits=DepositsUsers::join('deposit_controllers as D','deposits_users.deposit_id','=','D.id')->where('deposits_users.user_id','=',$request->user_id)->get('D.*');
            foreach ($deposits as $deposit) {
                $services=StockHistoryController::where('type','=','entry')
                ->where('depot_id','=',$deposit->id)
                ->where('user_id','=',$request->user_id)
                ->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
                ->select('service_id')
                ->groupBy('service_id')
                ->get();

                foreach ($services as $service) {
                    $entries=StockHistoryController::select(DB::raw('sum(quantity) as totalEntries'))->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])->where('depot_id','=',$deposit->id)->where('service_id','=',$service['service_id'])->where('type','=','entry')->where('user_id','=',$request->user_id)->get('totalEntries')->first();
                    $withdraw=StockHistoryController::select(DB::raw('sum(quantity) as totalWithdraw'))->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])->where('depot_id','=',$deposit->id)->where('service_id','=',$service['service_id'])->where('type','=','withdraw')->where('user_id','=',$request->user_id)->get('totalWithdraw')->first();
                    $before=StockHistoryController::select(DB::raw('sum(quantity_before) as totalBefore'))->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])->where('depot_id','=',$deposit->id)->where('service_id','=',$service['service_id'])->where('user_id','=',$request->user_id)->get('totalBefore')->last();
                    $service['totalEntries']=$entries['totalEntries'];
                    $service['totalWithdraw']=$withdraw['totalWithdraw'];
                    $service['sold']=$entries['totalEntries']-$withdraw['totalWithdraw'];
                    // $service['totalBefore']=$before['totalBefore'];
                    $service['service']=$serviceCtrl->show(ServicesController::find($service['service_id']))['service'];
                    array_push($list_data,$service);
                }
                    
             }
        }
        
        return response()->json([
            'data'=>$list_data,
            'from'=>$request['from'],
            'to'=>$request['to']
        ]);
     } 

     public function fordeposit(Request $request){

        $list=collect(StockHistoryController::where('depot_id','=',$request->deposit_id)->orderby('created_at','desc')->get());
        $list_data=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $list_data;
     }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StockHistoryController  $stockHistoryController
     * @return \Illuminate\Http\Response
     */
    public function edit(StockHistoryController $stockHistoryController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStockHistoryControllerRequest  $request
     * @param  \App\Models\StockHistoryController  $stockHistoryController
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStockHistoryControllerRequest $request, StockHistoryController $stockHistoryController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StockHistoryController  $stockHistoryController
     * @return \Illuminate\Http\Response
     */
    public function destroy(StockHistoryController $stockHistoryController)
    {
        //
    }
}
