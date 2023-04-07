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
                                        <select name="user_h" id="user_h" class="form-control">
                                            <option value=""> اختر اسم المستخدم.... </option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="box_h" id="box_h" class="form-control">
                                            <option value=""> اختر الصندوق .... </option>
                                            @foreach($boxs as $box)
                                                <option value="{{$box->id}}">{{$box->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td>
                                        <select name="type_h" id="type_h" class="form-control">
                                                <option value=""> اختر نوع السند .... </option>
                                                <option value="قبض مستودع">قبض مستودع</option>
                                                <option value="صرف مستودع">صرف مستودع</option>
                                                <option value="قبض صندوق مستقل">قبض صندوق مستقل</option>
                                                <option value="صرف صندوق مستقل">صرف صندوق مستقل</option>
                                                <option value="صرف الضمان">صرف الضمان</option>
                                                <option value="خصومات">خصومات</option>
                                                <option value="مكافأت">مكافأت</option>
                                                <option value="صرف سلفة">صرف سلفة</option>
                                                <option value="صرف اجور معلم"> صرف اجور معلم</option>
                                                <option value="صرف راتب">صرف راتب</option>
                                                <option value="قبض الدورات">قبض الدورات</option>
                                               

                                        </select>
                                    </td>
                                       <td>
                                        <select name="action_h" id="action_h" class="form-control">
                                            <option value=""> اختر الاجراء .... </option>
                                                <option value="ادخال">ادخال</option>
                                                <option value="تعديل">تعديل</option>
                                                <option value="حذف">حذف</option>
                                        </select>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
                                                <th>التاريخ</th>
                                                <th>اسم المستخدم</th>
                                                <th> نوع السند </th>
                                                <th>اسم الصندوق</th>
                                                <th>رقم السند</th>
                                                <th>الاسم</th>
                                                <th>المبلغ</th>
                                                <th>الاجراء</th>
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
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')

    <script>
        $(function() {
            var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var abTable = $('#users-table').DataTable({
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
                ajax: {
                    url: '/CMS/datatables/UserQ',
                    data: function (d) {
                        d.searchAbsence = $('#users-table_filter input[type=search]').val();
                        d.typeId = $('select[name=type_h]').val();
                        d.boxId = $('select[name=box_h]').val();
                        d.userId = $('select[name=user_h]').val();
                        d.actionId = $('select[name=action_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                    }
                },
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'us', name: 'us' },
                    { data: 'type', name: 'type' },
                    { data: 'box', name: 'box' },
                    { data: 'id_sys', name: 'id_sys' },
                    { data: 'nam', name: 'nam' },
                    { data: 'amount', name: 'amount' },
                    { data: 'action', name: 'action' },
                ]
            });
            //filtering
            $('#type_h').change(function() {
                abTable.draw();
            });
            $('#box_h').change(function() {
                abTable.draw();
            });
            $('#user_h').change(function() {
                abTable.draw();
            });
              $('#action_h').change(function() {
                abTable.draw();
            });
            $('#money_id').change(function() {
                abTable.draw();
            });

        });

    </script>
@endsection
