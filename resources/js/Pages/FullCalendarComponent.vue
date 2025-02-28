<template>
    <div>
      <FullCalendar 
        :events="calendarEvents"
        :options="calendarOptions" 
      />
    </div>
  </template>
  
  <script>
  import FullCalendar from "@fullcalendar/vue3";
  import dayGridPlugin from "@fullcalendar/daygrid";
  import timeGridPlugin from "@fullcalendar/timegrid";
  import interactionPlugin from "@fullcalendar/interaction";

  export default {
    components: { FullCalendar },
    data() {
      return {
        calendarEvents: [
          { title: "Event 1", date: "2025-02-15" },
          { title: "Event 2", date: "2025-02-16" },
        ],
        calendarOptions: {
          plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
          initialView: "dayGridMonth",
          selectable: true,
          editable: true,
        },
      };
    },
  };
  </script>
  