<?php

namespace App\Http\Controllers;

use App\Models\Debts;
use App\Models\DebtPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreDebtsRequest;
use App\Http\Requests\UpdateDebtsRequest;

class DebtsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($enterprise_id)
    {
        $list=collect(Debts::join('invoices','debts.invoice_id','=','invoices.id')->where('enterprise_id','=',$enterprise_id)->where('debts.status','=','0')->get(['debts.*']));
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
     * @param  \App\Http\Requests\StoreDebtsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDebtsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Debts  $debts
     * @return \Illuminate\Http\Response
     */
    public function show(Debts $debts)
    {
        $debt=Debts::join('invoices as I','debts.invoice_id','=','I.id')
        ->leftjoin('moneys as M','I.money_id','=','M.id')
        ->leftjoin('customer_controllers as C','I.customer_id','=','C.id')
        ->where('debts.id','=',$debts->id)
        ->get(['M.money_name','M.abreviation','C.customerName','I.uuid as invoiceUuid','I.total as invoice_total_amount','I.amount_paid as invoice_amount_paid','debts.*'])[0];

        $payments=DebtPayments::leftjoin('users as U', 'debt_payments.done_by_id','=','U.id')
        ->where('debt_payments.debt_id', '=', $debt['id'])
        ->get(['U.user_name','debt_payments.*']);

        return ['debt'=>$debt,'payments'=>$payments];
    }

    /**
     * Payment
     */
    public function payment_debt(Request $request){
        $message='';
        $debt=Debts::where('id','=',$request['debt_id'])->get()[0];
        if($debt){
            if($debt['sold']>0 && $debt['status']=='0' && $request['amount_payed']<=$debt['sold']){
                $request['uuid']=$this->getUuId('P','D');
                $request['sync_status']='1';
                DebtPayments::create($request->all());
                $sumpayments=0;
                $allpayments=DebtPayments::where('debt_id','=',$debt['id'])->get();
                foreach ($allpayments as $key => $payment) {
                    $sumpayments=$sumpayments+$payment['amount_payed'];
                }

                if($sumpayments==$debt['amount']){
                    //update debt
                    DB::update('update debts set sold = ?, status= ? where id = ? ',[$debt['amount']-$sumpayments,'1',$debt['id']]);
                }else{
                    //update debt
                    DB::update('update debts set sold = ? where id = ? ',[$debt['amount']-$sumpayments,$debt['id']]);
                }
                
                $message='success';
                $debt=Debts::where('id','=',$request['debt_id'])->get()[0];
            }else{
                $message='finished';
            }
        }

        return response()->json([
            'data' =>$this->show($debt),
            'message'=>$message
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Debts  $debts
     * @return \Illuminate\Http\Response
     */
    public function edit(Debts $debts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDebtsRequest  $request
     * @param  \App\Models\Debts  $debts
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDebtsRequest $request, Debts $debts)
    {
        return $this->show(Debts::find($debts->update($request->all())));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Debts  $debts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Debts $debts)
    {
        DebtPayments::where('debt_payments.debt_id', '=', $debts->id)->delete();
        
         return Debts::destroy($debts);
    }
}
