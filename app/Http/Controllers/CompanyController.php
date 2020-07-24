<?php

namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Mail;
use App\Mail\InformationMail;

class CompanyController extends Controller
{
    //======================================================================
    // COnstrutor
    //======================================================================
    public function __construct()
    {
        $this->middleware(['auth:company','verified']);
    }

    public function Dashboard()
    {
        $admins = DB::table('admins')->get()->where('company_id',Auth::user()->id)->where('isActive',1);
        
        return view('company.dashboard')->with('admins',$admins);
    }


    //======================================================================
    // to add new Admin and delete the existing Admin
    //======================================================================
    protected function validator(array $data, $table)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.$table.''],   //unique:$table (check the $table table for email uniqueness)
        ],
         [
            'email.required' => 'Email is required',
            'email.min' => 'Email must be at least 2 characters.',
            'email.max' => 'Email should not be greater than 50 characters.',
            'email.unique' => 'Email already Registered',
         ]);
    }

    public function ManageAdmin(Request $request)
    {
        if ($request->has('RegisterAdmin'))
        {
            $validator = $this->validator($request->all(),  'admins');
            if ($validator->fails())
            {
                return back()->withErrors($validator->messages());
            }
            else
            {
                $password = rand(5000000, 1000000000);
                $count = DB::table('admins')->insert([
                    'name' => $request->input('name'), 
                    'email' => $request->input('email'), 
                    'company_id' => Auth::user()->id,
                    'password' => Hash::make($password),
                    'email_verified_at' => Carbon::now(), 
                    'created_at' => Carbon::now() , 
                    'isActive' => 1,
                ]);

                if($count != null)
                {
                    Mail::to($request->input('email'))->send(new InformationMail(json_encode((array(['name'=>$request->input('name'),'email'=>$request->input('email'),'password'=>$password])),true)));
                    return redirect('/company')->with(["success" => "Admin Added Successfully"]);
                }
                else
                {
                    return back()->withErrors(["SomethingWentWrong" => "Something went wrong"]);
                }
            }
        }
        else if ($request->has('DeleteAdmin'))
        {
            $check = DB::table('admins')->where([
                
                'company_id' => Auth::user()->id,
                'id' => $request->input('admin_id'),
                'isActive' => 1
                
                ])->delete();

            if($check != FALSE)
            {
                DB::table('users')->where([
                    
                    'company_id' => Auth::user()->id,
                    'admin_id' => $request->input('admin_id')
                
                ])->update([
                    'isActive' => 0
                ]);
                // DB::table('students')->where(['school_id' => $request->input('schoolid')])->delete();
                // DB::table('scourses')->where(['school_id' => $request->input('schoolid')])->delete();
                // DB::table('tcourses')->where(['school_id' => $request->input('schoolid')])->delete();
                // DB::table('chapters')->where(['school_id' => $request->input('schoolid')])->delete();
                return redirect('/company')->with(["success" => "Admin Deleted Successfully"]);
            }
            else
            {
                return back()->withErrors(["WrongInput" => "Wrong Admin ID"]);
            }
        }
        return "xxxxxx";
        return redirect()->intended('/principal');
    }


    //======================================================================
    //showing all admins in company
    //======================================================================
    public function AdminView(Request $request)
    {
        $admin = DB::table('admins')->where([
            
            'id' => $request->route('adminId'),
            'company_id' => Auth::user()->id,
            
            ])->select()->get();
        
        if(!$admin->isEmpty())
        {
            $users = DB::table('users')->where([
            
                'admin_id' => $request->route('adminId'),
                'company_id' => Auth::user()->id,
                'isActive' => 1,

                ])->select()->get();

            $existingUser = DB::table('users')->where([
        
                'isActive' => 0,
                'company_id' => Auth::user()->id,
                
                ])->select()->get();

            return view('company.adminview')->with(['user' => $users, 'existingUser' => $existingUser,'adminId' => $request->route('adminId')]);
        }
        else
        {
            return redirect()->intended('/company')->withErrors(["WrongInput" => "Wrong Admin ID"]);
        }
    }


    //======================================================================
    //for uploading Users to Admin
    //======================================================================
    public function UserUpload(Request $request)
    {
        if ($request->has('RegUser'))
        {
            $admin = DB::table('admins')->where([
            
                'id' => $request->route('adminId'),
                'company_id' => Auth::user()->id,
                
                ])->select()->get();
            
            if(!$admin->isEmpty())
            {
                $validator = $this->validator($request->all(),  'users');
                if ($validator->fails())
                {
                    return back()->withErrors($validator->messages());
                }
                else
                {
                    $password = rand(5000000, 1000000000);
                    $count = DB::table('users')->insert([
                        'name' => $request->input('name'), 
                        'email' => $request->input('email'), 
                        'company_id' => Auth::user()->id,
                        'admin_id' => $request->route('adminId'),
                        'password' => Hash::make($password),
                        'email_verified_at' => Carbon::now(), 
                        'created_at' => Carbon::now() , 
                        'isActive' => 1,
                    ]);
    
                    if($count != null)
                    {
                        Mail::to($request->input('email'))->send(new InformationMail(json_encode((array(['name'=>$request->input('name'),'email'=>$request->input('email'),'password'=>$password])),true)));
                        return redirect('/company'.'/'.$request->route('adminId').'')->with(["success" => "User Added Successfully"]);
                    }
                    else
                    {
                        return back()->withErrors(["SomethingWentWrong" => "Something went wrong"]);
                    }
                }
            }
            else
            {
                return redirect()->intended('/company')->withErrors(["WrongInput" => "Wrong Admin ID"]);
            }
        }
        else  if($request->has('AssignUser'))
        {

            $admin = DB::table('admins')->where([
            
                'id' => $request->route('adminId'),
                'company_id' => Auth::user()->id,
                
                ])->select()->get();

            if(!$admin->isEmpty())
            {
                $assigned = $request->input('assigned');
                foreach ($assigned as $ass) {

                    DB::table('users')->where([
                        'company_id' => Auth::user()->id,
                        'isActive' => 0,
                        'id' => $ass
                    ])->update([
                        'isActive' => 1,
                        'admin_id' =>  $request->route('adminId')
                    ]);
                }
            }
            else
            {
                return redirect()->intended('/company')->withErrors(['error' => 'Wrong Admin ID']);
            }
            return redirect()->intended('/company/'.$admin[0]->id)->with(['success' => 'Users Assigned Successfully']);
        }
        return redirect()->intended('/company')->withErrors(['error' => 'Something Went Wrong']);
    }

    //FOR IDLEING USERS FROM ADMIN
    public function IdleUser(Request $request)
    {
        if($request->idleUserformId != null)
        {
            $adminId = $request->route('adminId');
            
            $check = DB::table('users')->where('id', $request->idleUserformId)->where('company_id', Auth::user()->id)->update([
                'isActive' => 0
            ]);

            if($check == true)
            {
                return redirect()->intended('/company/'.$adminId)->with(['success' => 'Users Idle Successfully']);
            }
            return "FormUpdateFailed";
        }
        return "Failed";
    }

    public function deleteUser(Request $request)
    {
        if($request->deleteUserformId != null)
        {
            $adminId = $request->route('adminId');
            
            $check = DB::table('users')->where('id', $request->deleteUserformId)->where('company_id', Auth::user()->id)->delete();
            if($check == true)
            {
                return redirect()->intended('/company/'.$adminId)->with(['success' => 'Users Deleted Successfully']);
            }
            return "FormUpdateFailed";
        }
        return "Failed";
    }


    public function ViewIdleUsers(Request $request)
    {
         $users = DB::table('users')->where([

                'company_id' => Auth::user()->id,

            ])->select()->get();

        return view('company.viewIdleUsers')->with(['user' => $users]);
    }

    public function DeleteIdleUsers(Request $request)
    {
        if($request->deleteUserformId != null)
        {
            $check = DB::table('users')->where('id', $request->deleteUserformId)->where('company_id', Auth::user()->id)->delete();
            if($check == true)
            {
                return redirect('/company/ViewIdleUsers')->with(['success' => 'Users Deleted Successfully']);
            }
            return redirect()->intended('/company')->withErrors(["SomethingWentWrong" => "Something went wrong"]);
        }
        return redirect()->intended('/company')->withErrors(["SomethingWentWrong" => "No User was Selected"]);
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
