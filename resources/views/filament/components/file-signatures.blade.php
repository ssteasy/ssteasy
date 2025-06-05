{{-- resources/views/filament/components/file-signatures.blade.php --}}
<div class="space-y-4">
    <h3 class="text-lg font-semibold">{{ $record->title }}</h3>

    <table class="min-w-full text-sm">
        <thead>
            <tr class="border-b text-left">
                <th class="py-2 font-medium">Colaborador</th>
                <th class="py-2">Cargo</th>
                <th class="py-2">Estado firma</th>
                <th class="py-2">Fecha firma</th>
            </tr>
        </thead>
        <tbody>
            @foreach($record->assignedUsers as $user)
                @php
                    $pivot = $record->signatories->firstWhere('user_id', $user->id);
                @endphp
                <tr class="border-b last:border-0">
                    <td class="py-2">
                        {{ $user->primer_nombre }} {{ $user->primer_apellido }}
                        <br><span class="text-xs text-gray-500">{{ $user->email }}</span>
                    </td>
                    <td class="py-2">{{ $user->cargo?->nombre ?? '—' }}</td>
                    <td class="py-2">
                        @if($pivot && $pivot->signed_at)
                            <span class="text-green-600 font-semibold">Firmado</span>
                        @else
                            <span class="text-red-600 font-semibold">Pendiente</span>
                        @endif
                    </td>
                    <td class="py-2">
                        {{ optional($pivot?->signed_at)->format('Y-m-d H:i') ?? '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Resumen --}}
    @php
        $total      = $record->assignedUsers->count();
        $firmados   = $record->signatories->whereNotNull('signed_at')->count();
        $pendientes = $total - $firmados;
    @endphp

    <div class="text-sm text-gray-600">
        <strong>Total asignados:</strong> {{ $total }} —
        <strong>Firmados:</strong> <span class="text-green-600">{{ $firmados }}</span> —
        <strong>Pendientes:</strong> <span class="text-red-600">{{ $pendientes }}</span>
    </div>
</div>
