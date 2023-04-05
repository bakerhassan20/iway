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
                                    <select name="how_id" id="how_id" class="form-control">
                                        <option value=""> اختر الطريقة .... </option>
                                        @foreach($hows as $how)
                                            <option {{old("how_id")==$how->id?"selected":""}} value="{{$how->id}}"> {{$how->title}} </option>
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
                                <th>طريقة كيف سمعت عنا</th>
                                <th>مجموع التعداد</th>
                                <th>{{date('Y')-1}}</th>
                                <th>{{date('Y')-2}}</th>
                                <th>{{date('Y')-3}}</th>
                                <th>{{date('Y')-4}}</th>
                                <th>{{date('Y')-5}}</th>
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

 <script>

        $(function() {
               var subtitle ="<?= $parentTitle ?>";
                  var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var hTable = $('#users-table').DataTable({
              dom: 'Bfrtip',
                order: [[1, 'desc']],
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
                columnDefs: [{
                        targets: '_all',
                        render: function(data, type, row) {
                            if (type === 'PDF') {
                                return String(data).split(' ').reverse().join('  ');
                            }  return data;} }
                   ],

                ajax: {
                    url: '/CMS/datatables/HowTo',
                    data: function (d) {
                        d.searchHow = $('#users-table_filter input[type=search]').val();
                        d.howId = $('select[name=how_id]').val();
                    }
                },
                columns: [

                    { data: 'title', name: 'title' },
                    { data: 'all', name: 'all'},
                    { data: 'year1', name: 'year1'},
                    { data: 'year2', name: 'year2'},
                    { data: 'year3', name: 'year3'},
                    { data: 'year4', name: 'year4'},
                    { data: 'year5', name: 'year5'},

                ]
            });
            //filtering
            $('#how_id').change(function() {
                hTable.draw();

        });
        $('#search_1_h').click(function(e) {
                e.preventDefault();
                hTable.draw();
            });
        });



    </script>

@endsection
