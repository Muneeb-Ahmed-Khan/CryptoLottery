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
        $lotteries = DB::table('lotteries')->where([
            'isActive' => 1,
            'winners' => null
            ])->get();

        
        return view('user.dashboard')->with(['lotteries' => $lotteries]);
    }


    //======================================================================
    // Show Lottery Deitals by {lotteryID}
    //======================================================================
    public function ShowLotteryDetails(Request $request)
    {
        $lotteries = DB::table('lotteries')->where([
            'isActive' => 1,
            'id' => $request->route('lotteryId')
            ])->get();
        
        $transactions = DB::table('transactions')->where([
            'lottery_id' => $request->route('lotteryId'),
            ])->get();

        if(!$lotteries->isEmpty())
        {
            return view('user.lotteryDetail')->with(['lottery' => $lotteries, 'transactions' => $transactions]);
        }

        return redirect('/user')->withErrors(["WrongInput" => "No Lottery"]);
    }

    //=================================
    // Open Lottery
    //=================================
    public function OpenLottery($lotteryID)
    {

        //Auto Pick Winners
        $lotteries = DB::table('lotteries')->where([
            'id' => $lotteryID,
            'admin_id'=> Auth::user()->id,
            'isActive' => 1
            ])->get();



        if(!$lotteries->isEmpty())
        {
            $transactions = DB::table('transactions')->where([
                'lottery_id' => $lotteryID,
                ])->inRandomOrder()->limit((int)$lotteries[0]->no_of_winners)->get();

            $winners = array();

            foreach ($transactions as $t) {
                array_push($winners, $t->email);
            }

            $check = DB::table('lotteries')->where([
                'id' => $lotteryID,
                'admin_id'=> Auth::user()->id,
                'isActive' => 1
            ])->update([
                'winners' => $winners,
                'isActive' => 0,
                'updated_at' => Carbon::now()
            ]);

            if($check)
            {
                return redirect('/user/history/recentLotteries')->with(["success" => "Lottery Opened Successfully"]);
            }
            return redirect('/user')->withErrors(["WrongInput" => "No Lottery"]);
        }
    }

    //======================================================================
    // Buy Lottery by {lotteryID}
    //======================================================================
    public function BuyLottery(Request $request)
    {

        $lotteries = DB::table('lotteries')->where([
            'isActive' => 1,
            'id' => $request->route('lotteryId')
            ])->get();
        
        $transactions = DB::table('transactions')->where([
            'lottery_id' => $request->route('lotteryId'),
            ])->count();

        $myparticipation = DB::table('transactions')->where([
            'lottery_id' => $request->route('lotteryId'),
            'email' => Auth::user()->email
            ])->count();
        
        if($myparticipation >= $lotteries[0]->max_tickets)
        {
            return redirect()->intended('/user/'.$request->route('lotteryId'))->with(['info' => "Maximum Tickets Purchased"]);
        }

        if($transactions >= $lotteries[0]->max_participants)
        {
            return redirect()->intended('/user/'.$request->route('lotteryId'))->with(['info' => "Maximum Transaction Achieved"]);
        }

        $check = DB::table('transactions')->insert([

            'username' => Auth::user()->name,
            'email' => Auth::user()->email,
            'lottery_id' => $request->route('lotteryId'),
            'admin_id' => 1,
            'transaction_token' => "TemporaryToken",
            'created_at' => Carbon::now()

        ]);

        if($check == true)
        {
            //check if the participants are completed, then draw the lottery
            $transactions = DB::table('transactions')->where([
                'lottery_id' => $request->route('lotteryId'),
            ])->count();
            if($transactions >= $lotteries[0]->max_participants)
            {
                $this->OpenLottery($lotteries[0]->id);
            }

            //otherwise just return with message
            return redirect()->intended('/user/'.$request->route('lotteryId'))->with(['success' => "Bought SuccesFully"]);
        }

        return redirect('/user')->withErrors(["WrongInput" => "No Lottery"]);
    }


    public function settings(Request $request)
    {
        $record = DB::table('users')->select('address')->where([
                'id' => Auth::user()->id, 
                'email' => Auth::user()->email, 
            ])->get();
            
        return view('user.settings')->with('address', $record[0]->address);
    }

    public function Updatesettings(Request $request)
    {
        $affected = DB::table('users') ->where([
                    'id' => Auth::user()->id, 
                    'email' => Auth::user()->email, 
                ])->update([
                    'address' => $request->input('address')
                ]);

        if($affected == 1)
        {
            return redirect('/settings')->with(['success' => "Address Updated Sucessfully."]);
        }
        return redirect('/settings');
    }



    //=================================
    //  Recent Withdraws
    //=================================
    public function RecentLotteries(Request $request)
    {
        $lotteries = DB::table('lotteries')->whereNotNull('winners')->orderBy('updated_at', 'DESC')->get();
        return view('user.history')->with(['lotteries' => $lotteries]);
    }


    public function RecentLotteriesMyWinnings(Request $request)
    {
        $lotteries = DB::table('lotteries')->whereNotNull('winners')->orderBy('updated_at', 'DESC')->get();

        $myLottery = array();

        for ($i=0; $i < count($lotteries); $i++) { 
            
            $filter = json_decode($lotteries[$i]->winners);

            $repeat = false;

            for ($j=0; $j < count($filter); $j++) { 
                
                if($filter[$j] == Auth::user()->email && $repeat == false)
                {
                    array_push($myLottery, $lotteries[$i]);
                    $repeat = true;
                }
            }
        }

        return view('user.mywinnings')->with(['lotteries' => $myLottery]);
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
