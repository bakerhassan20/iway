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
@can('اضافة تسجيل بالدورة')
<a class="btn btn-primary btn-md" href="{{ route('english.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>  إضافة اختبار مستوى جديد </a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
@can('عرض  تسجيل بالدورة')

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
                        <table class="col-md-6 table table-bordered table-striped table-hover">
                           <tbody>
                            <tr>
                                <td>
                                    <select name="teacher_h" id="teacher_h" class="form-control">
                                        <option value=""> اختر اسم المعلم.... </option>
                                        @foreach($teachers as $teacher)
                                        <option {{old("teacher_h")==$teacher->id?"selected":""}} value="{{$teacher->id}}"> {{$teacher->name}} </option>
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

<br><br>
                     <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <!--Table Wrapper Start-->
                                        <div class="table-responsive ls-table">
                                            <table class="table table-bordered table-striped table-hover"  id="courses-table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>الاسم الدورة</th>
                                                        <th>مدة الدورة</th>
                                                        <th>اسم المعلم</th>
                                                        <th>الرسوم</th>
                                                        <th>عدد المسجلين</th>
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

                            @cannot('عرض  تسجيل بالدورة')
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
        $(function() {
            var cTable = $('#courses-table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
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
                    url: '/CMS/datatables/CourseReg',
                    data: function (d) {
                        d.searchCourse = $('#courses-table_filter input[type=search]').val();
                        d.teacherId = $('select[name=teacher_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id',orderable: true },
                    { data: 'courseAR', name: 'courseAR',orderable: true },
                    { data: 'course_time', name: 'course_time',orderable: true },
                    { data: 'teacher_id', name: 'teacher_id',orderable: true },
                    { data: 'total_fees', name: 'total_fees',orderable: true },
                    { data: 'total_reg_student', name: 'total_reg_student',orderable: true },
                    {"mRender": function ( data, type, row ) {
                            var add ='<a class="btn btn-sm btn-success" href="/CMS/add/StudentCourse/'+row.id+'">تسجيل</a>';
                            var ress ='';
                            @can('اضافة  تسجيل بالدورة')
                                ress=ress+' '+add;
                            @endcan
                            return ress;}
                        ,orderable: false}
                ]
            });
            //filtering
            $('#teacher_h').change(function() {
                cTable.draw();
            });
            $('#money_id').change(function() {
                cTable.draw();
            });
        });

    </script>
@endsection
