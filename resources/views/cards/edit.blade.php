@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Carta Promo</h1>
    <form action="{{ route('cards.update', $card) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ $card->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Expansión</label>
            <input type="text" name="expansion_id" class="form-control" value="{{ $card->expansion_id }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Imagen (URL)</label>
            <input type="url" name="image_url" class="form-control" value="{{ $card->image_url }}">
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('cards.listarPromos') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
