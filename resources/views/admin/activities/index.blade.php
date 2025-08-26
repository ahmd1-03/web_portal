@extends('admin.layouts.app')

@section('content')
    <div class="p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Aktivitas Terakhir</h1>
            <a href="{{ route('admin.activities.deleted') }}"
                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                Lihat Aktivitas Dihapus
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="p-4 md:p-6">
                @if($activities->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">Tidak ada aktivitas yang tersedia</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Waktu</th>
                                    <th class="px-6 py-3">Tipe</th>
                                    <th class="px-6 py-3">Aksi</th>
                                    <th class="px-6 py-3">Judul</th>
                                    <th class="px-6 py-3">Detail</th>
                                    <th class="px-6 py-3">User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activities as $activity)
                                                <tr class="bg-white border-b hover:bg-gray-50">
                                                    <td class="px-6 py-4">
                                                        <span data-time-ago data-timestamp="{{ $activity->timestamp->toISOString() }}">
                                                            {{ $activity->timestamp->diffForHumans() }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded-full 
                                                                        {{ $activity->type === 'card' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                            {{ ucfirst($activity->type) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <span
                                                            class="px-2 py-1 text-xs font-semibold rounded-full 
                                                                        {{ $activity->action === 'created' ? 'bg-green-100 text-green-800' :
                                    ($activity->action === 'updated' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                            {{ ucfirst($activity->action) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 font-medium text-gray-900">
                                                        {{ $activity->title }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @if($activity->details)
                                                            <div class="text-xs text-gray-600 max-w-xs truncate">
                                                                {{ Str::limit($activity->details, 50) }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{ $activity->user_id ?? 'System' }}
                                                    </td>
                                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection