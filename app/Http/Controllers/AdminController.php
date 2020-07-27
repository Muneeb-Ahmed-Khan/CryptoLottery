<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    //======================================================================
    // COnstrutor
    //======================================================================
    public function __construct()
    {
        $this->middleware(['auth:admin','verified']);
    }

    public function Dashboard()
    {
        $lotteries = DB::table('lotteries')->where([
            'admin_id'=> Auth::user()->id,
            'isActive' => 1,
            'winners' => null
            ])->get();

        
        return view('admin.dashboard')->with(['lotteries' => $lotteries]);
    }

    //======================================================================
    //for Adding/Deleting a Lottery
    //======================================================================
    public function ManageForm(Request $request)
    {
        if ($request->has('AddLottery'))
        {
            $uploads = array();
            foreach($_FILES as $key0=>$FILES) {
                foreach($FILES as $key=>$value) {
                    $uploads[$key0][$key] = $value;
                }
            }

            $filename = $uploads["files"]['name'];
            $targetDir = "content/";
            $targetFilePath = $targetDir.time().$filename;

            if($uploads != null)
            {
                
                $allowTypes = array('jpg','jpeg','png');

                if($uploads["files"]["size"] < 50000000)
                    {
                        if($uploads["files"]['error'] == 0)
                        {
                            if($uploads["files"]["tmp_name"] != "")
                            {
                                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                                if(in_array($fileType, $allowTypes))
                                {

                                }else {
                                    return "Extension Not Allowed";
                                }
                            }else {
                                return "Temporary File not Found in Header";
                            }
                        }else {
                            return "Error";
                        }
                    }else {
                        return "Size Greater than 50MB";
                }

                if($uploads["files"]['error'] == 0)
                    {
                        if($uploads["files"]["tmp_name"] != "")
                        {
                            if($uploads["files"]["size"] > 0)
                            {
                                if(move_uploaded_file($uploads["files"]['tmp_name'], $targetFilePath))
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
            
            $count = DB::table('lotteries')->insert([
                'name' => $request->input('name'),
                'admin_id' => Auth::user()->id,
                'cost_of_lottery' => $request->input('cost_of_lottery'),
                'no_of_winners' =>$request->input('no_of_winners'),
                'max_participants' =>$request->input('requiredUsers'),
                'max_tickets' =>$request->input('ticketLimit'),
                'product_picture' =>$targetFilePath,
                'isActive' => 1,
                'created_at' => Carbon::now()
            ]);

            if($count != null)
            {
               return redirect('/admin')->with(["success" => "Lottery Posted Successfully"]);
            }
            return redirect()->intended('/admin')->withErrors(['error' => 'Lottery Creation Failed']);
        }
        else if ($request->has('DeleteLottery'))
        {

            $check = DB::table('lotteries')->where([
                
                'admin_id' => Auth::user()->id,
                'id' => $request->input('lotteryId'),
                'isActive' => 1
                ])->delete();

            if($check != FALSE)
            {
                return redirect('/admin')->with(["success" => "Lottery Deleted Successfully"]);
            }
            return redirect()->intended('/admin')->withErrors(['error' => 'Lottery Deletion Failed']);
        }

        
        return redirect()->intended('/admin')->withErrors(['error' => 'Something Went Wrong']);
    }



    //======================================================================
    // Show Lottery Deitals by {lotteryID}
    //======================================================================
    public function ShowLotteryDetails(Request $request)
    {
        $lotteries = DB::table('lotteries')->where([
            'admin_id'=> Auth::user()->id,
            'isActive' => 1,
            'id' => $request->route('lotteryId')
            ])->get();

        $transactions = DB::table('transactions')->where([
            'lottery_id' => $request->route('lotteryId'),
            ])->get();

        if(!$lotteries->isEmpty())
        {
            return view('admin.lotteryDetail')->with(['lottery' => $lotteries, 'transactions' => $transactions]);
        }

        return redirect('/admin')->withErrors(["WrongInput" => "No Lottery"]);
    }


    //======================================================================
    // Open Lottery Deitals by {lotteryID}
    //======================================================================
    public function OpenLottery(Request $request)
    {
        if ($request->has('open_lottery'))
        {
            //Auto Pick Winners
            $lotteries = DB::table('lotteries')->where([
                'id' => $request->route('lotteryId'),
                'admin_id'=> Auth::user()->id,
                'isActive' => 1
                ])->get();
            
            
            if(!$lotteries->isEmpty())
            {
                if($request->input('winning_user') == "")
                {
                    $transactions = DB::table('transactions')->where([
                        'lottery_id' => $request->route('lotteryId'),
                        ])->inRandomOrder()->limit((int)$lotteries[0]->no_of_winners)->get();
    
                    $winners = array();
    
                    foreach ($transactions as $t) {
                        array_push($winners, $t->email);
                    }
    
                    $check = DB::table('lotteries')->where([
                        'id' => $request->route('lotteryId'),
                        'admin_id'=> Auth::user()->id,
                        'isActive' => 1
                    ])->update([
                        'winners' => $winners,
                        'isActive' => 0,
                        'updated_at' => Carbon::now()
                    ]);
        
                    if($check)
                    {
                        //dd($transactions, $winners);
                        return redirect('/admin')->with(["success" => "Lottery Opened Successfully"]);
                    }
                    return redirect()->intended('/admin')->withErrors(['error' => 'Lottery Opening Failed']);
                    
                }
                else
                {
                    //Make This person Winner
                    dd($request->input('winning_user'));
                }
            }
            

            return redirect()->intended('/admin')->withErrors(['error' => 'Lottery Opening Failed']);
        }

        return redirect()->intended('/admin')->withErrors(['error' => 'Lottery Opening Failed']);
    }
    






    //======================================================================
    //View Form
    //======================================================================
    public function ViewForm(Request $request)
    {
        $id = $request->route('formId');
        
        $inbox_forms = DB::table('sent_forms')->where([
            'id' => $id,
            'admin_id'=> Auth::user()->id,
            'company_id'=> Auth::user()->company_id,
            'isActive' => 1,
            ])->get();

        //dd(json_decode($inbox_forms[0]->data,true));

        if(!$inbox_forms->isEmpty())
        {
            return view('admin.viewform')->with(['form' => $inbox_forms[0]->data, 'form_name' => $inbox_forms[0]->user_form_name]);
        }

        return redirect('/user')->withErrors(["WrongInput" => "Cannot View Form"]);
    }

    //======================================================================
    //INBOX
    //======================================================================
    public function Inbox(Request $request)
    {
        $inbox_forms = DB::table('sent_forms')->where([
            'admin_id'=> Auth::user()->id,
            'company_id'=> Auth::user()->company_id,
            'isActive' => 1,
        ])->get();
        
        if(!$inbox_forms->isEmpty())
        {
            return view('admin.inbox')->with(['form' => $inbox_forms]);
        }

        return redirect('/user')->withErrors(["WrongInput" => "Inbox Empty"]);
    }

    //======================================================================
    //Show Users
    //======================================================================
    public function ShowUsers(Request $request)
    {
        $users = DB::table('users')->where([
            
            'admin_id' => Auth::user()->id,
            'company_id' => Auth::user()->company_id,
            'isActive' => 1

            ])->select()->get();

        return view('admin.users')->with(['user' => $users]);
    }

    

    //======================================================================
    //for Deleting Inbox Forms
    //======================================================================
    public function ManageFormsInbox(Request $request)
    {
        if ($request->has('DeleteForm'))
        {
            $check = DB::table('sent_forms')->where([
                
                'company_id' => Auth::user()->company_id,
                'admin_id' => Auth::user()->id,
                'id' => $request->input('adminformId'),
                'isActive' => 1
                ])->delete();

            if($check != FALSE)
            {
                return redirect()->back()->with(["success" => "Form Deleted Successfully"]);
            }
            return redirect()->back()->withErrors(['error' => 'Failed to Delete Form']);
        }
        return redirect()->intended('/admin')->withErrors(['error' => 'Something Went Wrong']);
    }


    // EDIT form
    public function EditForm(Request $request)
    {
        $id = $request->route('formId');
        $form = DB::table('forms')->where('admin_id',Auth::user()->id)->where('company_id', Auth::user()->company_id)->where('id', $id)->get();
        if(!$form->isEmpty())
        {
            //dd($form[0]->data);
            return view("admin.createTest")->with(['form' => $form[0]]);
        }
       return view("admin.createTest");
    }

    public function UpdateForm(Request $request)
    {

        $count = DB::table('forms')->where([

            'id' => (int)$request->route('formId'),
            'company_id' => Auth::user()->company_id,
            'admin_id' => Auth::user()->id,
            
            ])->update([

            'data' => $request->input('data'),
            'updated_at' => Carbon::now()

        ]);
        
        if($count != null)
        {
            return redirect('/admin')->with(["success" => "Form Edited Successfully"]);
        }
        return redirect()->intended('/admin')->withErrors(['error' => 'Form Edit Unsuccessfull']);
    }

    public function DeployForm(Request $request)
    {
       if($request->allowed_users != null)
        {
            
            $check = DB::table('forms')->where('id', $request->formId)->where('admin_id',Auth::user()->id)->where('company_id', Auth::user()->company_id)->update([
                'allowed' => $request->allowed_users,
                'isActive' => 1
            ]);

            if($check == true)
            {
                return back()->with(["success" => "Form Deployed Successfully"]);
            }
            else
            {
                return back()->withErrors(["error" => "Form Deploty Failed"]);
            }
        }
        return "Failed";
    }


    //==========================================================
    //  CONDITIONAL  LOGIC
    //==========================================================

    public function ConditionalLogic(Request $request)
    {
        $id = $request->route('formId');
        $form = DB::table('forms')->where('admin_id',Auth::user()->id)->where('company_id', Auth::user()->company_id)->where('id', $id)->get();
        if(!$form->isEmpty())
        {
            //dd($form[0]->data);
            return view("admin.conditional_logic")->with(['form' => $form[0]]);
        }
        return redirect()->intended('/admin')->withErrors(['error' => 'Form Edit Unsuccessfull']);
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



    //======================================================================
    //Excel Download Helper
    //======================================================================
    public function ExcelDownloadHelper(Request $request)
    {
        
        $inbox_forms = DB::table('sent_forms')->where([
            
            'form_id' => $request->input('formId'),
            'admin_id'=> Auth::user()->id,
            'company_id'=> Auth::user()->company_id,
            'isActive' => 1,

            ])->get();
            
        if(!$inbox_forms->isEmpty())
        {
            return $inbox_forms;
        }

        return json_encode(array());
    }


}
