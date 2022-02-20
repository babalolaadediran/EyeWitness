<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Agency;
use App\Report;
use App\Province;
use App\District;
use App\Municipal;
use App\ReportMedia;
use App\Administrator;
use App\MunicipalHead;
use App\DistrictHead;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    # admin dashboard
    public function home(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # registered municipal heads
                $municipal_heads = MunicipalHead::count();

                # registered district heads
                $district_heads = DistrictHead::count();

                # registered citizens
                $citizens = User::count();

                # total reports
                $total_reports = Report::count();

                # total district
                $total_district = District::count();

                # total municipal
                $total_municipal = Municipal::count();                            

                # municipals
                $municipals = Municipal::select('id', 'name')->orderBy('name', 'DESC')->get();

                return view::make('administrator/home')->with([
                    'admin' => $admin,
                    'municipal_heads' => $municipal_heads,
                    'district_heads' => $district_heads,
                    'citizens' => $citizens,
                    'total_reports' => $total_reports,
                    'total_district' => $total_district,
                    'total_municipal' => $total_municipal,
                    'municipals' => $municipals
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # admin profile page
    public function profile(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                return view::make('administrator/profile')->with([
                    'admin' => $admin,
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

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

                    # validate admin
                    $validateAdministrator = Administrator::where('email', $loggedInAdmin)->first();

                    if(!$validateAdministrator){
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

                        # admin id
                        $admin_id = $validateAdministrator->id;

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
                                $validateAdminPhone = Administrator::where('phone', $phone)->first();

                                if($validateAdminPhone && $validateAdminPhone->id != $admin_id){
                                    $error = Session::flash('error', 'Phone number already registered with another administrator.');
                                    return redirect()->back()->with($error);
                                }else{

                                    # validate email
                                    $validateAdminEmail = Administrator::where('email', $email)->first();

                                    if($validateAdminEmail && $validateAdminEmail->id != $admin_id){
                                        $error = Session::flash('error', 'Email already registered with another administrator.');
                                        return redirect()->back()->with($error);
                                    }else{

                                        try{
        
                                            # encode image
                                            $picture = $request->picture;
                                            $filename = file_get_contents($picture);
                                            $encode_image = base64_encode($filename);
                
                                            # update profile
                                            $updateProfile = Administrator::find($validateAdministrator->id)->update([
                                                'fullname' => $fullname,
                                                'gender' => $gender,
                                                'dob' => $dob,
                                                'phone' => $phone,
                                                'email' => $email,
                                                'address' => $address,
                                                'picture' => $encode_image
                                            ]);

                                            # set session due email update
                                            $setSession = Session::put('administrator', $email);
                
                                            $success = Session::flash('success', 'Profile updated successfully.');
                                            return redirect()->to('administrator/profile')->with($success);
                                        }catch(\Exception $ex){
                                            $error = Session::flash('error', 'Sorry, profile update could not be completed.');
                                            return redirect()->back()->with($error);
                                        }
                                    }
                                }
                            }
                        }else{

                            # validate phone
                            $validateAdminPhone = Administrator::where('phone', $phone)->first();

                            if($validateAdminPhone && $validateAdminPhone->id != $admin_id){
                                $error = Session::flash('error', 'Phone number already registered with another administrator.');
                                return redirect()->back()->with($error);
                            }else{

                                # validate email
                                $validateAdminEmail = Administrator::where('email', $email)->first();

                                if($validateAdminEmail && $validateAdminEmail->id != $admin_id){
                                    $error = Session::flash('error', 'Email already registered with another administrator.');
                                    return redirect()->back()->with($error);
                                }else{

                                    try{
            
                                        # update profile
                                        $updateProfile = Administrator::find($validateAdministrator->id)->update([
                                            'fullname' => $fullname,
                                            'gender' => $gender,
                                            'dob' => $dob,
                                            'phone' => $phone,
                                            'email' => $email,
                                            'address' => $address
                                        ]);

                                        # set session due email update
                                        $setSession = Session::put('administrator', $email);
            
                                        $success = Session::flash('success', 'Profile updated successfully.');
                                        return redirect()->to('administrator/profile')->with($success);
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                $rules = [
                    'old_password' => 'required',
                    'new_password' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator);
                }else{

                    # validate admin
                    $validateAdministrator = Administrator::where('email', $loggedInAdmin)->first();

                    if(!$validateAdministrator){
                        $error = Session::flash('error', 'Sorry, you are not authorized.');
                        return redirect()->back()->with($error);
                    }else{

                        # collect data
                        $old_password = $request->old_password;
                        $new_password = $request->new_password;

                        try{

                            # compare passwords
                            $previousPassword = $validateAdministrator->password;
                            $checkPassword = Hash::check($old_password, $previousPassword);

                            if(!$checkPassword){
                                $error = Session::flash('error', 'Invalid old password supplied.');
                                return redirect()->back()->with($error);
                            }else{

                                # hash new password
                                $password = Hash::make($new_password);

                                # update profile
                                $updateProfile = Administrator::find($validateAdministrator->id)->update([
                                    'password' => $password
                                ]);
    
                                $success = Session::flash('success', 'Password updated successfully.');
                                return redirect()->to('administrator/profile')->with($success);
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

    # district page
    public function districtPage(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # districts
                $districts = District::orderBy('created_at', 'DESC')->get();                

                return view::make('administrator/district')->with([
                    'admin' => $admin,
                    'districts' => $districts
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # create new district
    public function createNewDistrict(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # rules
                $rules = [
                    'district_name' => 'required',
                    'longitude' => 'required',
                    'latitude' => 'required',
                    'logo' => 'required|image|mimes:jpeg,png,jpg|max:4048'                    
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    # collect data
                    $name = $request->district_name;
                    $longitude = $request->longitude;
                    $latitude = $request->latitude;

                    # encode logo
                    $logo = $request->logo;
                    $filename = file_get_contents($logo);
                    $encode_image = base64_encode($filename);

                    # get province
                    $province = Province::first();

                    # validate district existence
                    $validate_district = District::where('name', $name)->first();

                    if($validate_district){
                        $error = Session::flash('error', 'A district with the supplied name already exist.');
                        return redirect()->back()->withInput()->with($error);
                    }else{

                        # validate disctrict complete data
                        $validate_district_data = District::where('name', $name)->where('longitude', $longitude)->where('latitude', $latitude)->first();

                        if($validate_district_data){
                            $error = Session::flash('error', 'A district with the data supplied already exist.');
                            return redirect()->back()->withInput()->with($error);
                        }else{

                            try{

                                # create new district
                                $create_district = District::create([
                                    'name' => $name,
                                    'longitude' => $longitude,
                                    'latitude' => $latitude,
                                    'logo' => $encode_image,
                                    'province_id' => $province->id
                                ]);

                                $success = Session::flash('success', 'District registered successfully.');
                                return redirect()->to('administrator/district')->with($success);
                            }catch(\Exception $ex){
                                $error = Session::flash('error', 'Sorry, district registration failed. Try again.'.$ex->getMessage());
                                return redirect()->back()->withInput()->with($error);
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

    # edit district 
    public function editDistrict(Request $request, $id) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # districts
                $districts = District::orderBy('created_at', 'ASC')->get();

                # district id
                $district_id = $request->id;
                
                # edit district
                $edit = District::findOrFail($district_id);

                return view::make('administrator/district')->with([
                    'admin' => $admin,
                    'districts' => $districts,
                    'edit' => $edit
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update district
    public function updateDistrict(Request $request, $id) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # rules
                $rules = [
                    'district_name' => 'required',
                    'longitude' => 'required',
                    'latitude' => 'required'                    
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{
                    

                    # collect data
                    $name = $request->district_name;
                    $longitude = $request->longitude;
                    $latitude = $request->latitude;
                    $district_id = $request->id;

                    # get province
                    $province = Province::first();

                    # check for logo
                    if($request->hasFile('logo')){

                        $rules = [
                            'logo' => 'required|image|mimes:jpeg,png,jpg|max:4048'
                        ];

                        # validator
                        $validator = Validator::make($request->all(), $rules);

                        if($validator->fails()){
                            return redirect()->back()->withErrors($validator);
                        }else{

                            # validate district existence
                            $validate_district = District::where('name', $name)->first();

                            if($validate_district && $validate_district->id != $district_id){
                                $error = Session::flash('error', 'A district with the supplied name already exist.');
                                return redirect()->back()->with($error);
                            }else{

                                # validate disctrict complete data
                                $validate_district_data = District::where('name', $name)->where('longitude', $longitude)->where('latitude', $latitude)->first();

                                if($validate_district_data && $validate_district_data->id != $district_id){
                                    $error = Session::flash('error', 'A district with the data supplied already exist.');
                                    return redirect()->back()->with($error);
                                }else{

                                    # encode logo
                                    $logo = $request->logo;
                                    $filename = file_get_contents($logo);
                                    $encode_image = base64_encode($filename);

                                    try{

                                        # update  district
                                        $create_district = District::find($district_id)->update([
                                            'name' => $name,
                                            'longitude' => $longitude,
                                            'latitude' => $latitude,
                                            'logo' => $encode_image,                                    
                                        ]);

                                        $success = Session::flash('success', 'District updated successfully.');
                                        return redirect()->to('administrator/district')->with($success);
                                    }catch(\Exception $ex){
                                        $error = Session::flash('error', 'Sorry, district update failed. Try again.');
                                        return redirect()->back()->withInput()->with($error);
                                    }
                                }
                            }
                        }
                    }else{

                        # validate district existence
                        $validate_district = District::where('name', $name)->first();
    
                        if($validate_district && $validate_district->id != $district_id){
                            $error = Session::flash('error', 'A district with the supplied name already exist.');
                            return redirect()->back()->with($error);
                        }else{
    
                            # validate disctrict complete data
                            $validate_district_data = District::where('name', $name)->where('longitude', $longitude)->where('latitude', $latitude)->first();
    
                            if($validate_district_data && $validate_district_data->id != $district_id){
                                $error = Session::flash('error', 'A district with the data supplied already exist.');
                                return redirect()->back()->with($error);
                            }else{
    
                                try{
    
                                    # update  district
                                    $create_district = District::find($district_id)->update([
                                        'name' => $name,
                                        'longitude' => $longitude,
                                        'latitude' => $latitude,                                    
                                    ]);
    
                                    $success = Session::flash('success', 'District updated successfully.');
                                    return redirect()->to('administrator/district')->with($success);
                                }catch(\Exception $ex){
                                    $error = Session::flash('error', 'Sorry, district update failed. Try again.');
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

    # delete district
    public function deleteDistrict(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                if($request->ajax()){

                    # district id
                    $district_id = $request->id;

                    # validate district 
                    $validate_district = District::find($district_id);

                    if($validate_district){

                        try{

                            # validate id district has an account for district head
                            $validateDistrictHead = DistrictHead::where('district_id', $district_id)->first();

                            if($validateDistrictHead){
                                $error = 'Sorry, district could not be deleted because it has a district head account.';
                                return response()->json(['status' => 403, 'message' => $error]);
                            }else{

                                # delete district
                                $deleteDistrict = $validate_district->delete();
    
                                $success = 'District deleted successfully.';
                                return response()->json(['status' => 200, 'message' => $success]);
                            }
                        }catch(\Exception $ex){
                            $error = 'Sorry, district could not be deleted. Try again.';
                            return response()->json(['status' => 403, 'message' => $error]);
                        }
                    }else{
                        $error = 'Sorry, district could not be validated.';
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

    # register district head page
    public function registerDistrictHeadPage(Request $request) {
        
        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # district heades
                $district_heads = DistrictHead::orderBy('created_at', 'DESC')->get();
                
                # districts
                $districts = District::all();

                return view::make('administrator/district_head')->with([
                    'admin' => $admin,
                    'district_heads' => $district_heads,
                    'districts' => $districts
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # register new district head
    public function registerDistrictHead(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # rules
                $rules = [
                    'fullname' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',
                    'picture' => 'required|image|mimes:jpeg,png,jpg|max:4048',
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',                    
                    'district_id' => 'required'                  
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
                    $district_id = $request->district_id;

                    # encode image
                    $picture = $request->picture;
                    $filename = file_get_contents($picture);
                    $encode_image = base64_encode($filename);

                    # validate district head existence
                    $validate_district_head = DistrictHead::where('fullname', $fullname)->where('gender', $gender)->where('dob', $dob)->where('district_id', $district_id)->first();

                    if($validate_district_head){
                        $error = Session::flash('error', 'Duplicate district head is not allowed.');
                        return redirect()->back()->withInput()->with($error);
                    }else{

                        # validate disctrict head email
                        $validate_district_head_email = DistrictHead::where('email', $email)->first();

                        if($validate_district_head_email){
                            $error = Session::flash('error', 'A District head with this email already exist.');
                            return redirect()->back()->withInput()->with($error);
                        }else{

                            # validate district head phone 
                            $validate_district_head_phone = DistrictHead::where('phone', $phone)->first();

                            if($validate_district_head_phone){
                                $error = Session::flash('error', 'A District head with this phone number already exist.');
                                return redirect()->back()->withInput()->with($error);
                            }else{

                                try{
    
                                    # create new district head account
                                    $create_district = DistrictHead::create([
                                        'fullname' => $fullname,
                                        'gender' => $gender,
                                        'dob' => $dob,                                        
                                        'picture' => $encode_image,
                                        'email' => $email,
                                        'phone' => $phone,
                                        'address' => $address,
                                        'password' => $password,
                                        'district_id' => $district_id,
                                    ]);
    
                                    $success = Session::flash('success', 'District head registered successfully.');
                                    return redirect()->to('administrator/district/head')->with($success);
                                }catch(\Exception $ex){
                                    $error = Session::flash('error', 'Sorry, district head registration failed. Try again.');
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

    # manage district heads
    public function manageDistrictHeads(Request $request) {
        
        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # district heades
                $district_heads = DistrictHead::select('districts.name as district', 'district_heads.fullname', 'district_heads.gender', 'district_heads.dob', 'district_heads.picture', 'district_heads.email', 'district_heads.phone', 'district_heads.address', 'district_heads.id')->leftJoin('districts', 'districts.id', '=', 'district_heads.district_id')->orderBy('district_heads.created_at', 'DESC')->get();                
                
                # districts
                $districts = District::all();

                return view::make('administrator/manage_district_heads')->with([
                    'admin' => $admin,
                    'district_heads' => $district_heads,
                    'districts' => $districts
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # edit district head
    public function editDistrictHead(Request $request, $id) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # district heads
                $district_heads = DistrictHead::orderBy('created_at', 'DESC')->get();

                # district id
                $district_id = $request->id;
                
                # edit district head
                $edit = DistrictHead::findOrFail($district_id);

                # districts
                $districts = District::all();

                return view::make('administrator/district_head')->with([
                    'admin' => $admin,
                    'district_heads' => $district_heads,
                    'edit' => $edit,
                    'districts' => $districts
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update district head
    public function updateDistrictHead(Request $request, $id) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # rules
                $rules = [
                    'fullname' => 'required',
                    'gender' => 'required',
                    'dob' => 'required',                
                    'email' => 'required',
                    'phone' => 'required',
                    'address' => 'required',                    
                    'district_id' => 'required'                  
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
                    $district_id = $request->district_id;
                    $district_head_id = $request->id;

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

                            # validate district head existence
                            $validate_district_head = DistrictHead::where('fullname', $fullname)->where('gender', $gender)->where('dob', $dob)->where('district_id', $district_id)->first();

                            if($validate_district_head && $validate_district_head->id != $district_head_id){
                                $error = Session::flash('error', 'Duplicate district head is not allowed.');
                                return redirect()->back()->withInput()->with($error);
                            }else{

                                # validate disctrict head email
                                $validate_district_head_email = DistrictHead::where('email', $email)->first();

                                if($validate_district_head_email && $validate_district_head_email->id != $district_head_id){
                                    $error = Session::flash('error', 'A District head with this email already exist.');
                                    return redirect()->back()->withInput()->with($error);
                                }else{

                                    # validate district head phone 
                                    $validate_district_head_phone = DistrictHead::where('phone', $phone)->first();

                                    if($validate_district_head_phone && $validate_district_head_phone->id != $district_head_id){
                                        $error = Session::flash('error', 'A District head with this phone number already exist.');
                                        return redirect()->back()->withInput()->with($error);
                                    }else{

                                        try{
            
                                            # update district head account
                                            $create_district = DistrictHead::find($district_head_id)->update([
                                                'fullname' => $fullname,
                                                'gender' => $gender,
                                                'dob' => $dob,                                        
                                                'picture' => $encode_image,
                                                'email' => $email,
                                                'phone' => $phone,
                                                'address' => $address,                                                
                                                'district_id' => $district_id,
                                            ]);
            
                                            $success = Session::flash('success', 'District head updated successfully.');
                                            return redirect()->to('administrator/district/head')->with($success);
                                        }catch(\Exception $ex){
                                            $error = Session::flash('error', 'Sorry, district head update failed. Try again.');
                                            return redirect()->back()->withInput()->with($error);
                                        }
                                    }
                                }
                            }
                        }
                    }else{
    
                        # validate district head existence
                        $validate_district_head = DistrictHead::where('fullname', $fullname)->where('gender', $gender)->where('dob', $dob)->where('district_id', $district_id)->first();
    
                        if($validate_district_head && $validate_district_head->id != $district_head_id){
                            $error = Session::flash('error', 'Duplicate district head is not allowed.');
                            return redirect()->back()->withInput()->with($error);
                        }else{
    
                            # validate disctrict head email
                            $validate_district_head_email = DistrictHead::where('email', $email)->first();
    
                            if($validate_district_head_email && $validate_district_head_email->id != $district_head_id){
                                $error = Session::flash('error', 'A District head with this email already exist.');
                                return redirect()->back()->withInput()->with($error);
                            }else{
    
                                # validate district head phone 
                                $validate_district_head_phone = DistrictHead::where('phone', $phone)->first();
    
                                if($validate_district_head_phone && $validate_district_head_phone->id != $district_head_id){
                                    $error = Session::flash('error', 'A District head with this phone number already exist.');
                                    return redirect()->back()->withInput()->with($error);
                                }else{
    
                                    try{
        
                                        # create new district head account
                                        $create_district = DistrictHead::find($district_head_id)->update([
                                            'fullname' => $fullname,
                                            'gender' => $gender,
                                            'dob' => $dob,                                                                                    
                                            'email' => $email,
                                            'phone' => $phone,
                                            'address' => $address,                                            
                                            'district_id' => $district_id,
                                        ]);
        
                                        $success = Session::flash('success', 'District head updated successfully.');
                                        return redirect()->to('administrator/district/head')->with($success);
                                    }catch(\Exception $ex){
                                        $error = Session::flash('error', 'Sorry, district head update failed. Try again.');
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
    
    # delete district head
    public function deleteDistrictHead(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                if($request->ajax()){

                    # district head id
                    $district_head_id = $request->id;

                    # validate district head
                    $validate_district_head = DistrictHead::find($district_head_id);

                    if($validate_district_head){

                        try{

                            # delete district head
                            $deleteDistrictHead = $validate_district_head->delete();

                            $success = 'District head deleted successfully.';
                            return response()->json(['status' => 200, 'message' => $success]);
                        }catch(\Exception $ex){
                            $error = 'Sorry, district head could not be deleted. Try again.';
                            return response()->json(['status' => 403, 'message' => $error]);
                        }
                    }else{
                        $error = 'Sorry, district head could not be validated.';
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

    # municipal page
    public function municipalPage(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # municipals
                $municipals = Municipal::select('districts.name as district', 'municipals.name', 'municipals.id', 'municipals.longitude', 'municipals.latitude', 'municipals.logo')->leftJoin('districts', 'districts.id', '=', 'municipals.district_id')->orderBy('municipals.created_at', 'DESC')->get();
                
                # districts
                $districts = District::all();

                return view::make('administrator/municipal')->with([
                    'admin' => $admin,
                    'municipals' => $municipals,
                    'districts' => $districts
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # create new municipal
    public function createNewMunicipal(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # rules
                $rules = [
                    'municipal_name' => 'required',
                    'longitude' => 'required',
                    'latitude' => 'required',
                    'district_id' => 'required',
                    'logo' => 'required|image|mimes:jpeg,png,jpg|max:4048'                    
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    # collect data
                    $name = $request->municipal_name;
                    $longitude = $request->longitude;
                    $latitude = $request->latitude;
                    $district_id = $request->district_id;

                    # encode logo
                    $logo = $request->logo;
                    $filename = file_get_contents($logo);
                    $encode_image = base64_encode($filename);

                    # validate municipal existence
                    $validate_municipal = Municipal::where('name', $name)->first();

                    if($validate_municipal){
                        $error = Session::flash('error', 'A municipal with the supplied name already exist.');
                        return redirect()->back()->withInput()->with($error);
                    }else{

                        # validate municipal complete data
                        $validate_municipal_data = Municipal::where('name', $name)->where('longitude', $longitude)->where('latitude', $latitude)->first();

                        if($validate_municipal_data){
                            $error = Session::flash('error', 'A municipal with the data supplied already exist.');
                            return redirect()->back()->withInput()->with($error);
                        }else{

                            try{

                                # create new municipal
                                $create_municipal = Municipal::create([
                                    'name' => $name,
                                    'longitude' => $longitude,
                                    'latitude' => $latitude,
                                    'logo' => $encode_image,
                                    'district_id' => $district_id
                                ]);

                                $success = Session::flash('success', 'Municipal registered successfully.');
                                return redirect()->to('administrator/municipal')->with($success);
                            }catch(\Exception $ex){
                                $error = Session::flash('error', 'Sorry, municipal registration failed. Try again.');
                                return redirect()->back()->withInput()->with($error);
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

    # edit municipal 
    public function editMunicipal(Request $request, $id) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # municipals
                $municipals = Municipal::select('districts.name as district', 'municipals.name', 'municipals.id', 'municipals.longitude', 'municipals.latitude')->leftJoin('districts', 'districts.id', '=', 'municipals.district_id')->orderBy('municipals.created_at', 'ASC')->get();

                # districts
                $districts = District::all();

                # municipal id
                $municipal_id = $request->id;
                
                # edit district
                $edit = Municipal::findOrFail($municipal_id);

                return view::make('administrator/municipal')->with([
                    'admin' => $admin,
                    'municipals' => $municipals,
                    'districts' => $districts,
                    'edit' => $edit
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # update municipal
    public function updateMunicipal(Request $request, $id) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # rules
                $rules = [
                    'municipal_name' => 'required',
                    'longitude' => 'required',
                    'latitude' => 'required',                    
                    'district_id' => 'required'                    
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator)->withInput();
                }else{

                    # collect data
                    $name = $request->municipal_name;
                    $longitude = $request->longitude;
                    $latitude = $request->latitude;
                    $district_id = $request->district_id;
                    $municipal_id = $request->id;                                        

                    if($request->hasFile('logo')){

                        $rules = [
                            'logo' => 'required|image|mimes:jpeg,png,jpg|max:4048'
                        ];

                        # validator
                        $validator = Validator::make($request->all(), $rules);

                        if($validator->fails()){
                            return redirect()->back()->withErrors($validator);
                        }else{

                            # validate municipal existence
                            $validate_municipal = Municipal::where('name', $name)->first();

                            if($validate_municipal && $validate_municipal->id != $municipal_id){
                                $error = Session::flash('error', 'A municipal with the supplied name already exist.');
                                return redirect()->back()->with($error);
                            }else{

                                # validate municipal complete data
                                $validate_municipal_data = Municipal::where('name', $name)->where('longitude', $longitude)->where('latitude', $latitude)->first();

                                if($validate_municipal_data && $validate_municipal_data->id != $municipal_id){
                                    $error = Session::flash('error', 'A municipal with the data supplied already exist.');
                                    return redirect()->back()->with($error);
                                }else{

                                    # encode logo
                                    $logo = $request->logo;
                                    $filename = file_get_contents($logo);
                                    $encode_image = base64_encode($filename);

                                    try{

                                        # update  municipal
                                        $update_municipal = Municipal::find($municipal_id)->update([
                                            'name' => $name,
                                            'longitude' => $longitude,
                                            'latitude' => $latitude,
                                            'logo' => $encode_image,
                                            'district_id' => $district_id                                    
                                        ]);

                                        $success = Session::flash('success', 'Municipal updated successfully.');
                                        return redirect()->to('administrator/municipal')->with($success);
                                    }catch(\Exception $ex){
                                        $error = Session::flash('error', 'Sorry, municipal update failed. Try again.');
                                        return redirect()->back()->withInput()->with($error);
                                    }
                                }
                            }
                        }
                    }else{

                        # validate municipal existence
                        $validate_municipal = Municipal::where('name', $name)->first();
    
                        if($validate_municipal && $validate_municipal->id != $municipal_id){
                            $error = Session::flash('error', 'A municipal with the supplied name already exist.');
                            return redirect()->back()->with($error);
                        }else{
    
                            # validate municipal complete data
                            $validate_municipal_data = Municipal::where('name', $name)->where('longitude', $longitude)->where('latitude', $latitude)->first();
    
                            if($validate_municipal_data && $validate_municipal_data->id != $municipal_id){
                                $error = Session::flash('error', 'A municipal with the data supplied already exist.');
                                return redirect()->back()->with($error);
                            }else{
    
                                try{
    
                                    # update  municipal
                                    $update_municipal = Municipal::find($municipal_id)->update([
                                        'name' => $name,
                                        'longitude' => $longitude,
                                        'latitude' => $latitude,
                                        'district_id' => $district_id                                    
                                    ]);
    
                                    $success = Session::flash('success', 'Municipal updated successfully.');
                                    return redirect()->to('administrator/municipal')->with($success);
                                }catch(\Exception $ex){
                                    $error = Session::flash('error', 'Sorry, municipal update failed. Try again.');
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

    # delete municipal
    public function deleteMunicipal(Request $request) {

        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                if($request->ajax()){

                    # municipal id
                    $municipal_id = $request->id;

                    # validate municipal 
                    $validate_municipal = Municipal::find($municipal_id);

                    if($validate_municipal){

                        try{

                            # validate if municipal has an account for municipal head
                            $validateMunicipalHead = MunicipalHead::where('municipal_id', $municipal_id)->first();

                            if($validateMunicipalHead){
                                $error = 'Sorry, municipal could not be deleted because it has a municipal head account.';
                                return response()->json(['status' => 403, 'message' => $error]);
                            }else{

                                # validate if municipal has users
                                $validateMunicipalUsers = User::where('municipal_id', $municipal_id)->first();

                                if($validateMunicipalUsers){
                                    $error = 'Sorry, municipal could not be deleted because it has registered users.';
                                    return response()->json(['status' => 403, 'message' => $error]);
                                }else{

                                    # delete municipal
                                    $deleteMunicipal = $validate_municipal->delete();
        
                                    $success = 'Municipal deleted successfully.';
                                    return response()->json(['status' => 200, 'message' => $success]);
                                }
                            }
                        }catch(\Exception $ex){
                            $error = 'Sorry, municipal could not be deleted. Try again.';
                            return response()->json(['status' => 403, 'message' => $error]);
                        }
                    }else{
                        $error = 'Sorry, municipal could not be validated.';
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

    # register municipal head page
    public function registerMunicipalHeadPage(Request $request) {
        
        try{

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # municipal heads
                $municipal_heads = MunicipalHead::orderBy('created_at', 'DESC')->get();
                
                # municipals
                $municipals = Municipal::all();

                return view::make('administrator/municipal_head')->with([
                    'admin' => $admin,
                    'municipal_heads' => $municipal_heads,
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
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
                                    return redirect()->to('administrator/municipal/head')->with($success);
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # municipal heads
                $municipal_heads = MunicipalHead::select('municipals.name as municipal', 'municipal_heads.fullname', 'municipal_heads.gender', 'municipal_heads.dob', 'municipal_heads.picture', 'municipal_heads.email', 'municipal_heads.phone', 'municipal_heads.address', 'municipal_heads.municipal_id', 'municipal_heads.id')->leftJoin('municipals', 'municipals.id', '=', 'municipal_heads.municipal_id')->orderBy('municipal_heads.created_at', 'DESC')->get();                

                return view::make('administrator/manage_municipal_heads')->with([
                    'admin' => $admin,
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # municipal head id
                $municipal_head_id = $request->id;
                
                # edit municipal head
                $edit = MunicipalHead::findOrFail($municipal_head_id);

                # municipals
                $municipals = Municipal::all();

                return view::make('administrator/municipal_head')->with([
                    'admin' => $admin,
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
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
                                            return redirect()->to('administrator/municipal/head')->with($success);
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
                                        return redirect()->to('administrator/municipal/head')->with($success);
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # municipals
                $municipals = Municipal::all();

                return view::make('administrator/citizen')->with([
                    'admin' => $admin,
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
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
                                    return redirect()->to('administrator/citizen')->with($success);
                                }catch(\Exception $ex){
                                    $error = Session::flash('error', 'Sorry, citizen registration failed.'.$ex->getMessage());
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
                # municipals
                $municipals = Municipal::all();

                # citizens
                $citizens = User::select('users.fullname', 'users.picture', 'users.email', 'users.phone', 'users.address', 'users.id', 'municipals.name as municipal')->leftJoin('municipals', 'municipals.id', '=', 'users.municipal_id')->orderBy('users.created_at', 'DESC')->get();

                return view::make('administrator/manage_citizens')->with([
                    'admin' => $admin,
                    'municipals' => $municipals,
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){
                
                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # citizen id
                $citizen_id = $request->id;

                # edit citizen
                $edit = User::findOrFail($citizen_id);

                # municipals
                $municipals = Municipal::all();

                return view::make('administrator/citizen')->with([
                    'admin' => $admin,
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();
                
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
                                            return redirect()->to('administrator/citizen')->with($success);
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
                                $validateCitizenData = User::where('fullname', $fullname)->where('email', $email)->where('phone', $phone)->first();
    
                                if($validateCitizenData && $validateCitizenData->id != $citizen_id){
                                    $error = Session::flash('error', 'A citizen is already registered with the data supplied.');
                                    return redirect()->back()->withInput()->with($error);
                                }else{
    
                                    try{
    
                                        # create new citizen account
                                        $updateCitizen = User::find($citizen_id)->update([
                                            'fullname' => $fullname,              
                                            'email' => $email,
                                            'phone' => $phone,                                            
                                            'address' => $address,
                                            'role' => 'user',
                                            'municipal_id' => $municipal
                                        ]);
    
                                        $success = Session::flash('success', 'Citizen record updated successfully.');
                                        return redirect()->to('administrator/citizen')->with($success);
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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){
                
                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # get reports
                $reports = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'report_media.media_url', 'report_media.media_type')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->orderBy('reports.created_at', 'DESC')->paginate(8); 

                return view::make('administrator/reports')->with([
                    'admin' => $admin,
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
            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # verify
                $verify = Report::findOrFail($id);

                # report id
                $report_id = $request->id;

                # get report details
                $report_details = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'users.fullname', 'users.picture', 'report_media.media_url', 'report_media.media_type')->leftJoin('users', 'reports.user_id', '=', 'users.id')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->where('reports.id', $report_id)->first();                

                return view::make('administrator/report_details')->with([
                    'admin' => $admin,
                    'report_details' => $report_details
                ]);
            }else{
                return redirect()->to('/');
            }            
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # apporve report
    public function approveReport(Request $request) {

        try{
            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

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

            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){

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
            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){
                
                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # registered agencies
                $agencies = Agency::select('agencies.id', 'municipals.name', 'agencies.municipal_id', 'agencies.agency_name', 'agencies.description', 'agencies.email', 'agencies.phone', 'agencies.location')->leftJoin('municipals', 'municipals.id', '=', 'agencies.municipal_id')->orderBy('agencies.created_at')->get();                            

                # municipals
                $municipals = Municipal::all();

                return view::make('administrator/agency')->with([
                    'admin' => $admin,
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
            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){
                
                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

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
                            $validateAgencyData = Agency::where('municipal_id', $municipal)->where('agency_name', $agency_name)->where('description', $description)->where('location', $location)->first();

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
                                return redirect()->to('administrator/agency')->with($success);
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
            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){
                
                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                # registered agencies
                $agencies = Agency::select('agencies.id', 'municipals.name', 'agencies.municipal_id', 'agencies.agency_name', 'agencies.description', 'agencies.email', 'agencies.phone', 'agencies.location')->leftJoin('municipals', 'municipals.id', '=', 'agencies.municipal_id')->orderBy('agencies.created_at')->get();

                # municipals
                $municipals = Municipal::all();

                # agency id
                $id = $request->id;

                # verify agency
                $verify = Agency::findOrFail($id);

                # collate data for edit
                $edit = Agency::select('agencies.id', 'municipals.id as municipal_id', 'municipals.name', 'agencies.municipal_id', 'agencies.agency_name', 'agencies.description', 'agencies.email', 'agencies.phone', 'agencies.location')->leftJoin('municipals', 'municipals.id', '=', 'agencies.municipal_id')->where('agencies.id', $id)->first();                

                return view::make('administrator/agency')->with([
                    'admin' => $admin,
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
            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){
                
                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

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
                                return redirect()->to('administrator/agency')->with($success);
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
            # logged in admin session
            $loggedInAdmin = $request->session()->get('administrator');

            if(!empty($loggedInAdmin)){
                
                # admin data
                $admin = Administrator::where('email', $loggedInAdmin)->first();

                if(!$admin){
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

    # admin logout
    public function logout(Request $request) {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->to('/');        
    }
}
