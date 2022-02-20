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

class MunicipalController extends Controller
{
    # municipal head dashboard
    public function home(Request $request) {

        try{

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                # municipals
                $municipal_citizens = User::select('id')->where('municipal_id', $municipal_head->municipal_id)->get(); 

                # registered citizens
                $citizens = User::where('municipal_id', $municipal_head->municipal_id)->count();

                # total reports
                $total_reports = Report::whereIn('user_id', $municipal_citizens)->count();                

                return view::make('municipal/home')->with([
                    'municipal_head' => $municipal_head,
                    'citizens' => $citizens,
                    'total_reports' => $total_reports,
                ]);
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # municipal head profile page
    public function profile(Request $request) {

        try{

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                return view::make('municipal/profile')->with([
                    'municipal_head' => $municipal_head,
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

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

                    # validate municipal head
                    $validateMunicipalHead = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                    if(!$validateMunicipalHead){
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

                        # municipal head id
                        $municipal_head_id = $validateMunicipalHead->id;

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
                                $validateMunicipalHeadPhone = MunicipalHead::where('phone', $phone)->first();

                                if($validateMunicipalHeadPhone && $validateMunicipalHeadPhone->id != $municipal_head_id){
                                    $error = Session::flash('error', 'Phone number already registered with another municipal head.');
                                    return redirect()->back()->with($error);
                                }else{

                                    # validate email
                                    $validateMunicipalHeadEmail = MunicipalHead::where('email', $email)->first();

                                    if($validateMunicipalHeadEmail && $validateMunicipalHeadEmail->id != $municipal_head_id){
                                        $error = Session::flash('error', 'Email already registered with another municipal head.');
                                        return redirect()->back()->with($error);
                                    }else{

                                        try{
        
                                            # encode image
                                            $picture = $request->picture;
                                            $filename = file_get_contents($picture);
                                            $encode_image = base64_encode($filename);
                
                                            # update profile
                                            $updateProfile = MunicipalHead::find($municipal_head_id)->update([
                                                'fullname' => $fullname,
                                                'gender' => $gender,
                                                'dob' => $dob,
                                                'phone' => $phone,
                                                'email' => $email,
                                                'address' => $address,
                                                'picture' => 'data:image/png;base64,'.$encode_image
                                            ]);

                                            # set session due email update
                                            $setSession = Session::put('municipal_head', $email);
                
                                            $success = Session::flash('success', 'Profile updated successfully.');
                                            return redirect()->to('municipal/profile')->with($success);
                                        }catch(\Exception $ex){
                                            $error = Session::flash('error', 'Sorry, profile update could not be completed.');
                                            return redirect()->back()->with($error);
                                        }
                                    }
                                }
                            }
                        }else{

                            # validate phone
                            $validateMunicipalHeadPhone = MunicipalHead::where('phone', $phone)->first();

                            if($validateMunicipalHeadPhone && $validateMunicipalHeadPhone->id != $municipal_head_id){
                                $error = Session::flash('error', 'Phone number already registered with another municipal head.');
                                return redirect()->back()->with($error);
                            }else{

                                # validate email
                                $validateMunicipalHeadEmail = MunicipalHead::where('email', $email)->first();

                                if($validateMunicipalHeadEmail && $validateMunicipalHeadEmail->id != $municipal_head_id){
                                    $error = Session::flash('error', 'Email already registered with another municipal head.');
                                    return redirect()->back()->with($error);
                                }else{

                                    try{
            
                                        # update profile
                                        $updateProfile = MunicipalHead::find($municipal_head_id)->update([
                                            'fullname' => $fullname,
                                            'gender' => $gender,
                                            'dob' => $dob,
                                            'phone' => $phone,
                                            'email' => $email,
                                            'address' => $address
                                        ]);

                                        # set session due email update
                                        $setSession = Session::put('municipal_head', $email);
            
                                        $success = Session::flash('success', 'Profile updated successfully.');
                                        return redirect()->to('municipal/profile')->with($success);
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                $rules = [
                    'old_password' => 'required',
                    'new_password' => 'required'
                ];

                # validator
                $validator = Validator::make($request->all(), $rules);

                if($validator->fails()){
                    return redirect()->back()->withErrors($validator);
                }else{

                    # validate municipal head
                    $validateMunicipalHead = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                    if(!$validateMunicipalHead){
                        $error = Session::flash('error', 'Sorry, you are not authorized.');
                        return redirect()->back()->with($error);
                    }else{

                        # collect data
                        $old_password = $request->old_password;
                        $new_password = $request->new_password;

                        try{

                            # compare passwords
                            $previousPassword = $validateMunicipalHead->password;
                            $checkPassword = Hash::check($old_password, $previousPassword);

                            if(!$checkPassword){
                                $error = Session::flash('error', 'Invalid old password supplied.');
                                return redirect()->back()->with($error);
                            }else{

                                # hash new password
                                $password = Hash::make($new_password);

                                # update password
                                $updatePassword = MunicipalHead::find($validateMunicipalHead->id)->update([
                                    'password' => $password
                                ]);
    
                                $success = Session::flash('success', 'Password updated successfully.');
                                return redirect()->to('municipal/profile')->with($success);
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

    # citizen page
    public function citizenPage(Request $request) {

        try{

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();
                
                # municipal
                $municipal = Municipal::where('id', $municipal_head->municipal_id)->first();

                return view::make('municipal/citizen')->with([
                    'municipal_head' => $municipal_head,
                    'municipal' => $municipal
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();
                
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
                                    return redirect()->to('municipal/citizen')->with($success);
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                # municipal associated with municipal head
                $municipal = Municipal::select('id')->where('id', $municipal_head->municipal_id)->first();
                
                # citizens
                $citizens = User::select('users.fullname', 'users.picture', 'users.email', 'users.phone', 'users.address', 'users.id', 'municipals.name as municipal')->leftJoin('municipals', 'municipals.id', '=', 'users.municipal_id')->where('users.municipal_id', $municipal->id)->orderBy('users.created_at', 'DESC')->get();

                return view::make('municipal/manage_citizens')->with([
                    'municipal_head' => $municipal_head,
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){
                
                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                # citizen id
                $citizen_id = $request->id;

                # edit citizen
                $edit = User::findOrFail($citizen_id);

                # municipal
                $municipal = Municipal::where('id', $municipal_head->municipal_id)->first();

                return view::make('municipal/citizen')->with([
                    'municipal_head' => $municipal_head,
                    'edit' => $edit,
                    'municipal' => $municipal
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();
                
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
                                            return redirect()->to('municipal/citizen')->with($success);
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
                                        return redirect()->to('municipal/citizen')->with($success);
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){
                
                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                # get reports
                $reports = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'report_media.media_url', 'report_media.media_type')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->orderBy('reports.created_at', 'DESC')->paginate(8);

                return view::make('municipal/reports')->with([
                    'municipal_head' => $municipal_head,
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
            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

                # verify
                $verify = Report::findOrFail($id);

                # report id
                $report_id = $request->id;

                # get report details
                $report_details = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'users.fullname', 'users.picture', 'report_media.media_url', 'report_media.media_type')->leftJoin('users', 'reports.user_id', '=', 'users.id')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->where('reports.id', $report_id)->first();                

                return view::make('municipal/report_details')->with([
                    'municipal_head' => $municipal_head,
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
            
            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();

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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();
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

            # logged in municipal head session
            $loggedInMunicipalHead = $request->session()->get('municipal_head');

            if(!empty($loggedInMunicipalHead)){

                # municipal head data
                $municipal_head = MunicipalHead::where('email', $loggedInMunicipalHead)->first();
            }else{
                return redirect()->to('/');
            }
        }catch(\Exception $ex){
            return redirect()->to('/');
        }
    }

    # district logout
    public function logout(Request $request) {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->to('/');
    }
}
