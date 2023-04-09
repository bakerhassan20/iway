@extends('layouts.master')
@section('css')
<script src="https://cdn.jsdelivr.net/npm/apexcharts" on:load={initCharts}></script>

<!--  Owl-carousel css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
<style>
.dt-buttons{
display:none !important;
}
</style>
@endsection
@section('title-page-header')
الصفحه الرئيسيه
@endsection
@section('page-header')
الاحصائيات
@endsection

@section('content')
@can('الصفحة الرئيسية')




<?php

 $moneyYears = \App\Models\Money_year::where('active','1')->orderBy('year')->get();
 $moneyWork = \App\Models\Money_year::where('basic_work','1')->first();

$userL = \App\Models\User_year::where('user_id',Auth::user()->id)->count(); ?>
    @if ($userL>0)
        <?php
        $userY = \App\Models\User_year::where('user_id',Auth::user()->id)->first();
        $uY = $userY->year;
        ?>
    @else
        <?php $uY = null; ?>
    @endif<?php $userL = \App\Models\User_year::where('user_id',Auth::user()->id)->count(); ?>
    @if ($userL>0)
        <?php
        $userY = \App\Models\User_year::where('user_id',Auth::user()->id)->first();
        $uY = $userY->year;
        ?>
    @else
        <?php $uY = null; ?>
    @endif

				<!-- row -->
				<div class="row row-sm">
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-primary-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h4 class="mb-3 text-white">الطلاب</h4>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h3 class="font-weight-bold mb-1 text-white">{{ \App\Models\Student::where("isdelete",0)->count() }}</h3>

										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-danger-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h4 class="mb-3 text-white">عدد تسجيل الدورات</h4>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h3 class="font-weight-bold mb-1 text-white">{{ \App\Models\Student_course::where("isdelete",0)->count() }}</h3>

										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-success-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h4 class="mb-3 text-white">عدد الانسحابات</h4>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h3 class=" font-weight-bold mb-1 text-white">{{ \App\Models\Withdrawal::where("isdelete",0)->count() }}</h3>

										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-warning-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h4 class="mb-3 text-white">عدد فحص المستوي</h4>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h3 class="font-weight-bold mb-1 text-white">{{ App\Models\English::where("isdelete",0)->count() }}</h3>

										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
				<!-- row closed -->

				<!-- row opened -->
					<div class="row row-sm">
					<div class="col">
						<div class="card">
							<div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mb-0">احصائيات الوضع المالي</h4>
									<i class="mdi mdi-dots-horizontal text-gray"></i>
								</div>

							</div>
							<div class="card-body">
								<div class="total-revenue">
									<div>

									  <label><span class="bg-primary"></span>الصندوق الرئيسي</label>
									</div>
									<div>

									  <label><span class="bg-danger"></span>صندوق المركز</label>
									</div>
									<div>
									  <label><span class="bg-warning"></span>صندوق الدورت</label>
									</div>
                                    <div>
									  <label><span class="bg-success"></span>صندوق الرواتب</label>
									</div>
								  </div>
								<div id="bar" class="sales-bar mt-4"></div>
							</div>
						</div>
					</div>

				</div>
				<!-- row closed -->

				<!-- row opened -->
				<div class="row row-sm">
					<div class="col">
						<div class="card">
					<div style="width:100%;">

                        {!! $chartjs->render() !!}
                    </div>
						</div>
					</div>
                    	</div>


