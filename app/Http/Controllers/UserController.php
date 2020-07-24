<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
     //======================================================================
    // COnstrutor
    //======================================================================
    public function __construct()
    {
        $this->middleware(['auth:user','verified']);
    }

    public function Dashboard()
    {
        return view("user.dashboard");
    }

    public function ShowForms(Request $request)
    {
        //get the active test where(isActive, 1);
        $DataBase_forms = DB::table('forms')->where([

            'admin_id' => Auth::user()->admin_id,
            'company_id' => Auth::user()->company_id,
            'isActive' => 1

        ])->get();
        
        $forms = array();

        if(!$DataBase_forms->isEmpty()) //if there are any forms
        {
            foreach ($DataBase_forms as $f) {
                $allowed_users = json_decode($f->allowed, true);
                if($allowed_users != null)
                {
                    foreach ($allowed_users as $key => $value)
                    {
                        if(Auth::user()->id == intval($value))
                        {
                            array_push($forms, $f);
                        }
                    }
                }
            }
            return view('user.forms')->with(['form' => $forms]);
        }
        else
        {  
            return redirect()->intended('/user')->withErrors(["WrongInput" => "No Form"]);
        }
        return redirect('/user')->withErrors(["WrongInput" => "Something went Wrong"]);
    }

    public function ShowPublishedForms(Request $request)
    {
       //get the active test where(isActive, 1);
       $DataBase_forms = DB::table('forms')->where([

        'admin_id' => Auth::user()->admin_id,
        'company_id' => Auth::user()->company_id,
        'isActive' => 1

        ])->get();
        
        $forms = array();

        if(!$DataBase_forms->isEmpty()) //if there are any forms
        {
            foreach ($DataBase_forms as $f) {
                $allowed_users = json_decode($f->allowed, true);
                if($allowed_users != null)
                {
                    foreach ($allowed_users as $key => $value)
                    {
                        if(Auth::user()->id == intval($value))
                        {
                            array_push($forms, $f->id);
                        }
                    }
                }
            }
        }
        else
        {  
            return redirect()->intended('/user')->withErrors(["WrongInput" => "No Forms"]);
        }
        
        $sent_form = DB::table('sent_forms')->whereIn('form_id', $forms)->get();


        if(!$sent_form->isEmpty())
        {
            return view('user.sentforms')->with(['form' => $sent_form]);
        }

        return redirect('/user')->withErrors(["WrongInput" => "No Published Form"]);
    }

    public function ShowSavedForms(Request $request)
    {
        //get the active test where(isActive, 1);
        $DataBase_forms = DB::table('forms')->where([

            'admin_id' => Auth::user()->admin_id,
            'company_id' => Auth::user()->company_id,
            'isActive' => 1

        ])->get();
        
        $forms = array();

        if(!$DataBase_forms->isEmpty()) //if there are any forms
        {
            foreach ($DataBase_forms as $f) {
                $allowed_users = json_decode($f->allowed, true);
                if($allowed_users != null)
                {
                    foreach ($allowed_users as $key => $value)
                    {
                        if(Auth::user()->id == intval($value))
                        {
                            array_push($forms, $f->id);
                        }
                    }
                }
            }
        }
        else
        {  
            return redirect()->intended('/user')->withErrors(["WrongInput" => "No Forms"]);
        }
        
        $saved_form = DB::table('saved_form')->whereIn('form_id', $forms)->get();

        if(!$saved_form->isEmpty())
        {
            return view('user.savedforms')->with(['form' => $saved_form]);
        }

        return redirect('/user')->withErrors(["WrongInput" => "No Saved Form"]);
    }



    //USE FORM (DISPLAY FORM)
    public function UseForm(Request $request)
    {
        //get the active test where(isActive, 1);
        $DataBase_forms = DB::table('forms')->where([

            'id' => $request->route('formId'),
            'admin_id' => Auth::user()->admin_id,
            'company_id' => Auth::user()->company_id,
            'isActive' => 1
            

        ])->get();


        $saved_data = DB::table('saved_form')->where([

            'form_id' => $request->route('formId'),
            'admin_id' => Auth::user()->admin_id,
            'company_id' => Auth::user()->company_id,
            'isActive' => 1
        ])->get();

            
        //dd($DataBase_forms[0]);

        if(!$DataBase_forms->isEmpty())
        {
            return view('user.useform')->with(['form' => $DataBase_forms[0], 'savedData' => $saved_data]);
        }
        return redirect('/user')->withErrors(["WrongInput" => "Something went Wrong"]);
    }

    //SAVE FORM
    public function SaveForm(Request $request)
    {
        $formId = $request->route('formId');
        $data = $request->input('data');
        $form_name = $request->input('form_name');

        $check = DB::table('saved_form')->updateOrInsert(
            [
                'form_id' => $formId,
                'admin_id' => Auth::user()->admin_id,
                'company_id' => Auth::user()->company_id,
            ],
            [
                'form_name' => $form_name,
                'form_id' => $formId,
                'admin_id' => Auth::user()->admin_id,
                'company_id' => Auth::user()->company_id,
                'data' => $data,
                'isActive' => 1,
                'created_at' => Carbon::now() ,
            ]
        );

        if($check == true)
        {
            return "success";
        }
        else
        {
            return "Error While Saving";
        }
    }


    public function SendForm(Request $request)
    {
        $imgList = explode(',', $request->input('imagesData'));
        
        $uploads = array();
        foreach($_FILES as $key0=>$FILES) {
            foreach($FILES as $key=>$value) {
                foreach($value as $key2=>$value2) {
                    $uploads[$key0][$key2][$key] = $value2;
                }
            }
        }
        //dd($uploads);

        if($uploads != null)
        {
            $targetDir = "content/";

            foreach($uploads["files"] as $key=>$value)
            {
                if($uploads["files"][$key]["size"] < 50000000)
                {
                    if($uploads["files"][$key]['error'] == 0)
                    {
                        if($uploads["files"][$key]["tmp_name"] != "")
                        {
                            $filename = $uploads["files"][$key]['name'];
                            $targetFilePath = $targetDir.NOW().time().$filename;
                            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                            
                        }else {
                            return "Temporary File not Found in Header";
                        }
                    }else {
                        return "Error";
                    }
                }else {
                    return "Size Greater than 50MB";
                }
            }


            foreach($uploads["files"] as $key=>$value)
            {
                if($uploads["files"][$key]['error'] == 0)
                {
                    if($uploads["files"][$key]["tmp_name"] != "")
                    {
                        if($uploads["files"][$key]["size"] > 0)
                        {
                            $filename = $uploads["files"][$key]['name'];
                            //$targetFilePath = $targetDir.time().time().$filename;
                            $targetFilePath = $targetDir.$imgList[$key];
                            //dd($targetFilePath);
                            if(move_uploaded_file($uploads["files"][$key]['tmp_name'], $targetFilePath))
                            {

                            }
                            else
                            {
                                return "Insertion ERROR";
                            }
                        }
                    }
                }
            }
        }

        //Delete the exisitng Saved Record for This Form
        DB::table('saved_form')->where([
            'form_id' => $request->route('formId'),
            'admin_id' => Auth::user()->admin_id,
            'company_id' => Auth::user()->company_id,
        ])->delete();

        //Send it to Admin
        $check = DB::table('sent_forms')->insert([

            'form_name' => $request->input('form_name'),
            'form_id' => $request->route('formId'),
            'user_id' => Auth::user()->id,
            'admin_id' => Auth::user()->admin_id,
            'company_id' => Auth::user()->company_id,
            'user_name' => Auth::user()->name,
            'user_form_name' => $request->input('user_form_name'),
            'data' => $request->input('data'),
            'isActive' => 1,
            'created_at' => NOW()
        ]);

        if($check == true)
        {
            return "Test Saved Successfully";
        }
        else
        {
            return "Test Saved Failed";
        }
        return "Test Saved Failed";
    }




    //=========================================
    //  Show the View of Sent Form
    //=========================================
    public function ShowViewOfSentForm(Request $request)
    {
        
        $sent_form = DB::table('sent_forms')->where([

            'id' => $request->route('formId'),
            'user_id' => Auth::user()->id,
            'admin_id' => Auth::user()->admin_id,
            'company_id' => Auth::user()->company_id,
            'isActive' => 1
        ])->get();

        if(!$sent_form->isEmpty())
        {
            return view('user.viewSentForm')->with(['form' => $sent_form[0]->data]);
        }
        
        return redirect('/user')->withErrors(["WrongInput" => "Cannot View Form"]);

    }



    //==========================================================
    //  DOWNLOAD RESOURCE
    //==========================================================

    public function download(Request $request)
    {
        $filename = $request->route('filename');
        // Check if file exists in app/storage/file folder
        $file_path = public_path() . "/content/" . $filename;
        
        $headers = array(
            'Content-Type' => 'application/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
          );
        if ( file_exists( $file_path ) ) {
            // Send Download
            return \Response::download( $file_path, $filename, $headers );
        } 
        else 
        {
            exit( 'Requested file does not exist on our server!' );
        }
    }

}
