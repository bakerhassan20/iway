@extends('layouts.master')
@section('css')


@section('title')
 Iwayc System

@endsection

@section('title-page-header')
{{ $title }}
@endsection
@section('page-header')
{{ $subtitle }}

@endsection
@section('button1')
@can('اضافة صرف مكافأة')
<a class="btn btn-primary btn-md" href="{{ route('ReceiptReward.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i> اضافه سند صرف جديد </a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
@can('عرض صرف مكافأة')


  <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="">
                    <h3 class="panel-title">
                    <a aria-controls="collapseExample" aria-expanded="false" class="btn ripple btn-success" data-toggle="collapse" href="#collapseExample" role="button">قائمة الفرز والفلترة</a>
                    </h3>
                </div>
                <div class="collapse" id="collapseExample">
                    <div class="table-responsive ls-table">
                            <table class="col-md-12 table table-bordered table-striped table-hover">
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <select name="employee_6_h" id="employee_6_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                                                <option value="all"> اختر اسم الموظف.... </option>
                                                                @foreach($employees as $employee)
                                                                    <option {{old("employee_6_h")==$employee->id?"selected":""}} value="{{$employee->id}}"> {{$employee->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="month_6_h" id="month_6_h" class="form-control">
                                                                <option value=""> اختر من الشهر.... </option>
                                                                <option {{old("month_6_h")=="1"?"selected":""}} value="1"> Jan - 01 </option>
                                                                <option {{old("month_6_h")=="2"?"selected":""}} value="2"> Fab - 02 </option>
                                                                <option {{old("month_6_h")=="3"?"selected":""}} value="3"> Mar - 03 </option>
                                                                <option {{old("month_6_h")=="4"?"selected":""}} value="4"> Apr - 04 </option>
                                                                <option {{old("month_6_h")=="5"?"selected":""}} value="5"> May - 05 </option>
                                                                <option {{old("month_6_h")=="6"?"selected":""}} value="6"> June - 06 </option>
                                                                <option {{old("month_6_h")=="7"?"selected":""}} value="7"> July - 7 </option>
                                                                <option {{old("month_6_h")=="8"?"selected":""}} value="8"> Aug - 08 </option>
                                                                <option {{old("month_6_h")=="9"?"selected":""}} value="9"> Sept - 09 </option>
                                                                <option {{old("month_6_h")=="10"?"selected":""}} value="10"> Oct - 10 </option>
                                                                <option {{old("month_6_h")=="11"?"selected":""}} value="11"> Nov - 11 </option>
                                                                <option {{old("month_6_h")=="12"?"selected":""}} value="12"> Dec - 12 </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="type_6_h" id="type_6_h" class="form-control">
                                                                <option value=""> اختر من النوع.... </option>
                                                                <option {{old("type_6_h")=="0"?"selected":""}} value="0"> مكافأت </option>
                                                                <option {{old("type_6_h")=="1"?"selected":""}} value="1"> خصومات </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user_6_h" id="user_6_h" class="form-control">
                                                                <option value=""> اختر اسم المستخدم.... </option>
                                                                @foreach($users as $user)
                                                                    <option {{old("user_6_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <br>



<div class="row">

  <div class="col">
       <h3 class="panel-title"> مجموع المكافات :<span class="tag">
        <strong id="total_6_reward_filter"></strong> </span> دينار</h3>
 </div>
  <div class="col">
   <h3 class="panel-title">مجموع الخصومات:<span class="tag"><Strong id="total_6_receipt_filter"></Strong></span> دينار</h3>
 </div>
  <div class="col">
          <h3 class="panel-title">الصافى : <span class="tag"><Strong id="total_6_safi_filter"></Strong></span> دينار</h3>
 </div>
 </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                    <table class="table table-bordered table-striped table-hover"  id="receipt-reward-table" style="width:100%">
                                       <thead>
                                                    <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>الموظف</th>
                                                        <th class="total_6_type_filter">النوع</th>
                                                        <th>السبب</th>
                                                        <th class="total_6_filter">المبلغ</th>
                                                        <th>من شهر</th>
                                                        <th>اسم المستخدم</th>
                                                        <th>تاريخ ووقت الادخال</th>
                                                        <th></th>
                                                    </tr>
                                                    </thead>
                                    </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



@endcan

                            @cannot('عرض صرف مكافأة')
                                <div class="col-md-offset-1 col-md-10 alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    ليس لديك صلاحية يرجي مراجعة المسؤول
                                </div>
                            @endcannot


			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section("js")
    <script>
      setTimeout(function() {
            var rrTable = $('#receipt-reward-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                buttons: [
                    {'extend':'excel','text':'أكسيل'},
                    {'extend':'print','text':'طباعة'},
                    {'extend':'pdf','text':'pdf'},
                    {'extend':'pageLength','text':'حجم العرض'},
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json',
                },
                ajax: {
                    url: '/CMS/datatables/ReceiptReward',
                    data: function (d) {
                        d.searchReceiptReward = $('#receipt-reward-table_filter input[type=search]').val();
                        d.employeeId = $('select[name=employee_6_h]').val();
                        d.monthId = $('select[name=month_6_h]').val();
                        d.typeId = $('select[name=type_6_h]').val();
                        d.userId = $('select[name=user_6_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },
                columns: [
                { data: 'id_sys', name: 'id_sys' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'employee_id', name: 'employee_id' },
                    { data: 'type', name: 'type' },
                    { data: 'reason', name: 'reason' },
                    { data: 'amount', name: 'amount' },
                    { data: 'receipts_rewards', name: 'receipts_rewards' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' },
                    {"mRender": function ( data, type, row ) {
                            var show ='<a class="btn btn-sm btn-success" href="/CMS/ReceiptReward/'+row.id+'">عرض</a>';
                            var edit ='<a class="btn btn-sm btn-info" href="/CMS/ReceiptReward/'+row.id+'/edit">تعديل</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/ReceiptReward/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض صرف مكافأة')
                                ress=ress+' '+show;
                            @endcan
                                    @can('تعديل صرف مكافأة')
                                ress=ress+' '+edit;
                            @endcan
                                    @can('حذف صرف مكافأة')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#employee_6_h').change(function() {
                rrTable.draw();
            });
            $('#month_6_h').change(function() {
                rrTable.draw();
            });
            $('#type_6_h').change(function() {
                rrTable.draw();
            });
            $('#user_6_h').change(function() {
                rrTable.draw();
            });
            $('#money_id').change(function() {
                rrTable.draw();
            });
             rrTable.on( 'xhr', function () {
                var json = rrTable.ajax.json();

    $("#total_6_reward_filter").replaceWith('<Strong id="total_6_reward_filter">'+json.rewards+'</Strong>');

    var rewards=json.rewards.replace(',','');
    var all=json.all.replace(',','');
    var deductions = parseFloat(all) - parseFloat(rewards);
    var safi = parseFloat(rewards) - parseFloat(deductions);
$("#total_6_receipt_filter").replaceWith('<Strong id="total_6_receipt_filter">'+deductions.toFixed(2)+'</Strong>');
  $("#total_6_safi_filter").replaceWith('<Strong id="total_6_safi_filter">'+safi.toFixed(2)+'</Strong>');
            });
        },200);
    </script>
@endsection()