{{-- <div class="row row-sm">
		<div class="col">
		  <div class="card">

         <div class="col-lg-12 col-xl-12">
            <div class="card card-dashboard-map-one">
                <label class="main-content-label"></label>
                <div class="" style="width: 100%">
                  {!! $chartjs_2->render() !!}
                </div>
            </div>
        </div>
						</div>
</div>
<div class="col">
						<div class="card ">
							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="d-flex align-items-center pb-2">
											<p class="mb-0">المستخدمين</p>
										</div>
										<h4 class="font-weight-bold mb-2">{{ \App\Models\User::count() }}</h4>
										<div class="progress progress-style progress-sm">
											<div class="progress-bar bg-primary-gradient wd-80p" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78"></div>
										</div>
									</div>
									<div class="col-md-6 mt-4 mt-md-0">
										<div class="d-flex align-items-center pb-2">
											<p class="mb-0">المستخدمين الفعالين</p>
										</div>
										<h4 class="font-weight-bold mb-2">{{ \App\Models\User::where('Status','مفعل')->count() }}</h4>
										<div class="progress progress-style progress-sm">
											<div class="progress-bar bg-danger-gradient wd-75" role="progressbar"  aria-valuenow="45" aria-valuemin="0" aria-valuemax="45"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div> --}}
				<!-- row close -->
      <!-- row -->
				<div class="row">
                			<!--div-->
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">

							</div>
							<div class="card-body">
								<div class="table-responsive country-table">
									<table id="users-table" class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">

                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>اسم المستخدم</th>
                                                <th>الموضوع</th>
                                                <th>العدد الكلي</th>
                                            </tr>
                                            </thead>

									</table>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->
				</div>



				<!-- row closed -->

			</div>

            		<!-- row opened -->
				<div class="row row-sm row-deck">

					<div class="col">
						<div class="card card-table-two">


							<div class="table-responsive country-table">
								<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
									<thead>

										<tr>
                                        	<th class="wd-lg-25p">اسم الموظف</th>
											<th class="wd-lg-25p tx-right">عدد الغياب</th>
											<th class="wd-lg-25p tx-right"> ايام التاخير</th>
                                            <th class="wd-lg-25p tx-right">مجموع التاخير</th>
                                            <th class="wd-lg-25p tx-right">عدد المكافات</th>
                                            <th class="wd-lg-25p tx-right">قيمه المكافات</th>
                                            <th class="wd-lg-25p tx-right">عدد الخصومات</th>
                                            <th class="wd-lg-25p tx-right">قيمه الخصومات</th>
										</tr>
									</thead>
									<tbody>

                                    @foreach ( $query_emp as $query_e )
                                    	<tr>
											<td class="tx-right tx-medium ">{{$query_e->name}}</td>
											<td class="tx-right tx-medium ">{{$query_e->absence}}</td>
											<td class="tx-right tx-medium ">{{$query_e->late}}</td>
											<td class="tx-right tx-medium ">{{$query_e->all_late}}</td>
                                            <td class="tx-right tx-medium ">{{$query_e->reward}}</td>
											<td class="tx-right tx-medium ">{{$query_e->all_reward}}</td>
											<td class="tx-right tx-medium ">{{$query_e->reward1}}</td>
                                            <td class="tx-right tx-medium ">{{$query_e->all_reward1}}</td>
										</tr>
                                    @endforeach

									</tbody>
								</table>
							</div>
						</div>
					</div>
						</div>

				<!-- /row -->


				<!-- row opened -->
				<div class="row row-sm row-deck">

					<div class="col">
						<div class="card card-table-two">
							<div class="d-flex justify-content-between">
								<h4 class="card-title mb-1">احصائيات المستودعات</h4>
								<i class="mdi mdi-dots-horizontal text-gray"></i>
							</div>

							<div class="table-responsive country-table">
								<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
									<thead>
										<tr>
											<th class="wd-lg-25p">اسم المستودع</th>
											<th class="wd-lg-25p tx-right">عدد الاقسام</th>
											<th class="wd-lg-25p tx-right">عدد المنتجات</th>
                                            <th class="wd-lg-25p tx-right">قيمه القبض</th>
                                            <th class="wd-lg-25p tx-right">قيمه الصرف</th>
										</tr>
									</thead>
									<tbody>

                                    @php
                                          $repos = \App\Models\Repository::where('isdelete',0)->get();

                                    @endphp
                                    @foreach ( $repos as $repo )
                                    	<tr>
											<td class="tx-right tx-medium tx-primary">{{$repo->name}}</td>
											<td class="tx-right tx-medium tx-inverse">{{\App\Models\Rep_section::where('repository_id',$repo->id)->where('isdelete',0)->count() }}</td>
											<td class="tx-right tx-medium tx-inverse">{{\App\Models\Material::where('repository_id',$repo->id)->where('isdelete',0)->count() }}</td>
                                       <td class="tx-right tx-medium tx-inverse">{{\App\Models\Repository_year::where('repository_id',$repo->id)->where('m_year','=',$userL>0?$uY:$moneyWork->year)->first()->repository_in }}</td>
                                            <td class="tx-right tx-medium tx-inverse">{{\App\Models\Repository_year::where('repository_id',$repo->id)->where('m_year','=',$userL>0?$uY:$moneyWork->year)->first()->repository_out }}</td>
										</tr>
                                    @endforeach

									</tbody>
								</table>
							</div>
						</div>
					</div>
						</div>
				
				<!-- /row -->


                @endcan

                @cannot('الصفحة الرئيسية')
        <div class="col-md-offset-1 col-md-10 alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            ليس لديك صلاحية يرجي مراجعة المسؤول
        </div>
    @endcannot
			</div>
		</div>
		<!-- Container closed -->
