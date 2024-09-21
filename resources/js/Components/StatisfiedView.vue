<template>
    

    <div v-once v-if="params.data.status == 'Resuelto'">
        <select
            class="border border-gray-300 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            v-model="currentValue" @change="onChange">
            <option  v-for="option in params.values" :key="option" :value="option" :selected="option == currentValue">{{ option }}</option>
        </select>
    </div>
    <div v-else class="px-2">
        <div>Sin responder</div>
    </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, defineExpose } from 'vue';
import { useForm } from '@inertiajs/vue3';
// Define las propiedades del componente
/* props que debes de mandar en ag grid
cellRendererParams: {
      values: ['Baja', 'Media', 'Alta', 'Urgente'], // Puedes pasar parámetros adicionales si es necesario
      name: 'priority'
    },

*/
const props = defineProps({
    params: {
        type: Object,
        required: true
    },
});
const currentValue = ref(props.params.value);
const emit = defineEmits(['valueChange']);

const form = useForm({
    [props.params.name]: currentValue.value, // Estado inicial del ticket
    ticket_id: props.params.data.id // Incluye el ID del ticket si es necesario
});
// Maneja el cambio de selección
const onChange = (event) => {
    console.log("emit", event.target.value)
    form[props.params.name] = event.target.value;
    console.log("form: ", form);
    form.put(route('tickets.update', form.ticket_id), {
        onSuccess: () => form.reset(), // Resetea el formulario después de una actualización exitosa
    });
    emit('valueChange', event.target.value);
};

// Actualiza currentValue cuando props.value cambia
/* watch(() => props.params, (newValue) => {
    console.log("wooow");
    currentValue.value = newValue;
}); */

// Implementa getValue() para que AG Grid pueda obtener el valor del componente
const getValue = () => {
    return currentValue.value;
};

// Expone getValue para AG Grid
defineExpose({ getValue });
</script>
<style scoped>
select {
    cursor: pointer;
    width: 100%;
    background: transparent;
    border: none;
    color: #000000;
    padding: 0;
    -webkit-appearance: none;
    /* Eliminar estilos predeterminados en navegadores WebKit */
    -moz-appearance: none;
    /* Eliminar estilos predeterminados en Firefox */
    appearance: none;
    /* Eliminar estilos predeterminados en otros navegadores */
    padding: 10px;
    padding-right: 30px;
    /* Espacio para la flecha */
    font-size: 16px;
    color: #333;
    /* Color del texto */
    /* Usar una imagen de flecha personalizada */
    background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="%23FFFFFF" d="M4 6l4 4 4-4z"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    /* Posiciona la flecha a la derecha */
    background-size: 12px;
    /* Ajusta el tamaño de la flecha */

}

select:focus {
    color: #ffffff;
    /*  background: transparent;
        outline: transparent !important;
        outline-offset: 0px !important;
        outline: transparent !important;
        outline-offset: 0px !important;
        --tw-ring-color: transparent !important;
        padding: 0; */
}

select:not(:focus) {
    color: #fff;
}

select option {
    color: #000;
    /* Asegura que las opciones dentro del select tengan un color negro */
}
</style>