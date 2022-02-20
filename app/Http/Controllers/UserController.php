<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Report;
use App\Municipal;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * User Login
     * 
     * @param \Illuminate\Http $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {

        # rules
        $rules = [
            'phone' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 400);
        } else {

            # verify user
            $verifyUser = User::where('phone', $request->phone)->first();

            if (!$verifyUser) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Invalid Login Credentials',
                ], 404);
            } else {

                # compare password
                $checkPassword = Hash::check($request->password, $verifyUser->password);

                if (!$checkPassword) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Invalid Login Credentials',
                    ], 404);
                } else {

                    return response()->json([
                        'status' => 200,
                        'data' => $verifyUser,
                        'message' => 'Login Successful'
                    ], 200);
                }
            }
        }
    }

    /**
     * Register
     * 
     * @param \Illuminate\Http $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {

        try {

            # rules 
            $rules = [
                'fullname' => 'required',
                'picture' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'municipal_id' => 'required',
            ];
    
            # validator
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            } else {
    
                # collate data
                $fullname = $request->fullname;
                $picture = $request->picture;
                $email = $request->email;
                $phone = $request->phone;
                $password = Hash::make($request->password);
                $municipal_id = $request->municipal_id;
    
                # check user email
                $checkUserEmail = User::where('email', $email)->first();
    
                if ($checkUserEmail) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Email already exist!'
                    ]);
                } else {
    
                    # check user phone
                    $checkUserPhone = User::where('phone', $phone)->first();
    
                    if ($checkUserPhone) {
                        return response()->json([
                            'status' => 400,
                            'message' => 'Phone number already exist!'
                        ]);
                    } else {
    
                        # create user account
                        User::create([
                            'fullname' => $fullname,
                            'picture' => $picture,
                            'email' => $email,
                            'phone' => $phone,
                            'password' => $password,
                            'role' => 'user',
                            'address' => '',
                            'municipal_id' => $municipal_id,
                        ]);
    
                        return response()->json([
                            'status' => 201,
                            'message' => 'Registration successful.'
                        ]);
                    }
                }
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Sorry, your request could not be completed!'.$ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Forgot Password
     * 
     * @param \Illuminate\Http $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request) {

        try {
            
            # rules
            $rules = [
                'email' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            } else {

                # collate data
                $email = $request->email;

                # verify user email
                $verifyUserEmail = User::where('email', $email)->first();

                if (!$verifyUserEmail) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'Sorry, you are unauthorized!'
                    ], 404);
                } else {

                    try {

                        # generate new password
                        $password = Str::random(6);
    
                        # mail data
                        $data = [
                            'fullname' => $verifyUserEmail->fullname,
                            'email' => $email,
                            'password' => $password,
                        ];
    
                        # send mail
                        Mail::send('emails.user_password_reset', $data, function ($message) use($email) {
                            $message->from(env('MAIL_FROM_ADDRESS'), 'Hotspot Reporter');
                            $message->to($email);
                            $message->subject('Password Reset');
                        });
    
                        # reset password
                        $verifyUserEmail->update([
                            'password' => Hash::make($password)
                        ]);
    
                        return response()->json([
                            'status' => 200,
                            'message' => 'Password reset successful. Kindly check your email!'
                        ], 200);
                    } catch (\Exception $ex) {
                        return response()->json([
                            'status' => 500,
                            'message' => 'Sorry, your password reset request failed. Please try again.'
                        ], 500);
                    }
                }
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Sorry, your request could not be completed!'
            ], 500);
        }
    }

    /**
     * Profile Update
     * 
     * @param \Illuminate\Http $request
     * @param integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function profileUpdate(Request $request) {

        try {
            
            # rules
            $rules = [
                'fullname' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'municipal_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            } else {

                # collate data
                $fullname = $request->fullname;
                $phone = $request->phone;
                $email = $request->email;
                $municipal_id = $request->municipal_id;
                $user_id = $request->user_id;

                # verify user email
                $verifyUserEmail = User::where('email', $email)->first();

                if ($verifyUserEmail && $verifyUserEmail->id != $user_id) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Email already exist!'
                    ], 400);
                } else {

                    # verify user phone
                    $verifyUserPhone = User::where('phone', $phone)->first();

                    if ($verifyUserPhone && $verifyUserPhone->id != $user_id) {
                        return response()->json([
                            'status' => 400,
                            'message' => 'Phone number already exist!'
                        ], 400);
                    } else {

                        # update profile
                        User::find($user_id)->update([
                            'fullname' => $fullname,
                            'phone' => $phone,
                            'email' => $email,
                            'municipal_id' => $municipal_id
                        ]);

                        return response()->json([
                            'status' => 202,
                            'message' => 'User profile update successful.'
                        ], 202);
                    }
                }
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Sorry, your request could not be completed!',
            ], 500);
        }
    }

    /**
     * Profile Info
     * 
     * @param \Illuminate\Http $request
     * @param integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function profileInfo(Request $request, $id) {
        
        try {
            
            # verify logged in user
            $verifyUser = User::find($id);

            if (!$verifyUser) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User not found!'
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'data' => $verifyUser,
                ], 200);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Sorry, your request could not be completed!',
            ], 500);
        }
    }

    /**
     * Municipals
     * 
     * @param \Illuminate\Http $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function municipals(Request $request) {

        try {

            # municipals
            $municipals = Municipal::all();

            return response()->json([
                'status' => 200,
                'data' => $municipals,
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Your request could not be completed',
            ]);
        }
    }

    /**
     * Report Incident
     * 
     * @param \Illuminate\Http $request
     * @param integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function reportIncident(Request $request) {

        try {

            # rules
            $rules = [
                'incident' => 'required',
                'media_url' => 'required',
                'media_type' => 'required',
                'longitude' => 'required',
                'latitude' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json($validator->messages(), 400);
            } else {
                    
                # collate data
                $incident = $request->incident;
                $media_url = $request->media_url;
                $media_type = $request->media_type;
                $longitude = $request->longitude;
                $latitude = $request->latitude;
                $user_id = $request->user_id;

                # create report
                $report = Report::create([
                    'incident' => $incident,
                    'status' => 'pending',
                    'longitude' => $longitude,
                    'latitude' => $latitude,
                    'total_views' => 0,
                    'user_id' => $user_id,
                ]);

                # attach report media
                $report->report_media()->create([
                    'media_url' => $media_url,
                    'media_type' => $media_type
                ]);

                return response()->json([
                    'status' => 201,
                    'data' => $report,
                    'message' => 'Report sent successfully.'
                ], 201);
            }
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Sorry, your request could not be completed!'.$ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Reports
     * 
     * @param \Illuminate\Http $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function reports(Request $request) {

        try {
            
            # get reports
            $reports = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at','users.municipal_id', 'users.fullname', 'users.picture','report_media.media_url', 'report_media.media_type')->leftJoin('users', 'reports.user_id', '=', 'users.id')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->orderBy('reports.created_at', 'DESC')->get();

            return response()->json([
                'status' => 200,
                'data' => $reports,
                'message' => 'Report List',
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Sorry, your request could not be completed!'
            ], 500);
        }
    }

    /**
     * View Report
     * 
     * @param \Illuminate\Http\Request $request
     * @param integer $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function viewReport(Request $request, $id) {
        
        try {

            # get report details
            $report_details = Report::select('reports.id', 'reports.incident', 'reports.status', 'reports.longitude', 'reports.latitude', 'reports.total_views', 'reports.created_at', 'users.fullname', 'users.picture', 'report_media.media_url', 'report_media.media_type')->leftJoin('users', 'reports.user_id', '=', 'users.id')->leftJoin('report_media', 'reports.id', 'report_media.report_id')->where('reports.id', $id)->first();

            # update view if report is seen
            if ($report_details) {
                $report_details->increment('total_views');
            }

            return response()->json([
                'status' => 200,
                'report' => $report_details ?? (Object)[],
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 500,
                'message' => 'Sorry, your request could not be completed!',
            ], 500);
        }
    }
}