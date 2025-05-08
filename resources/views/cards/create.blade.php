@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Añadir Carta Promo</h1>
    <form action="{{ route('cards.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Expansión</label>
            <input type="text" name="expansion_id" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Imagen (URL)</label>
            <input type="url" name="image_url" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('cards.listarPromos') }}" class="btn btn-secondary">Volver</a>
    </form>
</div>
@endsection
