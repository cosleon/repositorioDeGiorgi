@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cartas Promo</h1>
    <a href="{{ route('cards.create') }}" class="btn btn-primary">Añadir Carta</a>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Expansión</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cards as $card)
                <tr>
                    <td>{{ $card->name }}</td>
                    <td>{{ $card->expansion }}</td>
                    <td>
                        @if ($card->image_url)
                            <img src="{{ $card->image_url }}" width="50">
                        @else
                            No disponible
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('cards.edit', $card) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('cards.destroy', $card) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('¿Eliminar carta?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
