@extends('admin.layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Aktivitas Dihapus</h1>
            <a href="{{ route('admin.activities.index') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Kembali ke Aktivitas
            </a>
        </div>

        <!-- Toast Notification -->
        <div id="toast-container" class="fixed top-4 right-4 z-50"></div>

        <!-- Modal Konfirmasi -->
        <div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-40">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Penghapusan</h3>
                        <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus permanen aktivitas ini?</p>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeModal()" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                Batal
                            </button>
                            <button type="button" onclick="confirmDelete()" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                Hapus Permanen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="p-4 md:p-6">
                @if($deletedActivities->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">Tidak ada aktivitas yang dihapus</p>
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
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deletedActivities as $activity)
                                    <tr class="bg-white border-b hover:bg-gray-50 transition-opacity duration-300" 
                                        id="activity-{{ $activity->id }}">
                                        <td class="px-6 py-4">
                                            {{ $activity->timestamp->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $activity->type === 'card' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($activity->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
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
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="restoreActivity({{ $activity->id }})" 
                                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 hover:bg-green-200 transition">
                                                    Pulihkan
                                                </button>
                                                <button onclick="showDeleteModal({{ $activity->id }})" 
                                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 hover:bg-red-200 transition">
                                                    Hapus Permanen
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $deletedActivities->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let currentActivityId = null;

        // Show SweetAlert2 toast notification
        function showToast(message, type = 'success') {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Show SweetAlert2 confirmation
        function showSweetAlertConfirm(title, text, confirmText = 'Ya, lanjutkan') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true
            });
        }

        // Show loading state
        function showLoading(title = 'Memproses...') {
            Swal.fire({
                title: title,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        // Restore activity via AJAX with SweetAlert2
        function restoreActivity(activityId) {
            showSweetAlertConfirm(
                'Konfirmasi Pemulihan',
                'Apakah Anda yakin ingin memulihkan aktivitas ini?'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Memulihkan aktivitas...');
                    
                    fetch(`/admin/activities/${activityId}/restore`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            showToast('Aktivitas berhasil dipulihkan');
                            
                            // Smooth animation to remove row
                            const row = document.getElementById(`activity-${activityId}`);
                            row.style.transition = 'all 0.5s ease';
                            row.style.transform = 'translateX(100%)';
                            row.style.opacity = '0';
                            
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is empty
                                const tbody = document.querySelector('tbody');
                                if (tbody.children.length === 0) {
                                    location.reload(); // Reload to show empty state
                                }
                            }, 500);
                        } else {
                            showToast(data.message || 'Gagal memulihkan aktivitas', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        showToast('Terjadi kesalahan: ' + error.message, 'error');
                    });
                }
            });
        }

        // Delete permanently with SweetAlert2
        function showDeleteModal(activityId) {
            currentActivityId = activityId;
            showSweetAlertConfirm(
                'Konfirmasi Penghapusan',
                'Apakah Anda yakin ingin menghapus permanen aktivitas ini? Tindakan ini tidak dapat dibatalkan.',
                'Ya, hapus permanen'
            ).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Menghapus permanen...');
                    
                    fetch(`/admin/activities/${activityId}/permanent-delete`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.success) {
                            showToast('Aktivitas berhasil dihapus permanen');
                            
                            // Smooth animation to remove row
                            const row = document.getElementById(`activity-${activityId}`);
                            row.style.transition = 'all 0.5s ease';
                            row.style.transform = 'translateX(-100%)';
                            row.style.opacity = '0';
                            
                            setTimeout(() => {
                                row.remove();
                                
                                // Check if table is empty
                                const tbody = document.querySelector('tbody');
                                if (tbody.children.length === 0) {
                                    location.reload(); // Reload to show empty state
                                }
                            }, 500);
                        } else {
                            showToast(data.message || 'Gagal menghapus aktivitas', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        showToast('Terjadi kesalahan: ' + error.message, 'error');
                    });
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
