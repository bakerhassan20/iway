<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\User;
use App\Models\Option;
use App\Events\MakeTask;
use App\Models\Box_year;
use App\Models\Material;
use App\Models\Repository;
use App\Models\Us_qu;
use App\Models\Prin_t;
use App\Models\Rep_section;
use Illuminate\Http\Request;
use App\Models\Repository_in;
use App\Models\Repository_year;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\CMSBaseController;
use App\Notifications\NewLessonNotification;

class RepositoryInController extends CMSBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subtitle="قبض مستودع";
        $title="المستودع";
        $items=Repository_in::where('isdelete',0)->paginate(10);
        $materials=Material::where('isdelete',0)->orderBy('name')->get();
        $repositories=Repository::leftJoin('repositories_year as rp', 'rp.repository_id','=','repositories.id')
            ->leftJoin('repository_view', 'repository_view.repository_id','=','repositories.id')
            ->select([ 'repositories.id', 'repositories.name', 'rp.m_year', 'rp.active'])
            ->where('repositories.isdelete',0)->where('rp.active',1)->where('rp.m_year',$this->getMoneyYear())->orderBy('repositories.name')
            ->where('repository_view.user_id','=',Auth::user()->id)
            ->get();
        $users=User::where("isdelete",0)->where("Status",'مفعل')->orderBy('name')->get();
        return view("cms.repositoryIn.index",compact("title","subtitle","items","users","materials","repositories"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title="المستودع";
        $parentTitle="قبض مستودع";
        $last_rep_in=Repository_in::latest()->first();
        if($last_rep_in){
            $id_comp=($last_rep_in->id_comp + 1);
        }else{
            $id_comp=1;
        }

        $linkApp="/CMS/RepositoryIn/";
        $repositories=Repository::leftJoin('repositories_year as rp', 'rp.repository_id','=','repositories.id')
        ->leftJoin('repository_view', 'repository_view.repository_id','=','repositories.id')
            ->select([ 'repositories.id', 'repositories.name', 'rp.m_year', 'rp.active'])
            ->where('repositories.isdelete',0)->where("rp.active",1)->where('rp.m_year',$this->getMoneyYear())
            ->where('repository_view.user_id','=',Auth::user()->id)->get();

        return view("cms.repositoryIn.add",compact("title","parentTitle","id_comp","linkApp","repositories"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,FlasherInterface $flasher)
    {
        $this->validate($request,
            [
                'customer' => 'required',
                'id_comp' => 'required',
                'repository_id' => 'required',
                'section' => 'required',
                'material_id' => 'required',
                'count_h' => 'required',
                'single_pay' => 'required',
                'quantity' => 'required',
                'total_h' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);
        $re_id_sys = Repository_in::where('repository_id',$request->input("repository_id"))->where('m_year',$this->getMoneyYear())->latest()->first();
        if(  $re_id_sys){
            $id_sys=($re_id_sys->id_sys + 1);
        }else{
            $id_sys=1;
        }
        $repository_id = $request->input("repository_id");
        $material_id = $request->input("material_id");
        $repository_in = new Repository_in();
        $repository_in->m_year = $request->input("edu_year_h");
        $repository_in->id_comp = $request->input("id_comp");
        $repository_in->id_sys = $id_sys;
        $repository_in->customer = $request->input("customer");
        $repository_in->repository_id = $repository_id;
        $repository_in->section = $request->input("section");
        $repository_in->material_id = $material_id;
        $repository_in->count = $request->input("count_h");
        $repository_in->single_pay = $request->input("single_pay");
        $repository_in->quantity = $request->input("quantity");
        $repository_in->total = $request->input("total_h");
        $repository_in->notes = $request->input("notes");
        $repository_in->print = $request->input("print")?1:0;
        $repository_in->created_by = $this->getId();

        if ($repository_in->save()){
            $repository = Repository_year::where('repository_id',$repository_id)->where('m_year',$this->getMoneyYear())->first();
            $rep = Repository::where('id',$repository_id)->first();
            $repository->repository_in = $repository->repository_in+$request->input("total_h");
            if ($repository->save()){
                $box = Box_year::where('box_id',$rep->box_id)->where('m_year',$this->getMoneyYear())->first();

                $box->income = $repository->repository_in;
                $box->save();
                $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
                $primary->income += $request->input("total_h");
                $primary->save();
            }

            $matrial = Material::where('id',$material_id)->first();
            $matrial->count_new = $matrial->count_new - $request->input("quantity");
            $matrial->save();


        /////////////////////////
            $rep = Repository::where('id',$repository_in->repository_id)->first();
            if( $rep){
                $box =$rep->box_id;
                $name=$rep->name;
            }else{
                $box =null;
                $name=null;
            }

            $us_qu= new Us_qu();
            $us_qu->m_year = $repository_in->m_year;
            $us_qu->id_main = $repository_in->id;
            $us_qu->id_sys = $repository_in->id_sys;
            $us_qu->name = $name;
            $us_qu->type = 'قبض مستودع';
            $us_qu->action = 'ادخال';
            $us_qu->amount = $repository_in->total;
            $us_qu->date = $repository_in->created_at;
            $us_qu->created_by = $repository_in->created_by;
            $us_qu->slug='RepositoryIn';
            $us_qu->box_id =$box;
            $us_qu->save();
            //////////////

            if(Auth::user()->responsible_id != null){
                $user=User::where('id',Auth::user()->responsible_id)->get();
            \Notification::send($user,new NewLessonNotification('RepositoryIn/'.$repository_in->id,$this->getId(),' قبض مستودع','RepositoryIn'));
            MakeTask::dispatch(Auth::user()->responsible_id);
            }

        }




        $flasher->addSuccess("تمت عملية الاضافة بنجاح");
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Repository_in  $repository_in
     * @return \Illuminate\Http\Response
     */
    public function show($id,FlasherInterface $flasher)
    {
        $parentTitle="عرض قبض المستودع ";
        $item=Repository_in::where("id",$id)->where("isdelete",0)->first();
        $title="ادارة الواردات";
        $linkApp="/CMS/RepositoryIn/";
        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/RepositoryIn");
        }
        return view("cms.repositoryIn.show",compact("title","item","id","parentTitle","linkApp"));
    }

    public function print($id)
    {
        $parentTitle="عرض قبض المستودع ";
        $item=Repository_in::where("id",$id)->where("isdelete",0)->first();
        $title="ادارة الواردات";
        $linkApp="/CMS/RepositoryIn/";
        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/RepositoryIn");
        }
        $print = Prin_t::first();

        if($print->type == 'A5'){
            return view("cms.repositoryIn.printA5",compact("title","item","id","parentTitle",'print'));
        }else{
            return view("cms.repositoryIn.printA6",compact("title","item","id","parentTitle",'print'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Repository_in  $repository_in
     * @return \Illuminate\Http\Response
     */
    public function edit($id,FlasherInterface $flasher)
    {
        $parentTitle="تعديل الوارد ";
        $item=Repository_in::where("id",$id)->where("isdelete",0)->first();
        $sections=Rep_section::where('repository_id',$item->repository_id)->where('isdelete',0)->get();
        $repositories=Repository::leftJoin('repositories_year as rp', 'rp.repository_id','=','repositories.id')
        ->leftJoin('repository_view', 'repository_view.repository_id','=','repositories.id')
            ->select([ 'repositories.id', 'repositories.name', 'rp.m_year', 'rp.active'])
            ->where('repositories.isdelete',0)->where('rp.m_year',$this->getMoneyYear())
            ->where('repository_view.user_id','=',Auth::user()->id)->get();
        $materials=Material::where('section',$item->section)->where('isdelete',0)->where('active',1)->get();
        $title="ادارة الواردات";
        $linkApp="/CMS/RepositoryIn/";
        if($item==NULL){
            flash()->addWarning("الرجاء التأكد من الرابط المطلوب");
            return redirect("/CMS/RepositoryIn/");
        }
        return view("cms.repositoryIn.edit",compact("title","item","id","sections","repositories","materials","parentTitle","linkApp"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Repository_in  $repository_in
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,FlasherInterface $flasher)
    {
        $this->validate($request,
            [
                'customer' => 'required',
                'repository_id' => 'required',
                'section' => 'required',
                'material_id' => 'required',
                'count_h' => 'required',
                'single_pay' => 'required',
                'quantity' => 'required',
                'total_h' => 'required'
            ],
            [
                "required"=>"يجب ادخال هذا الحقل"
            ]);

        $item=Repository_in::find($id);
        $item->customer = $request->input("customer");
        $item->m_year=$request->input("m_year");
        $item->repository_id=$request->input("repository_id");
        $item->section=$request->input("section");
        $item->material_id=$request->input("material_id");
        $item->count=$request->input("count_h");
        $item->single_pay=$request->input("single_pay");
        $item->quantity=$request->input("quantity");
        $total =$item->total;
        $item->total=$request->input("total_h");
        $item->notes=$request->input("notes");
        $item->print = $request->input("print")?1:0;
        $item->isdelete=$request->input("isdelete")?1:0;
        $item->updated_by=$this->getId();
        if ($item->save()){
            $repository = Repository_year::where('repository_id',$request->input("repository_id"))->where('m_year',$this->getMoneyYear())->first();
            $rep = Repository::where('id',$request->input("repository_id"))->first();
            $repository->repository_in -= $total-$request->input("total_h");
            if ($repository->save()){
                $box = Box_year::where('box_id',$rep->box_id)->where('m_year',$this->getMoneyYear())->first();
                $box->income = $repository->repository_in;
                $box->save();
                $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
                $primary->income -= $total-$request->input("total_h");
                $primary->save();
            }


/////////////////////////
            $rep = Repository::where('id',$item->repository_id)->first();
            if( $rep){
                $box =$rep->box_id;
                $name=$rep->name;
               }else{
                $box =null;
                $name=null;
               }

           $us_qu= new Us_qu();
           $us_qu->m_year = $item->m_year;
           $us_qu->id_main = $item->id;
           $us_qu->id_sys = $item->id_sys;
           $us_qu->name = $name;
           $us_qu->type = 'قبض مستودع';
           $us_qu->action = 'تعديل';
           $us_qu->amount = $item->total;
           $us_qu->date = $item->created_at;
           $us_qu->created_by = $this->getId();
           $us_qu->slug='RepositoryIn';
           $us_qu->box_id =$box;
           $us_qu->save();
//////////////

if(Auth::user()->responsible_id != null){
    $user=User::where('id',Auth::user()->responsible_id)->get();
            \Notification::send($user,new NewLessonNotification('RepositoryIn/'.$item->id,$this->getId(),' تعديل قبض مستودع','RepositoryIn'));
            MakeTask::dispatch(Auth::user()->responsible_id);
            }
        }

        $flasher->addSuccess("تمت عملية الحفظ بنجاح");
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Repository_in  $repository_in
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDelete($id,FlasherInterface $flasher)
    {
        $item=Repository_in::find($id);
        $item->isdelete=1;
        $item->deleted_by=$this->getId();

        if($item->save()){
            $repository = Repository_year::where('repository_id',$item->repository_id)->where('m_year',$this->getMoneyYear())->first();
            $rep = Repository::where('id',$item->repository_id)->first();
            $repository->repository_in -= $item->total;
            if ($repository->save()){
                $box = Box_year::where('box_id',$rep->box_id)->where('m_year',$this->getMoneyYear())->first();
                $box->income = $repository->repository_in;
                $box->save();
                $primary = Box_year::where('box_id',1)->where('m_year',$this->getMoneyYear())->first();
                $primary->income -= $item->total;
                $primary->save();


/////////////////////////
            $rep = Repository::where('id',$item->repository_id)->first();
            if( $rep){
                $box =$rep->box_id;
                $name=$rep->name;
               }else{
                $box =null;
                $name=null;
               }

           $us_qu= new Us_qu();
           $us_qu->m_year = $item->m_year;
           $us_qu->id_main = $item->id;
           $us_qu->id_sys = $item->id_sys;
           $us_qu->name = $name;
           $us_qu->type = 'قبض مستودع';
           $us_qu->action = 'حذف';
           $us_qu->amount = $item->total;
           $us_qu->date = $item->created_at;
           $us_qu->created_by = $this->getId();
           $us_qu->slug='RepositoryIn';
           $us_qu->box_id =$box;
           $us_qu->save();
//////////////
            }
        }
        flash()->addError("تمت عملية الحذف بنجاح");
        return redirect("/CMS/RepositoryIn/");
    }

    public function getIdComp($id){
        $item=Repository_in::where('repository_id',$id)->latest()->first();
        if($item){
            $data=($item->id_comp + 1);
        }else{
            $data=1;
        }

        return response()->json($data);
    }
}
