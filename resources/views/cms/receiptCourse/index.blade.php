@extends('layouts.master')
@section('css')
<!-- Internal Select2 css -->

<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

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
<a class="btn btn-primary btn-md" href="{{ route('ReceiptCourse.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>اضافة سند صرف جديد</a>
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')

 @can('عرض صرف دورة')
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
                                                            <select name="teacher_2_h" id="teacher_2_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                                                <option value="all"> اختر اسم المعلم.... </option>
                                                                @foreach($teachers as $teacher)
                                                                    <option  value="{{$teacher->teacher_id}}"> {{$teacher->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="course_2_h" id="course_2_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                                                <option value="all"> اختر اسم الدورة.... </option>
                                                                @foreach($courses as $course)
                                                                    <option {{old("course_2_h")==$course->id?"selected":""}} value="{{$course->id}}"> {{$course->courseAR}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user_2_h" id="user_2_h" class="form-control">
                                                                <option value=""> اختر اسم المستخدم.... </option>
                                                                @foreach($users as $user)
                                                                    <option {{old("user_2_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table><br>
                                                <table class="table table-bordered table-striped table-hover">
                                                    <h5><strong>فرز بحسب تاريخ الطلب : </strong></h5>
                                                    <tbody>
                                                    <tr>
                                                        <td>
                                                            <input onchange="$('#to_2_h').val(this.value);document.getElementById('to_2_h').min=this.value;"
                                                                   type="text" value="{{old("from_2_h")}}"
                                                                   class="form-control text-input fc-datepicker" id="from_2_h" name="from_2_h" placeholder="من....">
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{old("to_2_h")}}"
                                                                   class="form-control text-input fc-datepicker" id="to_2_h" name="to_2_h" placeholder="الي....">
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary" id="search_2_h" name="search_2_h">بحث</a>
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
                            <h3 class="panel-title text-left">المجموع: <Strong id="total_2_filter"></Strong> دينار</h3>
                            <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                    <table class="table table-bordered table-striped table-hover"  id="receipt-course-table" style="width:100%">
                                       <thead>
                                                    <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>المعلم</th>
                                                        <th>الدورة</th>
                                                        <th class="total_2_filter">المبلغ</th>
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

                            @cannot('عرض صرف دورة')
                                <div class="col-md-offset-1 col-md-10 alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    ليس لديك صلاحية يرجي مراجعة المسؤول
                                </div>
                            @endcannot

</div>
				<!-- row closed -->
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

  <script>
  var date = $('.fc-datepicker').datepicker({ dateFormat: 'yy-mm-dd' }).val();
        setTimeout(function() {
            var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var rcTable = $('#receipt-course-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                 buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle,   customize: function ( win ) {
                       var json = rcTable.ajax.json();
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<br<h3 class="panel-title text-left">المجموع: <Strong id="total_2_filter">'+json.tot+'</Strong> دينار</h3><br>'
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
                   var json= rcTable.ajax.json();
                    $('#total_2_filter').replaceWith('<strong id="total_2_filter">'+json.tot+'</strong>')
                },
                ajax: {
                    url: '/CMS/datatables/ReceiptCourse',
                    data: function (d) {
                        d.searchReceiptCourse = $('#receipt-course-table_filter input[type=search]').val();
                        d.teacherId = $('select[name=teacher_2_h]').val();
                        d.courseId = $('select[name=course_2_h]').val();
                        d.userId = $('select[name=user_2_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                        d.fromId = $('#from_2_h').val();
                        d.toId = $('#to_2_h').val();
                    }
                },
                columns: [
                    { data: 'id_sys', name: 'id_sys' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'teacher', name: 'teacher' },
                    { data: 'courseAR', name: 'courseAR' },
                    { data: 'amount', name: 'amount' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                            var show ='<a class="btn btn-sm btn-success" href="/CMS/ReceiptCourse/'+row.id+'">عرض</a>';
                            var edit ='<a class="btn btn-sm btn-info" href="/CMS/ReceiptCourse/'+row.id+'/edit">تعديل</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/ReceiptCourse/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض صرف دورة')
                                ress=ress+' '+show;
                            @endcan
                                    @can('تعديل صرف دورة')
                                ress=ress+' '+edit;
                            @endcan
                                    @can('حذف صرف دورة')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#teacher_2_h').change(function() {
                rcTable.draw();
            });
            $('#course_2_h').change(function() {
                rcTable.draw();
            });
            $('#user_2_h').change(function() {
                rcTable.draw();
            });
            $('#search_2_h').click(function(e) {
                e.preventDefault();
                rcTable.draw();
            });
            $('#money_id').change(function() {
                rcTable.draw();
            });
        },600);

    </script>
@endsection
