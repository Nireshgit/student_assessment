<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Assessments</h2>
        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">Class</th>
                    <th class="border border-gray-300 p-2">Section</th>
                    <th class="border border-gray-300 p-2">Start Date</th>
                    <th class="border border-gray-300 p-2">End Date</th>
                    <th class="border border-gray-300 p-2">Grace Time</th>
                    <th class="border border-gray-300 p-2">Seats</th>
                    <th class="border border-gray-300 p-2">Published</th>
                    <th class="border border-gray-300 p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assessments as $assessment)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $assessment->classSection->standard }}@if($assessment->classSection->standard == '1')st @elseif($assessment->classSection->standard=='2')nd @else th @endif</td>
                    <td class="border border-gray-300 p-2">{{ $assessment->classSection->section }}</td>
                    <td class="border border-gray-300 p-2">{{ $assessment->start_date->format('d-m-Y') }}</td>
                    <td class="border border-gray-300 p-2">{{ $assessment->end_date->format('d-m-Y') }}</td>
                    <td class="border border-gray-300 p-2">{{ $assessment->grace_time }} mins</td>
                    <td class="border border-gray-300 p-2">{{ $assessment->seats }}</td>
                    <td class="border border-gray-300 p-2">{{ $assessment->is_published ? 'Yes' : 'No' }}</td>
                    <td class="border border-gray-300 p-2">
                            <form action="{{ route('assessments.generate_slots_form', $assessment->id) }}" method="GET">
                                <button type="submit" class="btn btn-primary">Schedule</button>
                            </form>
                            <form action="{{ route('assessments.assign_students_form', $assessment->id) }}" method="GET">
                                <button type="submit" class="btn btn-primary">Assign Students</button>
                            </form>
                            <!-- <button type="button" class="btn btn-success">Published</button> -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
