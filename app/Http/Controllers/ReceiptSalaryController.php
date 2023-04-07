<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\User;
use App\Models\Salary;
use App\Events\MakeTask;
use App\Models\Box_year;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Receipt_salary;
use App\Models\Us_qu;
use App\Models\Approval_record;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\NewLessonNotification;

class ReceiptSalaryController extends CMSBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subtitle="سند صرف راتب";
        $title="شوؤن الموظفين";
        $users=User::where("isdelete",0)->where("Status","مفعل")->orderBy('name')->get();

        $employees=Employee::where('isdelete',0)->where('active',1)->orderBy('name')->get();
        return view("cms.receiptSalary.index",compact("title","subtitle",'employees','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentTitle="اضافة سند صرف راتب";
        $title="شوؤن الموظفين";


        $id = 1;
        $id_comp = 1;
        $date = date('Y-m-d');
        $isReceipt = Receipt_salary::count();
        if ($isReceipt>0){
            $receipt = Receipt_salary::where('id_comp','!=',null)->orderBy('id','desc')->first();
            $id = $receipt->id + 1;
            $receiptss = Receipt_salary::where('m_year',$this->getMoneyYear())->where('id_comp','!=',null)->orderBy('id','desc')->first();
            if($receiptss){
            $id_comp = $receiptss->id_comp + 1;
            }else{
                 $id_comp = 1;
            }
        }else{
            $id = 1;
            $id_comp = 1;
        }

        $employees=Employee::where('isdelete',0)->where('active',1)->get();
        return view("cms.receiptSalary.add",compact("title","parentTitle","id","id_comp","date","employees"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,FlasherInterface $flasher)
    {

        $this->validate($request,
            [
                'employee_id' => 'required',
                'month' => 'required',
                'rewards_h' => 'required',
                'receipts_h' => 'required',
                'nets_h' => 'required',
                'salary_h' => 'required',
                'advance_payment_h' => 'required',
                'remainder_h' => 'required',
                'amount_h' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);
            $id_system=Receipt_salary::where('m_year',$this->getMoneyYear())->latest()->first();
            if($id_system){
                $id_sys =$id_system->id_sys + 1;
            }else{
                $id_sys = 1;
            }
        $receipt_salary = Receipt_salary::create([
            'id' => $request->input("id"),
            'id_comp' => $request->input("id_comp"),
            'id_sys' => $id_sys,
            'm_year' => $request->input("edu_year_h"),
            'employee_id' => $request->input("employee_id"),
            'date' => $request->input("date"),
            'month' => $request->input("month"),
            'salary' => $request->input("salary_h"),
            'receipts' => $request->input("receipts_h"),
            'rewards' => $request->input("rewards_h"),
            'nets' => $request->input("nets_h"),
            'advance_payment' => $request->input("advance_payment_h"),
            'remainder' => $request->input("remainder_h"),
            'amount' => $request->input("amount_h"),
            'notes' => $request->input("notes"),
            'box_id' => 4,
            'created_by' => $this->getId()
        ]);



        if ($receipt_salary){
            if(Auth::user()->responsible_id == null){
            $box = Box_year::where('box_id',4)->where('m_year',$this->getMoneyYear())->first();
            $box->expense += $request->input("amount_h");
//            $box->income += $request->input("advance_payment");
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->expense += $request->input("amount_h");
//            $center->income += $request->input("advance_payment");
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense += $request->input("amount_h");
//            $primary->income += $request->input("advance_payment");
            $primary->save();
            $isSalary=Salary::where('id',$request->input("month"))->count();
            if ($isSalary>0){
                $salary=Salary::where('id',$request->input("month"))->first();
                $rr=$salary->remaind;
                $salary->remaind = $rr-$request->input("amount_h");
                $salary->save();
            }


    }else{
        $Receipt_salary = Receipt_salary::latest()->first();
        $add = new Approval_record();
        $add->row_id=$Receipt_salary->id;
        $add->model_id='App\Models\Receipt_salary';
        $add->slug='ReceiptSalary';
        $add->section='صرف رواتب';
        $add->user_id=$Receipt_salary->created_by;
        $add->res_id=Auth::user()->responsible_id;
        $add->date=$Receipt_salary->created_at;
        $add->save();
    }

 }

          /////////////
        $emp_id = Employee::where('id',$receipt_salary->employee_id)->first()->name;
        $us_qu= new Us_qu();
        $us_qu->m_year = $receipt_salary->m_year;
        $us_qu->id_main = $receipt_salary->id;
        $us_qu->id_sys = $receipt_salary->id_sys;
        $us_qu->name = $emp_id;
        $us_qu->type = 'صرف راتب';
        $us_qu->action = 'ادخال';
        $us_qu->amount = $receipt_salary->amount;
        $us_qu->date = $receipt_salary->created_at;
        $us_qu->created_by = $this->getId();
        $us_qu->slug='ReceiptSalary';
        $us_qu->box_id =4;
        $us_qu->save();
         ///////

        $users=User::where('isdelete',0)->where('Status','مفعل')->get();
        foreach($users as $user){

        if($user->hasRole('owner') && $user->id != $this->getId()){
        \Notification::send($user,new NewLessonNotification('ReceiptSalary/'.$receipt_salary->id,$this->getId(),'انشاء صرف راتب ','ReceiptSalary'));
        MakeTask::dispatch($user->id);

        }

        }
        $flasher->addSuccess("تمت عملية الاضافة بنجاح");
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipt_salary  $receipt_salary
     * @return \Illuminate\Http\Response
     */
    public function show($id,FlasherInterface $flasher)
    {
        $parentTitle="عرض سندات الصرف";
        $item=Receipt_salary::where("id",$id)->where("isdelete",0)->first();
        $title="شوؤن الموظفين";

        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/ReceiptSalary/");
        }
        return view("cms.receiptSalary.show",compact("title","item","id","parentTitle"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipt_salary  $receipt_salary
     * @return \Illuminate\Http\Response
     */
    public function edit($id,FlasherInterface $flasher)
    {
        $parentTitle="تعديل سندات الصرف";
        $item=Receipt_salary::where("id",$id)->where("isdelete",0)->first();
        $employees=Employee::where('isdelete',0)->where('active',1)->get();
        $title="شوؤن الموظفين";

        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/ReceiptSalary/");
        }
        return view("cms.receiptSalary.edit",compact("title","item","id","parentTitle","employees"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipt_salary  $receipt_salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FlasherInterface $flasher)
    {


        $this->validate($request,
            [
                'employee_id' => 'required',
                'month' => 'required',
                'receipts_h' => 'required',
                'rewards_h' => 'required',
                'advance_payment_h' => 'required',
                'remainder_h' => 'required',
                'amount' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);

        $item=Receipt_salary::find($id);
        $item->id=$request->input("id");
        $item->id_comp=$request->input("id_comp");
        $item->m_year=$this->getMoneyYear();
        $item->date=$request->input("date");
        $item->employee_id=$request->input("employee_id");
        $item->month=$request->input("month");
        $item->salary=$request->input("salary_h");
        $item->receipts=$request->input("receipts_h");
        $item->rewards=$request->input("rewards_h");
        $advance_payment=$item->advance_payment;
        $item->advance_payment=$request->input("advance_payment_h");
        $item->remainder=$request->input("remainder_h");
        $amount = $item->amount;
        $item->amount=$request->input("amount");
        $item->notes=$request->input("notes");
        $item->updated_by=$this->getId();
        if ($item->save()){
            $box = Box_year::where('box_id',4)->where('m_year',$this->getMoneyYear())->first();
            $box->expense -= $amount - $request->input("amount");
//            $box->income -= $advance_payment - $request->input("advance_payment");
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->expense -= $amount - $request->input("amount");
//            $center->income -= $advance_payment - $request->input("advance_payment");
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense -= $amount - $request->input("amount");
//            $primary->income -= $advance_payment - $request->input("advance_payment");
            $primary->save();
            $isSalary=Salary::where('id',$request->input("month"))->count();
            if ($isSalary>0){
                $salary=Salary::where('id',$request->input("month"))->first();
                $rr=$salary->remaind;
                $salary->remaind = $rr-$request->input("amount");
                $salary->save();
            }

        /////////////
        $emp_id = Employee::where('id',$item->employee_id)->first()->name;
        $us_qu= new Us_qu();
        $us_qu->m_year = $item->m_year;
        $us_qu->id_main = $item->id;
        $us_qu->id_sys = $item->id_sys;
        $us_qu->name = $emp_id;
        $us_qu->type = 'صرف راتب';
        $us_qu->action = 'تعديل';
        $us_qu->amount = $item->amount;
        $us_qu->date = $item->created_at;
        $us_qu->created_by = $this->getId();
        $us_qu->slug='ReceiptSalary';
        $us_qu->box_id =4;
        $us_qu->save();
         ///////


            $users=User::where('isdelete',0)->where('Status','مفعل')->get();
            foreach($users as $user){

            if($user->hasRole('owner') && $user->id != $this->getId()){
            \Notification::send($user,new NewLessonNotification('ReceiptSalary/'.$item->id,$this->getId(),'تعديل صرف راتب ','ReceiptSalary'));
            MakeTask::dispatch($user->id);
            }

            }
        }

        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipt_salary  $receipt_salary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDelete($id,FlasherInterface $flasher)
    {
        $item=Receipt_salary::find($id);
        $item->isdelete=1;
        $item->deleted_by=$this->getId();
        if ($item->save()){
            $box = Box_year::where('box_id',4)->where('m_year',$this->getMoneyYear())->first();
            $box->expense -= $item->amount;
//            $box->income -= $item->advance_payment;
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->expense -= $item->amount;
//            $center->income -= $item->advance_payment;
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense -= $item->amount;
//            $primary->income -= $item->advance_payment;
            $primary->save();

                  /////////////
        $emp_id = Employee::where('id',$item->employee_id)->first()->name;
        $us_qu= new Us_qu();
        $us_qu->m_year = $item->m_year;
        $us_qu->id_main = $item->id;
        $us_qu->id_sys = $item->id_sys;
        $us_qu->name = $emp_id;
        $us_qu->type = 'صرف راتب';
        $us_qu->action = 'حذف';
        $us_qu->amount = $item->amount;
        $us_qu->date = $item->created_at;
        $us_qu->created_by = $this->getId();
        $us_qu->slug='ReceiptSalary';
        $us_qu->box_id =4;
        $us_qu->save();
         ///////

        }

        flash()->addError("تمت عملية الحذف بنجاح");
        return redirect("/CMS/ReceiptSalary/");
    }
}
