@extends('layouts.student_layout')

@section('title', 'Deadlines Calendar')
@section('page_header', 'Deadlines Calendar')

@push('styles')
<style>
    /* Custom adjustments to FullCalendar styling to fit maroon/gold system aesthetics */
    .fc {
        --fc-border-color: #f3f4f6;
        --fc-button-bg-color: #800000;
        --fc-button-border-color: #800000;
        --fc-button-hover-bg-color: #600000;
        --fc-button-hover-border-color: #600000;
        --fc-button-active-bg-color: #400000;
        --fc-button-active-border-color: #400000;
        --fc-today-bg-color: rgba(128, 0, 0, 0.03);
        font-size: 0.875rem;
    }
    .fc-header-toolbar {
        padding: 1rem;
        background: #f9fafb;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 0 !important;
    }
    .fc-theme-standard .fc-scrollgrid {
        border-radius: 0 0 12px 12px;
        overflow: hidden;
    }
    .fc-event {
        cursor: pointer;
        padding: 2px 4px;
        border-radius: 6px;
        transition: transform 0.15s ease;
    }
    .fc-event:hover {
        transform: scale(1.02);
    }
</style>
@endpush

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- Left Main Column: Full Calendar view --}}
    <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div id="calendar"></div>
        </div>
    </div>

    {{-- Right Sidebar: Google Calendar Integration Control --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Google Calendar Panel --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-lg">
                    <i class="fa-brands fa-google"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">Google Calendar</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Sync Integration</p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4 space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Status</span>
                    @if($isGoogleConnected)
                        <span class="px-2 py-0.5 text-xs font-bold text-emerald-700 bg-emerald-50 rounded border border-emerald-100 flex items-center gap-1">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Connected
                        </span>
                    @else
                        <span class="px-2 py-0.5 text-xs font-bold text-gray-500 bg-gray-50 rounded border border-gray-200">
                            Disconnected
                        </span>
                    @endif
                </div>

                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500 font-medium">Synced Tasks</span>
                    <span class="font-bold text-gray-800">{{ $syncedCount }} deadlines</span>
                </div>
            </div>

            {{-- Auto-sync toggle form --}}
            <form action="{{ route('student.profile.update') }}" method="POST" class="border-t border-gray-100 pt-4 block">
                @csrf
                <input type="hidden" name="first_name" value="{{ auth()->user()->first_name }}">
                <input type="hidden" name="last_name" value="{{ auth()->user()->last_name }}">
                <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                <input type="hidden" name="email_notifications" value="{{ auth()->user()->email_notifications ? '1' : '0' }}">
                
                <div class="flex items-center justify-between">
                    <div>
                        <label for="calendar_notifications_toggle" class="text-xs font-bold text-gray-700 uppercase tracking-wider block">Auto-Sync Deadlines</label>
                        <span class="text-[10px] text-gray-400">Syncs automatically to your Google Calendar</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="calendar_notifications_toggle" name="calendar_notifications" value="1"
                               class="sr-only peer" {{ $isGoogleConnected ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-maroon-800"></div>
                    </label>
                </div>
            </form>

            {{-- Sync Button --}}
            @if($isGoogleConnected)
                <form action="{{ route('student.calendar.sync') }}" method="POST" class="m-0 pt-2">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-maroon-800 to-maroon-700 text-white font-bold text-xs rounded-xl shadow-md transition-all duration-200 hover:brightness-110 active:scale-[0.98]">
                        <i class="fa-solid fa-sync text-xs"></i>
                        Force Manual Sync
                    </button>
                </form>
            @else
                <button type="button" onclick="alert('Please enable Auto-Sync Deadlines toggle first to grant permissions and connect Google Calendar.')"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-400 font-bold text-xs rounded-xl cursor-not-allowed">
                    <i class="fa-solid fa-sync text-xs"></i>
                    Sync Disabled
                </button>
            @endif
        </div>

        {{-- Next Deadline Card --}}
        <div class="bg-gradient-to-br from-maroon-900 to-maroon-800 text-white rounded-2xl p-6 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white/5 rounded-full blur-xl -mr-6 -mt-6"></div>
            <h4 class="text-xs font-bold text-gold-400 uppercase tracking-widest">TAMS Tips</h4>
            <h3 class="font-extrabold text-base mt-2">Reminders On The Go</h3>
            <p class="text-xs text-white/70 mt-1.5 leading-relaxed">
                By enabling Google Calendar sync, you will receive notifications directly on your phone 24 hours before any deadline!
            </p>
        </div>

    </div>
</div>

{{-- Event click detailed modal --}}
<div id="calendar-event-modal" data-modal-backdrop="true"
     class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 items-center justify-center p-4">
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all animate-[fadeInUp_0.2s_ease-out]">
        
        {{-- Modal header --}}
        <div class="px-6 py-4 bg-maroon-850 text-white flex justify-between items-center">
            <h3 class="font-bold text-base flex items-center gap-2">
                <i class="fa-solid fa-calendar-check text-gold-500"></i> Assignment Info
            </h3>
            <button onclick="closeModal('calendar-event-modal')" class="text-white/80 hover:text-white transition-colors">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>

        {{-- Modal body --}}
        <div class="p-6 space-y-4">
            <div>
                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Subject</label>
                <p id="modal-event-subject" class="text-sm font-semibold text-gray-800 mt-0.5">Subject Name</p>
            </div>

            <div>
                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Assignment</label>
                <p id="modal-event-title" class="text-base font-bold text-gray-900 mt-0.5">Title</p>
            </div>

            <div>
                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Deadline</label>
                <p id="modal-event-due" class="text-sm font-semibold text-maroon-700 mt-0.5">Due Date</p>
            </div>

            <div>
                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Instructions Snapshot</label>
                <div class="mt-1 bg-gray-50 border border-gray-150 p-3 rounded-lg text-xs text-gray-600 max-h-32 overflow-y-auto whitespace-pre-wrap" id="modal-event-desc">
                    Instructions details go here.
                </div>
            </div>
        </div>

        {{-- Modal footer --}}
        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-2 border-t border-gray-100">
            <button onclick="closeModal('calendar-event-modal')"
                    class="px-4 py-2 text-xs font-semibold text-gray-500 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors">
                Close
            </button>
            <a id="modal-event-link" href="#"
               class="px-4 py-2 text-xs font-bold text-white bg-maroon-800 rounded-lg hover:bg-maroon-900 transition-colors flex items-center gap-1.5">
                <i class="fa-solid fa-folder-open"></i> Go to Submission Page
            </a>
        </div>

    </div>
</div>

@push('scripts')
{{-- Load FullCalendar package --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        
        // Dynamic event array passed from controller
        const eventsData = @json($events);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'prev,today,next'
            },
            themeSystem: 'standard',
            events: eventsData,
            eventClick: function(info) {
                // Prevent redirection immediately
                info.jsEvent.preventDefault();
                
                // Set modal data fields from properties
                document.getElementById('modal-event-title').textContent = info.event.title.replace(/\s\([A-Z0-9-]+\)$/, '');
                document.getElementById('modal-event-subject').textContent = info.event.extendedProps.subject;
                document.getElementById('modal-event-due').textContent = info.event.extendedProps.due_date;
                document.getElementById('modal-event-desc').textContent = info.event.extendedProps.description || 'No instruction details provided.';
                document.getElementById('modal-event-link').href = info.event.url;

                // Toggle visibility
                openModal('calendar-event-modal');
            }
        });

        calendar.render();
    });
</script>
@endpush
@endsection
