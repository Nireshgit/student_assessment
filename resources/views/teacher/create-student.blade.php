<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Student') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form id="student-form" method="POST" action="{{ route('teacher.createStudent') }}">
                        @csrf

                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Phone -->
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <!-- Class -->
                        <div class="mt-4">
                            <x-input-label for="class_id" :value="__('Class')" />
                            <select id="class_id" class="block mt-1 w-full" name="class_id" required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->standard }} - {{ $class->section }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('class_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Create Student') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#student-form').on('submit', function(e) {
                let isValid = true;
                const name = $('#name').val();
                const email = $('#email').val();
                const phone = $('#phone').val();

                // Reset previous errors
                $('.input-error').remove();

                // Validate name
                if (name.trim() === "") {
                    isValid = false;
                    $('#name').after('<div class="input-error text-danger">Name cannot be empty.</div>');
                }

                // Validate email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    isValid = false;
                    $('#email').after('<div class="input-error text-danger">Please enter a valid email address.</div>');
                }

                // Validate phone
                if (!/^\d{10}$/.test(phone)) {
                    isValid = false;
                    $('#phone').after('<div class="input-error text-danger">Phone number must be 10 digits.</div>');
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>