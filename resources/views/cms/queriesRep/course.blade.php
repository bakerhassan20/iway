@extends('layouts.master')
@section('css')

@section('title')
 {{ $subtitle }}

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
@can('عرض دورة')


<div class="modal fade" id="favoritesModal"
     tabindex="-1" role="dialog"
     aria-labelledby="favoritesModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

    <div class="modal-header">
	<h6 class="modal-title">تقييم المعلم</h6>
    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
    <span aria-hidden="true">&times;</span></button>
	</div>
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="button"class="btn btn-default"data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>
    <br>
    <br>
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
                                    <select name="year_h" id="year_h" class="form-control">
                                        <option value=""> اختر السنه الماليه.... </option>
                                       @foreach ($years as $y )
                                      <option  value="{{$y->year}}">{{$y->year}}</option>
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
   <div class="row ls_divider col-md-12">
                        <div class="col-md-4 control-label">عدد المسجلين : <strong id="all_registered"></strong></div>
                        <div class="col-md-4 control-label">عدد المنسحبين : (<strong id="all_withdrawn"></strong>)</div>
                        <div class="col-md-4 control-label"> عدد الخريجين :
                        (<strong id="all_graduate"></strong>)</div>
                    </div>

                        <br/>
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
                                                <th>تصنيف الدوره</th>
                                                 <th> السنه الماليه</th>
                                                <th>عدد المسجلين</th>
                                                <th>عدد المنسحبين</th>
                                                <th>عدد الخرجين</th>

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
                @endcan

                @cannot('عرض دورة')
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
             var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var cTable = $('#users-table').DataTable({
                dom: 'Bfrtip',
                order: [[0, 'desc']],
                processing: true,
                serverSide: true,
                buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle,   customize: function ( win ) {
                         var json = cTable.ajax.json();
                    $(win.document.body)
                        .css( 'font-size', '16pt' )
                        .prepend(
                            '<br><div class="row ls_divider col-md-12"><div class="col-md-4 control-label">عدد المسجلين : <strong id="all_registered">'+json.all_registered+'</strong></div><div class="col-md-4 control-label">عدد المنسحبين : (<strong id="all_withdrawn">'+json.all_withdrawn+'</strong>)</div><div class="col-md-4 control-label"> عدد الخريجين :(<strong id="all_graduate">'+json.all_graduate+'</strong>)</div></div></div><br/>'
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
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json',
                },
                ajax: {
                    url: '/CMS/datatables/QueryCourse',
                    data: function (d) {
                        d.searchCourse = $('#users-table_filter input[type=search]').val();
                        d.yearId = $('select[name=year_h]').val();

                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'title', name: 'title' },
                    { data: 'year', name: 'year' },
                    { data: 'total_register', name: 'total_register',width: '1px' },
                    { data: 'total_withdrawn', name: 'total_withdrawn',width: '1px' },
                    { data: 'all_graduate', name: 'all_graduate',width: '1px' },




                ]
            });
            //filtering


            $('#year_h').change(function() {
                cTable.draw();
            });
            cTable.on( 'xhr', function () {
                var json = cTable.ajax.json();


                $("#all_registered").replaceWith('<Strong id="all_registered">'+json.all_registered+'</Strong>');
                $("#all_withdrawn").replaceWith('<Strong id="all_withdrawn">'+json.all_withdrawn+'</Strong>');
                $("#all_graduate").replaceWith('<Strong id="all_graduate">'+json.all_graduate+'</Strong>');



            });
        });


    </script>
@endsection
