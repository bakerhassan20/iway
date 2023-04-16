@extends('layouts.master')
@section('css')

@section('title')
تعديل   اعدادات الطباعه
@stop

@endsection
@section('title-page-header')
الاعدادات
@endsection
@section('page-header')
الطباعه
@endsection
@section('content')

<!-- row -->
<div class="row">
  
<div class="col-12 py-3">
	<div class="container">
		<div class=" d-flex row m-0">
			<div class="col-12 col-lg-6 my-2">
			
                <form method="POST" action="{{ route('print.head') }}">
                     @csrf
                  
                    <div class="col-12 p-0 main-box shadow">
                        <div class="col-12 px-0">
                            <div class="col-12 px-3 py-3">
                                <span class="bx bx-arrow-to-top fa-lg"></span> راس الطباعه
                            </div>
                            <div class="col-12 divider" style="min-height: 2px;"></div>
                        </div>
                        <div class="col-12 p-3">
                            <div class="col-12 p-2">
                                <div class="col-12">
                                  العنوان
                                <span style="color:red;font-size:16px">*</span></div>
                                <div class="col-12 pt-3">
                                    <input type="text" name="address" class="form-control" required value="{{ $print->address }}">
                                </div>
                                 <div class="text-danger">{{$errors->first('address')}}</div>
                            </div>

                            <div class="col-12 p-2">
                                <div class="col-12">
                                      الهاتف
                                <span style="color:red;font-size:16px">*</span></div>
                                <div class="col-12 pt-3">
                            <input type="number" name="phone" class="form-control" value="{{ $print->phone }}"required>
                                </div>
                                 <div class="text-danger">{{$errors->first('address')}}</div>
                            </div>
        

                            <div class="col-12 p-2">
                                <div class="col-12 pt-3">
                                    <button class="btn btn-primary">  حفظ </button>

                                </div>
                            </div>


                        </div>
                    </div>
                
				</form>
			</div>

                		<div class="col-12 col-lg-6 my-2">
			
                <form method="POST" action="{{route('print.footer')}}">
                     @csrf
                    <div class="col-12 p-0 main-box shadow">
                        <div class="col-12 px-0">
                            <div class="col-12 px-3 py-3">
                                <span class="bx bx-arrow-to-bottom  fa-lg"></span> تزيل الطباعه
                            </div>
                            <div class="col-12 divider" style="min-height: 2px;"></div>
                        </div>
                        <div class="col-12 p-3">
                            <div class="col-12 p-2">
                                <div class="col-12">
                                   السطر الاول
                                <span style="color:red;font-size:16px">*</span></div>
                                <div class="col-12 pt-3">
                                    <input type="text" name="line1" class="form-control"  value="{{ $print->line1 }}">
                                </div>
                            </div>

                            <div class="col-12 p-2">
                                <div class="col-12">
                                      السطر الثاني
                                <span style="color:red;font-size:16px">*</span></div>
                                <div class="col-12 pt-3">
                            <input type="text" name="line2" class="form-control" value="{{ $print->line2 }}">
                                </div>
                            </div>
        

                            <div class="col-12 p-2">
                                <div class="col-12 pt-3">
                                    <button class="btn btn-primary">  حفظ </button>

                                </div>
                            </div>


                        </div>
                    </div>
                
				</form>
			</div>
<div class="col-12 col-lg-6 my-2">
				<form method="POST" action="{{route('print.icon')}}" enctype="multipart/form-data">
					@csrf
                    <div class="col-12 p-0 main-box shadow">
						<div class="col-12 px-0">
							<div class="col-12 px-3 py-3">
							 	<span class="bx bx-info-circle lg"></span> شعار الطباعه 
							</div>
							<div class="col-12 divider" style="min-height: 2px;"></div>
						</div>
                         <div class="col-12 p-2">
                                <div class="col-12 pt-3">
                                    <div class="alert alert-warning">
                                      يفضل استخدام صوره صغيره مقاس  149 * 40
                                    </div>
                                </div>
                            </div>
						<div class="col-12 p-3">
							<div class="col-12 py-2 px-0 d-flex justify-content-center">
									<img src="{{ asset('uploads/print/'. $print->icon) }}" style="width:230px;height:100px;border-radius:5%;border:1px solid;" id="getlogo">
							</div>
                            <div class="col-12 p-2">
                                <div class="col-12">

                                </div>
                                <div class="col-12 pt-3">
                                    <input type="file" name="icon" class="form-control"  id="avatar-image"onchange="loadFile2(event)">
                            </div>
                             <div class="text-danger">{{$errors->first('icon')}}</div>
                            </div>

							<div class="col-12 p-2">
								<div class="col-12 pt-3">
									<button class="btn btn-primary">حفظ البيانات</button>

								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
           		<div class="col-12 col-lg-6 my-2">
			
                <form method="POST" action="{{route('print.type')}}">
                     @csrf
                    <div class="col-12 p-0 main-box shadow">
                        <div class="col-12 px-0">
                            <div class="col-12 px-3 py-3">
                                <span class="bx bxs-help-circle fa-lg"></span> نوع الطباعه
                            </div>
                            <div class="col-12 divider" style="min-height: 2px;"></div>
                        </div>
                        <div class="col-12 p-3">
                            <div class="col-12 p-2">
                                <div class="col-12">
                                   حجم الطباعه
                                <span style="color:red;font-size:16px">*</span></div>
                                <div class="col-12 pt-3">
                               <select required name="type" id="select-state" class="form-control">
                                        <option value=""> اختر من القائمة.... </option>
                                        <option value="A5" {{ $print->type =="A5" ?"selected":""}}>A5</option>
                                        <option value="A6" {{ $print->type =="A6" ?"selected":""}}>A6</option>
                                </select>
                                </div>
                                    <div class="text-danger">{{$errors->first('type')}}</div>
                            </div>
                            <div class="col-12 p-2">
                                <div class="col-12 pt-3">
                                    <button class="btn btn-primary">  حفظ </button>

                                </div>
                            </div>


                        </div>
                    </div>
                
				</form>
                
			</div>

  		<div class="col-12 col-lg-6 my-2">
 <form method="POST" action="{{route('print.link')}}">
                     @csrf
                    <div class="col-12 p-0 main-box shadow">
                        <div class="col-12 px-0">
                            <div class="col-12 px-3 py-3">
                                <span class="bx bx-link fa-lg"></span>  رابط المنصه
                            </div>
                            <div class="col-12 divider" style="min-height: 2px;"></div>
                        </div>
                        <div class="col-12 p-3">
                            <div class="col-12 p-2">
                                <div class="col-12">
                                       الرابط
                                <span style="color:red;font-size:16px">*</span></div>
                                <div class="col-12 pt-3">
                            <input type="text" name="link" class="form-control" value="{{ $print->link }}">
                                </div>
                            </div>
                            <div class="col-12 p-2">
                                <div class="col-12 pt-3">
                                    <button class="btn btn-primary">  حفظ </button>

                                </div>
                            </div>


                        </div>
                    </div>
                
				</form>

		</div>
		</div>
	</div>
</div>

</div>




				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->


@endsection
@section('js')


@endsection
