@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cartas Promo</h1>
    <a href="{{ route('cards.create') }}" class="btn btn-primary">Añadir Carta</a>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif
        <div id="promo-table" class="overflow-x-auto">
            @include('cards.partials._promo_table')
        </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
                e.preventDefault();
                fetch(e.target.href, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    document.querySelector('#promo-table').innerHTML = html;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    });
</script>
@endpush
