<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\User;
use App\Models\Course;
use App\Models\Student;
use App\Events\MakeTask;
use App\Models\Box_year;
use App\Models\Prin_t;
use App\Models\Us_qu;
use Illuminate\Http\Request;
use App\Models\Catch_receipt;
use App\Models\Student_course;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Notifications\NewLessonNotification;

class CatchReceiptController extends CMSBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title="شوؤن الطلبه";
        $subtitle="ادارة سندات القبض - دورات";
        $students=Student::leftJoin('students_year', 'students_year.student_id','=','students.id')
        ->select(['students.id', 'students.nameAR'])
        ->where('students_year.m_year','=',$this->getMoneyYear())
        ->where('students_year.active','=',1)->where('students.isdelete','=',0)
        ->orderBy('nameAR')->get();
    $courses=Course::where("isdelete",0)->where("active",1)->where('m_year','=',$this->getMoneyYear())->orderBy('courseAR')->get();
    $users=User::where("isdelete",0)->where("Status","مفعل")->orderBy('name')->get();
        return view("cms.catchReceipt.index",compact("title","subtitle","students","courses","users"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentTitle=" اضافة سند قبض جديد - دورات";
        $title="شوؤن الطلبه";
        $linkApp="/CMS/Static/";

        $id = 1;
        $id_comp = 1;
        $date = date('Y-m-d');
        $isCatch = Catch_receipt::where('id_comp','!=',null)->count();
        if ($isCatch>0){
            $catch = Catch_receipt::where('id_comp','!=',null)->orderBy('id','desc')->first();
            $id = $catch->id + 1;
            $catchss = Catch_receipt::where('m_year',$this->getMoneyYear())->where('id_comp','!=',null)->orderBy('id','desc')->first();

            if($catchss){

            $id_comp = ($catchss->id_comp + 1);
            }else{
                $id_comp = 1;
            }
        }else{
            $id = 1;
            $id_comp = 1;
        }

        $student_courses=Student_course::leftJoin('students', 'students.id', '=', 'student_course.student_id')
            ->leftJoin('courses', 'courses.id', '=', 'student_course.course_id')
            ->select(['student_course.id','student_course.m_year', 'courses.courseAR', 'students.nameAR', 'student_course.price', 'student_course.isdelete', 'student_course.iswithdrawal', 'student_course.created_at'])
            ->where('student_course.isdelete',0)->where('student_course.m_year',$this->getMoneyYear())
            // ->where('student_course.payment','>',0)
            ->orderBy('students.nameAR', 'asc')->orderBy('courses.courseAR', 'asc')->get();
        return view("cms.catchReceipt.add",compact("title","parentTitle","linkApp","id","id_comp","date","student_courses"));
    }

    public function getYearFilter()
    {
        $student_courses=Student_course::leftJoin('students', 'students.id', '=', 'student_course.student_id')
            ->leftJoin('courses', 'courses.id', '=', 'student_course.course_id')
            ->select(['student_course.id','student_course.m_year', 'courses.courseAR', 'students.nameAR', 'student_course.price', 'student_course.isdelete', 'student_course.iswithdrawal', 'student_course.created_at'])
            ->where('student_course.isdelete',0)->where('m_year',$this->getMoneyYear())->where('student_course.payment','>',0)->orderBy('students.nameAR', 'asc')->orderBy('courses.courseAR', 'asc')->get();

        return Response::json( $student_courses );
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
                'student_course_id' => 'required',
                'remainder' => 'required',
                'amount' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);
        $student_course=Student_course::where('id',$request->input("student_course_id"))->where('isdelete',0)->first();

        $id_system=Catch_receipt::where('m_year',$this->getMoneyYear())->latest()->first();
        if($id_system){
            $id_sys =$id_system->id_sys + 1;
        }else{
            $id_sys = 1;
        }
        if ($request->input("amount")>0){
            $catchReceipt = Catch_receipt::create([
                'id' => $request->input("id"),
                'id_comp' => $request->input("id_comp"),
                'id_sys' => $id_sys,
                'm_year' => $request->input("edu_year_h"),
                'box_id' => 3,
                'date' => $request->input("date"),
                'student_course_id' => $request->input("student_course_id"),
                'remainder' => $request->input("remainder"),
                'amount' => $request->input("amount"),
                'cheque_info' => $request->input("cheque_info"),
                'notes' => $request->input("notes"),
                'created_by' => $this->getId()
            ]);

            if ($catchReceipt){
                $box = Box_year::where('box_id',3)->where('m_year',$this->getMoneyYear())->first();
                $box->income += $request->input("amount");
                $box->save();
                $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
                $center->income += $request->input("amount");
                $center->save();
                $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
                $primary->income += $request->input("amount");
                $primary->save();
            }

            $student_course->payment = $student_course->payment - $request->input("amount");
            $student_course->save();
            $course=Course::where('id',$student_course->course_id)->first();
            if ($student_course->payment == 0){
                if ($course->ratio_type == 18 or $course->ratio_type == 29){
                    $student_course->teacher_pay = $course->value_sum;
                    $student_course->ratio_type = 1;
                    $student_course->teacher_id = $course->teacher_id;
                    $student_course->save();
                }else{
                    $student_course->teacher_pay = -1;
                    $student_course->ratio_type = 2;
                    $student_course->teacher_id = $course->teacher_id;
                    $student_course->save();
                }
            }

            $msg = 'تمت عملية الاضافة بنجاح';
        }else{
            $msg = 'يجب اضافة مبلغ';
        }

        /////////
        $std_id = Student_course::where('id',$catchReceipt->student_course_id)->first();
        if( $std_id){
         $std_id =$std_id->student_id;
         $std_name = Student::where('id',$std_id)->first()->nameAR;
        }else{
         $std_name =null;
        }
        $us_qu= new Us_qu();
        $us_qu->m_year = $catchReceipt->m_year;
        $us_qu->id_main = $catchReceipt->id;
        $us_qu->id_sys = $catchReceipt->id_sys;
        $us_qu->name = $std_name;
        $us_qu->type = 'قبض الدورات';
        $us_qu->action = 'ادخال';
        $us_qu->amount = $catchReceipt->amount;
        $us_qu->date = $catchReceipt->created_at;
        $us_qu->created_by = $catchReceipt->created_by;
        $us_qu->slug='CatchReceipt';
        $us_qu->box_id =3;
        $us_qu->save();
        /////////////

        if(Auth::user()->responsible_id != null){
            $user=User::where('id',Auth::user()->responsible_id)->get();
        \Notification::send($user,new NewLessonNotification('CatchReceipt',$catchReceipt->created_by,'انشاء قبض دوره','catchReceipt'));
        MakeTask::dispatch(Auth::user()->responsible_id);

        }
        $flasher->addSuccess($msg);
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Catch_receipt  $catch_receipt
     * @return \Illuminate\Http\Response
     */
    public function show($id,FlasherInterface $flasher)
    {
        $parentTitle="عرض سندات القبض - دورات ";
        $item=Catch_receipt::where("id",$id)->where("isdelete",0)->first();
        $title="شوؤن الطلبه";

        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/catchReceipt");
        }
        $returnHTML = view("cms.catchReceipt.show",compact("title","item","id","parentTitle"))->render();
            return response()->json(['html'=>$returnHTML]);
    }


 public function print($id)
 {
    $parentTitle="عرض سندات القبض - دورات ";
    $item=Catch_receipt::where("id",$id)->where("isdelete",0)->first();
    $title="شوؤن الطلبه";

    if($item==NULL){
        flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
        return redirect("/CMS/catchReceipt");
    }
     $print = Prin_t::first();
     if($print->type == 'A5'){
         return view("cms.catchReceipt.printA5",compact("title","item","id","parentTitle",'print'));
     }else{
         return view("cms.catchReceipt.printA6",compact("title","item","id","parentTitle",'print'));
     }

 }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Catch_receipt  $catch_receipt
     * @return \Illuminate\Http\Response
     */
    public function edit($id,FlasherInterface $flasher)
    {
        $parentTitle="تعديل سندات القبض - دورات ";
        $item=Catch_receipt::where("id",$id)->where("isdelete",0)->first();
        $student_courses=Student_course::where('isdelete',0)->where('m_year',$this->getMoneyYear())->get();
        $student_course=Student_course::where('id',$item->student_course_id)->where('isdelete',0)->first();
        $title="شوؤن الطلبه";
        $linkApp="/CMS/Static/";
        if($item==null){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");

            return redirect("/CMS/Static/");
        }
        if($student_course==null){

            flash()->addWarning("لا يمكن التعديل الطالب محذوف من الدورة");
            return redirect("/CMS/Static/");
        }
        return view("cms.catchReceipt.edit",compact("title","item","id","parentTitle","linkApp","student_courses"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Catch_receipt  $catch_receipt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FlasherInterface $flasher)
    {
        $this->validate($request,
            [
                'student_course_id' => 'required',
                'remainder_h' => 'required',
                'amount' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);

        $item=Catch_receipt::find($id);
        $item->id=$request->input("id");
        $item->id_comp=$request->input("id_comp");
        $item->m_year=$request->input("m_year");
        $item->student_course_id=$request->input("student_course_id");
        $item->remainder=$request->input("remainder_h");
        $amount = $item->amount;
        $item->amount=$request->input("amount");
        $item->date=$request->input("date");
        $item->cheque_info=$request->input("cheque_info");
        $item->notes=$request->input("notes");
        $item->updated_by=$this->getId();

        if ($item->save()){
            $box = Box_year::where('box_id',3)->where('m_year',$this->getMoneyYear())->first();
            $box->income -= $amount - $request->input("amount");
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->income -= $amount - $request->input("amount");
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->income -= $amount - $request->input("amount");
            $primary->save();


        /////////
        $std_id = Student_course::where('id',$item->student_course_id)->first();
        if( $std_id){
         $std_id =$std_id->student_id;
         $std_name = Student::where('id',$std_id)->first()->nameAR;
        }else{
         $std_name =null;
        }
        $us_qu= new Us_qu();
        $us_qu->m_year = $item->m_year;
        $us_qu->id_main = $item->id;
        $us_qu->id_sys = $item->id_sys;
        $us_qu->name = $std_name;
        $us_qu->type = 'قبض الدورات';
        $us_qu->action = 'تعديل';
        $us_qu->amount = $item->amount;
        $us_qu->date = $item->created_at;
        $us_qu->created_by = $this->getId();
        $us_qu->slug='CatchReceipt';
        $us_qu->box_id =3;
        $us_qu->save();
        /////////////

        if(Auth::user()->responsible_id != null){
            $user=User::where('id',Auth::user()->responsible_id)->get();
            \Notification::send($user,new NewLessonNotification('CatchReceipt',$this->getId(),'تعديل قبض دوره','catchReceipt'));
            MakeTask::dispatch(Auth::user()->responsible_id);



            }
        }



        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Catch_receipt  $catch_receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDelete($id,FlasherInterface $flasher)
    {
        $item=Catch_receipt::find($id);
        $item->isdelete=1;
        $item->deleted_by=$this->getId();
        if ($item->save()){
            $student_course = Student_course::where('id',$item->student_course_id)->first();
            $student_course->payment = $student_course->payment + $item->amount;
            $student_course->save();
            $box = Box_year::where('box_id',3)->where('m_year',$this->getMoneyYear())->first();
            $box->income -= $item->amount;
            $box->save();
            $center = Box_year::where('box_id',2)->where('m_year',$this->getMoneyYear())->first();
            $center->income -= $item->amount;
            $center->save();
            $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
            $primary->income -= $item->amount;
            $primary->save();
        }

        /////////
        $std_id = Student_course::where('id',$item->student_course_id)->first();
        if( $std_id){
         $std_id =$std_id->student_id;
         $std_name = Student::where('id',$std_id)->first()->nameAR;
        }else{
         $std_name =null;
        }
        $us_qu= new Us_qu();
        $us_qu->m_year = $item->m_year;
        $us_qu->id_main = $item->id;
        $us_qu->id_sys = $item->id_sys;
        $us_qu->name = $std_name;
        $us_qu->type = 'قبض الدورات';
        $us_qu->action = 'حذف';
        $us_qu->amount = $item->amount;
        $us_qu->date = $item->created_at;
        $us_qu->created_by = $this->getId();
        $us_qu->slug='CatchReceipt';
        $us_qu->box_id =3;
        $us_qu->save();
        /////////////
        flash()->addError("تمت عملية الحذف بنجاح");
        return redirect("/CMS/CatchReceipt/");
    }
}
