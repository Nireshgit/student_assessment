<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generated Slots') }}</a>
        </h2>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right ml-auto">
            <a href="{{ route('assessments.scheduled') }}">Back</a>
        </h2>
    </x-slot>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-semibold mb-4">Slots for {{ $assessment->name }}</h2>
        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">Slot Name</th>
                    <th class="border border-gray-300 p-2">Assessment Date</th>
                    <th class="border border-gray-300 p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($slots) > 0)
                    @foreach($slots as $slot)
                    <tr>
                        <td class="border border-gray-300 p-2">{{ $slot->name }}</td>
                        <td class="border border-gray-300 p-2">{{ $slot->assessment_date->format('Y-m-d') }}</td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('assessments.viewSlotStudents', $slot->id) }}" class="btn btn-primary">View Students</a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <p>No slots generated</p>
                @endif
            </tbody>
        </table>
    </div>
</x-app-layout>
