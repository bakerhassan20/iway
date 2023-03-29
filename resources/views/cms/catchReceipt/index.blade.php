@extends('layouts.master')
@section('css')

<style>
 .modal{
    padding: 0 !important;
   }
.testdialog {
     max-width: 70% !important;

     padding: 0;
     margin: 0;
   }

.testcontent {
     border-radius: 0 !important;

     max-width: 100% !important;

   }
</style>
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
 @can('اضافة قبض دورة')
<a class="btn btn-primary btn-md" href="{{ route('CatchReceipt.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>إضافة سند قبض جديد</a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
  @can('عرض قبض دورة')
<div class="modal fade" id="favoritesModal"
     tabindex="-1" role="dialog"
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog testdialog" role="document">
    <div class="modal-content testcontent">
      <div class="modal-header">
         <h4 class="modal-title" id="favoritesModalLabel">إدارة سندات القبض والصرف</h4>
        <button type="button" class="close"data-dismiss="modal"aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button"
           class="btn btn-default"
           data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>

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
                                        <select name="student_1_h" id="student_1_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                        <option value="all"> اختر اسم الطالب.... </option>
                                        @foreach($students as $student)
                                        <option  value="{{$student->id}}"> {{$student->nameAR}} </option>
                                        @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="course_1_h" id="course_1_h" class="form-control select2" data-parsley-class-handler="#slWrapper2" data-parsley-errors-container="#slErrorContainer2">
                                        <option value="all"> اختر اسم الدورة.... </option>
                                        @foreach($courses as $course)
                                        <option {{old("course_1_h")==$course->id?"selected":""}} value="{{$course->id}}"> {{$course->courseAR}} </option>
                                        @endforeach
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
                        </tbody>
                </table><br>
                <table class="table table-bordered table-striped table-hover">
                    <h5><strong>فرز بحسب تاريخ الطلب : </strong></h5>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text"
                                value="{{old("from_1_h")}}"
                                class="form-control text-input fc-datepicker" id="from_1_h" name="from_1_h"
                                placeholder="من....">
                            </td>
                        <td>
                                <input type="text"
                                value="{{old("to_1_h")}}"
                                class="form-control text-input fc-datepicker" id="to_1_h" name="to_1_h"
                                placeholder="الي....">
                            </td>
                        <td>
                                <a class="btn btn-primary"
                                id="search_1_h"
                                name="search_1_h">بحث</a>
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
                            <h3 class="panel-title text-left">المجموع: <span class="tag"><Strong id="total_1_filter"></Strong><span> دينار</h3>
                            <br>

		<!-- row -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                    <table class="table table-bordered table-striped table-hover"  id="catch-receipt-table" style="width:100%">
                                    <thead>
                                        <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>اسم الطالب</th>
                                                        <th>اسم الدورة</th>
                                                        <th class="total_1_filter">المبلغ</th>
                                                        <th>التاريخ والوقت للادخال</th>
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

                        @cannot('عرض قبض دورة')
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
            var crTable = $('#catch-receipt-table').DataTable({
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

                },
                ajax: {
                    url: '/CMS/datatables/CatchReceipt',
                    data: function (d) {
                        d.searchCatchReceipt = $('#catch-receipt-table_filter input[type=search]').val();
                        d.studentId = $('select[name=student_1_h]').val();
                        d.courseId = $('select[name=course_1_h]').val();
                        d.userId = $('select[name=user_1_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                        d.fromId = $('#from_1_h').val();
                        d.toId = $('#to_1_h').val();
                    }
                },
                columns: [
                    { data: 'id_sys', name: 'id_sys'},
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'studentAR', name: 'studentAR' },
                    { data: 'courseAR', name: 'courseAR' },
                    { data: 'amount', name: 'amount' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                            var show ='<a class="btn btn-sm btn-warning showModal" id="CMS/CatchReceipt/'+row.id+'"  onclick="showModal(this)">عرض</a>';
                            var edit ='<a class="btn btn-sm btn-info" href="/CMS/CatchReceipt/'+row.id+'/edit">تعديل</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/CatchReceipt/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض قبض دورة')
                                ress=ress+' '+show;
                            @endcan
                                    @can('تعديل قبض دورة')
                                ress=ress+' '+edit;
                            @endcan
                                    @can('حذف قبض دورة')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#student_1_h').change(function() {
                crTable.draw();
            });
            $('#course_1_h').change(function() {
                crTable.draw();
            });
            $('#user_1_h').change(function() {
                crTable.draw();
            });
            $('#search_1_h').click(function(e) {
                e.preventDefault();
                crTable.draw();
            });
            $('#money_id').change(function() {
                crTable.draw();
            });
            crTable.on( 'xhr', function () {
                var json = crTable.ajax.json();
                    $('#total_1_filter').replaceWith('<strong id="total_1_filter">'+json.tot+'</strong>')
            });
        },400);



        function showModal(selectID) {

            var id = selectID.id;
            $.ajax({
            url: "{{url('/')}}" + "/" + id,
            type: "GET",
            success: function (data) {

                $(".modal-body").html(data.html);
                $('#favoritesModal').modal();
            },
            error: function () {
                alert("Error, Unable to bring up the creation dialog.");
            }
        })


        }
    </script>
@endsection
