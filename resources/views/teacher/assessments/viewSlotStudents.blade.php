<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment Report Slot Wise') }}</a>
        </h2>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right ml-auto">
            <a href="{{ route('assessments.listSlots', $slot->assessment->id ) }}">Back</a>
        </h2>
    </x-slot>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">{{ $slot->assessment->name }} - Students in Slot: {{ $slot->name }}</h2>
        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">Name</th>
                    <th class="border border-gray-300 p-2">Attended</th>
                    <th class="border border-gray-300 p-2">Grace Time</th>
                    <th class="border border-gray-300 p-2">Class</th>
                    <th class="border border-gray-300 p-2">Assessment Name</th>
                    <th class="border border-gray-300 p-2">Assessment Date</th>
                    <th class="border border-gray-300 p-2">Slot Name</th>
                </tr>
            </thead>
            <tbody>
            @if(count($slot->students) > 0)
                @foreach($slot->students as $student)
                <tr>
                    <td class="border border-gray-300 p-2">{{ $student->name }}</td>
                    <td class="border border-gray-300 p-2">{{ $student->is_attended ? 'Yes' : 'No' }}</td>
                    <td class="border border-gray-300 p-2">{{ $slot->grace_time ?? 'None' }}</td>
                    <td class="border border-gray-300 p-2">{{ $slot->classSection->standard }} {{ $slot->classSection->section }}</td>
                    <td class="border border-gray-300 p-2">{{ $slot->assessment->name }}</td>
                    <td class="border border-gray-300 p-2">{{ $slot->assessment_date->format('Y-m-d') }}</td>
                    <td class="border border-gray-300 p-2">{{ $slot->name }}</td>
                </tr>
                @endforeach
            @else
                <p>No slots generated</p>
            @endif
            </tbody>
        </table>
    </div>
</x-app-layout>