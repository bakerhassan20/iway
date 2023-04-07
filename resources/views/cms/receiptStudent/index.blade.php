@extends('layouts.master')
@section('css')
<!-- Internal Select2 css -->

<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


<style>
    .select2 {
        width:100% !important;
    }

   @media print {
    @page{
        size:105mm 148mm !important;
    }
    td,th{
        font-size:6.5px !important;
        color:red;
    }
    .main_title{
        font-size:10px !important;
    }
    .main_footer{
      font-size:10px !important;
    }
    .main_subtitle{
     font-size:10px !important;
    }
    .panel-title{
        font-size:10px !important;
    }
    table{
        margin:0;
    }
 title{
display:none !important;
}

}
</style>
@section('title')


@endsection

@section('title-page-header')
{{ $title }}
@endsection
@section('page-header')
{{ $subtitle }}

@endsection
@section('button1')

@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')

 @can('عرض صرف مخالصة')


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
                    <table class="table table-bordered table-striped table-hover">
                      <tbody>
                            <tr>
                                <td>
                                    <select name="student_3_h" id="student_3_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                        <option value="all"> اختر اسم الطالب.... </option>
                                        @foreach($students as $student)
                                            <option {{old("student_3_h")==$student->id?"selected":""}} value="{{$student->id}}"> {{$student->nameAR}} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="course_3_h" id="course_3_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                        <option value="all"> اختر اسم الدورة.... </option>
                                        @foreach($courses as $course)
                                            <option {{old("course_3_h")==$course->id?"selected":""}} value="{{$course->id}}"> {{$course->courseAR}} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="user_3_h" id="user_3_h" class="form-control">
                                        <option value=""> اختر اسم المستخدم.... </option>
                                        @foreach($users as $user)
                                            <option {{old("user_3_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
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
    <h3 class="panel-title text-left">المجموع: (<Strong id="total_3_filter"></Strong> دينار)</h3>
    <br>

		<!-- row -->
				<div class="row">
                			<!--div-->
					<div class="col-xl-12">
						<div class="card mg-b-20">
							<div class="card-header pb-0">
								<div class="d-flex justify-content-between">

							</div>
							<div class="card-body">
								<div class="">
						<table id="receipt-student-table" class="table-bordered table-striped table-hover">

                   <thead>
                            <tr>
                                <th>الرقم الحاسوبي</th>
                                <th>الرقم الورقي</th>
                                <th>التاريخ</th>
                                <th>الطالب</th>
                                <th>الدورة</th>
                                <th>المعلم</th>
                                <th>المبلغ</th>
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
					<!--/div-->
				</div>


              </div>
				<!-- row closed -->

         @endcan

    @cannot('عرض صرف مخالصة')
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
            var rsTable = $('#receipt-student-table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
                      buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة', customize: function ( win ) {
                        var json = rsTable.ajax.json();
$(win.document.body).prepend('<div style="position:absolute; top:10; right:0;"class="main_title">My Title</div>')
         .prepend('<div style="position:absolute; bottom:20; left:0;font-size:40px"class="main_footer">Creato il:footer </div>')
         .prepend('<div style="position:absolute; top:10; left:50;font-size:24px;"class="main_subtitle">SubTitle</div>')

        .prepend('<br><br><br><h3 class="panel-title text-left">المجموع: (<Strong id="total_3_filter">'+json.tot+'</Strong> دينار)</h3>');

$(win.document.body).find('table').removeClass('dataTable')
$(win.document.body).find('th').each(function(index){
{{-- $(this).css('font-size','18px'); --}}
            });




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
                    url: '/CMS/datatables/ReceiptStudent',
                    data: function (d) {
                        d.searchReceiptStudent = $('#receipt-student-table_filter input[type=search]').val();
                        d.studentId = $('select[name=student_3_h]').val();
                        d.courseId = $('select[name=course_3_h]').val();
                        d.userId = $('select[name=user_3_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },

                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'studentAR', name: 'studentAR' },
                    { data: 'courseAR', name: 'courseAR' },
                    { data: 'teacherAR', name: 'teacherAR' },
                    { data: 'amount', name: 'amount' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                            var show ='<a class="btn btn-sm btn-warning" href="/CMS/ReceiptStudent/'+row.id+'">عرض</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/ReceiptStudent/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض صرف مخالصة')
                                ress=ress+' '+show;
                            @endcan
                                    @can('حذف صرف مخالصة')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#student_3_h').change(function() {
                rsTable.draw();
            });
            $('#course_3_h').change(function() {
                rsTable.draw();
            });
            $('#user_3_h').change(function() {
                rsTable.draw();
            });
            $('#money_id').change(function() {
                rsTable.draw();
            });
            rsTable.on( 'xhr', function () {
                var json = rsTable.ajax.json();
                $("#total_3_filter").replaceWith('<Strong id="total_3_filter">'+json.tot+'</Strong>');
            });
        });


</script>
@endsection
