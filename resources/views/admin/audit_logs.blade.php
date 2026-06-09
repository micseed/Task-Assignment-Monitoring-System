@extends('layouts.admin_layout')

@section('title', 'Audit Logs — WMSU TAMS Admin')

@section('admin_content')

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Audit Activity Logs</h2>
        <p class="text-sm text-gray-500 mt-1">Track system operations, configuration changes, user modifications, and security logs.</p>
    </div>

    {{-- Search Filter --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
        <form method="GET" action="{{ route('admin.audit_logs') }}" class="flex gap-3 items-end">
            <div class="flex-1">
                <label for="search" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-1.5">
                    Search Action / Description / Actor
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Search logs..."
                           class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm text-gray-700
                                  placeholder-gray-400 outline-none focus:border-maroon-700 focus:ring-2 focus:ring-maroon-700/15
                                  focus:bg-white transition-all" />
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="flex items-center gap-1.5 px-5 py-2.5 border border-gray-200 rounded-xl bg-gray-50 text-sm font-semibold text-gray-600 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.audit_logs') }}"
                       class="flex items-center justify-center w-10 h-10 border border-gray-200 rounded-xl text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Audit Logs Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider w-40">Timestamp</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Actor</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider w-32">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-gray-700 font-mono">{{ $log->created_at->format('Y-m-d H:i') }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-4">
                                @if($log->user)
                                    <p class="font-semibold text-gray-800 text-sm">{{ $log->user->name }}</p>
                                    <p class="text-[11px] text-gray-400 uppercase tracking-wide mt-0.5">{{ $log->user->role }}</p>
                                @else
                                    <span class="text-gray-400 italic text-sm">System</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $log->description }}</p>
                                @if($log->user_agent)
                                    <p class="text-[11px] text-gray-400 font-mono mt-1 truncate max-w-xs" title="{{ $log->user_agent }}">
                                        {{ Str::limit($log->user_agent, 65) }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="text-xs font-mono text-gray-500">{{ $log->ip_address }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                                <p class="text-sm text-gray-400 font-medium">No audit activities logged.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-100">
                <span class="text-xs text-gray-500">
                    Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ $logs->total() }} audit events
                </span>
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection
