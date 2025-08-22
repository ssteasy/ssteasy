{{-- resources/views/examenes_medicos/index.blade.php --}}
@extends('layouts.app')
@section('content')
<h2>Mis Exámenes Médicos</h2>
<table>… @foreach($exams as $e)
<tr>
    <td>{{ $e->fecha_examen->format('d/m/Y') }}</td>
    <td>{{ $e->profesiogramaExamenTipo->examenTipo->nombre }}</td>
    <td>{{ $e->concepto_medico }}</td>
    <td><a href="{{ route('exams.show',$e) }}">Ver</a></td>
</tr>
@endforeach </table>
@endsection
