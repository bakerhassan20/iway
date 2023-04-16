
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title> صرف سلفه</title>
    <link rel="shortcut icon" type="image/png" href="./favicon.png" />
    <link href="{{URL::asset('assets/css/print.css')}}" rel="stylesheet">

    <style>
       @media print {
    @page{
     size:148mm 210mm !important; 

    }

}
     
    </style>
  </head>
  <body>
    <section class="main-pd-wrapper" style="margin: auto">
    <p style="font-size:23px; text-align: center" > صرف سلفه</p><br>
    <div class="row"style="line-height: 1.5;font-size: 14px;color: #4a4a4a;">
        
                <div style="float:right" dir="rtl">
                <p>
                 <b> عنوان : </b>  {{ $print->address }}
                </p>
                <p>
                   <b> الهاتف : </b> {{ $print->phone }}
                </p>
                <p>
                 <b> التاريخ : </b> {{ date('h:i Y/m/d') }} 
                </p>
             
                </div>
                <img style="" width="120" height="60" src="{{ asset('uploads/print/'. $print->icon) }}">
                 
                </div>
                  
                <hr style="border: 1px dashed rgb(131, 131, 131); margin: 25px auto">
              </div>
                 
              <table style="width: 100%; table-layout: fixed;text-align: left;padding: 8px 6px;" dir="rtl">
                <thead>
                  <tr>
                    <th style="width: 50px; padding-left: 0;">الرقم</th>
                    <th style="width: 220px;">اسم الموظف</th>
                    
                    <th style="text-align: left; padding-right: 0;">المبلغ</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="invoice-items">
                    <td>{{ $item->id_sys }}</td>
                    <td >{{\App\Models\Employee::find($item->employee_id)->name}}</td>
                    <td style="text-left: right;"> {{$item->advance_payment}} دينار </td>
                  </tr>
                </tbody>
              </table>

              <table dir="rtl"style="width:100%;
              margin-top: 15px;
              border: 1px dashed #00cd00;
              border-radius: 3px;">
                <thead>
                <tr>
                    <td>  يبدا السداد: </td>
                    <td style="text-align: left;">{{$item->start_payment}}</td>
                  </tr>
                    <tr>
                    <td>  دفعات السداد: </td>
                    <td style="text-align: left;">{{number_format($item->month_payment,2)}}</td>
                  </tr>
        
                  <tr>
                    <td style="padding-left: 100px !important;">اسم المستخدم: </td>
                    <td style="text-align: left;">{{\App\Models\User::find($item->created_by)->name}}</td>
                  </tr>
                    <tr>
                    <td>التاريخ: </td>
                    <td style="text-align: left;">{{ $item->date }}</td>
                  </tr>
                </thead>
             
              </table>
              <div style="font-size:15px;text-align: center;margin-top:7px">
              <p> {{ $print->line1 }}<p>
              <p> {{ $print->line2 }}<p>
              </div>
    </section>
  </body>
  <script type="text/javascript">
	window.print();
</script>
</html>





