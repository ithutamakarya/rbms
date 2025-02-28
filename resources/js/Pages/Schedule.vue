<template>
    <Head title="Meeting Room Schedule" />

    <AuthenticatedLayout>
        <div>
            <div class="max-w-7xl mx-auto">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center">
                            <h1 class="font-semibold text-xl">Jadwal Rapat</h1>
                        </div>
                        <div class="my-4">
                            <label for="room">Ruang <span class="pr-4">: </span></label>
                            <select id="room" v-model="selectedRoom" class="border py-1 px-2 rounded-lg w-64">
                                <option selected value="">Semua Ruangan</option>
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

        <!-- Modal -->
        <div v-if="showModal" id="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg w-2/5 z-50">
                <h2 class="text-xl mb-2 font-bold">Detail Rapat</h2>
                <table>
                    <tbody>
                        <tr>
                            <th class="text-left py-1 w-[20%] content-baseline">Agenda</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ selectedEvent.modalTitle }}</td>
                        </tr>
                        <tr>
                            <th class="text-left py-1 w-[20%] content-baseline">Ruang</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ selectedEvent.room }}</td>
                        </tr>
                        <tr>
                            <th class="text-left py-1 w-[20%] content-baseline">Tanggal Mulai</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ formatDate(selectedEvent.start) }}</td>
                        </tr>
                        <tr v-if="selectedEvent.end">
                            <th class="text-left py-1 w-[20%] content-baseline">Tanggal Selesai</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ formatDate(selectedEvent.end) }}</td>
                        </tr>
                        <tr>
                            <th class="text-left py-1 w-[20%] content-baseline">Jam Mulai</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ formatStandardTime(selectedEvent.start_hour) }}</td>
                        </tr>
                        <tr>
                            <th class="text-left py-1 w-[20%] content-baseline">Jam Selesai</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ formatStandardTime(selectedEvent.finish_hour) }}</td>
                        </tr>
                        <tr>
                            <th class="text-left py-1 w-[20%] content-baseline">PIC</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ selectedEvent.requester.name }}
                                <span v-if="selectedEvent.requester.organization">- {{ selectedEvent.requester.organization.shortname }}</span></td>
                        </tr>
                        <tr>
                            <th class="text-left py-1 w-[20%] content-baseline">Catatan</th>
                            <td class="pl-4 pr-2 py-1 w-[4px] content-baseline">: </td>
                            <td class="text-left py-1">{{ selectedEvent.notes }}</td>
                        </tr>

                    </tbody>
                </table>
                <div class="flex justify-end">
                    <button @click="showModal = false" class="mt-4 bg-gray-500 text-white px-4 py-1 rounded-lg">Close</button>
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
import { formatStandardTime, formatDate } from '../../helpers/helpers';

const page = usePage();

const selectedRoom = ref(page.props.selectedRoom || ''); // Ruangan default
const eventProps = ref(page.props.events || []); // Data event dari backend

// Modal state
const showModal = ref(false);
const selectedEvent = ref({});

// Function to show modal with event details
const openModal = (event) => {
    console.log(event.event)
    selectedEvent.value = {
        title: event.event.title,
        modalTitle: event.event.extendedProps.modalTitle,
        room: event.event.extendedProps.room,
        start: event.event.startStr,
        end: event.event.endStr,
        notes: event.event.extendedProps.notes,
        requester: event.event.extendedProps.requester,
        start_hour: event.event.extendedProps.start_hour,
        finish_hour: event.event.extendedProps.finish_hour,
    };
    showModal.value = true;
};

// Prepare calendar events
const calendarEvents = ref([]);
const updateCalendarEvents = () => {
    calendarEvents.value = eventProps.value.map(event => ({
        id: event.id,
        title: `${event.room.name} - ${event.title}`,
        modalTitle: event.title,
        start: event.start_date,
        end: event.end_date,
        notes: event.notes || '-',
        requester: event.requester,
        room: event.room.name,
        start_hour: event.start_hour,
        finish_hour: event.finish_hour,
    }));
};

updateCalendarEvents()

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  selectable: true,
  editable: false,
  events: calendarEvents,
  eventClick: openModal // Handle event click
});

// Watch for room changes and fetch new events
watch(selectedRoom, async (newRoom) => {
    let queryParam = {}

    if (newRoom !== '') {
        queryParam = { room: newRoom }
    }
    
    router.get('/schedule', queryParam, { 
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

<style>
#showModal {
    z-index: 999;
}

.fc-event-title.fc-sticky,
.fc-event-title-container {
    cursor: pointer;
}
</style>