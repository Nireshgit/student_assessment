<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Assessment') }}
        </h2>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-right ml-auto"">
            <a href="{{ route('assessments.scheduled') }}">{{ __('Assessments') }}</a>
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form id="createAssessmentForm" method="POST" action="{{ route('teacher.createAssessment') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <x-input-label for="name" :value="__('Assessment Name')" />
                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Class Selection -->
                        <div class="mb-3">
                            <x-input-label for="class_id" :value="__('Class')" />
                            <select id="class_id" class="form-select" name="class_id" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->standard }} - {{ $class->section }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
                        </div>

                        <!-- Grace Time -->
                        <div class="mb-3">
                            <x-input-label for="grace_time" :value="__('Grace Time (mins)')" />
                            <x-text-input id="grace_time" class="form-control" type="number" name="grace_time" :value="old('grace_time')" required />
                            <x-input-error :messages="$errors->get('grace_time')" class="mt-2" />
                        </div>

                        <!-- Duration -->
                        <div class="mb-3">
                            <x-input-label for="duration" :value="__('Duration (hrs)')" />
                            <x-text-input id="duration" class="form-control" type="number" name="duration" :value="old('duration')" required />
                            <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                        </div>

                        <!-- Start Date -->
                        <div class="mb-3">
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-text-input id="start_date" class="form-control" type="date" name="start_date" :value="old('start_date')" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <!-- End Date -->
                        <div class="mb-3">
                            <x-input-label for="end_date" :value="__('End Date')" />
                            <x-text-input id="end_date" class="form-control" type="date" name="end_date" :value="old('end_date')" required />
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>

                        <!-- Seats -->
                        <!-- <div class="mb-3">
                            <x-input-label for="seats" :value="__('Seats (max 20)')" />
                            <x-text-input id="seats" class="form-control" type="number" name="seats" :value="old('seats')" required max="20" />
                            <x-input-error :messages="$errors->get('seats')" class="mt-2" />
                        </div> -->

                        <!-- Is Published -->
                        <!-- <div class="mb-3">
                            <x-input-label for="is_published" :value="__('Publish?')" />
                            <select id="is_published" class="form-select" name="is_published" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <x-input-error :messages="$errors->get('is_published')" class="mt-2" />
                        </div> -->

                        <div class="d-flex justify-content-end mt-4">
                            <x-primary-button>
                                {{ __('Create Assessment') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            flatpickr("#start_date", {
                dateFormat: "d-m-Y",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    endDatePicker.set("minDate", dateStr);
                }
            });

            const endDatePicker = flatpickr("#end_date", {
                dateFormat: "d-m-Y",
                minDate: "today",
            });

            $('#createAssessmentForm').submit(function(event) {
                let valid = true;

                const name = $('#name').val();
                const graceTime = $('#grace_time').val();
                const duration = $('#duration').val();
                //const seats = $('#seats').val();
                
                if (!/^[A-Za-z\s]+$/.test(name)) {
                    alert('Name must contain only letters.');
                    valid = false;
                }

                if (graceTime <= 0 || !$.isNumeric(graceTime)) {
                    alert('Grace time must be a positive number.');
                    valid = false;
                }

                if (duration <= 0 || !$.isNumeric(duration)) {
                    alert('Duration must be a positive number.');
                    valid = false;
                }

                // if (seats <= 0 || seats > 20 || !$.isNumeric(seats)) {
                //     alert('Seats must be a number between 1 and 20.');
                //     valid = false;
                // }

                if (!valid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>
