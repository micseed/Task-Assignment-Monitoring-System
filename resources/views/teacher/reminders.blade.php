@extends('layouts.teacher_layout')

@section('title', 'Reminders — WMSU TAMS Teacher')
@section('page_title', 'Reminders & Notifications')

@section('teacher_content')

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Reminders &amp; Notifications</h2>
            <p class="text-sm text-gray-500 mt-1">Send bulk reminders to students who haven't submitted yet.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

        {{-- Left: Send Reminder Form --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-maroon-900 to-maroon-700 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-white">Send Reminder</h3>
                            <p class="text-xs text-maroon-300 mt-0.5">Notify non-submitters</p>
                        </div>
                    </div>
                </div>

                @if($publishedAssignments->isEmpty())
                    <div class="px-6 py-10 text-center text-sm text-gray-400">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        No published assignments yet. Publish an assignment first to send reminders.
                    </div>
                @else
                    <form method="POST" action="{{ route('teacher.reminders.send') }}" class="px-6 py-5 space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Select Assignment *</label>
                            <select name="assignment_id" id="reminder-assignment" required
                                    onchange="loadNonSubmitters(this.value)"
                                    class="w-full text-sm border border-gray-200 rounded-xl px-4 py-2.5 bg-white
                                           focus:outline-none focus:ring-2 focus:ring-maroon-700/20">
                                <option value="">Choose an assignment...</option>
                                @foreach($publishedAssignments as $asgn)
                                    <option value="{{ $asgn->id }}"
                                            data-class="{{ $asgn->academicClass->section }}"
                                            data-due="{{ $asgn->due_date->format('M d, Y') }}">
                                        {{ $asgn->title }} — {{ $asgn->academicClass->section }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Selected assignment info --}}
                        <div id="asgn-info" class="hidden bg-amber-50 border border-amber-100 rounded-xl px-4 py-3">
                            <p class="text-xs font-semibold text-amber-700">
                                <svg class="w-3.5 h-3.5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Due: <span id="asgn-due"></span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Message *</label>
                            <textarea name="message" rows="5" required
                                      placeholder="Hi, this is a reminder that the assignment '...' is due soon. Please submit your work before the deadline."
                                      class="w-full text-sm border border-gray-200 rounded-xl px-4 py-3
                                             focus:outline-none focus:ring-2 focus:ring-maroon-700/20 resize-none"></textarea>
                            <p class="text-[11px] text-gray-400 mt-1">This message will be recorded and sent to all students who have not yet submitted.</p>
                        </div>

                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="fillTemplate()"
                                    class="flex-1 py-2.5 border border-maroon-200 text-maroon-700 text-xs font-bold rounded-xl hover:bg-maroon-50 transition-colors">
                                Use Template
                            </button>
                            <button type="submit"
                                    class="flex-1 py-2.5 bg-gradient-to-r from-maroon-800 to-maroon-700 text-white text-xs font-bold
                                           rounded-xl hover:brightness-110 transition-all">
                                <svg class="w-3.5 h-3.5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                                Send Reminder
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        {{-- Right: Reminder History --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Reminder History</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Last 30 reminders sent</p>
                </div>

                @if($reminderHistory->isEmpty())
                    <div class="px-8 py-14 text-center">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <p class="text-sm text-gray-400 font-medium">No reminders sent yet.</p>
                        <p class="text-xs text-gray-400 mt-1">Send your first reminder using the form on the left.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($reminderHistory as $reminder)
                            <div class="px-6 py-4 hover:bg-gray-50/50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-xl bg-maroon-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-4.5 h-4.5 text-maroon-700" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                {{ $reminder->assignment->title }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $reminder->assignment->academicClass->section }}
                                                &nbsp;·&nbsp;
                                                <span class="capitalize">{{ str_replace('_', ' ', $reminder->target) }}</span>
                                            </p>
                                            <p class="text-xs text-gray-600 mt-2 leading-relaxed line-clamp-2 italic">
                                                "{{ $reminder->message }}"
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-xs font-medium text-gray-500">{{ $reminder->created_at->format('M d') }}</p>
                                        <p class="text-[11px] text-gray-400">{{ $reminder->created_at->format('g:i A') }}</p>
                                        <p class="text-[11px] text-gray-400 mt-1">{{ $reminder->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const select   = document.getElementById('reminder-assignment');
    const infoBox  = document.getElementById('asgn-info');
    const dueSp    = document.getElementById('asgn-due');
    const textarea = document.querySelector('textarea[name="message"]');

    function loadNonSubmitters(val) {
        if (!val) { infoBox.classList.add('hidden'); return; }
        const opt  = select.options[select.selectedIndex];
        dueSp.textContent = opt.dataset.due;
        infoBox.classList.remove('hidden');
    }

    function fillTemplate() {
        if (!select.value) { alert('Please select an assignment first.'); return; }
        const opt   = select.options[select.selectedIndex];
        const title = opt.text.split(' — ')[0];
        textarea.value =
            `Hi! This is a reminder that the assignment "${title}" is due on ${opt.dataset.due}.\n\n` +
            `Please make sure to submit your work before the deadline. ` +
            `Late or missing submissions will affect your grade.\n\n` +
            `If you have any questions, feel free to reach out.\n\nThank you!`;
    }
</script>
@endpush
