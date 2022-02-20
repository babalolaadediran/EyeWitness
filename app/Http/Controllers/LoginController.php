<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Administrator;
use App\Agency;
use App\DistrictHead;
use App\MunicipalHead;

class LoginController extends Controller
{
    # login
    public function login(Request $request){

        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        # validator 
        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }else{

            # collect data
            $email = $request->email;
            $password = $request->password;                    

            # check if admin exists
            $validateAdmin = Administrator::where('email', $email)->first();

            if(!empty($validateAdmin)){

                # get validated admin password
                $adminPassword = $validateAdmin->password;

                # verify password match
                $verify = Hash::check($password, $adminPassword);

                if(!$verify){
                    $error = Session::flash('error', 'Invalid login credentials.');
                    return redirect()->back()->withInput()->with($error);
                }else{

                    # goto dashboard
                    $adminSession = Session::put('administrator', $email);
                    return redirect()->to('administrator/home')->with($adminSession);
                }
            }else{

                # check if district head exists
                $validateDistrictHead = DistrictHead::where('email', $email)->first();
                
                if(!empty($validateDistrictHead)){

                    # get validated district head password
                    $districtHeadPassword = $validateDistrictHead->password;

                    # verify pasword match
                    $authenticate = Hash::check($password, $districtHeadPassword);

                    if($authenticate){
                        # goto dashboard
                        $districtSession = Session::put('district_head', $email);
                        return redirect()->to('district/home')->with($districtSession);
                    }else{
                        $error = Session::flash('error', 'Invalid login credentials.');
                        return redirect()->back()->withInput()->with($error);
                    }
                }else{

                    # check for municipal head
                    $validateMunicipalHead = MunicipalHead::where('email', $email)->first();                    

                    if(!empty($validateMunicipalHead)){
                        
                        # get validated municipal head password
                        $municipalHeadPassword = $validateMunicipalHead->password;

                        # verify password
                        $authenticatePassword = Hash::check($password, $municipalHeadPassword);

                        if($authenticatePassword){
                            # goto dashboard
                            $municipalSession = Session::put('municipal_head', $email);
                            return redirect()->to('municipal/home')->with($municipalSession);
                        }else{
                            $error = Session::flash('error', 'Invalid login credentials.');
                            return redirect()->back()->withInput()->with($error);
                        }
                    }else{

                        # check for agency
                        $validateAgency = Agency::where('email', $email)->first();

                        if(!$validateAgency){
                            $error = Session::flash('error', 'Invalid login credentials.');
                            return redirect()->back()->withInput()->with($error);
                        }else{

                            # get validated agency password
                            $agencyPassword = $validateAgency->password;

                            # verify password
                            $authenticateAgencyPassword = Hash::check($password, $agencyPassword);

                            if($authenticateAgencyPassword){
                                # go to dashboard
                                $agencySession = Session::put('agency', $email);
                                return redirect()->to('agency/home')->with($agencySession);
                            }else{
                                $error = Session::flash('error', 'Invalid login credentials.');
                                return redirect()->back()->withInput()->with($error);
                            }
                        }                        
                    }                    
                }
            }
        }
    }
}
