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
@can('اضافة صرف ضمان')
<a class="btn btn-primary btn-md" href="{{ route('ReceiptWarranty.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>اضافه سند صرف جديد </a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
@can('عرض صرف ضمان')



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
                                                            <select name="employee_7_h" id="employee_7_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                                                <option value="all"> اختر اسم الموظف.... </option>
                                                                @foreach($employees as $employee)
                                                                    <option {{old("employee_7_h")==$employee->id?"selected":""}} value="{{$employee->id}}"> {{$employee->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="month_7_h" id="month_7_h" class="form-control">
                                                                <option value=""> اختر من الشهر.... </option>
                                                                <option {{old("month_7_h")=="01"?"selected":""}} value="01"> Jan - 01 </option>
                                                                <option {{old("month_7_h")=="02"?"selected":""}} value="02"> Fab - 02 </option>
                                                                <option {{old("month_7_h")=="03"?"selected":""}} value="03"> Mar - 03 </option>
                                                                <option {{old("month_7_h")=="04"?"selected":""}} value="04"> Apr - 04 </option>
                                                                <option {{old("month_7_h")=="05"?"selected":""}} value="05"> May - 05 </option>
                                                                <option {{old("month_7_h")=="06"?"selected":""}} value="06"> June - 06 </option>
                                                                <option {{old("month_7_h")=="07"?"selected":""}} value="07"> July - 07 </option>
                                                                <option {{old("month_7_h")=="08"?"selected":""}} value="08"> Aug - 08 </option>
                                                                <option {{old("month_7_h")=="09"?"selected":""}} value="09"> Sept - 09 </option>
                                                                <option {{old("month_7_h")=="10"?"selected":""}} value="10"> Oct - 10 </option>
                                                                <option {{old("month_7_h")=="11"?"selected":""}} value="11"> Nov - 11 </option>
                                                                <option {{old("month_7_h")=="12"?"selected":""}} value="12"> Dec - 12 </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user_7_h" id="user_7_h" class="form-control">
                                                                <option value=""> اختر اسم المستخدم.... </option>
                                                                @foreach($users as $user)
                                                                    <option {{old("user_7_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
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
                            <h3 class="panel-title text-left">المجموع:<span class="tag"><Strong id="total_7_filter"></Strong></span> دينار</h3>
                            <br>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                   <table class="table table-bordered table-striped table-hover"  id="receipt-warranty-table" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>الموظف</th>
                                                        <th>الشهر</th>
                                                        <th>الراتب</th>
                                                        <th class="total_7_filter">المبلغ</th>
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

                            @cannot('عرض صرف ضمان')
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
            var rwTable = $('#receipt-warranty-table').DataTable({
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
                drawCallback: function () {
                    var json = rwTable.ajax.json();
                    $('#total_7_filter').replaceWith('<strong id="total_7_filter">'+json.tot+'</strong>')
                },
                ajax: {
                    url: '/CMS/datatables/ReceiptWarranty',
                    data: function (d) {
                        d.searchReceiptWarranty = $('#receipt-warranty-table_filter input[type=search]').val();
                        d.employeeId = $('select[name=employee_7_h]').val();
                        d.monthId = $('select[name=month_7_h]').val();
                        d.userId = $('select[name=user_7_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },
                columns: [
                   { data: 'id_sys', name: 'id_sys' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'employee_id', name: 'employee_id' },
                    { data: 'month', name: 'month' },
                    { data: 'salary_id', name: 'salary_id' },
                    { data: 'amount', name: 'amount' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                             var show ='<a class="btn btn-sm btn-success" href="/CMS/ReceiptWarranty/'+row.id+'">عرض</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/ReceiptWarranty/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض صرف ضمان')
                                ress=ress+' '+show;
                            @endcan
                                    @can('حذف صرف ضمان')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#employee_7_h').change(function() {
                rwTable.draw();
            });
            $('#month_7_h').change(function() {
                rwTable.draw();
            });
            $('#user_7_h').change(function() {
                rwTable.draw();
            });
            $('#money_id').change(function() {
                rwTable.draw();
            });
        },400);
    </script>
@endsection()
