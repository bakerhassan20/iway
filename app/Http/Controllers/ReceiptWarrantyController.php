<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\User;
use App\Models\Salary;
use App\Events\MakeTask;
use App\Models\Box_year;
use App\Models\Us_qu;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Receipt_warranty;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\NewLessonNotification;

class ReceiptWarrantyController extends CMSBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subtitle="ادارة الضمان الاجتماعي";
        $title="شوؤن الموظفين";
        $employees=Employee::where('isdelete',0)->where('active',1)->orderBy('name')->get();
        $users=User::where("isdelete",0)->where("Status","مفعل")->orderBy('name')->get();
        return view("cms.receiptWarranty.index",compact("title","subtitle",'employees','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentTitle="اضافه سند الضمان الاجتماعي";
        $title="شوؤن الموظفين";

        $id = 1;
        $id_comp = 1;
        $date = date('Y-m-d');
        $isReceipt = Receipt_warranty::count();
        if ($isReceipt>0){
            $receipt = Receipt_warranty::where('id_comp','!=',null)->orderBy('id','desc')->first();
            $id = $receipt->id + 1;

            $receiptss = Receipt_warranty::where('m_year',$this->getMoneyYear())->where('id_comp','!=',null)->orderBy('id','desc')->first();
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
        $salaries=Salary::where('isdelete',0)->get();
        return view("cms.receiptWarranty.add",compact("title","parentTitle","id","id_comp","date","employees","salaries"));
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
                'salary' => 'required',
                'amount_h' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);


              //لمنع تكرار الشهر
        /*     $Receipt_war=Receipt_warranty::where('m_year',$this->getMoneyYear())->where('salary_id',$request->input("salary"))->first();
            if($Receipt_war){
                $flasher->addWarning("تم صرف ضمان لهذا الشهر من قبل");
                return Redirect::back();
            } */


            $id_system=Receipt_warranty::where('m_year',$this->getMoneyYear())->latest()->first();
            if($id_system){
                $id_sys =$id_system->id_sys + 1;
            }else{
                $id_sys = 1;
            }
        $receipt_warranty = Receipt_warranty::create([
            'id' => $request->input("id"),
            'id_comp' => $request->input("id_comp"),
            'id_sys' => $id_sys,
            'm_year' => $request->input("edu_year_h"),
            'date' => $request->input("date"),
            'salary_id' => $request->input("salary"),
            'amount' => $request->input("amount_h"),
            'notes' => $request->input("notes"),
            'box_id' => 4,
            'created_by' => $this->getId()
        ]);

        if ($receipt_warranty){
            $box = Box_year::where('box_id',4)->where('m_year',$this->getMoneyYear())->first();
            $box->expense += $request->input("amount_h");
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->expense += $request->input("amount_h");
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense += $request->input("amount_h");
            $primary->save();

            //////////////////
        $emp_id = Employee::where('id',$receipt_warranty->employee_id)->first();
        if( $emp_id){
         $emp_id =$emp_id->name;

        }else{
         $emp_id =null;
        }
       $us_qu= new Us_qu();
       $us_qu->m_year = $receipt_warranty->m_year;
       $us_qu->id_main = $receipt_warranty->id;
       $us_qu->id_sys = $receipt_warranty->id_sys;
       $us_qu->name = $emp_id;
       $us_qu->type = 'صرف الضمان';
       $us_qu->action = 'ادخال';
       $us_qu->amount = $receipt_warranty->amount;
       $us_qu->date = $receipt_warranty->created_at;
       $us_qu->created_by = $receipt_warranty->created_by;
       $us_qu->slug='ReceiptWarranty';
       $us_qu->box_id =4;
       $us_qu->save();


       ////////////////


       if(Auth::user()->responsible_id != null){
        $user=User::where('id',Auth::user()->responsible_id)->get();
       \Notification::send($user,new NewLessonNotification('ReceiptWarranty/'.$receipt_warranty->id,$this->getId(),'انشاء صرف الضمان ','ReceiptWarranty'));
       MakeTask::dispatch(Auth::user()->responsible_id);



       }

        }



        $flasher->addSuccess("تمت عملية الاضافة بنجاح");
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipt_warranty  $receipt_warranty
     * @return \Illuminate\Http\Response
     */
    public function show($id,FlasherInterface $flasher)
    {


        $parentTitle="عرض سند الضمان الاجتماعي";
        $item=Receipt_warranty::where("id",$id)->where("isdelete",0)->first();

        $title="شوؤن الموظفين";
        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/ReceiptWarranty/");
        }
        return view("cms.receiptWarranty.show",compact("title","item","id","parentTitle"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipt_warranty  $receipt_warranty
     * @return \Illuminate\Http\Response
     */
    public function edit($id,FlasherInterface $flasher)
    {
        $parentTitle="تعديل سند الضمان الاجتماعي ";
        $item=Receipt_warranty::where("id",$id)->where("isdelete",0)->first();
        $employees=Employee::where('isdelete',0)->where('active',1)->get();
        $salaries=Salary::where('isdelete',0)->get();
        $title="شوؤن الموظفين";
        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/ReceiptWarranty/");
        }
        return view("cms.receiptWarranty.edit",compact("title","item","id","employees","salaries","parentTitle"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipt_warranty  $receipt_warranty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FlasherInterface $flasher)
    {
        $this->validate($request,
            [
                'salary_id' => 'required',
                'amount' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);

        $item=Receipt_warranty::find($id);
        $item->id=$request->input("id");
        $item->id_comp=$request->input("id_comp");
        $item->m_year=$request->input("m_year");
        $item->salary_id=$request->input("salary_id");
        $amount = $item->amount;
        $item->amount=$request->input("amount");
        $item->notes=$request->input("notes");
        $item->updated_by=$this->getId();
        if ($item->save()){
            $box = Box_year::where('box_id',4)->where('m_year',$this->getMoneyYear())->first();
            $box->expense -= $amount - $request->input("amount");
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->expense -= $amount - $request->input("amount");
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense -= $amount - $request->input("amount");
            $primary->save();


        //////////////////
        $emp_id = Employee::where('id',$item->employee_id)->first();
        if( $emp_id){
         $emp_id =$emp_id->name;

        }else{
         $emp_id =null;
        }
       $us_qu= new Us_qu();
       $us_qu->m_year = $item->m_year;
       $us_qu->id_main = $item->id;
       $us_qu->id_sys = $item->id_sys;
       $us_qu->name = $emp_id;
       $us_qu->type = 'صرف الضمان';
       $us_qu->action = 'تعديل';
       $us_qu->amount = $item->amount;
       $us_qu->date = $item->created_at;
       $us_qu->created_by = $this->getId();
       $us_qu->slug='ReceiptWarranty';
       $us_qu->box_id =4;
       $us_qu->save();

       ////////////////
       if(Auth::user()->responsible_id != null){
        $user=User::where('id',Auth::user()->responsible_id)->get();
        \Notification::send($user,new NewLessonNotification('ReceiptWarranty/'.$item->id,$this->getId(),'تعديل صرف الضمان ','ReceiptWarranty'));
        MakeTask::dispatch(Auth::user()->responsible_id);


        }


        }



        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipt_warranty  $receipt_warranty
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDelete($id,FlasherInterface $flasher)
    {
        $item=Receipt_warranty::find($id);
        $item->isdelete=1;
        $item->deleted_by=$this->getId();
        if ($item->save()){
            $box = Box_year::where('box_id',4)->where('m_year',$this->getMoneyYear())->first();
            $box->expense -= $item->amount;
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->expense -= $item->amount;
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense -= $item->amount;
            $primary->save();

        //////////////////
        $emp_id = Employee::where('id',$item->employee_id)->first();
        if( $emp_id){
         $emp_id =$emp_id->name;

        }else{
         $emp_id =null;
        }
       $us_qu= new Us_qu();
       $us_qu->m_year = $item->m_year;
       $us_qu->id_main = $item->id;
       $us_qu->id_sys = $item->id_sys;
       $us_qu->name = $emp_id;
       $us_qu->type = 'صرف الضمان';
       $us_qu->action = 'حذف';
       $us_qu->amount = $item->amount;
       $us_qu->date = $item->created_at;
       $us_qu->created_by = $this->getId();
       $us_qu->slug='ReceiptWarranty';
       $us_qu->box_id =4;
       $us_qu->save();

       ////////////////


        }
        flash()->addError("تمت عملية الحذف بنجاح");
        return redirect("/CMS/ReceiptWarranty/");
    }
}
