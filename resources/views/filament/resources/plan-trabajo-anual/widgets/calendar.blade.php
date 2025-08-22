{{-- resources/views/filament/resources/plan-trabajo-anual/widgets/calendar.blade.php --}}
<div x-data="{ tab: 1 }">
    {{-- CSS para x-cloak --}}
    <style>[x-cloak] { display: none !important; }</style>

    <x-filament::section>
        <x-slot name="header">
            <h2 class="text-xl font-bold">Plan de Trabajo {{ $year }}</h2>
        </x-slot>

        {{-- pestañas de meses --}}
        <div class="border-b mb-4">
            <div class="flex flex-wrap gap-2">
                @foreach ([1=>'Ene',2=>'Feb',3=>'Mar',4=>'Abr',5=>'May',6=>'Jun',7=>'Jul',8=>'Ago',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dic'] as $i => $nom)
                    <button
                        @click="tab = {{ $i }}"
                        :class="tab === {{ $i }} ? 'bg-primary-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'"
                        class="px-3 py-1 rounded-md text-sm transition"
                    >{{ $nom }}</button>
                @endforeach
            </div>
        </div>

        {{-- secciones pre-cargadas, sólo mostramos/ocultamos --}}
        @foreach (range(1,12) as $i)
            <div x-show="tab === {{ $i }}" x-cloak>
                @php
                    $metrics = $monthData[$i]['kpis'];
                @endphp

                {{-- KPI tarjetas --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    @php
                        $cards = [
                            ['valor'=>$metrics['planear'],'label'=>'Planificadas','bg'=>'bg-cyan-100','text'=>'text-cyan-800','icon'=>'heroicon-o-pencil-square'],
                            ['valor'=>$metrics['pospuesta'],'label'=>'Pospuestas','bg'=>'bg-yellow-100','text'=>'text-yellow-800','icon'=>'heroicon-o-exclamation-triangle'],
                            ['valor'=>$metrics['ejecutada'],'label'=>'Ejecutadas','bg'=>'bg-green-100','text'=>'text-green-800','icon'=>'heroicon-o-check-circle'],
                        ];
                    @endphp

                    @foreach ($cards as $c)
                        <x-filament::card :class="$c['bg'].' '.$c['text'].' flex items-center justify-between p-4'">
                            <div>
                                <div class="text-2xl font-bold">{{ $c['valor'] }}</div>
                                <div class="text-sm">{{ $c['label'] }}</div>
                            </div>
                            <x-dynamic-component :component="$c['icon']" class="w-8 h-8"/>
                        </x-filament::card>
                    @endforeach
                </div>

                {{-- tabla de actividades --}}
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left font-medium">Actividad</th>
                            <th class="px-3 py-2 text-left">Responsable</th>
                            <th class="px-3 py-2 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($monthData[$i]['actividades'] as $a)
                            @php
                                $campo = ['ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'][$i-1];
                                $estado = $a->{"mes_$campo"};
                            @endphp
                            <tr>
                                <td class="px-3 py-2">{{ $a->actividad }}</td>
                                <td class="px-3 py-2">{{ $a->responsable }}</td>
                                <td class="px-3 py-2 capitalize">{{ str_replace('_',' ',$estado) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-4 text-center text-gray-500">
                                    Sin actividades para este mes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <hr class="my-6 border-t border-gray-200"/>
            </div>
        @endforeach
    </x-filament::section>
</div>
