<template>
    <ag-grid-vue @grid-ready="onGridReady" :gridOptions="gridOptions" @rowClicked="onRowClicked($event)" :key="rowData.id"
      :defaultColDef="defaultColDef" :rowData="rowData" :columnDefs="colDefs" style="height: 500px"
      :pagination="true"
      class="ag-theme-quartz">
    </ag-grid-vue>
  </template>
  
  <script setup>
  import { ref, shallowRef, onMounted, watch } from 'vue';
  import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the Data Grid
  import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the Data Grid
  import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
  import customButton from '@/Components/customButton.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SelectView from '@/Components/SelectView.vue';
  import StatisfiedView from '@/Components/StatisfiedView.vue';
  
  
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
  
  
  const rowData = ref([]);
  
  watch(
    () => props.tickets,
    () => {
      
      rowData.value = props.tickets;
  
    }
  )
  
  const defaultColDef = ref({
    minWidth: 100,
    flex: 1,
  });
  
  const row = ref({})
  // Column Definitions: Defines the columns to be displayed.
  const colDefs = ref([
    
    { field: "id", headerName: 'ID', maxWidth: 50,  },
    { field: "created_at", headerName: 'Fecha', maxWidth: 150, },
    { field: "title", headerName: 'Titulo', filter: "agTextColumnFilter" },
    { field: "description", headerName: 'Descrpción', },
    {
      field: "status",
      headerName: 'Estatus', maxWidth: 200,
      editable: true,
      cellRendererParams: {
        values: ['Abierto', 'En proceso', 'Pendiente', 'Resuelto', 'Cerrado'], // Puedes pasar parámetros adicionales si es necesario
        name: 'status'
      },
  
  
      cellClassRules: {
        'status-cell status-open rounded-md pl-2': params => params.value == 'Abierto', // Change color if value > 20
        'status-cell status-progress rounded-md pl-2': params => params.value == 'En proceso', // Change color if value > 20
        'status-cell status-pending rounded-md pl-2': params => params.value == 'Pendiente', // Change color if value > 20
        'status-cell status-done rounded-md pl-2': params => params.value == 'Resuelto', // Change color if value > 20
        'status-cell status-close rounded-md pl-2': params => params.value == 'Cerrado', // Change color if value > 20
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
    rowData.value = props.tickets
  })
  function onRowClicked(params) {
    // Access the selected row ID, node, or data
    console.log("ok");
    console.log(params.data);
  }
  
  // expose the custom cell renderer for use within AG Grid
  defineExpose({ customButton, PrimaryButton, SelectView, StatisfiedView })
  </script>
  
  
  
  <style>
  tbody {
    background: #fff;
  }
  
  tbody tr {
    padding: 2px;
  }
  
  .status-cell {
    padding-left: 10px !important;
  }
  
  .status-open {
    background: #3795BD;
    color: #fff;
    /* Verde para "Abierto" */
  }
  
  .status-progress {
    background: #FF9100;
    color: #fff;
    /* Rojo para "Cerrado" */
  }
  
  .status-pending {
    background: #4A249D;
    color: #fff;
    /* Rojo para "Cerrado" */
  }
  
  .status-done {
    background: #13b961;
    color: #fff;
    /* Azul para "Resuelto" */
  }
  .status-close {
    background: #b86d1c;
    color: #fff;
    /* Azul para "Resuelto" */
  }
  .status-no-response {
    background: #7bc513;
    color: #fff;
    /* Azul para "Resuelto" */
  }
  </style>