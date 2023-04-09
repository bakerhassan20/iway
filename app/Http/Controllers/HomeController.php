<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Box;
use App\Models\Logo;
use App\Models\Task;
use App\Models\User;
use App\Models\Course;
use App\Models\Option;
use App\Models\English;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Box_year;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\ColorTheme;
use App\Models\Money_year;
use App\Models\Query_user;
use App\Models\Withdrawal;
use App\Models\Certificate;
use App\Models\English_reg;
use App\Models\Query_admin;
use App\Models\Query_money;
use App\Models\Receipt_box;
use Illuminate\Http\Request;
use App\Models\Catch_receipt;
use App\Models\Legal_affairs;
use App\Models\Query_teacher;
use App\Models\Repository_in;
use App\Models\Receipt_course;
use App\Models\Receipt_reward;
use App\Models\Receipt_salary;
use App\Models\Repository_out;
use App\Models\Student_course;
use App\Models\Receipt_advance;
use App\Models\Receipt_student;
use App\Models\Receipt_warranty;
use App\Models\Query_emp;
use App\Models\Absence;
use Yajra\DataTables\DataTables;
use App\Models\Catch_receipt_box;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CMSBaseController;

class HomeController extends CMSBaseController



{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $addres=Option::where('parent_id',56)->where('isdelete',0)->where('active',1)->get();
        $competition_all=[];
        foreach($addres as $addre){
            array_push($competition_all, $addre->title);
        }
        $count_arr=[];
        foreach($addres as $addre){
           $std = Student::where('isdelete','=','0')->where('address',$addre->id)->count();
           array_push($count_arr,$std);
        }

     //   dd($count_arr);

        $chartjs = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->size(['width' => 40, 'height' => 20])
         ->labels($competition_all)
         ->datasets([
             [
                 "label" => "عدد الصلاب",
                 'backgroundColor' => ['#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384','#FF6384'],
                 'data' => $count_arr
             ],

         ])
         ->options([]);


