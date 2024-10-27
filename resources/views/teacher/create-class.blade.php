<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Class') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <form id="class-form" method="POST" action="{{ route('teacher.createClass') }}">
                        @csrf

                        <!-- Standard -->
                        <div>
                            <x-input-label for="standard" :value="__('Standard')" />
                            <x-text-input id="standard" class="block mt-1 w-full" type="number" name="standard" :value="old('standard')" required />
                            <x-input-error :messages="$errors->get('standard')" class="mt-2" />
                        </div>

                        <!-- Section -->
                        <div class="mt-4">
                            <x-input-label for="section" :value="__('Section')" />
                            <x-text-input id="section" class="block mt-1 w-full" type="text" name="section" :value="old('section')" required />
                            <x-input-error :messages="$errors->get('section')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Create Class') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#class-form').on('submit', function(e) {
                let isValid = true;
                const standard = $('#standard').val();
                const section = $('#section').val();

                $('.input-error').remove();

                if (!$.isNumeric(standard) || standard <= 0) {
                    isValid = false;
                    $('#standard').after('<div class="input-error text-danger">Please enter a valid standard between 1 to 12.</div>');
                }

                if (typeof section !== "string" || section.trim() === "" || !isNaN(section.trim())) {
                    isValid = false;
                    $('#section').after('<div class="input-error text-danger">Section cannot be empty and should be string such A or B.</div>');
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</x-app-layout>
<script>
    $(document).ready(function() {
        $('#create-class-form').on('submit', function(event) {
            const standard = $('#standard').val();
            const section = $('#section').val();

            if (!standard || isNaN(standard)) {
                alert("Please enter a valid numeric value for Standard.");
                event.preventDefault();
            }

            if (!section || !/^[a-zA-Z]+$/.test(section)) {
                alert("Please enter a valid string for Section.");
                event.preventDefault();
            }
        });
    });
</script>
