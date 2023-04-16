<?php

namespace App\Http\Controllers;

use App\Models\Prin_t;
use Illuminate\Http\Request;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class PrinterController extends Controller
{
    public function index(){
        $print = Prin_t::first();
        return view('cms.print.index',compact('print'));
    }

    public function updateHead(Request $request,FlasherInterface $flasher){
        $this->validate($request,
        [
            'address' => 'required',
            'phone' => 'required',
        ],
        [
            "required"=>"يجب ادخال هذا الحقل"
        ]);

        $print = Prin_t::first();
        $print->address=$request->input("address");
        $print->phone=$request->input("phone");
        $print->save();
        
        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    public function updateFooter(Request $request,FlasherInterface $flasher){
  
        $print = Prin_t::first();
        $print->line1=$request->input("line1");
        $print->line2=$request->input("line2");
        $print->save();
        
        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    public function updateType(Request $request,FlasherInterface $flasher){
  
        $this->validate($request,
        [
            'type' => 'required',
        ],
        [
            "required"=>"يجب ادخال هذا الحقل"
        ]);
        $print = Prin_t::first();
        $print->type=$request->input("type");
        $print->save();
        
        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    public function updateIcon(Request $request,FlasherInterface $flasher){
  
        $this->validate($request,
        [
            'icon' => 'required|mimes:jpeg,png,jpg,gif',
        ],
        [
            "required"=>"يجب ادخال هذا الحقل",
            "mimes"=>"يجب ان تكون صوره من النوع (jpeg,png,jpg,gif)"
        ]);


        $print = Prin_t::first();
        if($request->hasfile('icon'))
        {
           $destination = 'uploads/print/'.$print->icon;
           if(File::exists($destination))
           {
            File::delete($destination);
           }
           $file = $request->file('icon');
           $extention = $file->getClientOriginalExtension();
           $filename = time().'.'.$extention;
           $file->move('uploads/print/', $filename);
           $print->icon =  $filename;
    
        }

        $print->update();
        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    
    public function updateLink(Request $request,FlasherInterface $flasher){
  
        $this->validate($request,
        [
            'link' => 'required',
        ],
        [
            "required"=>"يجب ادخال هذا الحقل"
        ]);
        $print = Prin_t::first();
        $print->link=$request->input("link");
        $print->save();
        
        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }
}
