<?php

namespace App\Http\Controllers;

use App\Models\ClassSection;
use App\Models\Student;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\AssessmentSlot;
use App\Models\StudentAssessment;

class AssessmentController extends Controller
{
    // Show the form to assign students to slots of assessment
    public function showAssignStudentsForm(Assessment $assessment)
    {
        return view('teacher.assessments.assign_students', compact('assessment'));
    }

    public function getSlotsForDate(Assessment $assessment, Request $request)
    {
        $selectedDate = $request->date;
        $selectedDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

        //\DB::enableQueryLog();
        $studentAssessments = $assessment->studentAssessments()->whereDate('assessment_date', $selectedDate)->exists();
        //dd($studentAssessments);
        if($studentAssessments){
            return response()->json(['slots' => [], 'message' => "<p>Assessment for this date has already been completed<a href='".route('assessments.getSlotsForDate', $assessment->id )."' class='btn btn-primary'>Click here</a> to view slots</p>"]);
        }
        $slots = $assessment->slots()->whereDate('assessment_date', $selectedDate)->get();
        //dd(\DB::getQueryLog());
        return response()->json(['slots' => $slots, 'message' => '']);
    }

    public function getStudentsForClass(Assessment $assessment, Request $request)
    {
        $students = Student::where('class_id', $request->class_id)->get();
        return response()->json(['students' => $students]);
    }

    public function saveAssignments(Request $request)
    {
        $assessment = Assessment::find($request->assessment_id);
        $assessmentDate = Carbon::createFromFormat('d-m-Y', $request->assessment_date)->format('Y-m-d');
        //dd($assessment['class_id']);
        $students = $request->selected_students;
        foreach ($students as $studentId => $slotData) {
            //var_dump($slotData);
            StudentAssessment::create([
                'student_id' => $studentId,
                'assessment_id' => $request->assessment_id,
                'assessment_slot_id' => $slotData['slot_id'],
                'class_id' => $assessment['class_id'],
                'is_attended' => 1,
                'assessment_date' => $assessmentDate
            ]);
        }

        return response()->json(['message' => 'Assignments saved successfully.']);
    }

    public function listSlots(Assessment $assessment)
    {
        $slots = $assessment->slots()->with('classSection')->get();
        return view('teacher.assessments.slots', compact('slots', 'assessment'));
    }

    public function viewSlotStudents($slotId)
    {
        $slot = AssessmentSlot::with(['students', 'assessment', 'classSection'])
            ->where('id', $slotId)
            ->first();

        if (!$slot) {
            return redirect()->back()->with('error', 'Slot not found');
        }

        return view('teacher.assessments.viewSlotStudents', compact('slot'));
    }

    public function autoAssignStudentsToSlots($assessmentId, Request $request)
    {
        $selectedDate = $request->input('date');
        $selectedDate = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        //\DB::enableQueryLog();
        $assessment = Assessment::with(['slots', 'classSection.students'])->findOrFail($assessmentId);
        // Get all slots for the selected date
        $slots = $assessment->slots()->whereDate('assessment_date', $selectedDate)->get();
        //dd(\DB::getQueryLog());

        if ($slots->isEmpty()) {
            return response()->json(['message' => 'No slots generated for this date.'], 400);
        }

        // Fetch students of the specified class section, filter out those already assigned on this date
        $students = $assessment->classSection->students()->whereDoesntHave('studentAssignments', function($query) use ($selectedDate) {
            $query->whereDate('assessment_date', $selectedDate);
        })->get();

        $totalStudents = $students->count();
        $totalSlots = $slots->count();
        $studentsPerSlot = intdiv($totalStudents, $totalSlots);
        $remainingStudents = $totalStudents % $totalSlots;

        // Shuffle students randomly
        $students = $students->shuffle();

        // Assign students to slots
        $index = 0;
        foreach ($slots as $slot) {
            $assignedStudents = $students->slice($index, $studentsPerSlot + ($remainingStudents > 0 ? 1 : 0));
            $remainingStudents--;

            // Add entries to student_assignments table
            foreach ($assignedStudents as $student) {
                StudentAssessment::create([
                    'student_id' => $student->id,
                    'assessment_slot_id' => $slot->id,
                    'assessment_date' => $selectedDate,
                    'assessment_id' => $assessmentId,
                    'class_id' => $assessment['class_id'],
                    'is_attended' => 1,
                ]);
            }

            // Update index for the next set of students
            $index += $studentsPerSlot + ($remainingStudents > 0 ? 1 : 0);
        }

        return response()->json(['message' => 'Students have been assigned to slots automatically.']);
    }
}
