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
  @can('اضافة قبض صندوق')
<a class="btn btn-primary btn-md" href="{{ route('CatchReceiptBox.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>إضافة سند قبض جديد</a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('home') }}">رجوع</a>
@stop
@endsection
@section('content')
@can('عرض قبض صندوق')

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
                                                            <select name="box_8_h" id="box_8_h" class="form-control">
                                                                <option value=""> اختر اسم الصندوق.... </option>
                                                                @foreach($boxes as $box)
                                                                    <option {{old("box_8_h")==$box->id?"selected":""}} value="{{$box->id}}"> {{$box->name}} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="income_8_h" id="income_8_h" class="form-control">
                                                                <option value=""> اختر اسم الصندوق اولا.... </option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="user_8_h" id="user_8_h" class="form-control">
                                                                <option value=""> اختر اسم المستخدم.... </option>
                                                                @foreach($users as $user)
                                                                    <option {{old("user_8_h")==$user->id?"selected":""}} value="{{$user->id}}"> {{$user->name}} </option>
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
                                                            <input onchange="$('#to_8_h').val(this.value);document.getElementById('to_8_h').min=this.value;"
                                                                   type="text" value="{{old("from_8_h")}}"
                                                                   class="form-control text-input fc-datepicker" id="from_8_h" name="from_8_h" placeholder="من....">
                                                        </td>
                                                        <td>
                                                            <input type="text" value="{{old("to_8_h")}}"
                                                                   class="form-control text-input fc-datepicker" id="to_8_h" name="to_8_h" placeholder="الي....">
                                                        </td>
                                                        <td>
                                                            <a class="btn btn-primary" id="search_8_h" name="search_8_h">بحث</a>
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
                            <h3 class="panel-title text-left">المجموع: <Strong id="total_8_filter"></Strong> دينار</h3>
                            <br>


                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">

                                <div class="table-responsive ls-table">
                                <table class="table table-bordered table-striped table-hover"  id="catch-receipt-box-table" style="width: 100%;">
                                                    <thead>
                                                    <tr>
                                                        <th>الرقم الحاسوبي</th>
                                                        <th>الرقم الورقي</th>
                                                        <th>التاريخ</th>
                                                        <th>الصندوق الفرعي</th>
                                                        <th>تصنيف الايراد</th>
                                                        <th>اسم الزبون</th>
                                                        <th>العدد</th>
                                                        <th class="total_8_filter">المبلغ</th>
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

                            @cannot('عرض قبض صندوق')
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

      jQuery(document).ready(function($){
            $('#box_8_h').change(function(){
                var id=$(this).val();
                $.get("/CMS/IncomeBox/" + id,
                    function(data) {
                        var model = $('#income_8_h');
                        model.empty();

                        model.append("<option value=''>اختر نوع الايراد ....</option>");

                        $.each(data, function(index, element) {
                            model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                        });
                    });
            });
     });
        setTimeout(function() {
  var subtitle ="<?= $subtitle ?>";
            var pdfsubtitle =  String(subtitle).split(' ').reverse().join(' ');
            var crbTable = $('#catch-receipt-box-table').DataTable({
                dom: 'Bfrtip',
                processing: true,
                serverSide: true,
                title: 'Report',
               serverSide: true,
                buttons: [
                    {'extend':'excel','text':'أكسيل','title': subtitle,},
                    {'extend':'print','text':'طباعة','title': subtitle,   customize: function ( win ) {
                     var json = crbTable.ajax.json();
                    $(win.document.body)
                        .css( 'font-size', '10pt' )
                        .prepend(
                            ' <br> <h3 class="panel-title text-left">المجموع: <Strong id="total_8_filter">'+json.tot+'</Strong> دينار</h3><br>'
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
                drawCallback: function () {
                    var json = crbTable.ajax.json();

                    $('#total_8_filter').replaceWith('<strong id="total_8_filter">'+json.tot+'</strong>')

                },
                ajax: {
                    url: '/CMS/datatables/CatchReceiptBox',
                    data: function (d) {
                        d.searchCatchReceiptBox = $('#catch-receipt-box-table_filter input[type=search]').val();
                        d.boxId = $('select[name=box_8_h]').val();
                        d.incomeId = $('select[name=income_8_h]').val();
                        d.userId = $('select[name=user_8_h]').val();
                        d.moneyId = $('select[name=money_id]').val();
                        d.fromId = $('#from_8_h').val();
                        d.toId = $('#to_8_h').val();
                    }
                },
                columns: [
                    { data: 'id_sys', name: 'id_sys' },
                    { data: 'id_comp', name: 'id_comp' },
                    { data: 'date', name: 'date' },
                    { data: 'box_id', name: 'box_id' },
                    { data: 'type', name: 'type' },
                    { data: 'customer', name: 'customer' },
                    { data: 'count', name: 'count' },
                    { data: 'amount', name: 'amount' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'created_by', name: 'created_by' },
                    {"mRender": function ( data, type, row ) {
                            var show ='<a class="btn btn-sm btn-warning" href="/CMS/CatchReceiptBox/'+row.id+'">عرض</a>';
                            var edit ='<a class="btn btn-sm btn-info" href="/CMS/CatchReceiptBox/'+row.id+'/edit">تعديل</a>';
                            var dele ='<a class="btn Confirm btn-sm btn-danger" href="/CMS/delete/CatchReceiptBox/'+row.id+'">حذف</a>';
                            var ress ='';
                            @can('عرض قبض صندوق')
                                ress=ress+' '+show;
                            @endcan
                                    @can('تعديل قبض صندوق')
                                ress=ress+' '+edit;
                            @endcan
                                    @can('حذف قبض صندوق')
                                ress=ress+' '+dele;
                            @endcan
                            return ress;}
                    }
                ]
            });
            //filtering
            $('#box_8_h').change(function() {
                crbTable.draw();
            });
            $('#income_8_h').change(function() {
                crbTable.draw();
            });
            $('#user_8_h').change(function() {
                crbTable.draw();
            });
            $('#search_8_h').click(function(e) {
                e.preventDefault();
                crbTable.draw();
            });
            $('#money_id').change(function() {
                crbTable.draw();
            });

        },400);


    </script>
@endsection
