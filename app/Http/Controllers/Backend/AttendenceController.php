<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Attendence;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendenceController extends Controller
{
    public function EmployeeAttendenceList(){

        // $allData = Attendence::orderBy('id','desc')->get();
        $allData = Attendence::select('date')->groupBy('date')->orderBy('id','desc')->get();
        return view('backend.attendence.view_employee_attend',compact('allData'));
    }

    public function AddEmployeeAttendence(){
        $employees = Employee::all();
        return view('backend.attendence.add_employee_attend',compact('employees'));
    }// End Method 

    public function EmployeeAttendenceStore(Request $request){
        $date=date('Y-m-d',strtotime($request->date));
        Attendence::where('date',$date)->delete();
        foreach ($request->employee_id as $key => $employeeId) {
            $attend = new Attendence();
            $attend->date = $date;
            $attend->employee_id = $employeeId;
            $attend->attend_status = $request->{'attend_status' . $key}; // Accès dynamique au statut
            $attend->save();
        }
        $notification = [
            'message' => 'Data Inserted Successfully',
            'alert-type' => 'success'
        ];
        
        return redirect()->route('employee.attend.list')->with($notification);
    }

    public function EditEmployeeAttendence($date){
        $employees = Employee::all();
        $editData = Attendence::where('date',$date)->get();
        return view('backend.attendence.edit_employee_attend',compact('employees','editData'));

    }// End Method 

    public function ViewEmployeeAttendence($date){

        $details = Attendence::where('date',$date)->get();
        return view('backend.attendence.details_employee_attend',compact('details'));
    }// End Method 


}
