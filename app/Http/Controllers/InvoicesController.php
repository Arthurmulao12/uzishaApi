<?php

namespace App\Http\Controllers;

use App\Models\Debts;
use App\Models\Invoices;
use App\Models\DebtPayments;
use App\Models\InvoiceDetails;
use Illuminate\Support\Facades\DB;
use App\Models\StockHistoryController;
use App\Http\Requests\StoreInvoicesRequest;
use App\Http\Requests\UpdateInvoicesRequest;
use App\Models\CustomerController;
use App\Models\DepositServices;
use App\Models\DepositsUsers;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enterpriseid)
    {
        $list=collect(Invoices::where('enterprise_id','=',$enterpriseid)->where('type_facture','!=','order')->get());
        $listdata=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $listdata;
    }

    /**
     * Cancelling
     */
    public function cancelling(Request $request){
        $invoice=Invoices::find($request['id']);
        if($invoice){
            $request['status']='cancelled';
            $invoice->update($request->all());

            return response()->json([
                'data' =>$this->show($invoice),
                'message'=>'cancelled'
            ]);
        }else{
            return response()->json([
                'data' =>$this->show($invoice),
                'message'=>'failed'
            ]);
        }
    }   
     /**
      * for a specific users
      */
    public function foraspecificuser(Request $request){

        if(isset($request['from']) && !empty($request['from']) && isset($request['to']) && !empty($request['to'])){
            $list=collect(Invoices::where('edited_by_id','=',$request->user_id)
            ->whereBetween('created_at',[$request['from'].' 00:00:00',$request['to'].' 23:59:59'])
            ->get());
            $listdata=$list->map(function ($item,$key){
                return $this->show($item);
            });
            return $listdata;
        }
        else{
            $from=date('Y-m-d');
            $list=collect(Invoices::where('edited_by_id','=',$request->user_id)
            ->whereBetween('created_at',[$from.' 00:00:00',$from.' 23:59:59'])->get());
            $listdata=$list->map(function ($item,$key){
                return $this->show($item);
            });
            return $listdata;
        }
      
    }

    public function enterpriseorders($enterpriseid){

        $list=collect(Invoices::where('enterprise_id','=',$enterpriseid)->where('type_facture','=','order')->get());
        $listdata=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $listdata;
    }

    public function userorders($user_id){

        $list=collect(Invoices::where('edited_by_id','=',$user_id)->where('type_facture','=','order')->get());
        $listdata=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $listdata;
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
     * @param  \App\Http\Requests\StoreInvoicesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoicesRequest $request)
    {
        $User=$this->getinfosuser($request['edited_by_id']);
        $Ese=$this->getEse($request['edited_by_id']);
        if($User && $Ese){
            if($this->isactivatedEse($Ese['id'])){
                return $this->saveInvoice($request);
            }else{
                //count numbers of invoices done
                $sumInvoices =Invoices::select(DB::raw('count(*) as number'))->where('enterprise_id','=',$Ese['id'])->get('number')->first();
                if ($sumInvoices['number']>=500) {
                    return response()->json([
                        'data' =>'',
                        'message'=>'invoices number exceeded'
                    ]);
                }else{
                    return $this->saveInvoice($request);
                }
            }
        }else{
            return response()->json([
                'data' =>'',
                'message'=>'user unknown'
            ]); 
        }
    }

    public function saveInvoice(StoreInvoicesRequest $request){
             
            $request['uuid']=$this->getUuId('F','C');
            $invoice=Invoices::create($request->all());
            //enregistrement des details
            if(isset($request->details)){
                foreach ($request->details as $detail) {
                    $detail['invoice_id']=$invoice['id'];
                    $detail['total']=$detail['quantity']*$detail['price'];
                    InvoiceDetails::create($detail);
                    if((isset($request->type_facture) && $request->type_facture=='cash') || (isset($request->type_facture) && $request->type_facture=='credit') )
                    {
                        if(isset($detail['type_service']) && $detail['type_service']=='1'){
                            $stockbefore=DepositServices::where('deposit_id','=',$detail['deposit_id'])->where('service_id','=',$detail['service_id'])->get()[0];
                            DB::update('update deposit_services set available_qte = available_qte - ? where service_id = ? and deposit_id = ?',[$detail['quantity'],$detail['service_id'],$detail['deposit_id']]);
                            
                            StockHistoryController::create([
                                'service_id'=>$detail['service_id'],
                                'user_id'=>$invoice['edited_by_id'],
                                'invoice_id'=>$invoice['id'],
                                'quantity'=>$detail['quantity'],
                                'price'=>$detail['price'],
                                'type'=>'withdraw',
                                'type_approvement'=>$invoice['type_facture'],
                                'enterprise_id'=>$request['enterprise_id'],
                                'motif'=>'vente',
                                'depot_id'=>$detail['deposit_id'],
                                'quantity_before'=>$stockbefore->available_qte,
                            ]);
                        }
                    }
                }
            }
            //check if debt
            if($invoice['type_facture']=='credit'){
                if($invoice['customer_id']>0){
                    Debts::create([
                        'created_by_id'=>$invoice['edited_by_id'],
                        'customer_id'=>$invoice['customer_id'],
                        'invoice_id'=>$invoice['id'],
                        'status'=>'0',
                        'amount'=>$invoice['total']-$invoice['amount_paid'],
                        'sold'=>$invoice['total']-$invoice['amount_paid'],
                        'uuid'=>$this->getUuId('D','C'),
                        'sync_status'=>'1'
                    ]);
                }
            }

            return response()->json([
                'data' =>$this->show($invoice),
                'message'=>'can make invoice'
            ]);
    }

    /**
     * Saving Offline invoices
     */
    public function storebySafeGuard(StoreInvoicesRequest $request){
        $User=$this->getinfosuser($request['invoice']['edited_by_id']);
        $Ese=$this->getEse($request['invoice']['edited_by_id']);
        if($User && $Ese){
            if($this->isactivatedEse($Ese['id'])){
                return $this->saveOfflineInvoice($request);
            }else{
                //count numbers of invoices done
                $sumInvoices =Invoices::select(DB::raw('count(*) as number'))->where('enterprise_id','=',$Ese['id'])->get('number')->first();
                if ($sumInvoices['number']>=500) {
                    return response()->json([
                        'data' =>'',
                        'message'=>'invoices number exceeded'
                    ]);
                }else{
                    return $this->saveOfflineInvoice($request);
                }
            }
        }else{
            return response()->json([
                'data' =>'',
                'message'=>'user unknown'
            ]); 
        }
    }

    /**
     * SaveOffline Invoice
     */
    public function saveOfflineInvoice(StoreInvoicesRequest $request){
 
        // if(empty($request['invoice']['customer_id'])){
        //     // if (isset($request['invoice']['customer_uuid']) && !empty($request['invoice']['customer_uuid'])){
        //     //     # code...
        //     // }
        //     $customer=CustomerController::where('uuid','=',$request['invoice']['customer_uuid'])->get()->first();
        //     $request['invoice']['customer_id']=$customer->id;
        // }
        $invoice=Invoices::create($request['invoice']);
        
        //enregistrement des details
        if(isset($request->details)){
            foreach ($request->details as $detail) {
                $detail['invoice_id']=$invoice['id'];
                $detail['total']=$detail['quantity']*$detail['price'];
                InvoiceDetails::create($detail);
            }
        }
        return response()->json([
            'data' =>$this->show($invoice),
            'message'=>'can make invoice'
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show(Invoices $invoices)
    {
        $details=[];
        $debt=[];
        $payments=[];

        $details=InvoiceDetails::leftjoin('moneys as M','invoice_details.money_id','=','M.id')
        ->leftjoin('services_controllers','invoice_details.service_id','=','services_controllers.id')
        ->leftjoin('unit_of_measure_controllers as UOM','services_controllers.uom_id','=','UOM.id')
        ->where('invoice_details.invoice_id','=',$invoices->id)
        ->get(['UOM.name as uom_name','UOM.symbol as uom_symbol','M.money_name','M.abreviation','services_controllers.name as service_name','invoice_details.*']);

        $invoice=Invoices::leftjoin('customer_controllers as C', 'invoices.customer_id','=','C.id')
        ->leftjoin('moneys as M', 'invoices.money_id','=','M.id')
        ->leftjoin('users as U', 'invoices.edited_by_id','=','U.id')
        ->leftjoin('tables as T', 'invoices.table_id','=','T.id')
        ->leftjoin('servants as S', 'invoices.servant_id','=','S.id')
        ->where('invoices.id', '=', $invoices->id)
        ->get(['T.id as table_id','T.name as table_name','S.id as servant_id','S.name as servant_name','M.abreviation','M.money_name','U.user_name','U.full_name','C.customerName as customer_name','invoices.*'])[0];

        $debt=Debts::join('invoices as I','debts.invoice_id','=','I.id')
        ->leftjoin('moneys as M','I.money_id','=','M.id')
        ->leftjoin('customer_controllers as C','I.customer_id','=','C.id')
        ->where('invoice_id','=',$invoices->id)
        ->get(['M.money_name','M.abreviation','C.customerName','I.uuid as invoiceUuid','I.total as invoice_total_amount','I.amount_paid as invoice_amount_paid','debts.*']);
        if(count($debt)>0){
            $payments=DebtPayments::where('debt_payments.debt_id', '=', $debt[0]['id'])->get();
        }
       
      
        // return ['invoice'=>$invoice,'details'=>$details];
        return ['invoice'=>$invoice,'details'=>$details,'debt'=>$debt,'payments'=>$payments];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoices $invoices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateInvoicesRequest  $request
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoicesRequest $request, Invoices $invoices)
    {
       return $invoices->update($request->all());
    }

    /**
     * get for a specific customer
     */
    public function foracustomer($customerid){
        $list=collect(Invoices::where('customer_id','=',$customerid)->get());
        $listdata=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $listdata;
    }

    /**
     * compte courant for a specific customer
     */
    public function comptecourant($customerid){

        $list=collect(Invoices::where('customer_id','=',$customerid)->where('type_facture','=','credit')->get());
        $listdata=$list->map(function ($item,$key){
            return $this->show($item);
        });
        return $listdata;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoices $invoices)
    {
        //before deleting remove details
            $details=InvoiceDetails::where('invoice_id','=',$invoices->id)->get();
            foreach ($details as $detail) {
                InvoiceDetails::destroy($detail);
            }
        //remove stock history and making returning stock
        $histories=StockHistoryController::where('invoice_id','=',$invoices->id);
        foreach($histories as $history){
            $history['type']='discount';
            $history['motif']='ristourne appliqué à la suppréssion facture';
            StockHistoryController::create($history);
            StockHistoryController::destroy($history);
        }
        //remove debts and payments raws
            $debts=Debts::where('invoice_id','=',$invoices->id)->get();
            foreach($debts as $debt){
                $payments=DebtPayments::where('debt_payments.debt_id', '=', $debt->id)->get();
                foreach ($payments as $payment) {
                    DebtPayments::destroy($payment);
                }
                Debts::destroy($debt);
            }
            
      return  Invoices::destroy($invoices);
    }
}
