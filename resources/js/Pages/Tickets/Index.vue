<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, defineProps } from 'vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { useForm, Head } from '@inertiajs/vue3';
import TableAgGrid from '@/Components/TableAgGrid.vue';
import axios from 'axios';
const props = defineProps(['users', 'tickets', 'start_date', 'end_date']);

const form = useForm({
    title: '',
    description: '',
    status: 'Abierto',
    priority: '',
    assignee_id: '',
});

const filterByDate = useForm({
    start_date: props.start_date != '' ? props.start_date : '',
    end_date: props.end_date != '' ? props.end_date : ''
});

const getTickets = useForm({})

const isModalOpen = ref(false);
const openModal = () => isModalOpen.value = !isModalOpen.value;
const submit = () => {
    form.post(route('tickets.store'), {
        onSuccess: () => form.reset(),
    })
}

const exportToExcel = () => {
    console.log({filterByDate});
    if(filterByDate.start_date != '' && filterByDate.end_date != ''){
       /*  const start_date = '2024-08-15'; 
        const end_date = '2024-09-27'; */
        window.location.href = `tickets/export?start_date=${filterByDate.start_date}&end_date=${filterByDate.end_date}`;
    }
}
const getFilterByDates = () => {
    filterByDate.get(route('tickets.index'), {
        onSuccess: () => form.reset(),
    })
    console.log("filterByDate: ", filterByDate);
}
const resetFilters = () => {
    getTickets.get(route('tickets.index'));
}


</script>

<template>

    <Head title="Tickets"></Head>
    <AuthenticatedLayout>

        <PrimaryButton class="m-2" @click="openModal">Crear Nuevo Ticket</PrimaryButton>
        <Modal :closeable="true" :show="isModalOpen">
            <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
                <div class="w-full flex justify-end">
                    <PrimaryButton class="text-rigth" @click="openModal">cancelar</PrimaryButton>
                </div>
                <h1>Crear Ticket:</h1>
                <form @submit.prevent="submit">
                    <InputLabel for="motivo" value="Motivo" />
                    <input v-model="form.title"
                        class="shadow appearance-none border rounded w-full my-2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="username" type="text" placeholder="Title" />
                    <InputError :message="form.errors.title" class="mt-2" />

                    <InputLabel for="description" value="Description" />

                    <textarea id="description" name="description" v-model="form.description" placeholder="Descripción"
                        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>

                    <InputLabel for="prioridad" value="Prioridad" />
                    <!-- <select   v-model="form.status" class="my-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" name="status" id="status">
                    <option selected>Set status</option>
                    <option    v-for="st of ['Abierto','En proceso', 'Cerrado', 'Pendiente', 'Resuelto']" :value="st">{{ st }}</option>
                </select> -->
                    <select v-model="form.priority"
                        class="my-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                        name="prority" id="prority">
                        <option v-for="pr of ['Baja', 'Media', 'Alta', 'Urgente']" :value="pr">{{ pr }}</option>
                    </select>
                    <InputLabel for="Asignar" value="Asignar a:" />
                    <select v-model="form.assignee_id"
                        class="my-2 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                        name="user" id="user">
                        <option v-for="user of users" :value="user.id">{{ user.name }}</option>
                    </select>
                    <InputError :message="form.errors.message" class="mt-2" />
                    <PrimaryButton class="mt-4" @click="isModalOpen = false">Crear</PrimaryButton>

                </form>

            </div>
        </Modal>
        <div class="block ">

            <input v-model="filterByDate.start_date" class="m-2" type="date">
            <input v-model="filterByDate.end_date" class="m-2" type="date">
            <button class="bg-slate-600 text-white px-4 py-2 m-2" @click="getFilterByDates"
                :disabled="filterByDate.processing"> FILTRAR


            </button>
            <button v-if="filterByDate.start_date != ''" class="bg-slate-600 text-white px-4 py-2 m-2"
                @click="resetFilters"> LIMPIAR FILTROS</button>
            <button class="bg-slate-600 text-white px-4 py-2 m-2" @click="exportToExcel"> EXPORTAR A EXCEL</button>
        </div>
        <progress v-if="filterByDate.progress" :value="filterByDate.progress.percentage" max="100">
            {{ filterByDate.progress.percentage }}%
        </progress>
        <TableAgGrid :tickets="tickets" ></TableAgGrid> 

    </AuthenticatedLayout>
</template>