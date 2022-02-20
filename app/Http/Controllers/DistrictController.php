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
use App\Province;
use App\District;
use App\Municipal;
use App\ReportMedia;
use App\MunicipalHead;
use App\DistrictHead;


class DistrictController extends Controller
{
    # district head dashboard
    public function home(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # municipals associated with district
                $municipals = Municipal::select('id')->where('district_id', $district_head->district_id)->get();

                # registered citizens
                $citizens = User::whereIn('municipal_id', $municipals)->count();

                # users associated to distric via municipal
                $users = User::select('id')->whereIn('municipal_id', $municipals)->get();

                # total reports
                $total_reports = Report::whereIn('user_id', $users)->count();

                # total municipal
                $total_municipal = Municipal::where('district_id', $district_head->district_id)->count();                

                # municipals
                $district_municipals = Municipal::select('id', 'name')->where('district_id', $district_head->district_id)->orderBy('name', 'DESC')->get();

                return view::make('district/home')->with([
                    'district_head' => $district_head,
                    'citizens' => $citizens,
                    'total_reports' => $total_reports,                    
                    'total_municipal' => $total_municipal,
                    'district_municipals' => $district_municipals
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # district head profile page
    public function profile(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                return view::make('district/profile')->with([
                    'district_head' => $district_head,
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

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                $rules = [
                    'fullname' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    'phone' => 'required',                    
                    'email' => 'required',
                    'address' => 'required'                    
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator);
                }else{

                    # validate district head
                    $validateDistrictHead = DistrictHead::where('email', $loggedInDistrictHead)->first();

                    if(!$validateDistrictHead){
                        $error = Session::flash('error', 'Sorry, profile update could not be completed.');
                        return redirect()->back()->with($error);
                    }else{

                        # collect data
                        $fullname = $request->fullname;
                        $gender = $request->gender;
                        $dob = $request->dob;
                        $phone = $request->phone;
                        $email = $request->email;
                        $address = $request->address;

                        # district head id
                        $district_head_id = $validateDistrictHead->id;

                        # check if submitted data has image
                        if($request->hasFile('picture')){

                            $rules = [
                                'picture' => 'required|image|mimes:jpeg,png,jpg|max:4048',
                            ];

                            # image validator
                            $imageValidator = Validator::make($request->all(), $rules);
                            
                            if($imageValidator->fails()){
                                return redirect()->back()->withErrors($validator);
                            }else{

                                # validate phone
                                $validateDistrictHeadPhone = DistrictHead::where('phone', $phone)->first();

                                if($validateDistrictHeadPhone && $validateDistrictHeadPhone->id != $district_head_id){
                                    $error = Session::flash('error', 'Phone number already registered with another district head.');
                                    return redirect()->back()->with($error);
                                }else{

                                    # validate email
                                    $validateDistrictHeadEmail = DistrictHead::where('email', $email)->first();

                                    if($validateDistrictHeadEmail && $validateDistrictHeadEmail->id != $district_head_id){
                                        $error = Session::flash('error', 'Email already registered with another district head.');
                                        return redirect()->back()->with($error);
                                    }else{

                                        try{
        
                                            # encode image
                                            $picture = $request->picture;
                                            $filename = file_get_contents($picture);
                                            $encode_image = base64_encode($filename);
                
                                            # update profile
                                            $updateProfile = DistrictHead::find($district_head_id)->update([
                                                'fullname' => $fullname,
                                                'gender' => $gender,
                                                'dob' => $dob,
                                                'phone' => $phone,
                                                'email' => $email,
                                                'address' => $address,
                                                'picture' => $encode_image
                                            ]);

                                            # set session due email update
                                            $setSession = Session::put('district_head', $email);
                
                                            $success = Session::flash('success', 'Profile updated successfully.');
                                            return redirect()->to('district/profile')->with($success);
                                        }catch(\Exception $ex){
                                            $error = Session::flash('error', 'Sorry, profile update could not be completed.');
                                            return redirect()->back()->with($error);
                                        }
                                    }
                                }
                            }
                        }else{

                            # validate phone
                            $validateDistrictHeadPhone = DistrictHead::where('phone', $phone)->first();

                            if($validateDistrictHeadPhone && $validateDistrictHeadPhone->id != $district_head_id){
                                $error = Session::flash('error', 'Phone number already registered with another district head.');
                                return redirect()->back()->with($error);
                            }else{

                                # validate email
                                $validateDistrictHeadEmail = DistrictHead::where('email', $email)->first();

                                if($validateDistrictHeadEmail && $validateDistrictHeadEmail->id != $district_head_id){
                                    $error = Session::flash('error', 'Email already registered with another district head.');
                                    return redirect()->back()->with($error);
                                }else{

                                    try{
            
                                        # update profile
                                        $updateProfile = DistrictHead::find($district_head_id)->update([
                                            'fullname' => $fullname,
                                            'gender' => $gender,
                                            'dob' => $dob,
                                            'phone' => $phone,
                                            'email' => $email,
                                            'address' => $address
                                        ]);

                                        # set session due email update
                                        $setSession = Session::put('district_head', $email);
            
                                        $success = Session::flash('success', 'Profile updated successfully.');
                                        return redirect()->to('district/profile')->with($success);
                                    }catch(\Exception $ex){
                                        $error = Session::flash('error', 'Sorry, profile update could not be completed.');
                                        return redirect()->back()->with($error);
                                    }
                                }
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

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                $rules = [
                    'old_password' => 'required',
                    'new_password' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator);
                }else{

                    # validate district head
                    $validateDistrictHead = DistrictHead::where('email', $loggedInDistrictHead)->first();

                    if(!$validateDistrictHead){
                        $error = Session::flash('error', 'Sorry, you are not authorized.');
                        return redirect()->back()->with($error);
                    }else{

                        # collect data
                        $old_password = $request->old_password;
                        $new_password = $request->new_password;

                        try{

                            # compar passwords
                            $previousPassword = $validateDistrictHead->password;
                            $checkPassword = Hash::check($old_password, $previousPassword);

                            if(!$checkPassword){
                                $error = Session::flash('error', 'Invalid old password supplied.');
                                return redirect()->back()->with($error);
                            }else{

                                # hash new password
                                $password = Hash::make($new_password);

                                # update profile
                                $updateProfile = DistrictHead::find($validateDistrictHead->id)->update([
                                    'password' => $password
                                ]);
    
                                $success = Session::flash('success', 'Password updated successfully.');
                                return redirect()->to('district/profile')->with($success);
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

    # register municipal head page
    public function registerMunicipalHeadPage(Request $request) {
        
        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();
                
                # municipals
                $municipals = Municipal::where('district_id', $district_head->district_id)->get();

                return view::make('district/municipal_head')->with([
                    'district_head' => $district_head,
                    'municipals' => $municipals
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # register new municipal head
    public function registerMunicipalHead(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();
                
                # rules
                $rules = [
                    'fullname' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    'picture' => 'required|image|mimes:jpeg,png,jpg|max:4048',
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',                    
                    'municipal_id' => 'required'                  
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    # collect data
                    $fullname = $request->fullname;
                    $gender = $request->gender;
                    $dob = $request->dob;
                    $email = $request->email;
                    $phone = $request->phone;
                    $address = $request->address;
                    $password = Hash::make('123456');
                    $municipal_id = $request->municipal_id;

                    # encode image
                    $picture = $request->picture;
                    $filename = file_get_contents($picture);
                    $encode_image = base64_encode($filename);

                    # validate municipal head existence
                    $validate_municipal_head = MunicipalHead::where('fullname', $fullname)->where('gender', $gender)->where('dob', $dob)->where('municipal_id', $municipal_id)->first();

                    if($validate_municipal_head){
                        $error = Session::flash('error', 'Duplicate municipal head is not allowed.');
                        return redirect()->back()->withInput()->with($error);
                    }else{

                        # validate municipal head email
                        $validate_municipal_head_email = MunicipalHead::where('email', $email)->first();

                        if($validate_municipal_head_email){
                            $error = Session::flash('error', 'A Municipal head with this email already exist.');
                            return redirect()->back()->withInput()->with($error);
                        }else{

                            # validate municipal head phone 
                            $validate_municipal_head_phone = MunicipalHead::where('phone', $phone)->first();

                            if($validate_municipal_head_phone){
                                $error = Session::flash('error', 'A Municipal head with this phone number already exist.');
                                return redirect()->back()->withInput()->with($error);
                            }else{

                                try{
    
                                    # create new municipal head account
                                    $create_municipal_head = MunicipalHead::create([
                                        'fullname' => $fullname,
                                        'gender' => $gender,
                                        'dob' => $dob,                                        
                                        'picture' => $encode_image,
                                        'email' => $email,
                                        'phone' => $phone,
                                        'address' => $address,
                                        'password' => $password,
                                        'municipal_id' => $municipal_id
                                    ]);
    
                                    $success = Session::flash('success', 'Municipal head registered successfully.');
                                    return redirect()->to('district/municipal/head')->with($success);
                                }catch(\Exception $ex){
                                    $error = Session::flash('error', 'Sorry, municipal head registration failed. Try again.');
                                    return redirect()->back()->withInput()->with($error);
                                }
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

    # manage municipal heads
    public function manageMunicipalHeads(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # municipals associated with district
                $municipals = Municipal::select('id')->where('district_id', $district_head->id)->get();

                # municipal heads
                $municipal_heads = MunicipalHead::select('municipals.name as municipal', 'municipal_heads.fullname', 'municipal_heads.gender', 'municipal_heads.dob', 'municipal_heads.picture', 'municipal_heads.email', 'municipal_heads.phone', 'municipal_heads.address', 'municipal_heads.municipal_id', 'municipal_heads.id')->leftJoin('municipals', 'municipals.id', '=', 'municipal_heads.municipal_id')->whereIn('municipal_heads.municipal_id', $municipals)->orderBy('municipal_heads.created_at', 'DESC')->get();                

                return view::make('district/manage_municipal_heads')->with([
                    'district_head' => $district_head,
                    'municipal_heads' => $municipal_heads
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # edit municipal head
    public function editMunicipalHead(Request $request, $id) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # municipal head id
                $municipal_head_id = $request->id;
                
                # edit municipal head
                $edit = MunicipalHead::findOrFail($municipal_head_id);

                # municipals
                $municipals = Municipal::where('district_id', $district_head->district_id)->get();

                return view::make('district/municipal_head')->with([
                    'district_head' => $district_head,
                    'edit' => $edit,
                    'municipals' => $municipals
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update municipal head
    public function updateMunicipalHead(Request $request, $id) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();
                
                # rules
                $rules = [
                    'fullname' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',                
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',                    
                    'municipal_id' => 'required'                  
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    # collect data
                    $fullname = $request->fullname;
                    $gender = $request->gender;
                    $dob = $request->dob;
                    $email = $request->email;
                    $phone = $request->phone;
                    $address = $request->address;                    
                    $municipal_id = $request->municipal_id;
                    $municipal_head_id = $request->id;

                    # check if form has a new image
                    if($request->hasFile('picture')){

                        $rules = [
                            'picture' => 'required|image|mimes:jpeg,png,jpg|max:4048',
                        ];

                        # validator
                        $validator = Validator::make($request->all(), $rules);

                        if($validator->fails()){
                            return redirect()->back()->withInput()->withError($validator);
                        }else{

                            # encode image
                            $picture = $request->picture;
                            $filename = file_get_contents($picture);
                            $encode_image = base64_encode($filename);

                            # validate municipal head existence
                            $validate_municipal_head = MunicipalHead::where('fullname', $fullname)->where('gender', $gender)->where('dob', $dob)->where('municipal_id', $municipal_id)->first();

                            if($validate_municipal_head && $validate_municipal_head->id != $municipal_head_id){
                                $error = Session::flash('error', 'Duplicate municipal head is not allowed.');
                                return redirect()->back()->withInput()->with($error);
                            }else{

                                # validate municipal head email
                                $validate_municipal_head_email = MunicipalHead::where('email', $email)->first();

                                if($validate_municipal_head_email && $validate_municipal_head_email->id != $municipal_head_id){
                                    $error = Session::flash('error', 'A Municipal head with this email already exist.');
                                    return redirect()->back()->withInput()->with($error);
                                }else{

                                    # validate municipal head phone 
                                    $validate_municipal_head_phone = MunicipalHead::where('phone', $phone)->first();

                                    if($validate_municipal_head_phone && $validate_municipal_head_phone->id != $municipal_head_id){
                                        $error = Session::flash('error', 'A Municipal head with this phone number already exist.');
                                        return redirect()->back()->withInput()->with($error);
                                    }else{

                                        try{
            
                                            # update municipal head account
                                            $update_municipal_head = MunicipalHead::find($municipal_head_id)->update([
                                                'fullname' => $fullname,
                                                'gender' => $gender,
                                                'dob' => $dob,                                        
                                                'picture' => $encode_image,
                                                'email' => $email,
                                                'phone' => $phone,
                                                'address' => $address,                                                
                                                'municipal_id' => $municipal_id,
                                            ]);
            
                                            $success = Session::flash('success', 'Municipal head updated successfully.');
                                            return redirect()->to('district/municipal/head')->with($success);
                                        }catch(\Exception $ex){
                                            $error = Session::flash('error', 'Sorry, municipal head update failed. Try again.');
                                            return redirect()->back()->withInput()->with($error);
                                        }
                                    }
                                }
                            }
                        }
                    }else{
    
                        # validate municipal head existence
                        $validate_municipal_head = MunicipalHead::where('fullname', $fullname)->where('gender', $gender)->where('dob', $dob)->where('municipal_id', $municipal_id)->first();

                        if($validate_municipal_head && $validate_municipal_head->id != $municipal_head_id){
                            $error = Session::flash('error', 'Duplicate municipal head is not allowed.');
                            return redirect()->back()->withInput()->with($error);
                        }else{

                            # validate municipal head email
                            $validate_municipal_head_email = MunicipalHead::where('email', $email)->first();

                            if($validate_municipal_head_email && $validate_municipal_head_email->id != $municipal_head_id){
                                $error = Session::flash('error', 'A Municipal head with this email already exist.');
                                return redirect()->back()->withInput()->with($error);
                            }else{

                                # validate municipal head phone 
                                $validate_municipal_head_phone = MunicipalHead::where('phone', $phone)->first();

                                if($validate_municipal_head_phone && $validate_municipal_head_phone->id != $municipal_head_id){
                                    $error = Session::flash('error', 'A Municipal head with this phone number already exist.');
                                    return redirect()->back()->withInput()->with($error);
                                }else{

                                    try{
        
                                        # update municipal head account
                                        $update_municipal_head = MunicipalHead::find($municipal_head_id)->update([
                                            'fullname' => $fullname,
                                            'gender' => $gender,
                                            'dob' => $dob,                                                                                    
                                            'email' => $email,
                                            'phone' => $phone,
                                            'address' => $address,                                                
                                            'municipal_id' => $municipal_id,
                                        ]);
        
                                        $success = Session::flash('success', 'Municipal head updated successfully.');
                                        return redirect()->to('district/municipal/head')->with($success);
                                    }catch(\Exception $ex){
                                        $error = Session::flash('error', 'Sorry, municipal head update failed. Try again.');
                                        return redirect()->back()->withInput()->with($error);
                                    }
                                }
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
    
    # delete municipal head
    public function deleteMunicipalHead(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                if($request->ajax()){

                    # municipal head id
                    $municipal_head_id = $request->id;

                    # validate municipal head
                    $validate_municipal_head = MunicipalHead::find($municipal_head_id);

                    if($validate_municipal_head){

                        try{

                            # delete municipal head
                            $deleteMunicipaltHead = $validate_municipal_head->delete();

                            $success = 'Municipal head deleted successfully.';
                            return response()->json(['status' => 200, 'message' => $success]);
                        }catch(\Exception $ex){
                            $error = 'Sorry, municipal head could not be deleted. Try again.';
                            return response()->json(['status' => 403, 'message' => $error]);
                        }
                    }else{
                        $error = 'Sorry, municipal head could not be validated.';
                        return response()->json(['status' => 403, 'message' => $error]);
                    }
                }
            }else{
                $error = 'Sorry, you are not authorized to perform this operation.';
                return response()->json(['status' => 403, 'message' => $error]);
            }
        }catch(\Exception $ex){
            $error = 'Sorry, you are not authorized to perform this operation.';
            return response()->json(['status' => 403, 'message' => $error]);
        }
    }

    # citizen page
    public function citizenPage(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();
                
                # municipals
                $municipals = Municipal::where('district_id', $district_head->district_id)->get();

                return view::make('district/citizen')->with([
                    'district_head' => $district_head,
                    'municipals' => $municipals
                ]);               
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    } 

    # register citizen
    public function registerCitizen(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();
                
                $rules = [
                    'fullname' => 'required',
                    'picture' => 'required|image|mimes:jpeg,jpg,png|max:4084',
                    'email' => 'required',
                    'phone' => 'required',                    
                    'address' => 'required',
                    'municipal_id' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withInput()->withErrors($validator);
                }else{

                    # collect data
                    $fullname = $request->fullname;
                    $picture = $request->picture;
                    $email = $request->email;
                    $phone = $request->phone;
                    $address = $request->address;
                    $municipal = $request->municipal_id;
                    $password = Hash::make(123456);

                    # encode image
                    $picture = $request->picture;
                    $filename = file_get_contents($picture);
                    $encode_image = base64_encode($filename);

                    # validate citizen email
                    $validateCitizenEmail = User::where('email', $email)->first();

                    if($validateCitizenEmail){
                        $error = Session::flash('error', 'A citizen is already registered with this email.');
                        return redirect()->back()->withInput()->with($error);
                    }else{

                        # validate citizen phone
                        $validateCitizenPhone = User::where('phone', $phone)->first();

                        if($validateCitizenPhone){
                            $error = Session::flash('error', 'A citizen is already registered with this phone.');
                            return redirect()->back()->withInput()->with($error);
                        }else{

                            # validate citizen data
                            $validateCitizenData = User::where('fullname', $fullname)->where('email', $email)->where('phone', $phone)->first();

                            if($validateCitizenData){
                                $error = Session::flash('error', 'A citizen is already registered with the data supplied.');
                                return redirect()->back()->withInput()->with($error);
                            }else{

                                try{

                                    # create new citizen account
                                    $createCitizen = User::create([
                                        'fullname' => $fullname,                                        
                                        'picture' => $encode_image,
                                        'email' => $email,
                                        'phone' => $phone,
                                        'password' => $password,
                                        'address' => $address,
                                        'role' => 'user',
                                        'municipal_id' => $municipal
                                    ]);

                                    $success = Session::flash('success', 'Citizen registration successful.');
                                    return redirect()->to('district/citizen')->with($success);
                                }catch(\Exception $ex){
                                    $error = Session::flash('error', 'Sorry, citizen registration failed.');
                                    return redirect()->back()->withInput()->with($error);
                                }
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

    # manage citizens
    public function manageCitizens(Request $request) {
        
        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();


                # municipals associate with district
                $municipals = Municipal::select('id')->where('district_id', $district_head->district_id)->get();
                
                # citizens
                $citizens = User::select('users.fullname', 'users.picture', 'users.email', 'users.phone', 'users.address', 'users.id', 'municipals.name as municipal')->leftJoin('municipals', 'municipals.id', '=', 'users.municipal_id')->whereIn('users.municipal_id', $municipals)->orderBy('users.created_at', 'DESC')->get();

                return view::make('district/manage_citizens')->with([
                    'district_head' => $district_head,
                    'citizens' => $citizens
                ]);               
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # edit citizen
    public function editCitizen(Request $request, $id) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # citizen id
                $citizen_id = $request->id;

                # edit citizen
                $edit = User::findOrFail($citizen_id);

                # municipals
                $municipals = Municipal::where('district_id', $district_head->district_id)->get();

                return view::make('district/citizen')->with([
                    'district_head' => $district_head,
                    'edit' => $edit,
                    'municipals' => $municipals
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update citizen
    public function updateCitizen(Request $request, $id) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();
                
                $rules = [
                    'fullname' => 'required',                                        
                    'email' => 'required',
                    'phone' => 'required',                    
                    'address' => 'required',
                    'municipal_id' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withInput()->withErrors($validator);
                }else{

                    # collect data
                    $fullname = $request->fullname;                   
                    $email = $request->email;
                    $phone = $request->phone;
                    $address = $request->address;
                    $municipal = $request->municipal_id;                    

                    # citizen id
                    $citizen_id = $request->id;                    

                    # check if data contains image
                    if($request->hasFile('picture')){

                        $rules = [
                            'picture' => 'required|image|mimes:jpeg,jpg,png|max:4084',
                        ];

                        $validator = Validator::make($request->all(), $rules);
                        
                        if($validator->fails()){
                            return redirect()->back()->withInput()->withErrors($validator);
                        }else{

                            # validate citizen email
                            $validateCitizenEmail = User::where('email', $email)->first();

                            if($validateCitizenEmail && $validateCitizenEmail->id != $citizen_id){
                                $error = Session::flash('error', 'A citizen is already registered with this email.');
                                return redirect()->back()->withInput()->with($error);
                            }else{

                                # validate citizen phone
                                $validateCitizenPhone = User::where('phone', $phone)->first();

                                if($validateCitizenPhone && $validateCitizenPhone->id != $citizen_id){
                                    $error = Session::flash('error', 'A citizen is already registered with this phone.');
                                    return redirect()->back()->withInput()->with($error);
                                }else{

                                    # validate citizen data
                                    $validateCitizenData = User::where('fullname', $fullname)->where('email', $email)->where('phone', $phone)->first();

                                    if($validateCitizenData && $validateCitizenData->id != $citizen_id){
                                        $error = Session::flash('error', 'A citizen is already registered with the data supplied.');
                                        return redirect()->back()->withInput()->with($error);
                                    }else{

                                        try{

                                            # encode image
                                            $picture = $request->picture;
                                            $filename = file_get_contents($picture);
                                            $encode_image = base64_encode($filename);

                                            # create new citizen account
                                            $updateCitizen = User::find($citizen_id)->update([
                                                'fullname' => $fullname,
                                                'picture' => $encode_image,
                                                'email' => $email,
                                                'phone' => $phone,                                                
                                                'address' => $address,
                                                'role' => 'user',
                                                'municipal_id' => $municipal
                                            ]);

                                            $success = Session::flash('success', 'Citizen record updated successfully.');
                                            return redirect()->to('district/citizen')->with($success);
                                        }catch(\Exception $ex){
                                            $error = Session::flash('error', 'Sorry, citizen record update failed.');
                                            return redirect()->back()->withInput()->with($error);
                                        }
                                    }
                                }
                            }
                        }
                    }else{

                        # validate citizen email
                        $validateCitizenEmail = User::where('email', $email)->first();
    
                        if($validateCitizenEmail && $validateCitizenEmail->id != $citizen_id){
                            $error = Session::flash('error', 'A citizen is already registered with this email.');
                            return redirect()->back()->withInput()->with($error);
                        }else{
    
                            # validate citizen phone
                            $validateCitizenPhone = User::where('phone', $phone)->first();
    
                            if($validateCitizenPhone && $validateCitizenPhone->id != $citizen_id){
                                $error = Session::flash('error', 'A citizen is already registered with this phone.');
                                return redirect()->back()->withInput()->with($error);
                            }else{
    
                                # validate citizen data
                                $validateCitizenData = User::where('fullname', $fullname)->where('gender', $gender)->where('dob', $dob)->where('email', $email)->where('phone', $phone)->first();
    
                                if($validateCitizenData && $validateCitizenData->id != $citizen_id){
                                    $error = Session::flash('error', 'A citizen is already registered with the data supplied.');
                                    return redirect()->back()->withInput()->with($error);
                                }else{
    
                                    try{
    
                                        # create new citizen account
                                        $updateCitizen = User::find($citizen_id)->update([
                                            'fullname' => $fullname,
                                            'gender' => $gender,
                                            'dob' => $dob,                                            
                                            'email' => $email,
                                            'phone' => $phone,                                            
                                            'address' => $address,
                                            'role' => 'user',
                                            'municipal_id' => $municipal
                                        ]);
    
                                        $success = Session::flash('success', 'Citizen record updated successfully.');
                                        return redirect()->to('district/citizen')->with($success);
                                    }catch(\Exception $ex){
                                        $error = Session::flash('error', 'Sorry, citizen record update failed.');
                                        return redirect()->back()->withInput()->with($error);
                                    }
                                }
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

    # delete citizen
    public function deleteCitizen(Request $request) {

        try{

           # logged in distric head session
           $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                if($request->ajax()){

                    # citizen id
                    $citizen_id = $request->id;

                    # validate citizen
                    $validate_citizen = User::find($citizen_id);

                    if($validate_citizen){

                        # check for citizen's reports
                        $checkCitizenReports = Report::where('user_id', $citizen_id)->get();

                        if(count($checkCitizenReports)){
                            $error = 'Sorry, The selected citizen could not be deleted because of the report associated to his/her account.';
                            return response()->json(['status' => 403, 'message' => $error]);
                        }else{

                            try{
    
                                # delete citizen
                                $deleteCitizen = $validate_citizen->delete();
    
                                $success = 'Citizen deleted successfully.';
                                return response()->json(['status' => 200, 'message' => $success]);
                            }catch(\Exception $ex){
                                $error = 'Sorry, citizen could not be deleted. Try again.';
                                return response()->json(['status' => 403, 'message' => $error]);
                            }
                        }
                    }else{
                        $error = 'Sorry, citizen could not be validated.';
                        return response()->json(['status' => 403, 'message' => $error]);
                    }
                }
            }else{
                $error = 'Sorry, you are not authorized to perform this operation.';
                return response()->json(['status' => 403, 'message' => $error]);
            }
        }catch(\Exception $ex){
            $error = 'Sorry, you are not authorized to perform this operation.';
            return response()->json(['status' => 403, 'message' => $error]);
        }
    }

    # reports page
    public function reportsPage(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # get reports
                $reports = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'report_media.media_url', 'report_media.media_type')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->orderBy('reports.created_at', 'DESC')->paginate(8);

                return view::make('district/reports')->with([
                    'district_head' => $district_head,
                    'reports' => $reports
                ]);
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

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # verify
                $verify = Report::findOrFail($id);

                # report id
                $report_id = $request->id;

                # get report details
                $report_details = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'users.fullname', 'users.picture', 'report_media.media_url', 'report_media.media_type')->leftJoin('users', 'reports.user_id', '=', 'users.id')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->where('reports.id', $report_id)->first();

                return view::make('district/report_details')->with([
                    'district_head' => $district_head,
                    'report_details' => $report_details
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # approve report
    public function approveReport(Request $request) {

        try{
            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # report
                $report = $request->report;

                # verify report
                $verifyReport = Report::find($report);

                if($verifyReport){

                    # reporter
                    $reporter = $verifyReport->user_id;

                    # verify reporter
                    $verifyReporter = User::find($reporter);

                    if($verifyReporter){

                        # reporter municipal
                        $municipal = $verifyReporter->municipal_id;

                        # check if municipal has a registered agency
                        $checkMunicipalAgency = Agency::where('municipal_id', $municipal)->first();

                        if($checkMunicipalAgency){

                            try{
        
                                # update report status
                                $update = Report::find($report)->update([
                                    'status' => 'Approved'
                                ]);                                            
                                
                                return response()->json(['status' => 200, 'message' => 'Report pushed to agency successfully.']);
                            }catch(\Exception $ex){
                                return response()->json(['status' => 203, 'message' => 'Report could not be pushed to agency.']);
                            }
                        }else{
                            return response()->json(['status' => 203, 'message' => 'No agency is registered to handle this report in reporter\'s municipal.']);
                        }
                    }else{
                        return response()->json(['status' => 203, 'message' => 'Report verification failed.']);
                    }
                }else{
                    return response()->json(['status' => 203, 'message' => 'Report verification failed.']);
                }
            }else{
                return redirect()->to('/');
            }            
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # declince report
    public function declineReport(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # delete report
    public function deleteReport(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){

            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # agency page
    public function agencyPage(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();
                
                # municipals
                $municipals = Municipal::where('district_id', $district_head->district_id)->get();

                # agencies array
                $agencies = [];

                foreach($municipals as $municipal) {

                    # get registered agency             
                    $getAgency = Agency::select('agencies.id', 'municipals.name', 'agencies.municipal_id', 'agencies.agency_name', 'agencies.description', 'agencies.email', 'agencies.phone', 'agencies.location')->leftJoin('municipals', 'municipals.id', '=', 'agencies.municipal_id')->orderBy('agencies.created_at')->where('agencies.municipal_id', $municipal->id)->get();

                    if($getAgency == null){
                        continue;
                    }else{

                        foreach($getAgency as $agency){
                            
                            # push to array
                            array_push($agencies, $agency);
                        }
                    }
                }                            

                return view::make('district/agency')->with([
                    'district_head' => $district_head,
                    'agencies' => $agencies,
                    'municipals' => $municipals
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # register agency
    public function registerAgency(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # rules
                $rules = [
                    'municipal' => 'required',
                    'agency_name' => 'required',
                    'description' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'location' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    # collect data
                    $municipal = $request->municipal;
                    $agency_name = $request->agency_name;
                    $description = $request->description;
                    $email = $request->email;
                    $phone = $request->phone;
                    $location = $request->location;
                    $password = Hash::make('123456');
                    
                    # validate agency phones
                    $validateAgencyPhone = Agency::where('phone', $phone)->first();

                    if($validateAgencyPhone){
                        $error = Session::flash('error', 'An agency with this phone number already exist.');
                        return redirect()->back()->with($error)->withInput();
                    }else{

                        # validate agency email
                        $validateAgencyEmail = Agency::where('email', $email)->first();

                        if($validateAgencyEmail){
                            $error = Session::flash('error', 'An agency with this email already exist.');
                            return redirect()->back()->with($error)->withInput();
                        }else{

                            # validate agency data
                            $validateAgencyData = Agency::where('agency_name', $agency_name)->where('description', $description)->where('location', $location)->first();

                            if($validateAgencyData){
                                $error = Session::flash('error', 'An agency with the suppplied data already exist.');
                                return redirect()->back()->with($error)->withInput();
                            }else{

                                # register agency
                                $registerAgency = Agency::create([
                                    'municipal_id' => $municipal,
                                    'agency_name' => $agency_name,
                                    'description' => $description,
                                    'phone' => $phone,
                                    'email' => $email,
                                    'location' => $location,
                                    'password' => $password 
                                ]);
                                
                                # success
                                $success = Session::flash('success', 'Agency registered successfully.');
                                return redirect()->to('district/agency')->with($success);
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

    # edit agency
    public function editAgency(Request $request, $id) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # municipals
                $municipals = Municipal::where('district_id', $district_head->district_id)->get();

                # agencies array
                $agencies = [];

                foreach($municipals as $municipal) {

                    # get registered agency             
                    $getAgency = Agency::select('agencies.id', 'municipals.name', 'agencies.municipal_id', 'agencies.agency_name', 'agencies.description', 'agencies.email', 'agencies.phone', 'agencies.location')->leftJoin('municipals', 'municipals.id', '=', 'agencies.municipal_id')->orderBy('agencies.created_at')->where('agencies.municipal_id', $municipal->id)->get();

                    if($getAgency == null){
                        continue;
                    }else{

                        foreach($getAgency as $agency){
                            
                            # push to array
                            array_push($agencies, $agency);
                        }
                    }
                }

                # agency id
                $id = $request->id;

                # verify agency
                $verify = Agency::findOrFail($id);

                # collate data for edit
                $edit = Agency::select('agencies.id', 'municipals.id as municipal_id', 'municipals.name', 'agencies.municipal_id', 'agencies.agency_name', 'agencies.description', 'agencies.email', 'agencies.phone', 'agencies.location', )->leftJoin('municipals', 'municipals.id', '=', 'agencies.municipal_id')->where('agencies.id', $id)->first();

                return view::make('district/agency')->with([
                    'district_head' => $district_head,
                    'agencies' => $agencies,
                    'edit' => $edit,
                    'municipals' => $municipals
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update agency
    public function updateAgency(Request $request, $id) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                # rules
                $rules = [
                    'municipal' => 'required',
                    'agency_name' => 'required',
                    'description' => 'required',
                    'email' => 'required',
                    'phone' => 'required',
                    'location' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    # collect data
                    $municipal = $request->municipal;
                    $agency_name = $request->agency_name;
                    $description = $request->description;
                    $email = $request->email;
                    $phone = $request->phone;
                    $location = $request->location;
                    $password = Hash::make('123456');

                    # agency id
                    $agency_id = $request->id;
                    
                    # validate agency phones
                    $validateAgencyPhone = Agency::where('phone', $phone)->first();

                    if($validateAgencyPhone && $validateAgencyPhone->id != $agency_id){
                        $error = Session::flash('error', 'An agency with this phone number already exist.');
                        return redirect()->back()->with($error)->withInput();
                    }else{

                        # validate agency email
                        $validateAgencyEmail = Agency::where('email', $email)->first();

                        if($validateAgencyEmail && $validateAgencyEmail->id != $agency_id){
                            $error = Session::flash('error', 'An agency with this email already exist.');
                            return redirect()->back()->with($error)->withInput();
                        }else{

                            # validate agency data
                            $validateAgencyData = Agency::where('municipal_id', $municipal)->where('agency_name', $agency_name)->where('description', $description)->where('location', $location)->first();

                            if($validateAgencyData && $validateAgencyData->id != $agency_id){
                                $error = Session::flash('error', 'An agency with the suppplied data already exist.');
                                return redirect()->back()->with($error)->withInput();
                            }else{

                                # update agency
                                $updateAgency = Agency::find($agency_id)->update([
                                    'municipal_id' => $municipal,
                                    'agency_name' => $agency_name,
                                    'description' => $description,
                                    'phone' => $phone,
                                    'email' => $email,
                                    'location' => $location, 
                                ]);
                                
                                # success
                                $success = Session::flash('success', 'Agency record updated successfully.');
                                return redirect()->to('district/agency')->with($success);
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

    # delete agency
    public function deleteAgency(Request $request) {

        try{

            # logged in distric head session
            $loggedInDistrictHead = $request->session()->get('district_head');

            if(!empty($loggedInDistrictHead)){
                
                # district head data
                $district_head = DistrictHead::where('email', $loggedInDistrictHead)->first();

                if(!$district_head){
                    return response()->json(['status' => 403, 'message' => 'Sorry, you are not authorized.']);
                }else{

                    # agency id
                    $agency_id = $request->id;

                    # validate agency
                    $validateAgency = Agency::find($agency_id);

                    if($validateAgency){

                        # delete agency
                        $deleteAgency = $validateAgency->delete();

                        return response()->json(['status' => 200, 'message' => 'Agency deleted successfully.']);
                    }else{
                        return response()->json(['status' => 203, 'message' => 'Agency could not be validated.']);
                    }
                }
            }else{
                return response()->json(['status' => 403, 'message' => 'Sorry, you are not authorized.']);
            }
        }catch(\Exception $ex){
            return response()->json(['status' => 403, 'message' => 'Sorry, you are not authorized.']);
        }
    }

    # district logout
    public function logout(Request $request) {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->to('/');
    }
}
