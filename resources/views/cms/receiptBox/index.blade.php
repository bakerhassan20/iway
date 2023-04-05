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
  @can('اضافة  صرف صندوق')
<a class="btn btn-primary btn-md" href="{{ route('ReceiptBox.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>إضافة سند صرف جديد</a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
 @can('عرض  صرف صندوق')

		<!-- row -->
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
                                                            <select name="box_9_h" id="box_9_h" class="form-control">
                                                                <option value=""> اختر اسم الصندوق.... </option>
                                                                @foreach($boxes as $box)
                                                                    <option {{old("box_9_h")==$box->id?"selected":""}} value="{{$box->id}}"> {{$box->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="expense_9_h" id="expense_9_h" class="form-control">
                                                                <option value=""> اختر اسم الصندوق اولا.... </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user_9_h" id="user_9_h" class="form-control">
                                                                <option value=""> اختر اسم المستخدم.... </option>
                                                                @foreach($users as $user)
                                                                    <option {{old("user_9_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
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
                                                            <input onchange="$('#to_9_h').val(this.value);document.getElementById('to_9_h').min=this.value;"
                                                                   type="text" value="{{old("from_9_h")}}"
                                                                   class="form-control text-input fc-datepicker" id="from_9_h" name="from_9_h" placeholder="من....">
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{old("to_9_h")}}"
                                                                   class="form-control text-input fc-datepicker" id="to_9_h" name="to_9_h" placeholder="الي....">
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary" id="search_9_h" name="search_9_h">بحث</a>
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
                            <h3 class="panel-title text-left">المجموع: <Strong id="total_9_filter"></Strong> دينار</h3>
                            <br>


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                <table class="table table-bordered table-striped table-hover"  id="receipt-box-table" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>الصندوق الفرعي</th>
                                                        <th>تصنيف المصروف</th>
                                                        <th class="total_9_filter">المبلغ</th>
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
@cannot('عرض  صرف صندوق')
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
     $('#box_9_h').change(function(){

                var id=$(this).val();
                $.get("/CMS/ExpenseBox/" + id,
                    function(data) {
                        var model = $('#expense_9_h');
                        model.empty();

                        model.append("<option value=''>اختر نوع المصروف ....</option>");

                        $.each(data, function(index, element) {
                            model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                        });
                    });
            });
  setTimeout(function() {
    var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var rbTable = $('#receipt-box-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle,   customize: function ( win ) {
                     var json = rbTable.ajax.json();
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            '<br><h3 class="panel-title text-left">المجموع: <Strong id="total_9_filter">'+json.tot+'</Strong> دينار</h3><br>'
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
                    var json= rbTable.ajax.json();
                    $('#total_9_filter').replaceWith('<strong id="total_9_filter">'+json.tot+'</strong>')
                },
                ajax: {
                    url: '/CMS/datatables/ReceiptBox',
                    data: function (d) {
                        d.searchReceiptBox = $('#receipt-box-table_filter input[type=search]').val();
                        d.boxId = $('select[name=box_9_h]').val();
                        d.expenseId = $('select[name=expense_9_h]').val();
                        d.userId = $('select[name=user_9_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                        d.fromId = $('#from_9_h').val();
                        d.toId = $('#to_9_h').val();
                    }
                },
                columns: [
                    { data: 'id_sys', name: 'id_sys' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'box_id', name: 'box_id' },
                    { data: 'type', name: 'type' },
                    { data: 'amount', name: 'amount' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                             var show ='<a class="btn btn-sm btn-warning" href="/CMS/ReceiptBox/'+row.id+'">عرض</a>';
                            var edit ='<a class="btn btn-sm btn-info" href="/CMS/ReceiptBox/'+row.id+'/edit">تعديل</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/ReceiptBox/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض  صرف صندوق')
                                ress=ress+' '+show;
                            @endcan
                                    @can('تعديل  صرف صندوق')
                                ress=ress+' '+edit;
                            @endcan
                                    @can('حذف  صرف صندوق')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#box_9_h').change(function() {
                rbTable.draw();
            });
            $('#expense_9_h').change(function() {
                rbTable.draw();
            });
            $('#user_9_h').change(function() {
                rbTable.draw();
            });
            $('#search_9_h').click(function(e) {
                e.preventDefault();
                rbTable.draw();
            });
            $('#money_id').change(function() {
                rbTable.draw();
            });
        },400);
    </script>
@endsection
