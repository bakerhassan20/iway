@extends('layouts.master')
@section('css')


@section('title')
 Iwayc System

@endsection

@section('title-page-header')
{{ $title }}
@endsection

@section('page-header')
{{ $parentTitle }}

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
                    <table class="table table-bordered table-striped table-hover">
                        <tbody>
                            <tr>
                                <td>
                                    <select name="user_s" id="user_s" class="form-control">
                                        <option value=""> اختر اسم المستخدم.... </option>
                                        @foreach($users as $user)
                                            <option {{old("user_s")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="subject_s" id="subject_s" class="form-control">
                                        <option value=""> اختر الموضوع.... </option>
                                        @foreach($statics as $static)
                                            <option {{old("subject_s")==$static?"selected":""}} value="{{$static}}"> {{$static}} </option>
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

</div>

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
									<table id="users-table" class="table table-bordered table-striped table-hover">

                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>اسم المستخدم</th>
                                                <th>الموضوع</th>
                                                <th>العدد الكلي</th>
                                                <th>اليوم</th>
                                                <th>7 ايام</th>
                                                <th>15 يوم</th>
                                                <th>30 يوم</th>
                                                <th>60 يوم</th>
                                                <th>90 يوم</th>
                                                <th>6 شهور</th>
                                                <th>{{date('Y')-1}}</th>
                                                <th>{{date('Y')-2}}</th>
                                                <th>{{date('Y')-3}}</th>
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

@section("js")
    <script>
        $(function() {
    var subtitle ="<?= $parentTitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var qTable = $('#users-table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
                buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle},

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
                searching: false,
                // pageLength: 12,
                // lengthChange: false,
                orderBy:[0,'desc'],
                ajax: {
                    url: '/CMS/datatables/QueryUser',
                    data: function (d) {
                        d.userId = $('select[name=user_s]').val();
                        d.subjectId = $('select[name=subject_s]').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id', visible : false},
                    { data: 'user_id', name: 'user_id' },
                    { data: 'subject', name: 'subject' },
                    { data: 'count', name: 'count' },
                    { data: 'day1', name: 'day1' },
                    { data: 'day7', name: 'day7' },
                    { data: 'day15', name: 'day15' },
                    { data: 'day30', name: 'day30' },
                    { data: 'day60', name: 'day60' },
                    { data: 'day90', name: 'day90' },
                    { data: 'day180', name: 'day180' },
                    { data: 'last1', name: 'last1' },
                    { data: 'last2', name: 'last2' },
                    { data: 'last3', name: 'last3' }
                ]
            });
            $('#user_s').change(function() {
                qTable.draw();
            });
            $('#subject_s').change(function() {
                qTable.draw();
            });
        });
    </script>
@endsection
