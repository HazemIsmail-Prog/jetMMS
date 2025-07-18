<x-customer-layout>
    <div x-data="cancelSurveyComponent" class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">

        <template x-if="cancelSurveyCount == 0">
            <div>
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-semibold whitespace-pre-line text-gray-900 dark:text-gray-100">{{ __('messages.cancel_survey_message_title') }}</h1>
                </div>
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-400">{{ __('messages.cancel_survey_message.line_1') }}</p>
                    <table class="w-full rounded-lg overflow-hidden my-4">
                        <tbody>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ __('messages.order_number') }}</td>
                                <td class="px-4 py-2 text-start font-bold text-gray-900 dark:text-gray-100">{{ $order->id }}</td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-700 mt-2">
                                <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ __('messages.department') }}</td>
                                <td class="px-4 py-2 text-start font-bold text-gray-900 dark:text-gray-100">{{ $order->department->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="text-gray-600 dark:text-gray-400">{{ __('messages.cancel_survey_message.line_2') }}</p>
                    <p class="text-gray-600 dark:text-gray-400 font-bold">{{ __('messages.cancel_survey_message.line_3') }}</p>
                </div>
                <form x-on:submit.prevent="submitForm" class="space-y-4">
                    <div class="space-y-4">
                        <template x-for="reason in reasons" :key="reason.id">
                            <label class="flex items-center gap-3 p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="radio" x-model="cancelReason" x-bind:value="reason.id" class="text-primary-600">
                                <span class="text-gray-700 dark:text-gray-300" x-text="reason.name"></span>
                            </label>
                        </template>
                    </div>
                    <p class="text-red-500" x-show="errors.includes('cancelReason')" x-cloak>
                        {{ __('messages.cancel_reason_required') }}
                    </p>
                    <div
                        class="mt-4"
                        x-transition
                        x-show="cancelReason === 'other'"
                        x-cloak
                    >
                        <textarea
                            x-ref="otherReasonInput"
                            x-model="otherReason"
                            rows="3"
                            class="w-full rounded-lg  dark:bg-gray-700 dark:text-gray-300 shadow-sm "
                            :class="{
                                'border-red-500 dark:border-red-500 focus:ring-0 ': errors.includes('otherReason'),
                                'border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500': !errors.includes('otherReason')
                            }"
                            placeholder="{{ __('messages.other_reason_placeholder') }}"
                        ></textarea>
                    </div>
                    <div class="flex justify-center mt-6">
                        <x-button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                            {{ __('messages.submit_feedback') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </template>

        <template x-if="cancelSurveyCount > 0">
            <div class="text-center p-8">
                <p class="text-xl font-bold text-emerald-500 dark:text-emerald-400 mb-8">{{ __('messages.already_submitted_survey') }}</p>
                <p class="text-gray-600 dark:text-gray-400">{{ __('messages.thank_you_feedback') }}</p>
            </div>
        </template>

    </div>
    
    <script>
        function cancelSurveyComponent() {
            return {
                order: @json($order),
                reasons: @json($reasons),
                cancelSurveyCount: @json($order->cancelSurvey ? 1 : 0),
                cancelReason: null,
                otherReason: null,
                errors: [],
                init() {
                    this.$watch('cancelReason', (value) => {  
                        this.errors = [];
                        this.otherReason = null;
                        if (value === 'other') {
                            this.$nextTick(() => {
                                this.$refs.otherReasonInput.focus();
                            });
                        }
                    });
                },
                submitForm() {
                    if (!this.validateForm()) {
                        return;
                    }
                    axios.post('/cancel-survey/{{ $order->id }}', {
                        cancelReason: this.cancelReason,
                        otherReason: this.otherReason
                    }).then(response => {
                        this.cancelSurveyCount = 1;
                    }).catch(error => {
                        console.log(error);
                    });
                },

                validateForm() {
                    this.errors = [];
                    if (!this.cancelReason) {
                        this.errors.push('cancelReason');
                        return false;
                    }
                    if (this.cancelReason === 'other' && (!this.otherReason || this.otherReason.trim() === '')) {
                        this.errors.push('otherReason');
                        return false;
                    }
                    return true;
                }
            }
        }
    </script>
</x-customer-layout>