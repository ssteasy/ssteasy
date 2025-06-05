<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Empresa;
use App\Models\Modalidad;
use App\Models\Sede;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\{TextInput, Select, DatePicker, Tabs, Toggle};
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\View;

use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\{
    ImageColumn,
    TextColumn,
    IconColumn
};
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction; // ← importa esto
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;




class UserResource extends Resource
{
    public static function getNavigationGroup(): ?string
    {
        return 'Mi Empresa';
    }
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canViewAny(): bool
    {
        return auth()->user()->hasAnyRole(['superadmin', 'admin']);
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Usuario')->tabs([
                Tabs\Tab::make('Datos Básicos')->schema([
                    FileUpload::make('profile_photo_path')
                        ->label('Foto de perfil')
                        ->image()
                        ->directory('avatars')
                        ->imagePreviewHeight('75')
                        ->columnSpanFull(), // ocupa ancho completo
                    TextInput::make('primer_nombre')
                        ->label('Primer Nombre')
                        ->required(),
                    TextInput::make('segundo_nombre')
                        ->label('Segundo Nombre'),
                    TextInput::make('primer_apellido')
                        ->label('Primer Apellido')
                        ->required(),
                    TextInput::make('segundo_apellido')
                        ->label('Segundo Apellido'),
                        
                    Select::make('tipo_documento')
                        ->options([
                            'Cédula de ciudadanía' => 'Cédula de ciudadanía',
                            'Cédula extranjera' => 'Cédula extranjera',
                            // ...
                        ])->required(),
                    TextInput::make('numero_documento')
                        ->label('Número de documento')
                        ->numeric()             
                        ->rules(['numeric'])     
                        ->required(),

                    Select::make('sexo')
                        ->label('Sexo')
                        ->options([
                            'Masculino' => 'Masculino',
                            'Femenino'  => 'Femenino',
                            'Otro'      => 'Otro',
                        ])
                        ->required(),
                ]),
                Tabs\Tab::make('Acceso')->schema([
                    Hidden::make('empresa_id')
                        ->default(fn () => auth()->user()->empresa_id)
                        ->dehydrated()      // asegúrate de que se envíe en el payload
                        ->required(),
        
                    // 2) Select sólo para superadmin
                    Select::make('empresa_id')
                        ->label('Empresa')
                        ->options(fn () => Empresa::pluck('nombre', 'id'))
                        ->searchable()
                        ->visible(fn () => auth()->user()->hasRole('superadmin'))
                        ->required(),

                    
                    
                    
                    
                    // Mostrar solo para superadmin
                    /*Select::make('empresa_id')
                        ->label('Empresa')
                        ->options(fn () => Empresa::pluck('nombre','id'))
                        ->default(fn () => auth()->user()->empresa_id)
                        ->disabled(fn () => ! auth()->user()->hasRole('superadmin'))
                        ->searchable()
                        ->id('mi-select-empresa')
                        ->required(),
                    View::make('forms.admin-zone')
                        ->visible(fn() => auth()->user()->hasRole('admin')),
                    */
                    TextInput::make('email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->required(),
                
                    TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn($state) => filled($state))
                        ->required(fn(string $context) => $context === 'create'),
                
                    Select::make('roles')
                        ->label('Rol')
                        ->relationship('roles', 'name')
                        ->preload()
                        ->required()
                        ->options(function () {
                            $user = auth()->user();
                            if ($user->hasRole('superadmin')) {
                                return \Spatie\Permission\Models\Role::pluck('name', 'id');
                            }
                    
                            // admins solo pueden asignar roles dentro de su empresa
                            return \Spatie\Permission\Models\Role::whereIn('name', ['admin', 'colaborador'])->pluck('name', 'id');
                        }),
                    ]),
                Tabs\Tab::make('Contacto')->schema([
                    TextInput::make('telefono')
                        ->label('Teléfono')
                        ->required(),
                    TextInput::make('direccion')
                        ->label('Dirección')
                        ->required(),  
                     Select::make('pais_dane')
                        ->label('País')
                        ->options(\App\Models\Pais::all()->pluck('nombre', 'codigo'))
                        ->reactive()
                        ->required(),
                
                    Select::make('departamento_dane')
                        ->label('Departamento')
                        // filtrar opciones según país seleccionado
                        ->options(fn(callable $get) => \App\Models\Departamento::where('pais_codigo', $get('pais_dane'))->pluck('nombre', 'codigo'))
                        ->reactive()
                        ->required(),
                
                    Select::make('municipio_dane')
                        ->label('Municipio')
                        // filtrar según departamento seleccionado
                        ->options(fn(callable $get) => \App\Models\Municipio::where('departamento_codigo', $get('departamento_dane'))->pluck('nombre', 'codigo'))
                        ->required(),
                    Select::make('zona')
                        ->label('Zona')
                        ->options([
                            'Rural' => 'Rural',
                            'Urbana'=> 'Urbana',
                        ])
                        ->required(),
                ]),
                Tabs\Tab::make('Contrato')->schema([
                    Select::make('cargo_id')
                        ->label('Cargo')
                        ->reactive()
                        ->options(fn (callable $get) => 
                            \App\Models\Cargo::query()
                                ->where('empresa_id', $get('empresa_id') ?? auth()->user()->empresa_id)
                                ->where('activo', true)
                                ->pluck('nombre', 'id')
                        )
                        ->searchable()
                        ->nullable()      // permite que sea null en vez de required
                        ->default(null)   // opcional: por defecto sin selección
                        ->placeholder('— Sin cargo —'),

                    Select::make('tipo_contrato')
                        ->options([
                            'Fijo'        => 'Fijo',
                            'Indefinido'  => 'Indefinido',
                            'Aprendizaje' => 'Aprendizaje',
                            'Pensionado'  => 'Pensionado',
                            'Obra labor'  => 'Obra labor',
                            'Temporal'    => 'Temporal',
                        ])
                        ->required(),     
                    DatePicker::make('fecha_inicio'),
                    DatePicker::make('fecha_fin'),
                    
                    Select::make('modalidad')
                        ->label('Modalidad de contrato')
                        ->options([
                            'Presencial'      => 'Presencial',
                            'Hibrido'     => 'Hibrido',
                            'Trabajo Remoto'  => 'Trabajo Remoto',
                        ])
                        ->default('Presencial')
                        ->required(),
                    Select::make('nivel_riesgo')->options([
                        'Riesgo I' => 'Riesgo I',
                        'Riesgo II' => 'Riesgo II',
                        'Riesgo III' => 'Riesgo III',
                        'Riesgo IV' => 'Riesgo IV',
                        'Riesgo V' => 'Riesgo V',
                        
                    ])
                    ->required(),
                    
                    Select::make('sede_id')
                        ->label('Sede')
                        ->reactive()                                  // <— idem
                        ->options(fn (callable $get) => 
                            \App\Models\Sede::query()
                                ->where('empresa_id', $get('empresa_id') ?? auth()->user()->empresa_id)
                                ->where('activo', true)
                                ->pluck('nombre', 'id')
                        )
                        ->searchable()
                        ->nullable()
                        // si no eres superadmin, por defecto tu propia sede
                        ->default(fn () => auth()->user()->hasRole('superadmin') 
                            ? null 
                            : auth()->user()->sede_id
                        )
                        ->nullable(),
                        ]),
                
                
                
                Tabs\Tab::make('Cuentas')->schema([
                    TextInput::make('eps')
                        ->label('EPS')
                        ->required(),
                    TextInput::make('ips')
                        ->label('IPS')
                        ->nullable(),
                    TextInput::make('arl')
                        ->label('ARL')
                        ->required(),
                    TextInput::make('afp')
                        ->label('AFP')
                        ->required(),
                ]),
            ]),
        ])->columns(1);;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('export')
                    ->label('Exportar XLS')
                    ->action(function () {
                        $user = auth()->user();
                        $empresaId = $user->hasRole('admin') ? $user->empresa_id : null;
                        return Excel::download(new UsersExport($empresaId), 'usuarios.xlsx');
                    }),
            ])
            ->columns([
                // Foto de perfil
                ImageColumn::make('profile_photo_path')
                    ->label('Avatar')
                    ->circular()
                    ->size(40),
    
                TextColumn::make('primer_nombre')
                    ->label('Primer Nombre')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('primer_apellido')
                    ->label('Primer Apellido')
                    ->sortable()
                    ->searchable(),
    
                // Cédula (número de documento)
                TextColumn::make('numero_documento')
                    ->label('Cédula')
                    ->sortable()
                    ->searchable(),
    
                // Teléfono (u otro campo "importante")
                TextColumn::make('telefono')
                    ->label('Teléfono')
                    ->toggleable(),  // permite ocultar/mostrar
    
                // Para Superadmins: columna Empresa
                TextColumn::make('empresa.nombre')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable()
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
            ])
            ->filters([
                // Filtro de empresa para superadmins
                Tables\Filters\SelectFilter::make('empresa_id')
                    ->label('Empresa')
                    ->options(\App\Models\Empresa::pluck('nombre', 'id'))
                    ->visible(fn() => auth()->user()->hasRole('superadmin')),
    
                // Filtro por rol
                Tables\Filters\SelectFilter::make('roles.name')
                    ->label('Rol')
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                ViewAction::make()           // ← aquí añades el botón Ver
                    ->icon('heroicon-o-eye')
                    ->label('Ver'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view'   => Pages\ViewUser::route('/{record}/view'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
    
        if ($user->hasRole('superadmin')) {
            return parent::getEloquentQuery();
        }
    
        return parent::getEloquentQuery()->where('empresa_id', $user->empresa_id);
    }
    
    public static function afterCreate(Model $record): void
    {
        if (request()->has('roles')) {
            $record->syncRoles(request('roles'));
        }
    }
    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (! auth()->user()->hasRole('superadmin')) {
            $data['empresa_id'] = auth()->user()->empresa_id;
        }
        return $data;
    }

    
    
}
