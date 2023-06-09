<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\User;
use App\Events\MakeTask;
use App\Models\Box_year;
use App\Models\Us_qu;
use App\Models\Prin_t;
use App\Models\Receipt_box;
use Illuminate\Http\Request;
use App\Models\Approval_record;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\NewLessonNotification;

class ReceiptBoxController extends CMSBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subtitle="ادارة سندات الصرف-الصناديق";
        $title="شوؤن الموظفين";
        $items=Receipt_box::where('isdelete',0)->paginate(10);
        $users=User::where("isdelete",0)->where("Status","مفعل")->orderBy('name')->get();
        $boxes=Box::where('isdelete',0)->where('active',1)->where('type',147)->orderBy('name')->get();
        return view("cms.receiptBox.index",compact("title","subtitle","items",'users','boxes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentTitle="اضافة سند صرف - صناديق";
        $title="شوؤن الموظفين";


        $id = 1;
        $id_comp = 1;
        $date = date('Y-m-d');
        $isReceipt = Receipt_box::count();
        if ($isReceipt>0){
            $receipt = Receipt_box::where('id_comp','!=',null)->orderBy('id','desc')->first();
            $id = $receipt->id + 1;

            $receiptss = Receipt_box::where('m_year',$this->getMoneyYear())->where('id_comp','!=',null)->orderBy('id','desc')->first();

            if($receiptss){
            $id_comp = $receiptss->id_comp + 1;
            }else{
                 $id_comp = 1;
            }
        }else{
            $id = 1;
            $id_comp = 1;
        }

        $boxes=Box::where('type',147)->where('active',1)->where('isdelete',0)->get();
        return view("cms.receiptBox.add",compact("title","parentTitle","id","id_comp","date","boxes"));
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
                'box_id' => 'required',
                'type' => 'required',
                'amount' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);
            $id_system=Receipt_box::where('m_year',$this->getMoneyYear())->latest()->first();
            if($id_system){
                $id_sys =$id_system->id_sys + 1;
            }else{
                $id_sys = 1;
            }
        $receipt_box = Receipt_box::create([
            'id' => $request->input("id"),
            'id_comp' => $request->input("id_comp"),
            'id_sys' => $id_sys,
            'm_year' => $request->input("edu_year_h"),
            'box_id' => $request->input("box_id"),
            'date' => $request->input("date"),
            'type' => $request->input("type"),
            'amount' => $request->input("amount"),
            'notes' => $request->input("notes"),
            'created_by' => $this->getId()
        ]);

        if ($receipt_box){
         if(Auth::user()->responsible_id == null){
            $box = Box_year::where('box_id',$request->input("box_id"))->where('m_year',$this->getMoneyYear())->first();
            $box->expense += $request->input("amount");
            $box->save();
            $isCenter = Box_year::where('box_id',$box->parent_id)->where('m_year',$this->getMoneyYear())->count();
            if ($isCenter>0){
                $center = Box_year::where('box_id',$box->parent_id)->where('m_year',$this->getMoneyYear())->first();
                $center->expense += $request->input("amount");
                $center->save();
            }
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense += $request->input("amount");
            $primary->save();


        }else{
            $Receipt_box = Receipt_box::latest()->first();
            $add = new Approval_record();
            $add->row_id=$Receipt_box->id;
            $add->model_id='App\Models\Receipt_box';
            $add->slug='ReceiptBox';
            $add->section='صرف صندوق مستقل';
            $add->user_id=$Receipt_box->created_by;
            $add->res_id=Auth::user()->responsible_id;
            $add->date=$Receipt_box->created_at;
            $add->save();
            }
        }

 ////////////////
            $Box_expense = Box_expense::where('id',$receipt_box->type)->first();
            if( $Box_expense){
             $Box_expense =$Box_expense->name;

            }else{
             $Box_expense =null;
            }
           $us_qu= new Us_qu();
           $us_qu->m_year = $receipt_box->m_year;
           $us_qu->id_main = $receipt_box->id;
           $us_qu->id_sys = $receipt_box->id_sys;
           $us_qu->name = $Box_expense;
           $us_qu->type = 'صرف صندوق مستقل';
           $us_qu->action = 'تعديل';
           $us_qu->amount = $receipt_box->amount;
           $us_qu->date = $receipt_box->created_at;
           $us_qu->created_by = $receipt_box->created_by;
           $us_qu->slug='ReceiptBox';
           $us_qu->box_id =$receipt_box->box_id;
           $us_qu->save();
//////////////


if(Auth::user()->responsible_id != null){
    $user=User::where('id',Auth::user()->responsible_id)->get();
        \Notification::send($user,new NewLessonNotification('ReceiptBox/'.$receipt_box->id,$this->getId(),'تعديل صرف صندوق مستقل','ReceiptBox'));
        MakeTask::dispatch(Auth::user()->responsible_id);
        }

        $flasher->addSuccess("تمت عملية الاضافة بنجاح");
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Receipt_box  $receipt_box
     * @return \Illuminate\Http\Response
     */
    public function show($id,FlasherInterface $flasher)
    {
        $parentTitle="عرض سند صرف صندوق";
        $item=Receipt_box::where("id",$id)->where("isdelete",0)->first();
        $title="شوؤن الموظفين";

        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/ReceiptBox");
        }
        return view("cms.receiptBox.show",compact("title","item","id","parentTitle"));
    }

    public function print($id)
    {

        $parentTitle="عرض سند صرف صندوق";
        $item=Receipt_box::where("id",$id)->where("isdelete",0)->first();
        $title="شوؤن الموظفين";

        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/ReceiptBox");
        }
        $print = Prin_t::first();

        if($print->type == 'A5'){
            return view("cms.receiptBox.printA5",compact("title","item","id","parentTitle",'print'));
        }else{
            return view("cms.receiptBox.printA6",compact("title","item","id","parentTitle",'print'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Receipt_box  $receipt_box
     * @return \Illuminate\Http\Response
     */
    public function edit($id,FlasherInterface $flasher)
    {
        $parentTitle="تعديل سندات الصرف - الصناديق ";
        $item=Receipt_box::where("id",$id)->where("isdelete",0)->first();
        $boxes=Box::where('type',147)->where('active',1)->where('isdelete',0)->get();
        $title="شوؤن الموظفين";

        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/ReceiptBox");
        }
        return view("cms.receiptBox.edit",compact("title","item","id","parentTitle","boxes"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipt_box  $receipt_box
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FlasherInterface $flasher)
    {
        $this->validate($request,
            [
                'box_id' => 'required',
                'type' => 'required',
                'amount' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);

        $item=Receipt_box::find($id);
        $item->id=$request->input("id");
        $item->id_comp=$request->input("id_comp");
        $item->m_year=$request->input("m_year");
        $item->date=$request->input("date");
        $item->box_id=$request->input("box_id");
        $item->type=$request->input("type");
        $amount = $item->amount;
        $item->amount=$request->input("amount");
        $item->notes=$request->input("notes");
        $item->updated_by=$this->getId();
        if ($item->save()){
            $box = Box_year::where('box_id',$request->input("box_id"))->where('m_year',$this->getMoneyYear())->first();
            $box->expense -= $amount - $request->input("amount");
            $box->save();
            $isCenter = Box_year::where('box_id',$box->parent_id)->where('m_year',$this->getMoneyYear())->count();
            if ($isCenter>0){
                $center = Box_year::where('box_id',$box->parent_id)->where('m_year',$this->getMoneyYear())->first();
                $center->expense -= $amount - $request->input("amount");
                $center->save();
            }
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense -= $amount - $request->input("amount");
            $primary->save();

    ////////////////
            $Box_expense = Box_expense::where('id',$item->type)->first();
            if( $Box_expense){
             $Box_expense =$Box_expense->name;

            }else{
             $Box_expense =null;
            }
           $us_qu= new Us_qu();
           $us_qu->m_year = $item->m_year;
           $us_qu->id_main = $item->id;
           $us_qu->id_sys = $item->id_sys;
           $us_qu->name = $Box_expense;
           $us_qu->type = 'صرف صندوق مستقل';
           $us_qu->action = 'تعديل';
           $us_qu->amount = $item->amount;
           $us_qu->date = $item->created_at;
           $us_qu->created_by = $this->getId();
           $us_qu->slug='ReceiptBox';
           $us_qu->box_id =$item->box_id;
           $us_qu->save();
//////////////
if(Auth::user()->responsible_id != null){
    $user=User::where('id',Auth::user()->responsible_id)->get();
           \Notification::send($user,new NewLessonNotification('ReceiptBox/'.$item->id,$this->getId(),' تعديل صرف صندوق مستقل','ReceiptBox'));
           MakeTask::dispatch(Auth::user()->responsible_id);
           }


        }



        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipt_box  $receipt_box
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDelete($id,FlasherInterface $flasher)
    {
        $item=Receipt_box::find($id);
        $item->isdelete=1;
        $item->deleted_by=$this->getId();
        if ($item->save()){
            $box = Box_year::where('box_id',$item->box_id)->where('m_year',$this->getMoneyYear())->first();
            $box->expense -= $item->amount;
            $box->save();
            $isCenter = Box_year::where('box_id',$box->parent_id)->where('m_year',$this->getMoneyYear())->count();
            if ($isCenter>0){
                $center = Box_year::where('box_id',$box->parent_id)->where('m_year',$this->getMoneyYear())->first();
                $center->expense -= $item->amount;
                $center->save();
            }
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->expense -= $item->amount;
            $primary->save();

        ////////////////
                $Box_expense = Box_expense::where('id',$item->type)->first();
                if( $Box_expense){
                 $Box_expense =$Box_expense->name;

                }else{
                 $Box_expense =null;
                }
               $us_qu= new Us_qu();
               $us_qu->m_year = $item->m_year;
               $us_qu->id_main = $item->id;
               $us_qu->id_sys = $item->id_sys;
               $us_qu->name = $Box_expense;
               $us_qu->type = 'صرف صندوق مستقل';
               $us_qu->action = 'حذف';
               $us_qu->amount = $item->amount;
               $us_qu->date = $item->created_at;
               $us_qu->created_by = $this->getId();
               $us_qu->slug='ReceiptBox';
               $us_qu->box_id =$item->box_id;
               $us_qu->save();
    //////////////


        }
        flash()->addError("تمت عملية الحذف بنجاح");
        return redirect("/CMS/ReceiptBox/");
    }
}
