<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assign Students to Assessment') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form id="assignForm">
                        @csrf
                        <input type="hidden" id="assessmentId" value="{{ $assessment->id }}">

                        <!-- Assignment Type -->
                        <div class="mb-4">
                            <label class="font-semibold mr-2">Assignment Type:</label>
                            <input type="radio" name="assignment_type" value="manual" id="manual" class="mr-1"> Manual
                            <input type="radio" name="assignment_type" value="auto_shuffle" id="auto_shuffle" class="mr-1"> Auto-Shuffle
                        </div>

                        <div id="manualOptions" class="hidden">
                            <label for="assignment_date" class="block font-semibold mb-1">Select Date</label>
                            <input type="date" id="assignment_date" class="form-control" min="{{ $assessment->start_date->format('d-m-Y') }}" max="{{ $assessment->end_date->format('d-m-Y') }}">
                        </div>

                        <div id="autoOptions" class="hidden">
                            <label for="auto_assignment_date" class="block font-semibold mb-1">Select Date</label>
                            <input type="date" id="auto_assignment_date" class="form-control" min="{{ $assessment->start_date->format('d-m-Y') }}" max="{{ $assessment->end_date->format('d-m-Y') }}">
                        </div>

                        <!-- Slot Display and Student Selection (hidden initially) -->
                        <div id="slotSelection" class="hidden">
                            <h3 class="text-xl font-semibold mb-2">Select Slot and Students</h3>
                            <div id="slotContainer"></div>
                            <button type="button" id="nextSlot" class="btn btn-primary mt-3">Next Slot</button>
                        </div>
                        <div id="noSlotContainer"></div>

                        <button type="button" id="saveAssignments" class="btn btn-success mt-4 hidden">Save & Publish Assignments</button>

                        <button type="button" id="autoAssignments" class="btn btn-success mt-4 hidden">Auto Assign & Publish</button>
                        <a href="{{ route('assessments.getSlotsForDate', $assessment->id) }}" class="btn btn-primary">Slots</a>
                        <a href="{{ route('assessments.scheduled') }}" class="btn btn-primary">Back to Assessments</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const manualOption = document.getElementById('manual');
    const autoShuffleOption = document.getElementById('auto_shuffle');
    const manualOptions = document.getElementById('manualOptions');
    const autoOptions = document.getElementById('autoOptions');
    const assignmentDate = document.getElementById('assignment_date');
    const autoAssignmentDate = document.getElementById('auto_assignment_date');
    const slotSelection = document.getElementById('slotSelection');
    const slotContainer = document.getElementById('slotContainer');
    const noSlotContainer = document.getElementById('noSlotContainer');
    const nextSlot = document.getElementById('nextSlot');
    const saveAssignments = document.getElementById('saveAssignments');
    const autoAssignments = document.getElementById('autoAssignments');
    const assessmentId = document.getElementById('assessmentId') ? document.getElementById('assessmentId').value : null;
    const assignmentDateInput = document.getElementById('assignment_date');
    let slotIndex = 0;
    let slots = [];
    //localStorage.setItem('selectedStudents', {});
    localStorage.removeItem('selectedStudents');
    let selectedStudents = JSON.parse(localStorage.getItem('selectedStudents')) || {};

    flatpickr("#assignment_date", {
        dateFormat: "d-m-Y",
        minDate: assignmentDateInput.getAttribute('min'),
        maxDate: assignmentDateInput.getAttribute('max')
    });

    flatpickr("#auto_assignment_date", {
        dateFormat: "d-m-Y",
        minDate: autoAssignmentDate.getAttribute('min'),
        maxDate: autoAssignmentDate.getAttribute('max')
    });

    // Show manual options when 'Manual' is selected
    manualOption.addEventListener('change', function () {
        manualOptions.classList.remove('hidden');
        slotSelection.classList.add('hidden');
        autoOptions.classList.add('hidden');
    });

    autoShuffleOption.addEventListener('change', function () {
        manualOptions.classList.add('hidden');
        slotSelection.classList.add('hidden');
        autoOptions.classList.remove('hidden');
    });

    // Fetch slots on date change
    assignmentDate.addEventListener('change', function () {
        noSlotContainer.innerHTML = '';
        noSlotContainer.classList.add('hidden');
        const selectedDate = assignmentDate.value;
        const generateSlotsUrl = "{{ route('assessments.generate_slots_form', $assessment->id )}}";
        $.ajax({
            url: window.appUrl + `/assessments/${assessmentId}/slots`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: { date: selectedDate },
            success: function (response) {
                console.log(response);
                slots = response.slots;
                if (slots.length === 0) {
                    noSlotContainer.classList.remove('hidden');
                    if(response.message == ''){
                        noSlotContainer.innerHTML = "<p>No slots generated for this date. <a href='"+generateSlotsUrl+"' class='btn btn-primary'>Click here</a> to generate slots</p>";
                    } else {
                        noSlotContainer.innerHTML = response.message;
                    }
                    noSlotContainer.classList.remove('hidden');
                } else {
                    slotIndex = 0;
                    displaySlot(slotIndex);
                    slotSelection.classList.remove('hidden');
                }
            },
        });
    });

    autoAssignmentDate.addEventListener('change', function () {
        noSlotContainer.innerHTML = '';
        noSlotContainer.classList.add('hidden');
        const selectedDate = autoAssignmentDate.value;
        const generateSlotsUrl = "{{ route('assessments.generate_slots_form', $assessment->id )}}";
        $.ajax({
            url: window.appUrl + `/assessments/${assessmentId}/slots`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: { date: selectedDate },
            success: function (response) {
                console.log(response);
                slots = response.slots;
                console.log(slots.length);
                if (slots.length === 0) {
                    noSlotContainer.classList.remove('hidden');
                    if(response.message == ''){
                        noSlotContainer.innerHTML = "<p>No slots generated for this date. <a href='"+generateSlotsUrl+"' class='btn btn-primary'>Click here</a> to generate slots</p>";
                    } else {
                        noSlotContainer.innerHTML = response.message;
                    }
                } else {
                    // slotIndex = 0;
                    // displaySlot(slotIndex);
                    // slotSelection.classList.remove('hidden');
                    autoAssignments.classList.remove('hidden');
                }
            },
        });
    });

    autoAssignments.addEventListener('click', function() {
        const selectedDate = autoAssignmentDate.value;
        $.ajax({
            url: window.appUrl + `/assessments/${assessmentId}/auto-assign`,
            type: 'POST',
            data: {
                date: selectedDate,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                alert(response.message);
                window.location.href = "{{ route('assessments.listSlots', $assessment->id) }}";
            },
            error: function(error) {
                alert('Error assigning students.');
            }
        });
    });

    // Display current slot and students
    function displaySlot(index) {
        const slot = slots[index];
        slotContainer.innerHTML = `
            <h4 class="text-lg font-semibold">Slot: ${slot.name}</h4>
            <ul id="studentList" class="list-disc ml-5"></ul>
        `;

        fetchStudents(slot.class_id);
    }

    // Fetch students for the class and populate checkboxes
    function fetchStudents(classId) {
        $.ajax({
            url: window.appUrl + `/assessments/${assessmentId}/students`,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: { class_id: classId },
            success: function (response) {
                const studentList = document.getElementById('studentList');
                studentList.innerHTML = '';
                response.students.forEach(student => {
                    if (!selectedStudents[student.id]) {
                        const studentItem = `<li><input type="checkbox" class="student-checkbox" data-student-id="${student.id}">${student.name}</li>`;
                        studentList.innerHTML += studentItem;
                    }
                });
            },
        });
    }

    // Save selected students to local storage when moving to the next slot
    nextSlot.addEventListener('click', function () {
        saveSelectedStudents();
        if (slotIndex < slots.length - 1) {
            slotIndex++;
            displaySlot(slotIndex);
        } else {
            saveAssignments.classList.remove('hidden');
        }
    });

    // Save selected students to local storage, including assessment_slot_id
    function saveSelectedStudents() {
        const slotId = slots[slotIndex].id; // Assuming the slot has an 'id' field

        document.querySelectorAll('.student-checkbox:checked').forEach(checkbox => {
            const studentId = checkbox.dataset.studentId;
            if (!selectedStudents[studentId]) {
                selectedStudents[studentId] = {
                    slot_id: slotId, // Store the assessment_slot_id
                    slot: slots[slotIndex].name,
                };
            }
        });
        localStorage.setItem('selectedStudents', JSON.stringify(selectedStudents));
    }

    // Save to database, including assessment_slot_id
    saveAssignments.addEventListener('click', function () {
        saveSelectedStudents();
        $.ajax({
            url: window.appUrl + '/assessments/saveAssignments',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                assessment_id: assessmentId,
                selected_students: selectedStudents,
                assessment_date: assignmentDateInput.value
            },
            success: function (response) {
                alert(response.message);
                localStorage.removeItem('selectedStudents');

                window.location.href = "{{ route('assessments.listSlots', $assessment->id) }}";
            },
        });
    });
});
</script>
</x-app-layout>