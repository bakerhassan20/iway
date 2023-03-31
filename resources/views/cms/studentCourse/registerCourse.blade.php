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
@can('اضافة طالب')

@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')


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
                                        <select name="student_h" id="student_h" class="form-control">
                                            <option value=""> اختر اسم الطالب.... </option>
                                        @foreach($students as $st)
                                            <option {{old("student_h")==$st?"selected":""}} value="{{$st->id}}"> {{$st->nameAR}} </option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="course_h" id="course_h" class="form-control">
                                            <option value=""> اختر اسم الدورة.... </option>
                                            @foreach($c as $co)
                                            <option {{old("course_h")==$co?"selected":""}} value="{{$co}}"> {{\App\Models\Course::find($co)->courseAR}} </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="status_h" id="status_h" class="form-control">
                                            <option value=""> اختر الحالة.... </option>
                                            <option {{old("status_h")=='0'?"selected":""}} value="0"> مسجل </option>
                                            <option {{old("status_h")=='1'?"selected":""}} value="1"> منسحب </option>
                                            <option {{old("status_h")=='2'?"selected":""}} value="2"> محذوف </option>
                                        </select>
                                    </td>
                                        <td>
                                        <select name="user_1_h" id="user_1_h" class="form-control">
                                        <option value=""> اختر اسم المستخدم.... </option>
                                        @foreach($users as $user)
                                        <option {{old("user_1_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
                                        @endforeach
                                        </select>
                                    </td>

                                </tr>
                                    <tr>
                                        <td>
                                            <input type="text" value="{{old("from_1_h")}}"class="form-control text-input fc-datepicker" id="from_1_h" name="from_1_h" placeholder="من....">
                                        </td>
                                        <td>
                                            <input type="text" value="{{old("to_1_h")}}"class="form-control text-input fc-datepicker" id="to_1_h" name="to_1_h" placeholder="الي...">
                                        </td>
                                        <td>
                                        <a class="btn btn-primary" id="search_1_h" name="search_1_h">بحث</a>
                                        </td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<br><br>
<div class="row">

  <div class="col">
       <h3 class="panel-title"> مجموع الرسوم :<span class="tag">
        <strong id="total_6_reward_filter"></strong> </span> دينار</h3>
 </div>
  <div class="col">
   <h3 class="panel-title">مجموع المدفوع:<span class="tag"><Strong id="total_6_receipt_filter"></Strong></span> دينار</h3>
 </div>
  <div class="col">
          <h3 class="panel-title">المتبقي : <span class="tag"><Strong id="total_6_safi_filter"></Strong></span> دينار</h3>
 </div>
 </div>


		<!-- row -->
			         <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">

                                    <div class="panel-body">
                                        <!--Table Wrapper Start-->
                                        <div class="table-responsive ls-table">
                                            <table class="table table-bordered table-striped table-hover"  id="student-courses-table" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>اسم الطالب</th>
                                                        <th>اسم الدورة</th>
                                                        <th>الرسوم</th>
                                                        <th>المبلغ المدفوع</th>
                                                        <th>السنة</th>
                                                        <th>تاريخ التسجيل</th>
                                                        <th>المستخدم</th>
                                                        <th>الحالة</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

<!--Internal  Datepicker js -->
<script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js')}}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!-- Internal form-elements js -->
<script src="{{URL::asset('assets/js/form-elements.js')}}"></script>
<!-- Internal Select2.min js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>


   <script>
        $(function() {
            var sTable = $('#student-courses-table').DataTable({
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
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json',
                },
                ajax: {
                    url: '/CMS/datatables/StudentCourse',
                    data: function (d) {
                        d.searchStudent = $('#student-courses-table_filter input[type=search]').val();
                        d.courseId = $('select[name=course_h]').val();
                        d.studentId = $('select[name=student_h]').val();
                        d.statusId = $('select[name=status_h]').val();
                        d.userId = $('select[name=user_1_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                         d.fromId = $('#from_1_h').val();
                        d.toId = $('#to_1_h').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id',orderable: true },
                    { data: 'nameAR', name: 'nameAR',orderable: true },
                    { data: 'courseAR', name: 'courseAR',orderable: true },
                    { data: 'price', name: 'price',orderable: true },
                    { data: 'pay', name: 'pay',orderable: true },
                    { data: 'm_year', name: 'm_year',orderable: true },
                    { data: 'created_at', name: 'created_at',orderable: true },
                    { data: 'created_by', name: 'created_by',orderable: true },
                    { data: 'status', name: 'status',orderable: true },
                    {"mRender": function ( data, type, row ) {
                            var withdraw = '<a class="btn btn-sm btn-danger" href="/CMS/add/Withdrawal/'+row.id+'">انسحاب</a>';
                            var receipt = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/add/ReceiptStudent/'+row.id+'">صرف</a>';
                            var deleted = '<a class="btn btn-sm btn-danger Confirm" href="/CMS/delete/StudentCourse/'+row.id+'">حذف</a>';
                            var edit = '<a class="btn btn-sm btn-danger" href="/CMS/StudentCourse/'+row.id+'/edit">تعديل</a>';
                            if(row.status=="منسحب"){
                                withdraw = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/add/Withdrawal/'+row.id+'">انسحاب</a>';
                                deleted = '<a disabled class="btn btn-sm btn-danger Confirm disabled" href="/CMS/delete/StudentCourse/'+row.id+'">حذف</a>';
                                edit = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/StudentCourse/'+row.id+'/edit">تعديل</a>';
                            }
                            if(row.pay==0){
                                withdraw = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/add/Withdrawal/'+row.id+'">انسحاب</a>';
                            }
                            if(row.refund>0){
                                receipt = '<a class="btn btn-sm btn-danger" href="/CMS/add/ReceiptStudent/'+row.id+'">صرف</a>';
                            }
                            if(row.done==1){
                                receipt = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/add/ReceiptStudent/'+row.id+'">صرف</a>';
                                /*deleted = '<a class="btn btn-sm btn-danger Confirm" href="/CMS/delete/StudentCourse/'+row.id+'">حذف</a>';*/
                                edit = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/StudentCourse/'+row.id+'/edit">تعديل</a>';
                            }
                            if(row.status=="محذوف"){
                                withdraw = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/add/Withdrawal/'+row.id+'">انسحاب</a>';
                                deleted = '<a disabled class="btn btn-sm btn-danger Confirm disabled" href="/CMS/delete/StudentCourse/'+row.id+'">حذف</a>';
                                edit = '<a disabled class="btn btn-sm btn-danger disabled" href="/CMS/StudentCourse/'+row.id+'/edit">تعديل</a>';
                            }
                            var ress ='';
                            @can('اضافة انسحاب')
                                ress=ress+' '+withdraw;
                            @endcan
                            @can('اضافة صرف مخالصة')
                                ress=ress+' '+receipt;
                            @endcan
                            @can('تعديل  تسجيل بالدورة')
                                ress=ress+' '+edit;
                            @endcan
                            @can('حذف  تسجيل بالدورة')
                                ress=ress+' '+deleted;
                            @endcan
                            return ress;}
                        ,orderable: false}
                ]
            });

            //filtering
            $('#student_h').change(function(e) {
                sTable.draw();
            });
            $('#course_h').change(function(e) {
                sTable.draw();
            });
            $('#status_h').change(function(e) {
                sTable.draw();
            });
              $('#user_1_h').change(function() {
                sTable.draw();
            });
            $('#money_id').change(function() {
                sTable.draw();
            });
           $('#search_1_h').click(function(e) {
                e.preventDefault();
                sTable.draw();

        });
                sTable.on( 'xhr', function () {
                var json = sTable.ajax.json();
     var pay=json.all_pay;
    var price=json.all_price;
    var deductions = parseFloat(price) - parseFloat(pay);


                        $("#total_6_reward_filter").replaceWith('<Strong id="total_6_reward_filter">'+json.all_price+'</Strong>');
                        $("#total_6_receipt_filter").replaceWith('<Strong id="total_6_receipt_filter">'+json.all_pay+'</Strong>');
                         $("#total_6_safi_filter").replaceWith('<Strong id="total_6_safi_filter">'+deductions.toFixed(2)+'</Strong>');

            });
        });
    </script>
@endsection
