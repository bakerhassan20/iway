
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
         $char = app()->chartjs
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
							<div class="list-group list-group-flush ">

                                   <div class="col-lg-12 col-xl-12">


                                            <label class="main-content-label"></label>
                                            <div class="" style="width: 100%">
                                            {!! $char->render() !!}
                                            </div>

                                    </div>
							</div>
						</div>




					</div>
				</div>
			</div>
		</div>
<!--/Sidebar-right-->

