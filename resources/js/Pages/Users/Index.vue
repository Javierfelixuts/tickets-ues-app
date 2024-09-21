<template>
    <Head title="Usuarios" ></Head>
    <AuthenticatedLayout>

        <h3>Usuarios:</h3>
        <PrimaryButton class="m-2" @click="openModal">Crear Nuevo Ticket</PrimaryButton>
        
        <Modal 
            :closeable="true"
            :show="isModalOpen">
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <div class="w-full flex justify-end">
                <PrimaryButton class="text-rigth" @click="openModal">cancelar</PrimaryButton>
            </div>
            <h1>Crear usuario:</h1>
            <form @submit.prevent="submit">
                <InputLabel for="name" value="Nombre" />
                <input v-model="form.name" class="shadow appearance-none border rounded w-full my-2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Nombre" />
                <InputError :message="form.errors.name" class="mt-2" />
                
                <InputLabel for="email" value="Correo" />
                
                <input v-model="form.email" class="shadow appearance-none border rounded w-full my-2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="text" placeholder="Correo" />
                <InputError :message="form.errors.email" class="mt-2" />
                
                
                <InputLabel for="password" value="Contraseña" />
                <input v-model="form.password" class="shadow appearance-none border rounded w-full my-2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="Contraseña" />
                <InputError :message="form.errors.password" class="mt-2" />
                
               
                <InputLabel for="password" value="Confirmar contraseña" />
                <input v-model="form.password_confirmation" class="shadow appearance-none border rounded w-full my-2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="confirm-password" placeholder="Confirma contraseña" />
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
             
                <PrimaryButton class="mt-4" @click="isModalOpen = false">Crear</PrimaryButton>
                
            </form>
            
        </div>
    </Modal>
        <ag-grid-vue  :defaultColDef="defaultColDef" :rowData="rowData" :columnDefs="colDefs"
        style="height: 500px" class="ag-theme-quartz">
    </ag-grid-vue>
</AuthenticatedLayout>
</template>

<script setup>
import { AgGridVue } from 'ag-grid-vue3';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { ref, watch } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps(['users'])
const rowData = ref(props.users);
rowData.value = rowData.value.map(row => {row.edit = 'editar'; return row});
      console.log(rowData.value)
const defaultColDef = ref({
    minWidth: 200,
    
    flex: 1,
});
const colDefs = ref([

    { field: "id",  headerName: 'Id', maxWidth: 150, },
    { field: "name", editable: true, headerName: 'Nombre', maxWidth: 150, },
    { field: "email", headerName: 'Email', maxWidth: 150, },
    { field: "edit", headerName: 'Editar'}
])
const isModalOpen = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

watch(
  () => props.users,
  () => {
      rowData.value = props.users;
      

  }
)
const openModal = () => isModalOpen.value = !isModalOpen.value;
const submit = () => {
    form.post(route('users.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}
</script>