<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::orderBy('created_at', 'desc')->paginate(10);
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'student_id' => 'required|string|unique:students',
            'program' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:10',
            'specialization' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:active,inactive,graduated,suspended',
            'gpa' => 'nullable|numeric|between:0,4',
            'credits_earned' => 'nullable|integer|min:0'
        ]);

        if ($request->hasFile('profile_photo')) {
            // Si on a un user_id, stocker dans un dossier stable et écrasable
            if (!empty($validated['user_id'])) {
                $extension = $request->file('profile_photo')->getClientOriginalExtension();
                $directory = 'users/' . $validated['user_id'] . '/profile';
                $filename = 'photo.' . $extension;
                $validated['profile_photo'] = $request->file('profile_photo')->storeAs($directory, $filename, 'public');
            } else {
                $validated['profile_photo'] = $request->file('profile_photo')->store('students', 'public');
            }
        }

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Étudiant créé avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'student_id' => 'required|string|unique:students,student_id,' . $student->id,
            'program' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:10',
            'specialization' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:active,inactive,graduated,suspended',
            'gpa' => 'nullable|numeric|between:0,4',
            'credits_earned' => 'nullable|integer|min:0'
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($student->profile_photo) {
                Storage::disk('public')->delete($student->profile_photo);
            }

            // Si on a un user_id, stocker dans un dossier stable et écrasable
            if (!empty($student->user_id)) {
                $extension = $request->file('profile_photo')->getClientOriginalExtension();
                $directory = 'users/' . $student->user_id . '/profile';
                $filename = 'photo.' . $extension;
                $validated['profile_photo'] = $request->file('profile_photo')->storeAs($directory, $filename, 'public');
            } else {
                $validated['profile_photo'] = $request->file('profile_photo')->store('students', 'public');
            }
        }

        $student->update($validated);

        return redirect()->route('students.show', $student)->with('success', 'Profil étudiant mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        if ($student->profile_photo) {
            Storage::disk('public')->delete($student->profile_photo);
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Étudiant supprimé avec succès!');
    }
}
