<template>
    <Head title="Meeting Room Schedule" />

    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <h1 class="font-semibold text-xl">Schedule</h1>
                        </div>
                        <div class="my-4">
                            <select v-model="selectedRoom" class="border p-2 rounded">
                                <option v-for="room in page.props.rooms" :value="room.slug">
                                    {{ room.name }}
                                </option>
                            </select>
                        </div>
                        <div class="w-full mb-8">
                            <FullCalendar 
                                :events="calendarEvents"
                                :options="calendarOptions" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

const page = usePage();

const selectedRoom = ref(page.props.selectedRoom || 'inovasi'); // Ruangan default
const eventProps = ref(page.props.events || []); // Data event dari backend

const calendarEvents = ref([]);
const updateCalendarEvents = () => {
    calendarEvents.value = eventProps.value.map(event => ({
        title: event.title,
        start: event.start_date
    }));
};

updateCalendarEvents()

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true,
  editable: false,
  events: calendarEvents,
  eventDidMount(info) {
    console.log("Event Rendered: ", info.event.title);
  }
});

// Watch for room changes and fetch new events
watch(selectedRoom, async (newRoom) => {
    router.get('/schedule', { room: newRoom }, { 
        preserveState: true, 
        only: ['events'], // Optimize response to get only events
        onSuccess: (page) => {
            eventProps.value = page.props.events; // Update events
            updateCalendarEvents(); // Refresh calendar events
        }
    });
});

// Watch eventProps and update calendar events dynamically
watch(eventProps, () => {
    updateCalendarEvents();
});

</script>