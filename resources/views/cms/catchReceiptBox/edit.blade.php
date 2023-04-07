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
   @can('اضافة قبض صندوق')
<a class="btn btn-primary btn-md" href="{{ route('CatchReceiptBox.create') }}"><i class='fas fa-plus'style="margin-left: 10px"></i>إضافة سند قبض جديد</a>
@endcan
@endsection
@section('button2')
<a class="btn btn-primary btn-md" href="{{ route('CatchReceiptBox.index') }}">رجوع</a>
@stop
@endsection
@section('content')

@can('تعديل قبض صندوق')
<!-- row -->
<div class="row">

    <div class="col-lg-12 col-md-12">


        <div class="card">
        <div class="card-body">
 <form id="formID" class="formular form-horizontal ls_form" method="post" action="/CMS/CatchReceiptBox/{{$item->id}}">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">

                        <div class="col">
                                {{-- <label class="control-label">رقم السند الحاسوبي:* </label> --}}
                                    <input type="hidden" min="{{$item->id}}" value="{{$item->id}}" class="form-control validate[required] text-input" id="id"
                                           name="id">
                               {{--      <div class="text-danger">{{$errors->first('id')}}</div> --}}
                                </div>
                        <div class="row">

                               <div class="col">
                                <label class="control-label">رقم السند الورقي:* </label>
                                    <input type="number" min="0" value="{{$item->id_comp}}" class="form-control validate[required] text-input" id="id_comp"
                                           name="id_comp">
                                    <div class="text-danger">{{$errors->first('id_comp')}}</div>
                                </div>
                            <div class="col">
                                <label class="control-label">تاريخ السند الورقي:* </label>
                                    <input type="text" value="" class="form-control datepicker" id="date"
                                           name="date">
                                    <div class="text-danger">{{$errors->first('date')}}</div>
                                </div>
                            </div><br>

                        <div class="row">
                            <div class="col">
                                <label class="control-label">السنة المالية:* </label>
                                    <select name="m_year" id="m_year" class="form-control">
                                        @foreach($moneyYears as $moneyYear)
                                            <option {{$item->m_year==$moneyYear->year?"selected":""}} value="{{$moneyYear->year}}"> {{$moneyYear->year}} </option>
                                        @endforeach

                                    </select>
                                    <div class="text-danger">{{$errors->first('m_year')}}</div>
                                </div>
                               <div class="col">
                                <label class="control-label">الصندوق الفرعي:* </label>
                                    <select name="box_id" id="box_id" class="form-control">
                                        <option value=""> اختر من القائمة.... </option>
                                        @foreach($boxes as $box)
                                            <option {{$item->box_id==$box->id?"selected":""}} value="{{$box->id}}"> {{$box->name}} </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">{{$errors->first('box_id')}}</div>
                                </div>
                               <div class="col">
                                <label class="control-label">تصنيف الايراد:* </label>
                                    <select name="type" id="type" class="form-control">
                                        <option value=""> اختر اسم الصندوق اولا.... </option>
                                    </select>
                                    <div class="text-danger">{{$errors->first('type')}}</div>
                                </div>

                        </div><br>


                        <div class="row">
                            <div class="col">
                                <label class="control-label">اسم الزبون:* </label>
                                    <input type="text" value="{{$item->customer}}" class="form-control validate[required] text-input" id="customer"
                                           name="customer" placeholder="ادخل اسم الزبون">
                                    <div class="text-danger">{{$errors->first('customer')}}</div>
                                </div>
                            <div class="col">
                                <label class="control-label">العدد:* </label>
                                    <input type="number" value="{{$item->count}}" class="form-control validate[required] text-input" id="count"
                                           name="count" placeholder="أدخل العدد">
                                    <div class="text-danger">{{$errors->first('count')}}</div>
                                </div>
                                    <div class="col">
                                <label class="control-label">المبلغ:* </label>
                                    <input type="number" onchange="setTwoNumberDecimal(this)" step="any" value="{{$item->amount}}" class="form-control validate[required] text-input" id="amount"
                                           name="amount" placeholder="أدخل المبلغ">
                                    <div class="text-danger">{{$errors->first('amount')}}</div>
                                </div>

                        </div><br>

                        <div class="row">
                            <div class="col">
                                <label class="control-label">ملاحظات: </label>
                                    <textarea class="animatedTextArea form-control" id="notes" name="notes"
                                              placeholder="أدخل ملاحظات">{{$item->notes}}</textarea>
                                    <div class="text-danger">{{$errors->first('notes')}}</div>
                                </div>
                            </div><br>


                        <div class="row last">
                            <div class="col">
                                <label class="control-label"></label>
                                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                                    <button class="submit btn-primary btn" type="submit" name="submit">حفظ</button>
                                    <a href="/CMS/CatchReceiptBox" class="btn btn-danger"> إلغاء</a>
                                </div>
                            </div>
                        </div><br>

                    </form>

            </div>
        </div>
    </div>
</div>
<!-- row closed -->


    @endcan

    @cannot('تعديل قبض صندوق')
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
        jQuery(document).ready(function($){
            $('#box_id').change(function(){
                var id=$(this).val();
                $.get("/CMS/IncomeBox/" + id,
                    function(data) {
                        var model = $('#type');
                        model.empty();

                        model.append("<option value=''>اختر نوع الايراد ....</option>");

                        $.each(data, function(index, element) {
                            model.append("<option value='"+ element.id +"'>" + element.name + "</option>");
                        });
                    });
            });
        });
        window.onload = function() {
            if ($('#box_id').val()){
                var id=$('#box_id').val();
                $.get("/CMS/IncomeBox/" + id,
                    function(data) {
                        var model = $('#type');
                        model.empty();

                        $.each(data, function(index, element) {
                            <?php $m = "element.id;"?>
                            model.append("<option value='"+ element.id +"' {{old('type')==$m ?'selected':''}}>" + element.name + "</option>");
                        });
                    });
            }
            $(".datepicker").val('{{$item->date}}');
        };
    </script>
@endsection
