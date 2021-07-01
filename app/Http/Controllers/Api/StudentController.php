<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Student::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:students,name',
            'email' => 'nullable|email'
        ]);
        $file_name = '';
        if(request()->hasFile('image')){
            $file = request()->file('image');
            if($file->isValid()){
                $file_name = date('Ymdhms').$file->getClientOriginalExtension();
                $file->storeAs('student', $file_name);
            }
        }
        $student = Student::create($request->all() + [
            'image' => $file_name
        ]);
        return response()->json($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::where('id', $id)->first();
        return $student;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:students,name,'.$id,
            'email' => 'nullable|email'
        ]);
        $student = Student::findOrFail($id);
        $student->update($request->except('image'));

        $path = $student->image;
        if(request()->hasFile('image')){
            $file = request()->file('image');
            if($file->isValid()){
                $file_name = date('Ymdhms').$file->getClientOriginalExtension();
                $file->storeAs('student', $file_name);
            }
        }
        return response('Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::where('id', $id)->first();
        // $path = $student->image;
        unlink($student->image);
        Student::findOrFail($id)->delete();
        return response('Deleted!');
    }
}
