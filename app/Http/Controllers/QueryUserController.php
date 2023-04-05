<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\User;
use App\Models\Us_qu;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\Repository;
use App\Models\Box_expense;
use App\Models\Receipt_box;
use Illuminate\Http\Request;
use App\Models\Catch_receipt;
use App\Models\Repository_in;
use App\Models\Receipt_course;
use App\Models\Receipt_reward;
use App\Models\Receipt_salary;
use App\Models\Repository_out;
use App\Models\Student_course;
use App\Models\Receipt_advance;
use App\Models\Receipt_student;
use App\Models\Receipt_warranty;
use Yajra\DataTables\DataTables;
use App\Models\Catch_receipt_box;

class QueryUserController extends Controller
{
    public function getUserQ()
    {
        $subtitle="حركات المستخدمين";
        $title="الاعدادات";
        $this->getQuery();
        $users=User::where('isdelete',0)->where('Status','مفعل')->get();
        $boxs=Box::where('isdelete',0)->get();
        return view("cms.queriesRep.userQ",compact("title","subtitle","users",'boxs'));
    }


    public function getQuery(){

        $isQuery=Us_qu::count();
        if($isQuery>0){
            $query_del=Us_qu::truncate();
        }

        $Receipt_student = Receipt_student::where("isdelete",0)->get();
        foreach ($Receipt_student as $Receipt_S){
           $std_id = Student_course::where('id',$Receipt_S->student_course_id)->first()->student_id;
           $std_name = Student::where('id',$std_id)->first()->nameAR;
           $us_qu= new Us_qu();
           $us_qu->m_year = $Receipt_S->m_year;
           $us_qu->id_sys = $Receipt_S->id;
           $us_qu->name = $std_name;
           $us_qu->type = 'انسحاب طالب';
           $us_qu->amount = $Receipt_S->amount;
           $us_qu->date = $Receipt_S->created_at;
           $us_qu->created_by = $Receipt_S->created_by;
           $us_qu->slug='Receipt_student';
           $us_qu->box_id =3;
           $us_qu->save();

        }

        $Catch_receipt = Catch_receipt::where("isdelete",0)->get();
        foreach ($Catch_receipt as $Catch_r){

           $std_id = Student_course::where('id',$Catch_r->student_course_id)->first();
           if( $std_id){
            $std_id =$std_id->student_id;
            $std_name = Student::where('id',$std_id)->first()->nameAR;
           }else{
            $std_name =null;
           }

           $us_qu= new Us_qu();
           $us_qu->m_year = $Catch_r->m_year;
           $us_qu->id_sys = $Catch_r->id_sys;
           $us_qu->name = $std_name;
           $us_qu->type = 'قبض الدورات';
           $us_qu->amount = $Catch_r->amount;
           $us_qu->date = $Catch_r->created_at;
           $us_qu->created_by = $Catch_r->created_by;
           $us_qu->slug='CatchReceipt';
           $us_qu->box_id =3;
           $us_qu->save();

        }



        $Receipt_salary = Receipt_salary::where("isdelete",0)->get();
        foreach ($Receipt_salary as $Receipt_s){

           $emp_id = Employee::where('id',$Receipt_s->employee_id)->first()->name;
           $us_qu= new Us_qu();
           $us_qu->m_year = $Receipt_s->m_year;
           $us_qu->id_sys = $Receipt_s->id_sys;
           $us_qu->name = $emp_id;
           $us_qu->type = 'صرف راتب';
           $us_qu->amount = $Receipt_s->amount;
           $us_qu->date = $Receipt_s->created_at;
           $us_qu->created_by = $Receipt_s->created_by;
           $us_qu->slug='ReceiptSalary';
           $us_qu->box_id =4;
           $us_qu->save();

        }



        $Receipt_course = Receipt_course::where("isdelete",0)->get();
        foreach ($Receipt_course as $Receipt_c){

           $tech_id = Course::where('id',$Receipt_c->course_id)->first();
           if( $tech_id){
            $tech_id =$tech_id->teacher_id;
            $tech_name = Teacher::where('id',$tech_id)->first()->name;
           }else{
            $tech_name =null;
           }
           $us_qu= new Us_qu();
           $us_qu->m_year = $Receipt_c->m_year;
           $us_qu->id_sys = $Receipt_c->id_sys;
           $us_qu->name = $tech_name;
           $us_qu->type = 'صرف اجور معلم';
           $us_qu->amount = $Receipt_c->amount;
           $us_qu->date = $Receipt_c->created_at;
           $us_qu->created_by = $Receipt_c->created_by;
           $us_qu->slug='ReceiptCourse';
           $us_qu->box_id =3;
           $us_qu->save();

        }



        $Receipt_advance = Receipt_advance::where("isdelete",0)->get();
        foreach ($Receipt_advance as $Receipt_a){

           $emp_id = Employee::where('id',$Receipt_a->employee_id)->first()->name;
           $us_qu= new Us_qu();
           $us_qu->m_year = $Receipt_a->m_year;
           $us_qu->id_sys = $Receipt_a->id_sys;
           $us_qu->name = $emp_id;
           $us_qu->type = 'صرف سلفة';
           $us_qu->amount = $Receipt_a->advance_payment;
           $us_qu->date = $Receipt_a->created_at;
           $us_qu->created_by = $Receipt_a->created_by;
           $us_qu->slug='ReceiptAdvance';
           $us_qu->box_id =4;
           $us_qu->save();

        }


        $Receipt_reward = Receipt_reward::where("isdelete",0)->get();
        foreach ($Receipt_reward as $Receipt_r){
           if($Receipt_r->type == 0){
            $ty='مكافأت';
           }else{
            $ty='خصومات';
           }
           $emp_id = Employee::where('id',$Receipt_r->employee_id)->first();
           if( $emp_id){
            $emp_id =$emp_id->name;

           }else{
            $emp_id =null;
           }
           $us_qu= new Us_qu();
           $us_qu->m_year = $Receipt_r->m_year;
           $us_qu->id_sys = $Receipt_r->id_sys;
           $us_qu->name = $emp_id;
           $us_qu->type = $ty;
           $us_qu->amount = $Receipt_r->amount;
           $us_qu->date = $Receipt_r->created_at;
           $us_qu->created_by = $Receipt_r->created_by;
           $us_qu->slug='ReceiptReward';
           $us_qu->box_id =4;
           $us_qu->save();

        }


        $Receipt_warranty = Receipt_warranty::where("isdelete",0)->get();
        foreach ($Receipt_warranty as $Receipt_w){

            $emp_id = Employee::where('id',$Receipt_w->employee_id)->first();
            if( $emp_id){
             $emp_id =$emp_id->name;

            }else{
             $emp_id =null;
            }
           $us_qu= new Us_qu();
           $us_qu->m_year = $Receipt_w->m_year;
           $us_qu->id_sys = $Receipt_w->id_sys;
           $us_qu->name = $emp_id;
           $us_qu->type = 'صرف الضمان';
           $us_qu->amount = $Receipt_w->amount;
           $us_qu->date = $Receipt_w->created_at;
           $us_qu->created_by = $Receipt_w->created_by;
           $us_qu->slug='ReceiptWarranty';
           $us_qu->box_id =4;
           $us_qu->save();

        }



        $Catch_receipt_box = Catch_receipt_box::where("isdelete",0)->get();
        foreach ($Catch_receipt_box as $Catch_r_b){

           $us_qu= new Us_qu();
           $us_qu->m_year = $Catch_r_b->m_year;
           $us_qu->id_sys = $Catch_r_b->id_sys;
           $us_qu->name = $Catch_r_b->customer;
           $us_qu->type = 'قبض صندوق مستقل';
           $us_qu->amount = $Catch_r_b->amount;
           $us_qu->date = $Catch_r_b->created_at;
           $us_qu->created_by = $Catch_r_b->created_by;
           $us_qu->slug='CatchReceiptBox';
           $us_qu->box_id =$Catch_r_b->box_id;
           $us_qu->save();

        }

        $Receipt_box = Receipt_box::where("isdelete",0)->get();
        foreach ($Receipt_box as $Receipt_b){
            $Box_expense = Box_expense::where('id',$Receipt_b->type)->first();
            if( $Box_expense){
             $Box_expense =$Box_expense->name;

            }else{
             $Box_expense =null;
            }
           $us_qu= new Us_qu();
           $us_qu->m_year = $Receipt_b->m_year;
           $us_qu->id_sys = $Receipt_b->id_sys;
           $us_qu->name = $Box_expense;
           $us_qu->type = 'صرف صندوق مستقل';
           $us_qu->amount = $Receipt_b->amount;
           $us_qu->date = $Receipt_b->created_at;
           $us_qu->created_by = $Receipt_b->created_by;
           $us_qu->slug='ReceiptBox';
           $us_qu->box_id =$Receipt_b->box_id;
           $us_qu->save();

        }


        $Repository_in = Repository_in::where("isdelete",0)->get();
        foreach ($Repository_in as $Repository_i){
            $rep = Repository::where('id',$Repository_i->repository_id)->first();
            if( $rep){
                $box =$rep->box_id;
                $name=$rep->name;
               }else{
                $box =null;
                $name=null;
               }
           $us_qu= new Us_qu();
           $us_qu->m_year = $Repository_i->m_year;
           $us_qu->id_sys = $Repository_i->id_sys;
           $us_qu->name = $name;
           $us_qu->type = 'قبض مستودع';
           $us_qu->amount = $Repository_i->total;
           $us_qu->date = $Repository_i->created_at;
           $us_qu->created_by = $Repository_i->created_by;
           $us_qu->slug='RepositoryIn';
           $us_qu->box_id =$box;
           $us_qu->save();

        }



        $Repository_out = Repository_out::where("isdelete",0)->get();
        foreach ($Repository_out as $Repository_o){
            $rep = Repository::where('id',$Repository_o->repository_id)->first();
            if( $rep){
                $box =$rep->box_id;

               }else{
                $box =null;
               }
           $us_qu= new Us_qu();
           $us_qu->m_year = $Repository_o->m_year;
           $us_qu->id_sys = $Repository_o->id_sys;
           $us_qu->name = $Repository_o->customer;
           $us_qu->type = 'صرف مستودع';
           $us_qu->amount = $Repository_o->total;
           $us_qu->date = $Repository_o->created_at;
           $us_qu->created_by = $Repository_o->created_by;
           $us_qu->slug='RepositoryOut';
           $us_qu->box_id =$box;
           $us_qu->save();

        }

    }

