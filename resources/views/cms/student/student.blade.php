@extends('layouts.master')
@section('css')

<!--- Internal Select2 css-->
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
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
<a class="btn btn-primary btn-md" href="{{ route('Student.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>اضافه طالب جديد </a>
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

                <div class="panel-body">

                     <div class="                 row">

                       <div class="                        col">
                                <select name="student_h" id="student_h"class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2" data-placeholder="اختر اسم الطالب" required="">
                                    <option value="all"> اختر اسم الطالب.... </option>
                                    @foreach($s as $st)
                                        <option {{old("student_h")==$st?"selected":""}} value="{{$st}}"> {{\App\Models\Student::find($st)->nameAR}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col"                            >
                                <select name="course_h" id="course_h"class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                    <option value="all"> اختر اسم تصنيف الدوره.... </option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}"> {{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <select name="year_h" id="year_h" class="form-control">
                                    <option value=""> اختر العام الدراسي.... </option>
                                    @foreach($year as $y)
                                        <option {{old("year_h")==$y->year?"selected":""}} value="{{$y->year}}">{{$y->year}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <select name="status_h" id="status_h" class="form-control">
                                    <option value=""> اختر الحالة.... </option>
                                    <option {{old("status_h")==1?"selected":""}} value="1">منسحب</option>
                                    <option {{old("status_h")==2?"selected":""}} value="2">مسدد</option>
                                    <option {{old("status_h")==3?"selected":""}} value="3">غير مسدد</option>
                                </select>
                            </div>
                        </div><br>



                    <div class="row ls_divider">
                        <div class="col-md-4 control-label">اسم الطالب: <strong id="studentName"></strong></div>
                        <div class="col-md-4 control-label">الهواتف المتوفرة: <strong id="studentPhone"></strong></div>
                        <div class="col-md-4 control-label">تصنيف الطالب: <strong id="studentType"></strong></div>
                    </div><br>
                    <div class="row ls_divider">
                        <div class="col-md-4 control-label">مجموع الرسوم الكلي: <strong id="totalSum"></strong> دينار</div>
                        <div class="col-md-4 control-label">مجموع المدفوع: <strong id="receiptSum"></strong> دينار</div>
                        <div class="col-md-4 control-label">مجموع الذمم: <strong id="remaindSum"></strong> دينار</div>
                    </div><br>

                </div>
            </div>
        </div>

<br><br>



		<!-- row -->
				<div class="row">
                			<!--div-->
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">

							</div>
							<div class="card-body">
								<div class="table-responsive ls-table">
									<table id="student-courses-table" class="table table-bordered table-striped table-hover">

                       <thead>
                            <tr>
                                <th> اسم الطالب</th>
                                <th>اسم الدورة</th>
                                <th>اسم المعلم</th>
                                <th>العام</th>
                                <th>تاريخ بدايتها</th>
                                <th>الرسوم الكلي</th>
                                <th>المدفوع</th>
                                <th>الذمم</th>
                                <th>الحالة</th>
                                <th>عدد مرات المطالبة</th>
                                <th>الملاحظات</th>
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
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!--Internal  Select2 js -->
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<!--Internal  Parsley.min js -->
<script src="{{URL::asset('assets/plugins/parsleyjs/parsley.min.js')}}"></script>
<!-- Internal Form-validation js -->
<script src="{{URL::asset('assets/js/form-validation.js')}}"></script>
<script>
        $(function() {
                         var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var sTable = $('#student-courses-table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
                buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle,   customize: function ( win ) {
                     var json = sTable.ajax.json();


                     var pay=json.allrec;
                     var price=json.allprice;
                     var deductions = parseFloat(price) - parseFloat(pay);
                    $(win.document.body)
                        .css( 'font-size', '17pt' )
                        .prepend(
                            '<br><div class="row ls_divider"><div class="col-md-4 control-label">مجموع الرسوم الكلي: <strong id="totalSum">'+json.allprice+'</strong> دينار</div><div class="col-md-4 control-label">مجموع المدفوع: <strong id="receiptSum">'+json.allrec+'</strong> دينار</div><div class="col-md-4 control-label">مجموع الذمم: <strong id="remaindSum">'+deductions+'</strong> دينار</div></div><br>'
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
                ajax: {
                    url: '/CMS/datatables/StudentRep',
                    data: function (d) {
                        d.searchStudent = $('#student-courses-table_filter input[type=search]').val();
                        d.studentId = $('select[name=student_h]').val();
                        d.courseId = $('select[name=course_h]').val();
                        d.statusId = $('select[name=status_h]').val();
                        d.yearId = $('select[name=year_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },
                columns: [
                    { data: 'nameAR', name: 'nameAR',orderable: true },
                    { data: 'courseAR', name: 'courseAR',orderable: true },
                    { data: 'name', name: 'name',orderable: true },
                    { data: 'm_year', name: 'm_year',orderable: true },
                    { data: 'begin', name: 'begin',orderable: true },
                    { data: 'price', name: 'price',orderable: true },
                    { data: 'amount', name: 'amount',orderable: true },
                    { data: 'payment', name: 'payment',orderable: true },
                    { data: 'stat', name: 'stat',orderable: true },
                    { data: 'count', name: 'count',orderable: true },
                    { data: 'notes', name: 'notes',orderable: true }
                ]
            });

            //filtering
            $('#course_h').change(function(e) {
                sTable.draw();
            });
            $('#student_h').change(function(e) {
                sTable.draw();
            });
            $('#status_h').change(function(e) {
                sTable.draw();
            });
            $('#year_h').change(function() {
                sTable.draw();
            });
            $('#money_id').change(function() {
                sTable.draw();
            });
            sTable.on( 'xhr', function () {
                var json = sTable.ajax.json();


                     var pay=json.allrec;
                     var price=json.allprice;
                     var deductions = parseFloat(price) - parseFloat(pay);

                $('#totalSum').replaceWith('<strong id="totalSum">'+json.allprice+'</strong>');
                            $('#receiptSum').replaceWith('<strong id="receiptSum">'+json.allrec+'</strong>');
                            $('#remaindSum').replaceWith('<strong id="remaindSum">'+deductions+'</strong>');
            });
        });

        $('#student_h').change(function() {

            var id = $('select[name=student_h]').val();
            if (id) {
                $.ajax({
                    url: "/CMS/student/StudentRep/" + id,
                    success: function (data) {
                        if (data.status==1){
                            $('#studentName').replaceWith('<strong id="studentName">'+data.studentName+'</strong>');
                            $('#studentPhone').replaceWith('<strong id="studentPhone">'+data.studentPhone+'</strong>');
                            $('#studentType').replaceWith('<strong id="studentType">'+data.studentType+'</strong>');
                            // $('#totalSum').replaceWith('<strong id="totalSum">'+data.totalSum+'</strong>');
                            // $('#receiptSum').replaceWith('<strong id="receiptSum">'+data.receiptSum+'</strong>');
                            // $('#remaindSum').replaceWith('<strong id="remaindSum">'+data.remaindSum+'</strong>');
                        }
                        else{alert('حدث خطأ اثناء تنفيذ العملية');}
                    }

                })
            }
            else {
                // $('#studentName').replaceWith('<strong id="studentName"></strong>');
                // $('#studentPhone').replaceWith('<strong id="studentPhone"></strong>');
                // $('#studentType').replaceWith('<strong id="studentType"></strong>');
                // $('#totalSum').replaceWith('<strong id="totalSum"></strong>');
                // $('#receiptSum').replaceWith('<strong id="receiptSum"></strong>');
                // $('#remaindSum').replaceWith('<strong id="remaindSum"></strong>');
            }
        });

       // window.onload = function (ev) { }
    </script>

@endsection