         $chartjs_2 = app()->chartjs
         ->name('pieChartTest')
         ->type('pie')
         ->size(['width' => 400, 'height' => 200])
         ->labels(['Label x', 'Label y'])
         ->datasets([
             [
                 'backgroundColor' => ['#FF6384', '#36A2EB'],
                 'hoverBackgroundColor' => ['#FF6384', '#36A2EB'],
                 'data' => [69, 59]
             ]
         ])
         ->options([]);
        $this->getAllUser();
        $this->getAllEmploey();
        $query_emp = Query_emp::all();
        return view('home',compact('chartjs' ,'chartjs_2','query_emp'));

    }


    function theme(Request $request){
        $theme = ColorTheme::where('user_id',Auth::user()->id)->first();
         if($theme->mode == 'dark'){

                $theme->update([
                    'mode'=> 'light',
                ]);

            }
            else{
                $theme->update([
                    'mode'=> 'dark',
                ]);
            }
            return response()->json(['message'=>$theme->mode]);
            }


            public function anyQueryMoney(Request $request)
            {
                // $years=Money_year::where('isdelete',0)->where('basic_work',0)->orderBy('year','desc')->get();
                $years=Money_year::where('isdelete',0)->orderBy('year','desc')->get();
                $tasks =Box::leftJoin('box_year', 'boxes.id','=','box_year.box_id')->select([ 'boxes.id'])
                    ->where('boxes.isdelete','=','0')->where('boxes.id','<','5')
                    ->groupBy("boxes.name");
                $result= Datatables::of($tasks);
                $yearcols=['2'=>'7days','3'=>'15days','4'=>'1month','5'=>'2month','6'=>'3month','7'=>'6month'];
                $result->addColumn('day1', function ($tasks) {
                            if($tasks->id==1){
                                $boxes=Box::whereNotNull('parent_id')->get();
                                $expenses=0;
                                $incomes=0;
                                foreach($boxes as $box){
                                    if($box->id==3){
                                    $courses_receipt = Catch_receipt::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');

                                    $incomes+=$courses_receipt;
                                     $teacher_salaries = Receipt_course::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                    $receipt_students = Receipt_student::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');

                                    $expenses +=$teacher_salaries + $receipt_students;

                                }
                                else if($box->id==4){
                                    $advance_receipt = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('advance_payment');
                                    $incomes+=$advance_receipt;
                                     $salaries = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                    $warranties = Receipt_warranty::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                    $advances = Receipt_advance::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('advance_payment');
                                    $expenses+=$salaries+$warranties+$advances;
                                }
                                else if($box->repository_id >0){
                                    $incomes+= Repository_in::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('total');

                                     $expenses+= Repository_out::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('total');

                                }
                                else if($box->type=="147"){
                                    $incomes+= Catch_receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                     $expenses+= Receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');


                                }
                                }
                                return number_format($incomes-$expenses,2);
                            }
                            if($tasks->id==2){
                                $boxes=Box::where('parent_id',2)->get();
                                $expenses=0;
                                $incomes=0;
                                foreach($boxes as $box){
                                    if($box->id==3){
                                    $courses_receipt = Catch_receipt::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');

                                    $incomes+=$courses_receipt;
                                    $teacher_salaries = Receipt_course::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                    $receipt_students = Receipt_student::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');

                                    $expenses +=$teacher_salaries + $receipt_students;

                                }
                                else if($box->id==4){
                                    $advance_receipt = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('advance_payment');
                                    $incomes+=$advance_receipt;
                                     $salaries = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                    $warranties = Receipt_warranty::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                    $advances = Receipt_advance::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('advance_payment');
                                    $expenses+=$salaries+$warranties+$advances;
                                }
                                else if($box->repository_id >0){
                                    $incomes+= Repository_in::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('total');

                                     $expenses+= Repository_out::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('total');

                                }
                                else if($box->type=="147"){
                                    $incomes+= Catch_receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                                     $expenses+= Receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');


                                }
                                }
                                return number_format($incomes-$expenses,2);
                            }

                        if($tasks->id==3){
                            $courses_receipt = Catch_receipt::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');

                            $tasks->income=$courses_receipt;
                             $teacher_salaries = Receipt_course::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                            $receipt_students = Receipt_student::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');

                            $tasks->expense=$teacher_salaries + $receipt_students;

                        }
                        if($tasks->id==4){
                            $advance_receipt = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('advance_payment');
                            Box_year::where('box_id',$tasks->id)->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->update(array('income' => $advance_receipt));
                            $tasks->income=$advance_receipt;
                             $salaries = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                            $warranties = Receipt_warranty::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                            $advances = Receipt_advance::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('advance_payment');
                            $tasks->expense=$salaries+$warranties+$advances;
                        }
                        if($tasks->repository_id >0){
                            $tasks->income = Repository_in::where('repository_id','=',$tasks->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('total');

                             $tasks->expense= Repository_out::where('repository_id','=',$tasks->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('total');

                        }
                        if($tasks->type=="147"){
                            $tasks->income= Catch_receipt_box::where('box_id','=',$tasks->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');
                             $tasks->expense= Receipt_box::where('box_id','=',$tasks->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->whereDate('created_at', Carbon::today())->sum('amount');


                        }
                       $tot=$tasks->income-$tasks->expense;
                        return number_format($tot,2);

                    });
                foreach($yearcols as $key=>$value){
                    $result->addColumn('day'.$key, function ($tasks) use($value,$key) {
                        if($tasks->id==1){
                        $boxes=Box::whereNotNull('parent_id')->get();
                        $expenses=0;
                        $incomes=0;
                        foreach($boxes as $box){
                            if($box->id==3){
                            $courses_receipt = Catch_receipt::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');

                            $incomes+=$courses_receipt;
                             $teacher_salaries = Receipt_course::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $receipt_students = Receipt_student::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');

                            $expenses +=$teacher_salaries + $receipt_students;

                        }
                        else if($box->id==4){
                            $advance_receipt = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('advance_payment');
                            $incomes+=$advance_receipt;
                             $salaries = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $warranties = Receipt_warranty::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $advances = Receipt_advance::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('advance_payment');
                            $expenses+=$salaries+$warranties+$advances;
                        }
                        else if($box->repository_id >0){
                            $incomes+= Repository_in::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('total');

                             $expenses+= Repository_out::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('total');

                        }
                        else if($box->type=="147"){
                            $incomes+= Catch_receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                             $expenses+= Receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');


                        }
                        }
                        return number_format($incomes-$expenses,2);
                    }
                      if($tasks->id==2){
                        $boxes=Box::where('parent_id',2)->get();
                        $expenses=0;
                        $incomes=0;
                        foreach($boxes as $box){
                            if($box->id==3){
                            $courses_receipt = Catch_receipt::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');

                            $incomes+=$courses_receipt;
                             $teacher_salaries = Receipt_course::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $receipt_students = Receipt_student::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');

                            $expenses +=$teacher_salaries + $receipt_students;

                        }
                        else if($box->id==4){
                            $advance_receipt = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('advance_payment');
                            $incomes+=$advance_receipt;
                             $salaries = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $warranties = Receipt_warranty::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $advances = Receipt_advance::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('advance_payment');
                            $expenses+=$salaries+$warranties+$advances;
                        }
                        else if($box->repository_id >0){
                            $incomes+= Repository_in::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('total');

                             $expenses+= Repository_out::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('total');

                        }
                        else if($box->type=="147"){
                            $incomes+= Catch_receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                             $expenses+= Receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');


                        }
                        }
                        return number_format($incomes-$expenses,2);
                    }
                        if($tasks->id==3){
                            $courses_receipt = Catch_receipt::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');

                            $tasks->income=$courses_receipt;
                             $teacher_salaries = Receipt_course::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $receipt_students = Receipt_student::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');

                            $tasks->expense=$teacher_salaries + $receipt_students;

                        }
                        if($tasks->id==4){
                            $advance_receipt = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('advance_payment');
                            Box_year::where('box_id',$tasks->id)->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->update(array('income' => $advance_receipt));
                            $tasks->income=$advance_receipt;
                             $salaries = Receipt_salary::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $warranties = Receipt_warranty::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            $advances = Receipt_advance::where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('advance_payment');
                            $tasks->expense=$salaries+$warranties+$advances;
                        }
                        if($tasks->repository_id >0){
                            $tasks->income = Repository_in::where('repository_id','=',$tasks->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('total');
                            Box_year::where('box_id',$tasks->id)->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->update(array('income' => $tasks->income));
                             $tasks->expense= Repository_out::where('repository_id','=',$tasks->repository_id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('total');
                            Box_year::where('box_id',$tasks->id)->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->update(array('income' => $tasks->income));
                        }
                        if($tasks->type=="147"){
                            $tasks->income= Catch_receipt_box::where('box_id','=',$tasks->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                             $tasks->expense= Receipt_box::where('box_id','=',$tasks->id)->where('isdelete','=','0')->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->sum('amount');
                            Box_year::where('box_id',$tasks->id)->where('m_year',$this->getMoneyYear())->where('created_at','>',Carbon::now()->subDays($key))->update(array('income' => $tasks->income));

                        }
                       $tot=$tasks->income-$tasks->expense;
                        return number_format($tot,2);

                    });

              }


             // return response()->json(['html'=>$tasks]);
                   return $result->make(true);

            }







            public function getAllUser()
            {
                $statics=[
                    'عدد تسجيل طلاب',
                    'قيمة تحصيل رسوم',
                    'عدد فحص المستوى',
                    'عدد شهادات ذات الرسوم',
                    'عدد المهام الجديدة',
                    'عدد المهام المنجزة',
                    'تقييم المهام المنجزة',
                    'عدد حملات التسويق الهاتفي',
                ];
                $users=User::all();
                foreach ($users as $user){
                    $isQueryUser=Query_user::where('user_id',$user->id)->count();
                    if($isQueryUser>0){
                        $query_user_del=Query_user::truncate();
                    }
                    foreach ($statics as $static){
                        $query_user= new Query_user();
                        $query_user->user_id=$user->id;
                        $query_user->subject=$static;
                        if($static=="عدد تسجيل طلاب"){
                            $query_user->day1=Student_course::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->count();
                            $query_user->day7=Student_course::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->count();
                            $query_user->day15=Student_course::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->count();
                            $query_user->day30=Student_course::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->count();
                            $query_user->day60=Student_course::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->count();
                            $query_user->day90=Student_course::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->count();
                            $query_user->day180=Student_course::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->count();
                            $query_user->last1=Student_course::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->count();
                            $query_user->last2=Student_course::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->count();
                            $query_user->last3=Student_course::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->count();
                            $query_user->count=Student_course::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->count();
                            $query_user->save();
                        }
                        elseif($static=="قيمة تحصيل رسوم"){
                            $query_user->day1=Catch_receipt::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->sum('amount');
                            $query_user->day7=Catch_receipt::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->sum('amount');
                            $query_user->day15=Catch_receipt::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->sum('amount');
                            $query_user->day30=Catch_receipt::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->sum('amount');
                            $query_user->day60=Catch_receipt::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->sum('amount');
                            $query_user->day90=Catch_receipt::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->sum('amount');
                            $query_user->day180=Catch_receipt::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->sum('amount');
                            $query_user->last1=Catch_receipt::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->sum('amount');
                            $query_user->last2=Catch_receipt::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->sum('amount');
                            $query_user->last3=Catch_receipt::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->sum('amount');
                            $query_user->count=Catch_receipt::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->sum('amount');
                            $query_user->save();
                        }
                        elseif($static=="عدد فحص المستوى"){
                            $query_user->day1=English::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->count();
                            $query_user->day7=English::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->count();
                            $query_user->day15=English::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->count();
                            $query_user->day30=English::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->count();
                            $query_user->day60=English::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->count();
                            $query_user->day90=English::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->count();
                            $query_user->day180=English::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->count();
                            $query_user->last1=English::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->count();
                            $query_user->last2=English::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->count();
                            $query_user->last3=English::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->count();
                            $query_user->count=English::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->count();
                            $query_user->save();
                        }
                        elseif($static=="عدد شهادات ذات الرسوم"){
                            $query_user->day1=Certificate::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->count();
                            $query_user->day7=Certificate::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->count();
                            $query_user->day15=Certificate::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->count();
                            $query_user->day30=Certificate::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->count();
                            $query_user->day60=Certificate::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->count();
                            $query_user->day90=Certificate::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->count();
                            $query_user->day180=Certificate::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->count();
                            $query_user->last1=Certificate::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->count();
                            $query_user->last2=Certificate::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->count();
                            $query_user->last3=Certificate::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->count();
                            $query_user->count=Certificate::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->count();
                            $query_user->save();
                        }
                        elseif($static=="عدد المهام الجديدة"){
                            $query_user->day1=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->where('end_date',null)->count();
                            $query_user->day7=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->where('end_date',null)->count();
                            $query_user->day15=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->where('end_date',null)->count();
                            $query_user->day30=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->where('end_date',null)->count();
                            $query_user->day60=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->where('end_date',null)->count();
                            $query_user->day90=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->where('end_date',null)->count();
                            $query_user->day180=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->where('end_date',null)->count();
                            $query_user->last1=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->where('end_date',null)->count();
                            $query_user->last2=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->where('end_date',null)->count();
                            $query_user->last3=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->where('end_date',null)->count();
                            $query_user->count=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->where('end_date',null)->count();
                            $query_user->save();
                        }
                        elseif($static=="عدد المهام المنجزة"){
                            $query_user->day1=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->where('end_date','!=',null)->count();
                            $query_user->day7=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->where('end_date','!=',null)->count();
                            $query_user->day15=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->where('end_date','!=',null)->count();
                            $query_user->day30=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->where('end_date','!=',null)->count();
                            $query_user->day60=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->where('end_date','!=',null)->count();
                            $query_user->day90=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->where('end_date','!=',null)->count();
                            $query_user->day180=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->where('end_date','!=',null)->count();
                            $query_user->last1=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->where('end_date','!=',null)->count();
                            $query_user->last2=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->where('end_date','!=',null)->count();
                            $query_user->last3=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->where('end_date','!=',null)->count();
                            $query_user->count=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->where('end_date','!=',null)->count();
                            $query_user->save();
                        }
                        elseif($static=="تقييم المهام المنجزة"){
                            $query_user->day1=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->day7=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->day15=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->day30=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->day60=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->day90=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->day180=Task::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->last1=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->where('end_date','!=',null)->avg('evaluate');
                            $query_user->last2=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->where('end_date','!=',null)->avg('evaluate');
                            $query_user->last3=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->where('end_date','!=',null)->avg('evaluate');
                            $query_user->count=Task::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->where('end_date','!=',null)->avg('evaluate');
                            $query_user->save();
                        }

                        elseif($static=="عدد حملات التسويق الهاتفي"){
                            $query_user->day1=Campaign::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(1))->count();
                            $query_user->day7=Campaign::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(7))->count();
                            $query_user->day15=Campaign::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(15))->count();
                            $query_user->day30=Campaign::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(30))->count();
                            $query_user->day60=Campaign::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(60))->count();
                            $query_user->day90=Campaign::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(90))->count();
                            $query_user->day180=Campaign::where('created_by',$user->id)->where('created_at','>',Carbon::now()->subDays(180))->count();
                            $query_user->last1=Campaign::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-1)->count();
                            $query_user->last2=Campaign::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-2)->count();
                            $query_user->last3=Campaign::where('created_by',$user->id)->whereYear('created_at', '=', date('Y')-3)->count();
                            $query_user->count=Campaign::where('created_by',$user->id)->whereYear('created_at', '=', date('Y'))->count();
                            $query_user->save();
                        }
                        $query_user->save();
                    }
                }
            }



      public function getAllEmploey(){
        $isQuery=Query_emp::count();
        if($isQuery>0){
            $query_del=Query_emp::truncate();
        }

        $employees=Employee::where('isdelete',0)->where('active',1)->get();
        foreach($employees as $emp){
          $Absence1=Absence::where("employee_id",$emp->id)->where("type",124)->where("isdelete",0)->count();
          $Absence2=Absence::where("employee_id",$emp->id)->where("type",336)->where("isdelete",0)->count();

          $Abse_hours=Absence::where("employee_id",$emp->id)->where("type",336)->where("isdelete",0)->sum('hours');
          $Abse_minutes=Absence::where("employee_id",$emp->id)->where("type",336)->where("isdelete",0)->sum('minutes');
         // $day = $Abse_hours/24;
         // $hours =$Abse_minutes/60;
         $Absence3 = $Abse_hours . ":" . $Abse_minutes;
         $Absence4=Receipt_reward::where('employee_id',$emp->id)->where("isdelete",0)->where('type',0)->count();
         $Absence5=Receipt_reward::where('employee_id',$emp->id)->where("isdelete",0)->where('type',0)->sum('amount');
         $Absence6=Receipt_reward::where('employee_id',$emp->id)->where("isdelete",1)->where('type',0)->count();
         $Absence7=Receipt_reward::where('employee_id',$emp->id)->where("isdelete",1)->where('type',0)->sum('amount');

         $query_emp = new Query_emp();
         $query_emp->name=$emp->name;
         $query_emp->absence=$Absence1;
         $query_emp->late=$Absence2;
         $query_emp->all_late=$Absence3;
         $query_emp->reward=$Absence4;
         $query_emp->all_reward=$Absence5;
         $query_emp->reward1=$Absence6;
         $query_emp->all_reward1=$Absence7;
         $query_emp->save();
        }
      }








    }

