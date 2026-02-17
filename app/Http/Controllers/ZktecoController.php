<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Device;
use App\Models\ErrorLog;
use App\Models\Student;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\SecondaryAttendance;

class ZktecoController extends Controller
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Dhaka');
    }

    public function handshake(Request $request)
    {
        DB::beginTransaction();

        try{
            $device = Device::firstOrNew(['serial_number' => $request->SN]);
            $device->ip_address = $request->ip();
            $device->last_online = now();
            $device->save();

            DB::commit();

            $returnData = "GET OPTION FROM: {$request->input('SN')}\r\n" .
                "Stamp=9999\r\n" .
                "OpStamp=" . time() . "\r\n" .
                "ErrorDelay=60\r\n" .
                "Delay=30\r\n" .
                "ResLogDay=18250\r\n" .
                "ResLogDelCount=10000\r\n" .
                "ResLogCount=50000\r\n" .
                "TransTimes=00:00;23:05\r\n" .
                "TransInterval=1\r\n" .
                "TransFlag=1111000000\r\n" .
                "Realtime=1\r\n" .
                "Encrypt=0"
            ;

            return $returnData;
        }
        catch(\Exception $e){
            DB::rollBack();

            $returnData = "Device connection failed.";

            $errorLog = new ErrorLog;
            $errorLog->log = $returnData;
            $errorLog->ip_address = $request->ip();
            $errorLog->connection_time = now();
            $errorLog->save();

            return response()->json([
                'message' => $returnData
            ], 500);
        }
    }

    public function receiveRecords(Request $request)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql2')->beginTransaction();

        try{
            if(Device::where('serial_number', $request->SN)->doesntExist()){
                return response()->json([
                    'message' => "Attendance records could not be saved."
                ], 403);
            }

            $totalRecords = 0;

            $receivedRecords = preg_split('/\\r\\n|\\r|,|\\n/', $request->getContent());

            foreach($receivedRecords as $receivedRecord){
                if(empty($receivedRecord)){
                    continue;
                }

                $receivedRecord = str_replace('\\t', "\t", $receivedRecord);

                $data = explode("\t", $receivedRecord);

                $userId = $data[0] ?? null;

                if($userId){
                    $studentId = $this->getStudentId($userId);

                    if(!$studentId){
                        continue;
                    }

                    $timestamp = now();

                    $currentState = $this->determineState($timestamp);

                    $attendance = new Attendance;
                    $attendance->attendance_date = date("Y-m-d", strtotime($timestamp));
                    $attendance->attendance_time = date("H:i:s", strtotime($timestamp));
                    $attendance->status = $currentState;
                    $attendance->user_id = $studentId;
                    $attendance->save();

                    $secondaryAttendance = new SecondaryAttendance;
                    $secondaryAttendance->device_sn = $request->SN;
                    $secondaryAttendance->ip_address = $request->ip();
                    $secondaryAttendance->user_id = $userId;
                    $secondaryAttendance->attendance_time = $timestamp;
                    $secondaryAttendance->save();

                    $totalRecords++;
                }
            }

            DB::connection('mysql')->commit();
            DB::connection('mysql2')->commit();

            if($totalRecords == 0){
                $returnData = "No attendance records to save.";
            }
            else{
                $returnData = "Attendance records saved successfully.";
            }

            return $returnData;
        }
        catch(\Exception $e){
            DB::connection('mysql')->rollBack();
            DB::connection('mysql2')->rollBack();

            $returnData = "Attendance records could not be saved.";

            $errorLog = new ErrorLog;
            $errorLog->log = $returnData;
            $errorLog->ip_address = $request->ip();
            $errorLog->connection_time = now();
            $errorLog->save();

            return response()->json([
                'message' => $returnData
            ], 500);
        }
    }

    private function getStudentId($userId)
    {
        $studentId = Student::where('student_id', $userId)->value('id');

        return $studentId ?? null;
    }

    private function determineState($timestamp)
    {
        $time = Carbon::parse($timestamp);

        $timeInHourAndMinute = $time->format('H:i');

        if($timeInHourAndMinute >= '09:00' && $timeInHourAndMinute <= '10:00'){
            return 'Present';
        }
        elseif($timeInHourAndMinute > '10:00' && $timeInHourAndMinute < '11:00'){
            return 'Late';
        }
        else{
            return 'Absent';
        }
    }
}
