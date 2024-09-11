<template>
  <h2>Tickets: </h2>
  <ag-grid-vue @grid-ready="onGridReady" :gridOptions="gridOptions" @rowClicked="onRowClicked($event)" :key="rowData.id"
    :defaultColDef="defaultColDef" :rowData="rowData" :columnDefs="colDefs" style="height: 500px"
    class="ag-theme-quartz">
  </ag-grid-vue>
</template>

<script setup>
import { ref, shallowRef, onMounted, watch, defineComponent } from 'vue';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import customButton from '@/Components/customButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectView from '@/Components/SelectView.vue';


const props = defineProps(['tickets']);
const gridApi = shallowRef();
/* function formatDate(dateString) {
  const date = new Date(dateString);
  const readable = format(date, "short")

  return readable
} */

const gridOptions = {
  // Enable row selection with single click
  /* rowSelection: 'multiple',
  rowMultiSelectWithClick: true, */
  // Prevent deselection of previously selected rows when clicking on a new row
  suppressRowDeselection: true,
};
console.log(props.tickets.tickets)


const rowData = ref([]);
watch(
  () => props.tickets.tickets,
  () => {
    rowData.value = props.tickets.tickets;

  }
)

const defaultColDef = ref({
  minWidth: 100,
  flex: 1,
});

// Column Definitions: Defines the columns to be displayed.
const colDefs = ref([
  { field: "id", headerName: 'ID', maxWidth: 50, },
  { field: "created_at", headerName: 'Fecha', maxWidth: 100, },
  { field: "title", headerName: 'Titulo', },
  { field: "description", headerName: 'Descrpción', },
  {
    field: "status",
    headerName: 'Estatus', maxWidth: 200,
    editable: true,
    cellRenderer: 'SelectView',
    cellRendererParams: {
      values: ['Abierto', 'En proceso', 'Pendiente', 'Resuelto'], // Puedes pasar parámetros adicionales si es necesario
      name: 'status'
    },


    cellClassRules: {
      'status-cell status-open rounded-md': params => params.value == 'Abierto', // Change color if value > 20
      'status-cell status-progress rounded-md': params => params.value == 'En proceso', // Change color if value > 20
      'status-cell status-pending rounded-md': params => params.value == 'Pendiente', // Change color if value > 20
      'status-cell status-done rounded-md': params => params.value == 'Resuelto', // Change color if value > 20
    }
  },
  {
    field: "priority", headerName: 'Prioridad', maxWidth: 110,

    cellRenderer: 'SelectView',
    cellRendererParams: {
      values: ['Baja', 'Media', 'Alta', 'Urgente'], // Puedes pasar parámetros adicionales si es necesario
      name: 'priority'
    },
    cellClassRules: {
      'status-cell status-open rounded-md': params => params.value == 'Baja', // Change color if value > 20
      'status-cell status-progress rounded-md': params => params.value == 'Media', // Change color if value > 20
      'status-cell status-pending rounded-md': params => params.value == 'Alta', // Change color if value > 20
      'status-cell status-done rounded-md': params => params.value == 'Urgente', // Change color if value > 20
    }
  },
  { field: "user.name", headerName: 'Solicita', },
  { field: "assignee.name", headerName: 'Atiende', },
]);

const onGridReady = (params) => {
  gridApi.value = params.api;
  console.log("times")
  /* const updateData = (data) => {
    rowData.value = data;
  }; */

  /* fetch("https://www.ag-grid.com/example-assets/small-company-data.json")
    .then((resp) => resp.json())
    .then((data) => updateData(data)); */
};

onMounted(() => {
  rowData.value = props.tickets.tickets
})
function onRowClicked(params) {
  // Access the selected row ID, node, or data
  console.log("ok");
  console.log(params.data);
}

// expose the custom cell renderer for use within AG Grid
defineExpose({ customButton, PrimaryButton, SelectView })
</script>



<style>
tbody {
  background: #fff;
}

tbody tr {
  padding: 2px;
}

.status-cell {
  padding: 0 !important;
}

.status-open {
  background: #3795BD;
  color: #3795BD;
  /* Verde para "Abierto" */
}

.status-progress {
  background: #FF9100;
  color: #FF9100;
  /* Rojo para "Cerrado" */
}

.status-pending {
  background: #4A249D;
  color: #4A249D;
  /* Rojo para "Cerrado" */
}

.status-done {
  background: #13b961;
  color: #13b961;
  /* Azul para "Resuelto" */
}
</style>