
@php

  if (Auth::check()){
            $isUs = App\Models\User_year::where('user_id',Auth::user()->id)->count();
            if ($isUs>0){
                $us = App\Models\User_year::where('user_id',Auth::user()->id)->first();
            }else{
                $us = App\Models\Money_year::where('basic_work',1)->first();
            }
            $us=$us->year;
        }

        $user=Auth::user();
        $taskName=$user->name;
        $taskDone=App\Models\Task::where('receiver',Auth::user()->id)->where('isdelete',0)->where('active',1)->whereNotNull('evaluate')->where('end_date','!=',null)->count();
        $taskRun=App\Models\Task::where('receiver',Auth::user()->id)->where('isdelete',0)->where('active',1)->where('start_date','!=',null)->where('end_date','=',null)->count();
        $users=App\Models\User::where('Status','مفعل')->where('isdelete',0)->get();
        $ratio=array();
        foreach($users as $user){
            $count=App\Models\Task::where('receiver',Auth::user()->id)->where('isdelete',0)->where('active',1)->where('evaluate','!=',null)->where('end_date','!=',null)->whereColumn('sender', '!=','receiver')->where('sender',Auth::user()->id)->count();
        if ($count>0){
            $ratios=App\Models\Task::where('receiver',Auth::user()->id)->where('sender',Auth::user()->id)->where('isdelete',0)->where('active',1)->where('evaluate','!=',null)->where('end_date','!=',null)->whereColumn('sender', '!=','receiver')->sum('evaluate');
            if($count==0){$taskRatio = 0;
            }else{ $taskRatio = number_format($ratios/$count,2);}
            array_push($ratio,$taskRatio);
        }else{

            $taskRatio=0;
        }

        }

        $allratios=array_sum($ratio);
        $allcount=count($ratio);
        $alltaskRatio= $allcount == 0 ? 0 : number_format($allratios/$allcount,2);


$register=App\Models\Student_course::where('created_by',Auth::user()->id)->whereYear('created_at', '=', date('Y'))->count();
$Fee_collection=App\Models\Catch_receipt::where('created_by',Auth::user()->id)->whereYear('created_at', '=', date('Y'))->sum('amount');
$certificates=App\Models\Certificate::where('created_by',Auth::user()->id)->whereYear('created_at', '=', date('Y'))->count();
$English=App\Models\English::where('created_by',Auth::user()->id)->whereYear('created_at', '=', date('Y'))->count();
$Campaign=App\Models\Campaign::where('created_by',Auth::user()->id)->whereYear('created_at', '=', date('Y'))->count();

