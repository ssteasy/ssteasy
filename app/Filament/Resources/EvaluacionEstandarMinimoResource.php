<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvaluacionEstandarMinimoResource\Pages;
use App\Models\EvaluacionEstandarMinimo;
use App\Models\Empresa;

use Filament\Forms;
use Filament\Tables;

// Components de Filament Forms:
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Actions\ViewAction;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvaluacionEstandarMinimoExport;
use Filament\Tables\Actions\Action;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


use Filament\Resources\Resource;

use Illuminate\Database\Eloquent\Model;

class EvaluacionEstandarMinimoResource extends Resource
{
    protected static ?string $model = EvaluacionEstandarMinimo::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationGroup = 'Generalidades';
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('empresa_id', Auth::user()->empresa_id);
    }
    public static function canCreate(): bool
{
    $user = auth()->user();

    // sólo admin o superadmin
    if (! $user->hasRole(['admin', 'superadmin'])) {
        return false;
    }

    // bloqueo si ya existe evaluación este año para su empresa
    $exists = static::$model::where('empresa_id', $user->empresa_id)
                ->where('year', now()->year)
                ->exists();

    return ! $exists;
}
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole(['admin', 'superadmin']);
    }
    

    // EDITAR: firma compatible con Resource
    public static function canEdit(Model $record): bool
    {
        // Opcionalmente validamos que sea nuestro modelo
        if (!$record instanceof EvaluacionEstandarMinimo) {
            return false;
        }

        return auth()->user()->hasRole(['admin', 'superadmin'])
            && $record->year === now()->year;
    }
    public static function getWidgets(): array
    {
        return [
            //\App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets\StatsOverviewWidget::class,
            \App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets\ChartByStageWidget::class,
            \App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets\SubfaseChartWidget::class,
            //\App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets\ValoracionWidget::class,
            \App\Filament\Resources\EvaluacionEstandarMinimoResource\Widgets\ExtrasWidget::class,
        ];
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([


                // === Tabs con las 4 fases
                Tabs::make('Evaluación')->tabs([

                    //
                    // === TAB: “Planear” (10%)
                    //
                    Tab::make('Planear')->schema([

                        // — Sección “Recursos (10%)”
                        Section::make('Recursos (10%)')
                            ->description('Total de esta sección: 10%')
                            ->columns(2)
                            ->schema([
                                // 1.1.1
                                Select::make('planear_recursos_responsable_sst')
                                    ->label('1.1.1 Responsable SG-SST')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_responsable_sst_archivo')
                                    ->label('Soporte 1.1.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.1.2
                                Select::make('planear_recursos_responsabilidades_sst')
                                    ->label('1.1.2 Responsabilidades SG-SST')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_responsabilidades_sst_archivo')
                                    ->label('Soporte 1.1.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.1.3
                                Select::make('planear_recursos_asignacion_recursos')
                                    ->label('1.1.3 Asignación de recursos')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_asignacion_recursos_archivo')
                                    ->label('Soporte 1.1.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.1.4
                                Select::make('planear_recursos_afiliacion_sg_riesgos')
                                    ->label('1.1.4 Afiliación SG Riesgos')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_afiliacion_sg_riesgos_archivo')
                                    ->label('Soporte 1.1.4')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.1.5
                                Select::make('planear_recursos_pension_altoriesgo')
                                    ->label('1.1.5 Pago pensión alto riesgo')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_pension_altoriesgo_archivo')
                                    ->label('Soporte 1.1.5')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.1.6
                                Select::make('planear_recursos_conformacion_copasst')
                                    ->label('1.1.6 Conformación COPASST')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_conformacion_copasst_archivo')
                                    ->label('Soporte 1.1.6')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.1.7
                                Select::make('planear_recursos_capacitacion_copasst')
                                    ->label('1.1.7 Capacitación COPASST')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_capacitacion_copasst_archivo')
                                    ->label('Soporte 1.1.7')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.1.8
                                Select::make('planear_recursos_conformacion_convivencia')
                                    ->label('1.1.8 Comité Convivencia')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_recursos_conformacion_convivencia_archivo')
                                    ->label('Soporte 1.1.8')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),
                            ]),

                        // — Sección “Capacitación en SG-SST (6%)”
                        Section::make('Capacitación en SG-SST (6%)')
                            ->description('Total de esta sección: 6%')
                            ->columns(2)
                            ->schema([
                                // 1.2.1
                                Select::make('planear_capacitacion_programa_pyP')
                                    ->label('1.2.1 Programa Capacitación P y P')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_capacitacion_programa_pyP_archivo')
                                    ->label('Soporte 1.2.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.2.2
                                Select::make('planear_capacitacion_induccion_reinduccion')
                                    ->label('1.2.2 Inducción / Reinducción')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_capacitacion_induccion_reinduccion_archivo')
                                    ->label('Soporte 1.2.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 1.2.3
                                Select::make('planear_capacitacion_responsable_curso50h')
                                    ->label('1.2.3 Responsable con curso 50h')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_capacitacion_responsable_curso50h_archivo')
                                    ->label('Soporte 1.2.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),
                            ]),
                        Section::make('Gestión Integral del SG-SST (15%)')
                            ->description('Total de esta sección: 15%')
                            ->columns(2)
                            ->schema([
                                Select::make('planear_gestion_integral_politica_sst')
                                    ->label('2.1.1 Política SG-SST (1%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_gestion_integral_politica_sst_archivo')
                                    ->label('Soporte 2.1.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                Select::make('planear_gestion_integral_objetivos_sst')
                                    ->label('2.2.1 Objetivos SG-SST (1%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('planear_gestion_integral_objetivos_sst_archivo')
                                    ->label('Soporte 2.2.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // … Repite para cada punto hasta 2.11.1 …
                                Select::make('planear_gestion_integral_evaluacion_prioridades')
                                    ->label('2.3.1 Evaluación e identificación prioridades (1%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_evaluacion_prioridades_archivo')
                                    ->label('Soporte 2.3.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_plan_objetivos')
                                    ->label('2.4.1 Plan objetivos y cronograma (2%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_plan_objetivos_archivo')
                                    ->label('Soporte 2.4.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_retencion_documental')
                                    ->label('2.5.1 Retención documental SG-SST (2%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_retencion_documental_archivo')
                                    ->label('Soporte 2.5.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_rendicion_desempeno')
                                    ->label('2.6.1 Rendición desempeño (1%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_rendicion_desempeno_archivo')
                                    ->label('Soporte 2.6.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_matriz_legal')
                                    ->label('2.7.1 Matriz legal (2%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_matriz_legal_archivo')
                                    ->label('Soporte 2.7.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_comunicacion_sst')
                                    ->label('2.8.1 Comunicación SG-SST (1%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_comunicacion_sst_archivo')
                                    ->label('Soporte 2.8.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_adquisicion_productos')
                                    ->label('2.9.1 Adquisición productos/servicios (1%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_adquisicion_productos_archivo')
                                    ->label('Soporte 2.9.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_evaluacion_proveedores')
                                    ->label('2.10.1 Selección proveedores y contratistas (2%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_evaluacion_proveedores_archivo')
                                    ->label('Soporte 2.10.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),

                                Select::make('planear_gestion_integral_impacto_cambios')
                                    ->label('2.11.1 Impacto cambios internos/externos (1%)')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])->default('no_cumple')->required(),
                                FileUpload::make('planear_gestion_integral_impacto_cambios_archivo')
                                    ->label('Soporte 2.11.1')
                                    ->directory('evaluaciones_adjuntos')->visibility('public')
                                    ->enableDownload()->enableOpen(),
                            ]),
                    ]), // fin Tab “Planear”


                    //
                    // === TAB: “Hacer”
                    //
                    Tab::make('Hacer')->schema([

                        // — Sección “Gestión de la Salud (20%)”
                        Section::make('Gestión de la Salud (20%)')
                            ->description('Condiciones de salud, registro e indicadores (20%)')
                            ->columns(2)
                            ->schema([
                                // 3.1.1
                                Select::make('hacer_salud_evaluacion_medica_ocupacional')
                                    ->label('3.1.1 Evaluación Médica Ocupacional')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_evaluacion_medica_ocupacional_archivo')
                                    ->label('Soporte 3.1.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.2
                                Select::make('hacer_salud_actividades_promocion_prevencion')
                                    ->label('3.1.2 Actividades Promoción y Prevención')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_actividades_promocion_prevencion_archivo')
                                    ->label('Soporte 3.1.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.3
                                Select::make('hacer_salud_perfiles_cargo_info_medico')
                                    ->label('3.1.3 Perfiles de cargo al médico')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_perfiles_cargo_info_medico_archivo')
                                    ->label('Soporte 3.1.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.4
                                Select::make('hacer_salud_examenes_ocupacionales')
                                    ->label('3.1.4 Exámenes Médicos Ocupacionales')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_examenes_ocupacionales_archivo')
                                    ->label('Soporte 3.1.4')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.5
                                Select::make('hacer_salud_custodia_historias_clinicas')
                                    ->label('3.1.5 Custodia Historias Clínicas')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_custodia_historias_clinicas_archivo')
                                    ->label('Soporte 3.1.5')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.6
                                Select::make('hacer_salud_restricciones_recomendaciones')
                                    ->label('3.1.6 Restricciones y Recomendaciones')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_restricciones_recomendaciones_archivo')
                                    ->label('Soporte 3.1.6')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.7
                                Select::make('hacer_salud_estilos_vida_entornos_saludables')
                                    ->label('3.1.7 Estilos de vida y entornos saludables')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_estilos_vida_entornos_saludables_archivo')
                                    ->label('Soporte 3.1.7')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.8
                                Select::make('hacer_salud_agua_potable_sanitarios')
                                    ->label('3.1.8 Agua potable y sanitarios')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_agua_potable_sanitarios_archivo')
                                    ->label('Soporte 3.1.8')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.1.9
                                Select::make('hacer_salud_eliminacion_residuos')
                                    ->label('3.1.9 Eliminación adecuada de residuos')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_eliminacion_residuos_archivo')
                                    ->label('Soporte 3.1.9')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.2.1
                                Select::make('hacer_salud_reporte_accidentes_enfermedades')
                                    ->label('3.2.1 Reporte accidentes y enfermedades')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_reporte_accidentes_enfermedades_archivo')
                                    ->label('Soporte 3.2.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.2.2
                                Select::make('hacer_salud_investigacion_accidentes')
                                    ->label('3.2.2 Investigación de accidentes')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_investigacion_accidentes_archivo')
                                    ->label('Soporte 3.2.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.2.3
                                Select::make('hacer_salud_registro_estadistico')
                                    ->label('3.2.3 Registro/Análisis estadístico')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_registro_estadistico_archivo')
                                    ->label('Soporte 3.2.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.3.1
                                Select::make('hacer_salud_frecuencia_accidentalidad')
                                    ->label('3.3.1 Frecuencia accidentalidad')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_frecuencia_accidentalidad_archivo')
                                    ->label('Soporte 3.3.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.3.2
                                Select::make('hacer_salud_severidad_accidentalidad')
                                    ->label('3.3.2 Severidad accidentalidad')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_severidad_accidentalidad_archivo')
                                    ->label('Soporte 3.3.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.3.3
                                Select::make('hacer_salud_mortalidad_accidentes')
                                    ->label('3.3.3 Mortalidad por accidentes')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_mortalidad_accidentes_archivo')
                                    ->label('Soporte 3.3.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.3.4
                                Select::make('hacer_salud_prevalencia_enfermedad')
                                    ->label('3.3.4 Prevalencia enfermedad')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_prevalencia_enfermedad_archivo')
                                    ->label('Soporte 3.3.4')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.3.5
                                Select::make('hacer_salud_incidencia_enfermedad')
                                    ->label('3.3.5 Incidencia enfermedad')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_incidencia_enfermedad_archivo')
                                    ->label('Soporte 3.3.5')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 3.3.6
                                Select::make('hacer_salud_ausentismo_causa_medica')
                                    ->label('3.3.6 Ausentismo causa médica')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_salud_ausentismo_causa_medica_archivo')
                                    ->label('Soporte 3.3.6')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),
                            ]),

                        // — Sección “Gestión de Peligros y Riesgos (30%)”
                        Section::make('Gestión de Peligros y Riesgos (30%)')
                            ->description('Identificación y medidas (30%)')
                            ->columns(2)
                            ->schema([
                                // 4.1.1
                                Select::make('hacer_riesgos_metodologia_identificacion')
                                    ->label('4.1.1 Metodología identificación')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_metodologia_identificacion_archivo')
                                    ->label('Soporte 4.1.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.1.2
                                Select::make('hacer_riesgos_identificacion_participacion')
                                    ->label('4.1.2 Identificación participativa')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_identificacion_participacion_archivo')
                                    ->label('Soporte 4.1.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.1.3
                                Select::make('hacer_riesgos_identificacion_sustancias_cancerigenas')
                                    ->label('4.1.3 Sustancias cancerígenas')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_identificacion_sustancias_cancerigenas_archivo')
                                    ->label('Soporte 4.1.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.1.4
                                Select::make('hacer_riesgos_mediciones_ambientales')
                                    ->label('4.1.4 Mediciones ambientales')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_mediciones_ambientales_archivo')
                                    ->label('Soporte 4.1.4')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.2.1
                                Select::make('hacer_riesgos_medidas_prevencion_control')
                                    ->label('4.2.1 Medidas de prevención/control')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_medidas_prevencion_control_archivo')
                                    ->label('Soporte 4.2.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.2.2
                                Select::make('hacer_riesgos_verificacion_trabajadores')
                                    ->label('4.2.2 Verificación trabajadores')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_verificacion_trabajadores_archivo')
                                    ->label('Soporte 4.2.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.2.3
                                Select::make('hacer_riesgos_elaboracion_procedimientos')
                                    ->label('4.2.3 Elaboración procedimientos')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_elaboracion_procedimientos_archivo')
                                    ->label('Soporte 4.2.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.2.4
                                Select::make('hacer_riesgos_inspecciones_copasst')
                                    ->label('4.2.4 Inspecciones COPASST')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_inspecciones_copasst_archivo')
                                    ->label('Soporte 4.2.4')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.2.5
                                Select::make('hacer_riesgos_mantenimiento_periodico')
                                    ->label('4.2.5 Mantenimiento periódico')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_mantenimiento_periodico_archivo')
                                    ->label('Soporte 4.2.5')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 4.2.6
                                Select::make('hacer_riesgos_entrega_epp')
                                    ->label('4.2.6 Entrega de EPP')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_riesgos_entrega_epp_archivo')
                                    ->label('Soporte 4.2.6')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),
                            ]),

                        // — Sección “Gestión de Amenazas (10%)”
                        Section::make('Gestión de Amenazas (10%)')
                            ->description('Plan emergencia y brigada (10%)')
                            ->columns(2)
                            ->schema([
                                // 5.1.1
                                Select::make('hacer_amenazas_plan_emergencia')
                                    ->label('5.1.1 Plan de Emergencia')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_amenazas_plan_emergencia_archivo')
                                    ->label('Soporte 5.1.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 5.1.2
                                Select::make('hacer_amenazas_brigada_emergencias')
                                    ->label('5.1.2 Brigada de Emergencias')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('hacer_amenazas_brigada_emergencias_archivo')
                                    ->label('Soporte 5.1.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),
                            ]),
                    ]), // fin Tab “Hacer”


                    //
                    // === TAB: “Verificar” (5%)
                    //
                    Tab::make('Verificar')->schema([
                        Section::make('Verificación SG-SST (5%)')
                            ->description('Gestión y resultados (5%)')
                            ->columns(2)
                            ->schema([
                                // 6.1.1
                                Select::make('verificar_indicadores_sst')
                                    ->label('6.1.1 Definición de indicadores')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('verificar_indicadores_sst_archivo')
                                    ->label('Soporte 6.1.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 6.1.2
                                Select::make('verificar_auditoria_anual')
                                    ->label('6.1.2 Auditoría anual')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('verificar_auditoria_anual_archivo')
                                    ->label('Soporte 6.1.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 6.1.3
                                Select::make('verificar_revision_alta_direccion')
                                    ->label('6.1.3 Revisión alta dirección')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('verificar_revision_alta_direccion_archivo')
                                    ->label('Soporte 6.1.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 6.1.4
                                Select::make('verificar_planificacion_copasst')
                                    ->label('6.1.4 Planificación COPASST')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('verificar_planificacion_copasst_archivo')
                                    ->label('Soporte 6.1.4')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),
                            ]),
                    ]),


                    //
                    // === TAB: “Actuar” (10%)
                    //
                    Tab::make('Actuar')->schema([
                        Section::make('Mejoramiento (10%)')
                            ->description('Acciones correctivas y planes (10%)')
                            ->columns(2)
                            ->schema([
                                // 7.1.1
                                Select::make('actuar_acciones_prev_y_corr')
                                    ->label('7.1.1 Acciones preventivas y correctivas')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('actuar_acciones_prev_y_corr_archivo')
                                    ->label('Soporte 7.1.1')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 7.1.2
                                Select::make('actuar_mejoras_revision_alta_direccion')
                                    ->label('7.1.2 Mejoras tras revisión alta dirección')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('actuar_mejoras_revision_alta_direccion_archivo')
                                    ->label('Soporte 7.1.2')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 7.1.3
                                Select::make('actuar_mejoras_investigacion')
                                    ->label('7.1.3 Mejoras tras investigación')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('actuar_mejoras_investigacion_archivo')
                                    ->label('Soporte 7.1.3')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),

                                // 7.1.4
                                Select::make('actuar_plan_mejoramiento')
                                    ->label('7.1.4 Plan de Mejoramiento')
                                    ->options([
                                        'no_cumple' => 'No cumple',
                                        'cumple' => 'Cumple',
                                        'no_aplica' => 'No aplica',
                                    ])
                                    ->default('no_cumple')
                                    ->required(),
                                FileUpload::make('actuar_plan_mejoramiento_archivo')
                                    ->label('Soporte 7.1.4')
                                    ->directory('evaluaciones_adjuntos')
                                    ->visibility('public')
                                    ->enableDownload()
                                    ->enableOpen(),
                            ]),
                    ]),

                ])
                    ->columnSpan('full'), // fin Tabs

                //
                // === Card final: Puntaje Total (solo lectura)
                //
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('score_total')
                            ->label('Puntaje Total (%)')
                            ->disabled()
                            ->extraAttributes(['readonly' => true]),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('empresa.nombre')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('year')
                    ->label('Año')
                    ->sortable(),

                Tables\Columns\TextColumn::make('score_total')
                    ->label('Puntaje Total (%)')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('empresa_id')
                    ->label('Filtrar por Empresa')
                    ->relationship('empresa', 'nombre'),

                Tables\Filters\Filter::make('year')
                    ->form([
                        Forms\Components\Select::make('year')
                            ->label('Año')
                            ->options(fn() => EvaluacionEstandarMinimo::query()
                                ->select('year')
                                ->distinct()
                                ->pluck('year', 'year')
                                ->toArray())
                    ])
                    ->query(function ($query, $data) {
                        if ($data['year']) {
                            $query->where('year', $data['year']);
                        }
                    }),
            ])
            ->actions([
                ViewAction::make(),      // Botón “Ver” (solo lectura)
                Action::make('exportExcel')
                    ->label('Exportar Excel')
                    ->icon('heroicon-o-table-cells')
                    ->action(
                        fn(EvaluacionEstandarMinimo $record) =>
                        Excel::download(
                            new EvaluacionEstandarMinimoExport($record),
                            "evaluacion_{$record->year}.xlsx"
                        )
                    ),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEvaluacionEstandarMinimos::route('/'),
            // primero create
            'create' => Pages\CreateEvaluacionEstandarMinimo::route('/create'),
            // luego edit
            'edit' => Pages\EditEvaluacionEstandarMinimo::route('/{record}/edit'),
            // al final view
            'view' => Pages\ViewEvaluacionEstandarMinimo::route('/{record}'),
        ];
    }
}
