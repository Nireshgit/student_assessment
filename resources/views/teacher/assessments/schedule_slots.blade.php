<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Assessment Slots') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form action="{{ route('assessments.generateSlots', $assessment->id) }}" method="POST">
                        @csrf

                        <!-- Select Date -->
                        <div class="mb-3">
                            <label for="selected_date" class="form-label">Select Date</label>
                            <input type="date" id="selected_date" name="selected_date" class="form-control" min="{{ $assessment->start_date->format('d-m-Y') }}" max="{{ $assessment->end_date->format('d-m-Y') }}" required>
                            @if ($errors->has('selected_date'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('selected_date') }}
                                </div>
                            @endif
                        </div>

                        <!-- Duration -->
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (in hours)</label>
                            <input type="number" id="duration" value="{{ $assessment->duration }}" name="duration" class="form-control" min="1" max="8" required disabled>
                        </div>

                        <button type="submit" class="btn btn-primary">Generate Slots</button>
                        <a href="{{ route('assessments.getSlotsForDate', $assessment->id) }}" class="btn btn-primary">Slots</a>
                        <a href="{{ route('assessments.scheduled') }}" class="btn btn-primary">Back to Assessments</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const assignmentDateInput = document.getElementById('selected_date');
    flatpickr("#selected_date", {
        dateFormat: "d-m-Y",
        minDate: assignmentDateInput.getAttribute('min'),
        maxDate: assignmentDateInput.getAttribute('max')
    });
});
</script>
</x-app-layout>