///////////////////////


 $tasks = App\Models\Income_levels::where('isdelete',0)->where('active',1)->first();

 if($tasks){
   $cDate = Carbon\Carbon::parse($tasks->in_to);
   $ldate = date('Y-m-d H:i:s');
   if($cDate >  $ldate){
    $remaind = $cDate->diffInDays();
   }else{
    $remaind = 0;
   }

    $common_boxes=App\Models\Income_box::where('income_id',$tasks->id)->get();
                $balance=0;
                foreach($common_boxes as $common_box){
                $box= App\Models\Box::find($common_box->box_id);
                if($box){
                  $arrStart = explode("-", $tasks->in_from);
                        $arrEnd = explode("-", $tasks->in_to);
                        $from = Carbon\Carbon::create( $arrStart[0], $arrStart[1], $arrStart[2],0, 0, 0);
                        $to = Carbon\Carbon::create( $arrEnd[0], $arrEnd[1], $arrEnd[2],23, 59, 59);
                    if($box->id==3){
                        $courses_receipt = App\Models\Catch_receipt::where('isdelete','=','0')->whereBetween('date',[$from,$to])->sum('amount');

                        $balance+=$courses_receipt;
                    }
                    if($box->id==4){
                        $advance_receipt = App\Models\Receipt_salary::where('isdelete','=','0')->whereBetween('date',[$from,$to])->sum('advance_payment');

                        $balance+=$advance_receipt;
                    }
                    if($box->repository_id >0){
                        $rep = App\Models\Repository_in::where('repository_id','=',$box->repository_id)->where('isdelete','=','0')->whereBetween('created_at',[$from,$to])->sum('total');
                        $balance+=$rep;
                    }
                    if($box->type==147){
                        $rtype = App\Models\Catch_receipt_box::where('box_id','=',$box->id)->where('isdelete','=','0')->whereBetween('date',[$from,$to])->sum('amount');
                        $balance+=$rtype;
                    }
                }
                }

           $bala = $balance;
           $rate="";
         $all_leveles= $tasks->level1 + $tasks->level2 + $tasks->level3 + $tasks->level4 +$tasks->level5;

                if($bala <= $tasks->level1){
                    $rat = ($bala * 40) / $tasks->level1;
                    $rate = number_format($rat,1);
                }
                if($bala <= ($tasks->level2 + $tasks->level1) && $bala > $tasks->level1){
                    $rat = ($bala * 60) / ($tasks->level2 + $tasks->level1);
                     $rate = number_format($rat,1);
                }

                if($bala <= ($tasks->level3 + $tasks->level2+ $tasks->level1) && $bala > ($tasks->level2 + $tasks->level1)){
                    $rat = ($bala * 80) / ($tasks->level3 + $tasks->level2+ $tasks->level1);
                     $rate = number_format($rat,1);
                }

                if($bala <= ($tasks->level4 + $tasks->level3 + $tasks->level2+ $tasks->level1) && $bala > ($tasks->level3 +$tasks->level2 + $tasks->level1)){
                    $rat = ($bala * 90) / ($tasks->level4 + $tasks->level3 + $tasks->level2+ $tasks->level1);
                     $rate = number_format($rat,1);
                }
                if($bala <= ( $tasks->level5 + $tasks->level4 + $tasks->level3 + $tasks->level2+ $tasks->level1) && $bala > ($tasks->level4 + $tasks->level3 +$tasks->level2 + $tasks->level1)){
                    $rat = ($bala * 100) / ( $tasks->level5 + $tasks->level4 + $tasks->level3 + $tasks->level2+ $tasks->level1);
                     $rate = number_format($rat,1);
                }
                if( $bala > ( $tasks->level5 + $tasks->level4 + $tasks->level3 + $tasks->level2+ $tasks->level1)){
                     $rate = 100;
                }

 }





@endphp











