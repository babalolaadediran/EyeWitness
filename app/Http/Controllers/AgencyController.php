<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use DB;
use View;
use Carbon;
use Session;
use Redirect;
use Response;
use Validator;
use App\User;
use App\Agency;
use App\Report;
use App\Municipal;

class AgencyController extends Controller
{
    # home
    public function home(Request $request) {

        try{

            # logged in agency session
            $loggedInAgency = $request->session()->get('agency');

            if(!empty($loggedInAgency)){

                # agency data
                $agency = Agency::where('email', $loggedInAgency)->first();

                # get reports
                $reports = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'report_media.media_url', 'report_media.media_type')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->where('reports.status', 'Approved')->orderBy('reports.created_at', 'DESC')->paginate(8);

                return view::make('agency/home')->with([
                    'agency' => $agency,
                    'reports' => $reports
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # reports
    public function profile(Request $request) {
        
        try{

            # logged in agency session
            $loggedInAgency = $request->session()->get('agency');

            if(!empty($loggedInAgency)){

                # agency data
                $agency = Agency::where('email', $loggedInAgency)->first();                

                return view::make('agency/profile')->with([
                    'agency' => $agency
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update profile
    public function updateProfile(Request $request) {

        try{

            # logged in agency session
            $loggedInAgency = $request->session()->get('agency');

            if(!empty($loggedInAgency)){

                $rules = [
                    'phone' => 'required',
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator);
                }else{

                    # validate admin
                    $validateAgency = Agency::where('email', $loggedInAgency)->first();

                    if(!$validateAgency){
                        $error = Session::flash('error', 'Sorry, profile update could not be completed.');
                        return redirect()->back()->with($error);
                    }else{

                        # collect data
                        $phone = $request->phone;

                        # agency id
                        $agency_id = $validateAgency->id;

                        # validate phone
                        $validateAgencyPhone = Agency::where('phone', $phone)->first();
    
                        if($validateAgencyPhone && $validateAgencyPhone->id != $agency_id){
                            $error = Session::flash('error', 'Phone number already registered with another agency.');
                            return redirect()->back()->with($error);
                        }else{
                           
                            try{
    
                                # update profile
                                $updateProfile = Agency::find($agency_id)->update([
                                    'phone' => $phone,
                                ]);                                
    
                                $success = Session::flash('success', 'Profile updated successfully.');
                                return redirect()->to('agency/profile')->with($success);
                            }catch(\Exception $ex){
                                $error = Session::flash('error', 'Sorry, profile update could not be completed.');
                                return redirect()->back()->with($error);
                            }
                        }    
                    }
                }               
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update password
    public function updatePassword(Request $request) {

        try{

            # logged in agency session
            $loggedInAgency = $request->session()->get('agency');

            if(!empty($loggedInAgency)){

                $rules = [
                    'old_password' => 'required',
                    'new_password' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator);
                }else{

                    # validate agency
                    $validateAgency = Agency::where('email', $loggedInAgency)->first();

                    if(!$validateAgency){
                        $error = Session::flash('error', 'Sorry, you are not authorized.');
                        return redirect()->back()->with($error);
                    }else{

                        # collect data
                        $old_password = $request->old_password;
                        $new_password = $request->new_password;

                        try{

                            # compare passwords
                            $previousPassword = $validateAgency->password;
                            $checkPassword = Hash::check($old_password, $previousPassword);

                            if(!$checkPassword){
                                $error = Session::flash('error', 'Invalid old password supplied.');
                                return redirect()->back()->with($error);
                            }else{

                                # hash new password
                                $password = Hash::make($new_password);

                                # update profile
                                $updateProfile = Agency::find($validateAgency->id)->update([
                                    'password' => $password
                                ]);
    
                                $success = Session::flash('success', 'Password updated successfully.');
                                return redirect()->to('agency/profile')->with($success);
                            }
                        }catch(\Exception $ex){
                            $error = Session::flash('error', 'Sorry, password update could not be completed.');
                            return redirect()->back()->with($error);
                        }
                    }
                }               
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # report details
    public function reportDetails(Request $request, $id) {

        try{

            # logged in agency session
            $loggedInAgency = $request->session()->get('agency');

            if(!empty($loggedInAgency)){

                # agency data
                $agency = Agency::where('email', $loggedInAgency)->first();

                # verify
                $verify = Report::findOrFail($id);

                # report id
                $report_id = $request->id;

                # get report details
                $report_details = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'users.fullname', 'users.picture', 'report_media.media_url', 'report_media.media_type')->leftJoin('users', 'reports.user_id', '=', 'users.id')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->where('reports.id', $report_id)->first();

                return view::make('agency/report_details')->with([
                    'agency' => $agency,
                    'report_details' => $report_details
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # logout
    public function logout(Request $request) {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->to('/');
    }
}
