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
@can('اضافة صرف سلفة')
<a class="btn btn-primary btn-md" href="{{ route('ReceiptAdvance.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>اضافه سند صرف جديد </a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
@can('عرض صرف سلفة')


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
                                                            <select name="employee_5_h" id="employee_5_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                                                <option value="all"> اختر اسم الموظف.... </option>
                                                                @foreach($employees as $employee)
                                                                    <option {{old("employee_5_h")==$employee->id?"selected":""}} value="{{$employee->id}}"> {{$employee->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user_5_h" id="user_5_h" class="form-control">
                                                                <option value=""> اختر اسم المستخدم.... </option>
                                                                @foreach($users as $user)
                                                                    <option {{old("user_5_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
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
                            <h3 class="panel-title text-left">المجموع: <Strong id="total_5_filter"></Strong>دينار</h3>
                            <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                    <table class="table table-bordered table-striped table-hover"  id="receipt-advance-table" style="width:100%">
                                       <thead>
                                                    <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>الموظف</th>
                                                        <th class="total_5_filter">مبلغ السلفة</th>
                                                        <th>عدد شهور السداد</th>
                                                        <th>دفعات السداد الشهري</th>
                                                        <th>يبدأ السداد من راتب شهر</th>
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

                            @cannot('عرض صرف سلفة')
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
               var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var raTable = $('#receipt-advance-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
               buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle,   customize: function ( win ) {
                       var json = raTable.ajax.json();
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<br><h3 class="panel-title text-left">المجموع: <Strong id="total_5_filter">'+json.tot+'</Strong> دينار</h3><br>'
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
               {{--  drawCallback: function () {
                    var total_5_filter = this.api().column('.total_5_filter').data().sum();
                    $('#total_5_filter').replaceWith('<strong id="total_5_filter">'+total_5_filter+'</strong>')
                }, --}}
                ajax: {
                    url: '/CMS/datatables/ReceiptAdvance',
                    data: function (d) {
                        d.searchReceiptAdvance = $('#receipt-advance-table_filter input[type=search]').val();
                        d.employeeId = $('select[name=employee_5_h]').val();
                        d.userId = $('select[name=user_5_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },
                columns: [
                   { data: 'id_sys', name: 'id_sys' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'employee_id', name: 'employee_id' },
                    { data: 'advance_payment', name: 'advance_payment' },
                    { data: 'month_count', name: 'month_count' },
                    { data: 'month_payment', name: 'month_payment' },
                    { data: 'start_payment', name: 'start_payment' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                           var show ='<a class="btn btn-sm btn-success" href="/CMS/ReceiptAdvance/'+row.id+'">عرض</a>';
                            var edit ='<a class="btn btn-sm btn-info" href="/CMS/ReceiptAdvance/'+row.id+'/edit">تعديل</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/ReceiptAdvance/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض صرف سلفة')
                                ress=ress+' '+show;
                            @endcan
                                    @can('تعديل صرف سلفة')
                                ress=ress+' '+edit;
                            @endcan
                                    @can('حذف صرف سلفة')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#employee_5_h').change(function() {
                raTable.draw();
            });
            $('#user_5_h').change(function() {
                raTable.draw();
            });
            $('#money_id').change(function() {
                raTable.draw();
            });
             raTable.on( 'xhr', function () {
                var json = raTable.ajax.json();

                $("#total_5_filter").replaceWith('<Strong id="total_5_filter">'+json.tot+'</Strong>');


            });
        },600);
    </script>
@endsection()