@endsection
@section('js')




<script>

/* Apexcharts (#bar) */
	var optionsBar = {
	  chart: {
		height: 300,
		type: 'bar',
		toolbar: {
		   show: true,
		},
		fontFamily: 'Nunito, sans-serif',
		// dropShadow: {
		//   enabled: true,
		//   top: 1,
		//   left: 1,
		//   blur: 2,
		//   opacity: 0.2,
		// }
	  },
	 colors: ["#036fe7", '#f93a5a', '#f7a556','#319353'],
	 plotOptions: {
				bar: {
				  dataLabels: {
					enabled: false
				  },
				  columnWidth: '42%',
				  endingShape: 'rounded',
				}
			},
	  dataLabels: {
		  enabled: false
	  },
	  stroke: {
		  show: true,
		  width: 2,
		  endingShape: 'rounded',
		  colors: ['transparent'],
	  },
	  responsive: [{
		  breakpoint: 576,
		  options: {
			   stroke: {
			  show: true,
			  width: 1,
			  endingShape: 'rounded',
			  colors: ['transparent'],
			},
		  },


	  }],

      series: [{
        name: 'Speed',
        data: [7, 8, 5, 5, 7, 3, 6]
    },{
        name: 'Turnover',
        data: [4, 3, 10, 9, 4, 5, 5]
    },{
        name: 'Turnover',
        data: [4, 3, 10, 9, 4, 5, 5]
    },
     {
        name: 'In',
        data: [2, 3, 4, 7, 3, 6, 2]
    }],
    xaxis: {
        categories: ['day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'day7'],
    },

	  fill: {
		  opacity: 1
	  },
	  legend: {
		show: false,
		floating: true,
		position: 'top',
		horizontalAlign: 'left',
		// offsetY: -36

	  },
	  // title: {
	  //   text: 'Financial Information',
	  //   align: 'left',
	  // },
	  tooltip: {
		  y: {
			  formatter: function (val) {
				  return  val + " دينار"
			  }
		  }
	  }
	}




   var url = '/CMS/datatables/QueryMoney';
 $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        async: true,
        cache: false,

        success: function (response) {
         	 var chartq = new ApexCharts(document.querySelector('#bar'), optionsBar);
     chartq.render();

chartq.updateOptions({
 series: [{
        name: 'الصندوق الرئيسي',
       data: [response.data[0].day1, response.data[0].day15, response.data[0].day7,response.data[0].day30, response.data[0].day60, response.data[0].day90, response.data[0].day180]
    },{
        name: 'صندوق المركز',
        data: [response.data[1].day1, response.data[1].day15, response.data[1].day7,response.data[1].day30, response.data[1].day60, response.data[1].day90, response.data[1].day180]
    },{
        name: 'صندوق الدورت',
        data: [response.data[2].day1, response.data[2].day15, response.data[2].day7,response.data[2].day30, response.data[2].day60, response.data[2].day90, response.data[2].day180]
    },
     {
        name: 'صندوق الرواتب',
        data: [response.data[3].day1, response.data[3].day15, response.data[3].day7,response.data[3].day30, response.data[3].day60, response.data[3].day90, response.data[3].day180]
    }],
    xaxis: {
        categories: ['day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'day7'],
    },
});
        }
    });
	/* Apexcharts (#bar) closed */


</script>
{{-- <!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('assets/plugins/raphael/raphael.min.js')}}"></script>
<!--Internal  Flot js-->
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
<script src="{{URL::asset('assets/js/dashboard.sampledata.js')}}"></script>
<script src="{{URL::asset('assets/js/chart.flot.sampledata.js')}}"></script>
<!--Internal Apexchart js-->
<script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>

<script src="{{URL::asset('assets/js/modal-popup.js')}}"></script>
<!--Internal  index js -->
<script src="{{URL::asset('assets/js/index.js')}}"></script> --}}
 <script>
        $(function() {

            var qTable = $('#users-table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
               pageLength: 8,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json',
                },
                searching: false,
                orderBy:[0,'desc'],
                ajax: {
                    url: '/CMS/datatables/QueryUser',

                },
                columns: [
                    { data: 'id', name: 'id', visible : false},
                    { data: 'user_id', name: 'user_id' },
                    { data: 'subject', name: 'subject' },
                    { data: 'count', name: 'count' },

                ]
            });

        });
    </script>
@endsection
