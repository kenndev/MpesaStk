<script setup>
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeButton from '@/Components/Button.vue';
import Layout from '@/Layouts/Layout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    errors: Object,
});

const form = useForm({
    amount: '',
    phonenumber: '',
});

const submit = () => {
    form.post(route('process-mpesa'), {
        // onFinish: () => form.reset(),
        onSuccess: () => form.reset(),
    });
}

</script>
<template>

    <Head title="Mpesa" />
    <Layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mpesa stk push
            </h2>
        </template>
        <div class="py-12">
            <div class="flex flex-col items-center max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="w-full sm:max-w-md shadow-md overflow-hidden sm:rounded-lg overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        
                        <div v-if="$page.props.flash.errormessage" class="flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert">
                            <svg class="fill-current w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path
                                    d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z" />
                            </svg>
                            <p>{{ $page.props.flash.errormessage }}</p>
                        </div>
                        <form @submit.prevent="submit">
                            <div>
                                <BreezeLabel for="amount" value="Amount" />
                                <BreezeInput id="amount" v-model="form.amount" type="number" class="mt-1 block w-full"
                                    required autofocus autocomplete="amount" />
                                <div class="text-red-600" v-if="errors.amount">{{ errors.amount }}</div>
                            </div>
                            <div class="mt-4">
                                <BreezeLabel for="phonenumber" value="Phonenumber" />
                                <BreezeInput id="phonenumber" v-model="form.phonenumber" type="number" required
                                    class="mt-1 block w-full" autofocus autocomplete="phonenumber" />
                                <div class="text-red-600" v-if="errors.phonenumber">{{ errors.phonenumber }}</div>
                            </div>
                            <div class="mt-4 flex flex-col items-center">
                                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Process Payment
                                </BreezeButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </Layout>
</template>
<style>
</style>