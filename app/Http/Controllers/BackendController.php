<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use DataTables;
use Carbon\Carbon;
use App\Models\SecondaryAttendance;
use App\Models\ErrorLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BackendController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function index(Request $request){
        if($request->ajax()){
            $devices = Device::query();

            return DataTables::of($devices)->addIndexColumn()->editColumn('last_online', function($row){
                return Carbon::parse($row->last_online)->diffForHumans();
            })->order(function($query){
                $query->orderBy('id', 'desc');
            })->make(true);
        }

        return view('backend.index');
    }

    public function attendances(Request $request){
        if($request->ajax()){
            $attendances = SecondaryAttendance::query();

            return DataTables::of($attendances)->addIndexColumn()->editColumn('attendance_time', function($row){
                return Carbon::parse($row->attendance_time)->diffForHumans();
            })->order(function($query){
                $query->orderBy('id', 'desc');
            })->make(true);
        }

        return view('backend.attendances');
    }

    public function resetAttendances(Request $request){
        if(SecondaryAttendance::doesntExist()){
            $notification = array(
                'message' => 'No attendance records have been found.',
                'alert-type' => 'info'
            );

            return redirect()->back()->with($notification);
        }

        SecondaryAttendance::truncate();

        $notification = array(
            'message' => 'Attendance records have been successfully reset.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function errorLogs(Request $request){
        if($request->ajax()){
            $errorLogs = ErrorLog::query();

            return DataTables::of($errorLogs)->addIndexColumn()->editColumn('connection_time', function($row){
                return Carbon::parse($row->connection_time)->diffForHumans();
            })->order(function($query){
                $query->orderBy('id', 'desc');
            })->make(true);
        }

        return view('backend.errorLogs');
    }

    public function resetErrorLogs(Request $request){
        if(ErrorLog::doesntExist()){
            $notification = array(
                'message' => 'No error logs have been found.',
                'alert-type' => 'info'
            );

            return redirect()->back()->with($notification);
        }

        ErrorLog::truncate();

        $notification = array(
            'message' => 'Error logs have been successfully reset.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function changeEmail(){
        return view('backend.changeEmail');
    }

    public function updateEmail(Request $request){
        $validatedData = $request->validate([
            'current_password' => ['required', 'string', 'min:8', 'current_password'],
            'new_email' => ['required', 'string', 'email', 'max:255'],
        ],
        [
            'current_password.current_password' => 'The current password is incorrect.',
        ]);

        $user = Auth::user();
        $user->email = $request->new_email;
        $user->save();

        $notification = array(
            'message' => 'Email has been successfully changed.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function changePassword(){
        return view('backend.changePassword');
    }

    public function updatePassword(Request $request){
        $validatedData = $request->validate([
            'current_password' => ['required', 'string', 'min:8', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ],
        [
            'current_password.current_password' => 'The current password is incorrect.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        $notification = array(
            'message' => 'Password has been successfully changed.',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
