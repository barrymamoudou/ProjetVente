<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function AllEmployee(){
        $employee=Employee::latest()->get();
        return view('backend.employees.all_employee',compact('employee'));
    }

    public function AddEmployee(){
        return view('backend.employees.add_employee');
    }
    public function StoreEmployee(Request $request){
        $validateData = $request->validate([
            'name' => 'required|max:200',
            'email' => 'required|unique:employees|max:200',
            'phone' => 'required|max:200',
            'address' => 'required|max:400',
            'salary' => 'required|max:200',
            'vacation' => 'required|max:200',  
        ]);

        $image=$request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/employee/'.$name_gen);
        $save_url = 'upload/employee/'.$name_gen;

        Employee::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'experience' => $request->experience,
            'salary' => $request->salary,
            'vacation' => $request->vacation,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(), 
        ]);
        $notification = array(
            'message' => 'Employee Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.employee')->with($notification); 

    }
    public function EditEmployee($id){

        //$editEmp=Employee::find($id);
        $editEmp=Employee::findOrFail($id);
        return view('backend.employees.edit_employee',compact('editEmp'));
    }

    public function UpdateEmployee(Request $request){
        $employee_id = $request->id;
        $data = $request->only(['name', 'email', 'phone', 'address', 'experience', 'salary', 'vacation', 'city']);
        $data['created_at'] = Carbon::now();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300, 300)->save('upload/employee/' . $name_gen);
            $data['image'] = 'upload/employee/' . $name_gen;
        }

        Employee::findOrFail($employee_id)->update($data);

        return redirect()->route('all.employee')->with([
            'message' => 'Employee Updated Successfully',
            'alert-type' => 'success'
        ]);

    }

    public function UpdateEmployee1(Request $request){

        $employee_id = $request->id;

        if ($request->file('image')) {

        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(300,300)->save('upload/employee/'.$name_gen);
        $save_url = 'upload/employee/'.$name_gen;

        Employee::findOrFail($employee_id)->update([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'experience' => $request->experience,
            'salary' => $request->salary,
            'vacation' => $request->vacation,
            'city' => $request->city,
            'image' => $save_url,
            'created_at' => Carbon::now(), 

        ]);

         $notification = array(
            'message' => 'Employee Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.employee')->with($notification); 

        } else{

            Employee::findOrFail($employee_id)->update([

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'experience' => $request->experience,
            'salary' => $request->salary,
            'vacation' => $request->vacation,
            'city' => $request->city, 
            'created_at' => Carbon::now(), 

        ]);

         $notification = array(
            'message' => 'Employee Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.employee')->with($notification); 

        } // End else Condition  


    } // End Method 

    public function DeleteEmployee($id){

        $employee_img=Employee::findOrFail($id);
        $img = $employee_img->image;
        unlink($img);
        

        Employee::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Employee Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 

    } 
}
