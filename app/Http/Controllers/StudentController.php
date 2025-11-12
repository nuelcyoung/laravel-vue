<?php

namespace App\Http\Controllers;

use App\Helpers\HashHelper;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\StudentResource;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classes;

class StudentController extends Controller
{
    public function index(Request $request)
    {
         $studentQuery = Student::search($request);
        $classes = ClassesResource::collection(Classes::all());
        return inertia('Students/index', [
            'students' => StudentResource::collection($studentQuery->paginate(15)),
            'classes' => $classes,
            'search' => request('search')??''
        ]);
    }
    public function create()
    {
        $classes = ClassesResource::collection(Classes::all());
        return inertia('Students/Create',
        [
            'classes' => $classes,
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        

        Student::create($request->validated());
        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }   
    

    public function update(UpdateStudentRequest $request, string $student)
    {
        $studentId = HashHelper::decode($student);
        $student = Student::findOrFail($studentId);
        $student->update($request->validated());
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(string $student)
    {
        $studentId = HashHelper::decode($student);
        $student = Student::findOrFail($studentId);

        $student->delete();
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function edit(string $student)
    {
        $studentId = HashHelper::decode($student);
        $student = Student::findOrFail($studentId);

        $classes = ClassesResource::collection(Classes::all());
        return inertia('Students/Edit', [
            'student' => StudentResource::make($student),
            'classes' => $classes,
        ]);
    }
}
