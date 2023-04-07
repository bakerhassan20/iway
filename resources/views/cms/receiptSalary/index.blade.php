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
@can('اضافة صرف راتب')
<a class="btn btn-primary btn-md" href="{{ route('ReceiptSalary.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>اضافه سند صرف جديد  </a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
@can('عرض صرف راتب')
		<!-- row -->
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
                                                            <select name="employee_4_h" id="employee_4_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                                                <option value="all"> اختر اسم الموظف.... </option>
                                                                @foreach($employees as $employee)
                                                                    <option {{old("employee_4_h")==$employee->id?"selected":""}} value="{{$employee->id}}"> {{$employee->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="month_4_h" id="month_4_h" class="form-control">
                                                                <option value=""> اختر من الشهر.... </option>
                                                                <option  value="1"> Jan - 01 </option>
                                                                <option  value="2"> Fab - 02 </option>
                                                                <option  value="3"> Mar - 03 </option>
                                                                <option  value="4"> Apr - 04 </option>
                                                                <option  value="5"> May - 05 </option>
                                                                <option  value="6"> June - 06 </option>
                                                                <option  value="7"> July - 07 </option>
                                                                <option  value="8"> Aug - 08 </option>
                                                                <option  value="9"> Sept - 09 </option>
                                                                <option  value="10"> Oct - 10 </option>
                                                                <option  value="11"> Nov - 11 </option>
                                                                <option  value="12"> Dec - 12 </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user_4_h" id="user_4_h" class="form-control">
                                                                <option value=""> اختر اسم المستخدم.... </option>
                                                                @foreach($users as $user)
                                                                    <option {{old("user_4_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
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
    <div class="col-5">
         <h3 class="">مجموع مستردات السلف: <span class="tag">
        <strong id="tot2_filter"></strong> </span> دينار  </h3>
 </div>
  <div class="col">
       <h3 class="" style=""> مجموع الرواتب :<span class="tag">
        <strong id="tot3_filter"></strong> </span> دينار</h3>
 </div>
  <div class="col">
   <h3 class="">المجموع الكلى:<span class="tag">
        <Strong id="total_4_filter"></Strong> </span>دينار</h3>
 </div>
 </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                    <table class="table table-bordered table-striped table-hover"  id="receipt-salary-table" style="width:100%">
                                       <thead>
                                                    <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>الموظف</th>
                                                        <th>الشهر</th>
                                                        <th> سداد ذمم</th>
                                                        <th class="total_4_filter">المبلغ</th>
                                                        <th>المبلغ الكلى</th>
                                                        <th>تاريخ ووقت الادخال</th>
                                                        <th>اسم المستخدم</th>
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

                            @cannot('عرض صرف راتب')
                                <div class="col-md-offset-1 col-md-10 alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    ليس لديك صلاحية يرجي مراجعة المسؤول
                                </div>
                            @endcannot



			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

   <script>
         setTimeout(function() {
                 var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var rsaTable = $('#receipt-salary-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle,   customize: function ( win ) {
                        var json = rsaTable.ajax.json();
                var all = parseInt(json.tot2) + parseInt(json.tot);
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<br><div class="row"><div class="col-5"><h3 class="">مجموع مستردات السلف: <span class="tag"><strong id="tot2_filter">'+json.tot2+'</strong></span> دينار  </h3></div><div class="col"><h3 class="" style=""> مجموع الرواتب :<span class="tag"><strong id="tot3_filter">'+json.tot+'</strong> </span> دينار</h3></div><div class="col"><h3 class="">المجموع الكلى:<span class="tag"><Strong id="total_4_filter">'+all+'</Strong> </span>دينار</h3></div></div>'
                        );
                }},

                    {'extend':'pdf','text':'pdf','title': pdfsubtitle,'exportOptions': {'orthogonal': "PDF"},customize: function ( doc ) {processDoc(doc); //fun in app.js
                    },
                    },
                    {'extend':'pageLength','text':'حجم العرض'},

                   ],
                    columnDefs: [{
                        targets: '_all',
                        render: function(data, type, row) {
                            if (type === 'PDF') {
                                return String(data).split(' ').reverse().join(' ');
                            }  return data;} }
                   ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json',
                },
                drawCallback: function () {

                },
                ajax: {
                    url: '/CMS/datatables/ReceiptSalary',
                    data: function (d) {
                        d.searchReceiptSalary = $('#receipt-salary-table_filter input[type=search]').val();
                        d.employeeId = $('select[name=employee_4_h]').val();
                        d.monthId = $('select[name=month_4_h]').val();
                        d.userId = $('select[name=user_4_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },
                columns: [
                    { data: 'id_sys', name: 'id_sys' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'employee_id', name: 'employee_id' },
                    { data: 'month', name: 'month' },
                    { data: 'advance_payment', name: 'advance_payment' },
                    { data: 'amount', name: 'amount' },
                    { data: 'total_amount', name: 'total_amount' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                            var show ='<a class="btn btn-sm btn-success" href="/CMS/ReceiptSalary/'+row.id+'">عرض</a>';
                            var edit ='<a class="btn btn-sm btn-info" href="/CMS/ReceiptSalary/'+row.id+'/edit">تعديل</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/ReceiptSalary/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض صرف راتب')
                                ress=ress+' '+show;
                            @endcan
                                    @can('تعديل صرف راتب')
                                ress=ress+' '+edit;
                            @endcan
                                    @can('حذف صرف راتب')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#employee_4_h').change(function() {
                rsaTable.draw();
            });
            $('#month_4_h').change(function() {
                rsaTable.draw();
            });
            $('#user_4_h').change(function() {
                rsaTable.draw();
            });
            $('#money_id').change(function() {
                rsaTable.draw();
            });
            rsaTable.on( 'xhr', function () {
                var json = rsaTable.ajax.json();
                var all = parseInt(json.tot2) + parseInt(json.tot);
                $("#tot2_filter").replaceWith('<Strong id="tot2_filter">'+json.tot2+'</Strong>');
                $("#tot3_filter").replaceWith('<Strong id="tot3_filter">'+json.tot+'</Strong>');
                $("#total_4_filter").replaceWith('<Strong id="total_4_filter">'+all+'</Strong>');
            });
        },400);

    </script>
@endsection
