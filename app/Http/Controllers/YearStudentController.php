<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Option;
use App\Models\Student;
use App\Models\Money_year;
use App\Models\Student_year;
use Illuminate\Http\Request;
use App\Models\Student_course;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\CMSBaseController;

class YearStudentController extends CMSBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,FlasherInterface $flasher)
    {
        $parentTitle="عرض الطلاب ";
        $item=Student::where("id",$id)->where("isdelete",0)->first();
        $std_active=Student_year::where('student_id',$id)->where('m_year',$this->getMoneyYear())->first();
        $title="ادارة الطلاب";
        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/Student/");
        }
        return view("cms.yearstudent.show",compact("title","item","id","parentTitle",'std_active'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,FlasherInterface $flasher)
    {

        $parentTitle="تعديل الطلاب ";
        $item=Student::where("id",$id)->where("isdelete",0)->first();
        $std_active=Student_year::where('student_id',$id)->where('m_year',$this->getMoneyYear())->first();
        $genders=Option::where('parent_id',62)->where('isdelete',0)->where('active',1)->get();
        $addresses=Option::where('parent_id',56)->where('isdelete',0)->where('active',1)->get();
        $classes=Option::where('parent_id',12)->where('isdelete',0)->where('active',1)->get();
        $nationalities=Option::where('parent_id',55)->where('isdelete',0)->where('active',1)->get();
        $works=Option::where('parent_id',185)->where('isdelete',0)->where('active',1)->get();
        $levels=Option::where('parent_id',59)->where('isdelete',0)->where('active',1)->get();
        $hows=Option::where('parent_id',51)->where('isdelete',0)->where('active',1)->get();
        $title="ادارة الطلاب";

        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/Student/");
        }
        return view("cms.yearstudent.edit",compact("title","item","std_active","parentTitle","id","genders","addresses","classes","nationalities","works","levels","hows"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FlasherInterface $flasher)
    {
        $this->validate($request,
        [
            'nameAR' => 'required',
            'birthday' => 'required',
            'gender' => 'required',
            'place_birth' => 'required',
            'nationality' => 'required',
            'address' => 'required',
            'phone1' => 'required',
            'phone2' => 'required',
            'level' => 'required',
            'work' => 'required',
            'classification' => 'required'
        ],
        [
            "required"=>"يجب ادخال هذا الحقل"
        ]);

    $item=Student::find($id);
    $item->nameAR=$request->input("nameAR");
    $item->nameEN=$request->input("nameEN");
    $item->birthday=$request->input("birthday");
    $item->gender=$request->input("gender");
    $item->place_birth=$request->input("place_birth");
    $item->nationality=$request->input("nationality");
    $item->address=$request->input("address");
    $item->email=$request->input("email");
    $item->how_listen=$request->input("how_listen");
    $item->phone1=$request->input("phone1");
    $item->phone2=$request->input("phone2");
    $item->whatsup=$request->input("whatsup");
    $item->level=$request->input("level");
    $item->work=$request->input("work");
    $item->classification=$request->input("classification");
    $item->active=$request->input("active")?1:0;
    $item->notes=$request->input("notes");
    $item->updated_by=$this->getId();
    $item->save();

    if ($item){
        $isStudent_year=Student_year::where('student_id',$item->id)->where('m_year',$this->getMoneyYear())->count();
        if ($isStudent_year>0){
            $student_year=Student_year::where('student_id',$item->id)->where('m_year',$this->getMoneyYear())->first();
            $student_year->active=$request->input("active")?1:0;
            $student_year->updated_by= $this->getId();
            $student_year->save();
        }

    }

    $flasher->addSuccess("تمت عملية الحفظ بنجاح");
    return Redirect::back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
