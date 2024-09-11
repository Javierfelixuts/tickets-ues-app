<script setup>
import { onMounted } from 'vue';

const props = defineProps(['tickets']);
console.log(props.tickets);

onMounted(() => {
    console.log("render");
})

function formatDate(dateString) {
    const date = new Date(dateString);
    // Then specify how you want your dates to be formatted
    return new Intl.DateTimeFormat('default', { dateStyle: 'short' }).format(date);
}
const colorPriority = {
    'Baja': 'blue',
    'Media':'green',
    'Alta' : 'orange',
    'Urgente': 'red'
}
const colorStatus = {
    'Abierto': '#28a745',
    'Cerrado':'#dc3545',
    'Resuelto' : '#007bff',
    'Pendiente': '#ffc107',
    'En proceso': '#fd7e14',
}
</script>
<template>
    <div class="my-5">
        <table class="border-collapse table-fixed w-full text-sm">
            <thead>
                <tr class="bg-slate-600">

                    <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 dark:text-slate-200 text-left">Date</th>
                    <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 dark:text-slate-200 text-left">Title</th>
                    <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 dark:text-slate-200 text-left">Description</th>
                    <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 dark:text-slate-200 text-left max-w-0.5">Status</th>
                    <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 dark:text-slate-200 text-left">Priority</th>
                    <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 dark:text-slate-200 text-left">Requester</th>
                    <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 dark:text-slate-200 text-left">Assigned</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ticket of props.tickets.tickets" :key="ticket.id">
                    <td>{{ formatDate(ticket.created_at) }}</td>
                    <td>{{ ticket.title }}</td>
                    <td>{{ ticket.description }}</td>
                    <td :style="{background: colorStatus[ticket.status], color: '#fff'}">{{ ticket.status }}</td>
                    <td :style="{color: colorPriority[ticket.priority]}">{{ ticket.priority }}</td>
                    <td>{{ ticket.user?.name }}</td>
                    <td>{{ ticket.assignee?.name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style>
tbody {
    background: #fff;
}
tbody tr {
    padding: 2px;
}
.status-abierto {
  color: #28a745; /* Verde para "Abierto" */
}

.status-cerrado {
  color: #dc3545; /* Rojo para "Cerrado" */
}

.status-resuelto {
  color: #007bff; /* Azul para "Resuelto" */
}

</style>