<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\Student;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\AssessmentSlot;

class TeacherController extends Controller
{
    // Display class creation form
    public function createClassForm()
    {
        return view('teacher.create-class');
    }

    // Handle class creation form submission
    public function createClass(Request $request)
    {
        $request->validate([
            'standard' => 'required|integer',
            'section' => 'required|string',
        ]);

        ClassSection::create($request->only('standard', 'section'));

        return redirect()->back()->with('success', 'Class created successfully.');
    }

    // Display student creation form
    public function createStudentForm()
    {
        $classes = ClassSection::all();
        return view('teacher.create-student', compact('classes'));
    }

    // Handle student creation form submission
    public function createStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:students',
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
            'class_id' => 'required|exists:class_sections,id',
        ]);

        Student::create($request->only('name', 'email', 'phone', 'class_id'));

        return redirect()->back()->with('success', 'Student created successfully.');
    }

    // Display assessment creation form
    public function createAssessmentForm()
    {
        $classes = ClassSection::all();
        return view('teacher.create-assessment', compact('classes'));
    }

    // Handle assessment creation form submission
    public function createAssessment(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'class_id' => 'required|exists:class_sections,id',
            'grace_time' => 'required|integer',
            'duration' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // 'seats' => 'required|integer|max:20',
            // 'is_published' => 'required|boolean',
        ]);

        Assessment::create($request->only('name', 'class_id', 'grace_time', 'duration', 'start_date', 'end_date', 'seats', 'is_published'));

        return redirect()->back()->with('success', 'Assessment created successfully.');
    }

    public function showScheduledAssessments()
    {
        $assessments = Assessment::with('classSection')->get(); // Assuming each Assessment belongs to a ClassSection
        return view('teacher.scheduled-assessments', compact('assessments'));
    }

    // Show the form to schedule assessment slots
    public function showGenerateSlotsForm(Assessment $assessment)
    {
        return view('teacher.assessments.schedule_slots', compact('assessment'));
    }

    // Generate slots based on the selected date and duration
    public function generateSlots(Request $request, Assessment $assessment)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'selected_date' => 'required|date|after_or_equal:' . $assessment->start_date . '|before_or_equal:' . $assessment->end_date,
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Slot generation logic here...
        $selectedDate = Carbon::parse($request->input('selected_date'));
        $duration = $assessment->duration;

        $existingSlots = AssessmentSlot::whereDate('assessment_date', $selectedDate->toDateString())
        ->where('assessment_id', $assessment->id)
        ->exists();

        if ($existingSlots) {
            return redirect()->back()->withErrors(['selected_date' => 'Slots for the selected date ('.$selectedDate->toDateString().') have already been generated.'])->withInput();
        }

        // Define customizable time range
        $startTime = Carbon::createFromTime(10, 0, 0, $selectedDate->timezone); // Start time (10 AM)
        $endTime = Carbon::createFromTime(17, 0, 0, $selectedDate->timezone); // End time (5 PM)

        //echo $startTime;exit;

        // Generate slots based on the selected date
        $slots = [];
        while ($startTime->copy()->addHours($duration)->lte($endTime)) {
            $slotName = $startTime->format('H:i') . '-' . $startTime->addHours($duration)->format('H:i');

            $slots[] = [
                'name' => $slotName,
                'assessment_id' => $assessment->id,
                'class_id' => $assessment->class_id, // Ensure to link to the correct class
                'assessment_date' => $selectedDate,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert slots into the database
        AssessmentSlot::insert($slots);

        //return redirect()->back()->with('success', 'Slots generated successfully.');
        return redirect()->route('assessments.scheduled')->with('success', 'Slots generated successfully.');
    }

    // public function scheduleAssessment(Request $request, $id)
    // {
    //     $assessment = Assessment::findOrFail($id);
    //     $duration = $assessment->duration; // in hours
    //     $startTime = new \DateTime($assessment->start_date->format('H:i'));
    //     $endTime = new \DateTime($assessment->end_date->format('H:i'));

    //     // Generate slots based on duration
    //     $slots = [];
    //     while ($startTime < $endTime) {
    //         $slotEnd = clone $startTime;
    //         $slotEnd->modify("+{$duration} hour");

    //         // Break if slot end exceeds end time
    //         if ($slotEnd > $endTime) break;

    //         // Format slots and save to DB
    //         $slotName = $startTime->format('H:i') . '-' . $slotEnd->format('H:i');
    //         AssessmentSlot::create([
    //             'name' => $slotName,
    //             'assessment_id' => $assessment->id,
    //             'class_id' => $assessment->class_id,
    //         ]);

    //         $startTime = $slotEnd;
    //     }

    //     return redirect()->back()->with('success', 'Slots scheduled successfully.');
    // }


}
