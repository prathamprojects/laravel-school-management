<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\State;
use App\Models\SchoolPhoto;
use App\Models\Standard;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::with([
            'state',
            'district',
            'city',
        ])->latest()->paginate(10);

        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::orderBy('name')->get();
        $standards = Standard::orderBy('id')->get();
        return view('schools.create', compact('states', 'standards'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            // School validation
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'state_id' => ['required', 'exists:states,id'],
            'district_id' => [
                'required',
                Rule::exists('districts', 'id')
                    ->where('state_id', $request->state_id),
            ],
            'city_id' => [
                'required',
                Rule::exists('cities', 'id')
                    ->where('district_id', $request->district_id),
            ],
            'establishment_date' => 'required|date',
            'contact_number' => 'required|string|max:20',
            'login_id' => 'required|string|max:255|unique:schools,login_id',
            'password' => 'required|string|min:8',

            // School photos
            'school_photos' => 'nullable|array|max:5',
            'school_photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            // Student validation
            'students' => 'required|array|min:1',
            'students.*.name' => 'required|string|max:255',
            'students.*.standard_id' => 'required|exists:standards,id',
            'students.*.gender' => 'required|in:Male,Female',
            'students.*.year' => 'required|integer|min:2000|max:2100',
            'students.*.photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::transaction(function () use ($validated, $request) {

            // Separate uploaded files from school data.
            $schoolPhotos = $request->file('school_photos', []);
            $students = $validated['students'];

            unset($validated['school_photos'], $validated['students']);

            // Hash password before saving.
            $validated['password'] = Hash::make($validated['password']);

            // Save school.
            $school = School::create($validated);

            // Save school photos.
            foreach ($schoolPhotos as $photo) {
                $path = $photo->store('school-photos', 'public');

                $school->photos()->create([
                    'photo_path' => $path,
                ]);
            }

            // Save multiple students.
            foreach ($students as $index => $studentData) {

                $studentPhoto = $studentData['photo'] ?? null;

                unset($studentData['photo']);

                $student = $school->students()->create($studentData);

                // Save student photo if uploaded.
                if ($studentPhoto) {
                    $path = $studentPhoto->store('student-photos', 'public');

                    $student->update([
                        'photo_path' => $path,
                    ]);
                }
            }
        });

        return redirect()
            ->route('schools.index')
            ->with('success', 'School and students saved successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        $school->load([
            'state',
            'district',
            'city',
            'photos',
            'students.standard',
        ]);

        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        $school->load([
            'photos',
            'students',
        ]);

        $states = State::orderBy('name')->get();
        $standards = Standard::orderBy('id')->get();

        return view('schools.edit', compact(
            'school',
            'states',
            'standards'
        ));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, School $school)
    {
        $submittedStudents = $request->input('students', []); // Remember which existing students the user marked for removal.

        $deletedStudentIds = collect($submittedStudents)
            ->filter(fn ($student) => ($student['_delete'] ?? '0') == '1')
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        // Ignore removed rows during validation.
        $activeStudents = collect($submittedStudents)
            ->reject(fn ($student) => ($student['_delete'] ?? '0') == '1')
            ->all();

        $request->merge(['students' => $activeStudents]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'state_id' => ['required', 'exists:states,id'],

            'district_id' => [
                'required',
                Rule::exists('districts', 'id')
                    ->where('state_id', $request->state_id),
            ],

            'city_id' => [
                'required',
                Rule::exists('cities', 'id')
                    ->where('district_id', $request->district_id),
            ],

            'establishment_date' => 'required|date',
            'contact_number' => 'required|string|max:20',

            'login_id' => [
                'required',
                'string',
                'max:255',
                Rule::unique('schools', 'login_id')->ignore($school->id),
            ],

            'password' => 'nullable|string|min:8',

            'school_photos' => 'nullable|array|max:5',
            'school_photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'students' => 'required|array|min:1',
            'students.*.id' => [
                'nullable',
                'integer',
                Rule::exists('students', 'id')
                    ->where('school_id', $school->id),
            ],
            'students.*.name' => 'required|string|max:255',
            'students.*.standard_id' => 'required|exists:standards,id',
            'students.*.gender' => 'required|in:Male,Female',
            'students.*.year' => 'required|integer|min:2000|max:2100',
            'students.*.photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Keep the existing password if no new password was entered.
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        unset($validated['school_photos'], $validated['students']);

        DB::transaction(function () use (
            $request,
            $validated,
            $school,
            $activeStudents,
            $deletedStudentIds
        ) {
            // Update school information.
            $school->update($validated);

            // Soft delete removed students.
            if (!empty($deletedStudentIds)) {
                $school->students()
                    ->whereIn('id', $deletedStudentIds)
                    ->delete();
            }

            // Add newly uploaded school photos.
            foreach ($request->file('school_photos', []) as $photo) {
                $path = $photo->store('school-photos', 'public');

                $school->photos()->create([
                    'photo_path' => $path,
                ]);
            }

            // Update existing students or create new students.
            foreach ($activeStudents as $index => $studentData) {
                $studentId = $studentData['id'] ?? null;

                unset($studentData['id'], $studentData['_delete'], $studentData['photo']);

                if ($studentId) {
                    $student = $school->students()
                        ->whereKey($studentId)
                        ->firstOrFail();

                    $student->update($studentData);
                } else {
                    $student = $school->students()->create($studentData);
                }

                // Replace student photo only if a new one was uploaded.
                $studentPhoto = $request->file("students.$index.photo");

                if ($studentPhoto) {
                    $path = $studentPhoto->store('student-photos', 'public');

                    $student->update([
                        'photo_path' => $path,
                    ]);
                }
            }
        });

        return redirect()
            ->route('schools.index')
            ->with('success', 'School and student details updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $school->delete();

        return redirect()
            ->route('schools.index')
            ->with('success', 'School deleted successfully.');
    }

    public function exportPdf(School $school)
    {
        $school->load([
            'state',
            'district',
            'city',
            'photos',
            'students.standard',
        ]);

        $pdf = Pdf::loadView('schools.pdf', compact('school'));

        return $pdf->download('school-' . $school->id . '.pdf');
    }
}