    public function anyUserQ(Request $request)
    {
        $tasks = Us_qu::leftJoin('users', 'users.id','=','user_query.created_by')
       -> leftJoin('boxes', 'boxes.id','=','user_query.box_id')
            ->select(['user_query.id','user_query.m_year','user_query.name as nam', 'user_query.date','user_query.slug','users.name as us','boxes.name as box','user_query.amount','user_query.type','user_query.id_sys']);
        return Datatables::of($tasks)
             ->filter(function ($tasks) use ($request) {
        /*     if ($request->has('searchCollectionFees') and $request->get('searchCollectionFees') != "") {
                $tasks->where('collection_fees.isdelete','=','0')
                    ->where(function ($tasks) use ($request) {
                        $tasks->where('courses.courseAR', 'like', "%{$request->get('searchCollectionFees')}%")
                            ->orWhere('students.nameAR', 'like', "%{$request->get('searchCollectionFees')}%")
                            ->orWhere('collection_fees.phone', 'like', "%{$request->get('searchCollectionFees')}%")
                            ->orWhere('collection_fees.fees', 'like', "%{$request->get('searchCollectionFees')}%")
                            ->orWhere('collection_fees.fees_pay', 'like', "%{$request->get('searchCollectionFees')}%")
                            ->orWhere('collection_fees.fees_owed', 'like', "%{$request->get('searchCollectionFees')}%")
                            ->orWhere('collection_fees.warranty', 'like', "%{$request->get('searchCollectionFees')}%")
                            ->orWhere('collection_fees.count', 'like', "%{$request->get('searchCollectionFees')}%");
                    });
            }
            if ($request->has('studentId') and $request->get('studentId') != "all") {
                $tasks->where('students.id', '=', "{$request->get('studentId')}");
            }
            if ($request->has('courseId') and $request->get('courseId') != "all") {
                $tasks->where('courses.id', '=', "{$request->get('courseId')}");
            }*/
            if ($request->has('typeId') and $request->get('typeId') != "") {
                $tasks->where('user_query.type', '=', "{$request->get('typeId')}");
            }
            if ($request->has('boxId') and $request->get('boxId') != "") {
                $tasks->where('boxes.id', '=', "{$request->get('boxId')}");
            }
            if ($request->has('userId') and $request->get('userId') != "") {
                $tasks->where('user_query.created_by', '=', "{$request->get('userId')}");
            }
            if ($request->has('moneyId') and $request->get('moneyId') != "") {
                $tasks->where('user_query.m_year', '=', "{$request->get('moneyId')}");
            }
        })

        ->make(true);
    }



}