<!-- Sidebar-right-->
		<div class="sidebar sidebar-left sidebar-animate">
			<div class="panel panel-primary card mb-0 box-shadow">
				<div class="tab-menu-heading border-0 p-3">
					<div class="card-title mb-0">اللوحه الجانبيه</div>
					<div class="card-options mr-auto">
						<a href="#" class="sidebar-remove"><i class="fe fe-x"></i></a>
					</div>
				</div>
				<div class="panel-body tabs-menu-body latest-tasks p-0 border-0">
					<div class="tabs-menu ">
						<!-- Tabs -->
						<ul class="nav panel-tabs">

                            <li><a href="#side2" data-toggle="tab" class="active"><i class="ion ion-md-notifications tx-18  ml-2"></i> احصائيات</a></li>
                            <li><a href="#side1" data-toggle="tab"><i class="ion ion-md-contacts tx-18 ml-2"></i> المؤشر الصوري</a></li>
                            <li><a href="#side3" data-toggle="tab"><i class="ion ion-md-contacts tx-18 ml-2"></i> المستخدمين</a></li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="tab-pane " id="side3">
							<div class="list-group list-group-flush ">
                            <?php $users= \App\Models\User::where('Status','مفعل')->where('id','!=',Auth::user()->id)->get();
                            ?>
                    @foreach ($users as $user)
								<div class="list-group-item d-flex  align-items-center">
									<div class="ml-2">
										<span class="avatar avatar-md brround cover-image" data-image-src="{{URL::asset('storage/users-avatar/'. $user->avatar)}}"><span class="avatar-status bg-success"></span></span>
									</div>
									<div class="">
										<div class="font-weight-semibold" {{-- data-toggle="modal" data-target="#chatmodel" --}}>{{ $user->name }}</div>
									</div>
									<div class="mr-auto">
										<a href="/chatify/{{$user->id}}" class="btn btn-sm btn-light" {{-- data-toggle="modal" data-target="#chatmodel" --}} ><i class="fab fa-facebook-messenger"></i></a>
									</div>
								</div>
                     @endforeach
							</div>
						</div>

                        	<div class="tab-pane  active" id="side2" >
							<div class="list-group list-group-flush">


                                <div class="list-group-item d-flex  align-items-center">
								<div class="">
										<h6 class="tag tag-orange">تقيم المهام المنجزه</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ number_format($alltaskRatio,1) }}</h5>
									</div>
								</div>
                            <div class="list-group-item d-flex  align-items-center">
									<div class="">
										<h6 class="tag tag-orange">عدد المهام المنجزه</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ $taskDone }}</h5>
									</div>
								</div>


								<div class="list-group-item d-flex  align-items-center">
								<div class="">
										<h6 class="tag tag-orange">عدد المهام الجديده</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ $taskRun }}</h5>
									</div>
								</div>




								<div class="list-group-item d-flex  align-items-center">
								<div class="">
										<h6 class="tag tag-orange">عدد تسجيل الطلاب</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ $register }}</h5>
									</div>
								</div>



								<div class="list-group-item d-flex  align-items-center">
								<div class="">
										<h6 class="tag tag-orange">عدد فحص المستوي</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ $English }}</h5>
									</div>
								</div>
                                    	<div class="list-group-item d-flex  align-items-center">

									<div class="">
										<h6 class="tag tag-orange">قيمه تحصيل الرسوم</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ $Fee_collection }}</h5>
									</div>
								</div>
                             <div class="list-group-item d-flex  align-items-center">
                                		<div class="">
										<h6 class="tag tag-orange">عدد شهادات ذات الرسوم</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ $certificates }}</h5>
									</div>
								</div>
                                	<div class="list-group-item d-flex  align-items-center" >

									<div class="">
										<h6 class="tag tag-orange">عدد حملات التسويق الهاتفي</h6>
									</div>
									<div class="mr-auto">
										<h5>{{ $Campaign }}</h5>
									</div>
								</div>


							</div>
						</div>



                        <div class="tab-pane " id="side1">
                            @if($tasks)
                        		<div class="list-group list-group-flush ">
                                   <div class="col-lg-12 col-xl-12">
                                    @if ($rate > 10.0 )
                                    <span class="l1"style="position: absolute;top: 143px;right: 160px;font-size: 35px;">{{ $rate  }}%</span>
                                    @else
                                    <span class="l1"style="position: absolute;top: 143px;right: 174px;font-size: 35px;">{{ $rate  }}%</span>
                                    @endif

                                    <span class="l1"style="position: absolute;top: 201px;right: 184px;">نسبه الانجاز</span>
                                        <div class="progress-bar"
                                    style="width: 180px;
                                    height: 180px;
                                    border-radius: 50%;
                                    background:
                                        radial-gradient(closest-side, white 79%, transparent 80% 100%),
                                        conic-gradient(#1101fd {{ $rate  }}%, #9094cd 0);
                                        margin-top: 90px;
                                        margin-right: 115px;">
                                            <progress value="5%" min="0" max="100" style="visibility:hidden;height:0;width:0;"></progress>

                                        </div>

                                                                        </div>
                                        <span class="l1"style="position: absolute; top: 490px;right: 146px;">L1</span>
                                        <span class="l2"style="position: absolute;top: 490px;right: 270px;">L2</span>
                                        <span class="l3"style="position: absolute;top: 374px;right: 310px;">L3</span>
                                            <span class="l4"style="position: absolute;top: 322px;right: 275px;">L4</span>
                                        <span class="l5"style="position: absolute;top: 302px;right: 213px;">L5</span>
                                                                </div>
                                                                <div class=""style="margin:35px">
                                    <p> اسم المستوي :<span style="font-weight: bold;"> {{ $tasks->name }}</span>&ensp;&ensp; متبقي :<span style="font-weight: bold;">{{   $remaind }} يوم</span></p>
                                    <p>الفتره من : <span style="font-weight: bold;">{{ $from->format('d-m-Y')}}</span> &ensp;&ensp; الي :<span style="font-weight: bold;"> {{ $to->format('d-m-Y') }}</span> </p>

                                    </div>
                            @endif

						</div>
					</div>
				</div>
			</div>
		</div>
<!--/Sidebar-right-->

