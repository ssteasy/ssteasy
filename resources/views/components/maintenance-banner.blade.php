// resources/views/components/maintenance-banner.blade.php
@if(\App\Models\Setting::get('maintenance', false))
    <div class="bg-yellow-500 text-white text-sm text-center py-1">
        🔧 El sitio está en modo mantenimiento (visible solo para super-admin)
    </div>
@endif
