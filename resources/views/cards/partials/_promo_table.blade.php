<table class="w-full table-auto border-collapse border border-slate-400">
    <thead>
        <tr>
            <th class="border border-slate-300 p-2 bg-slate-100 text-left">Nombre</th>
            <th class="border border-slate-300 p-2 bg-slate-100 text-left">Imagen</th>
            <th class="border border-slate-300 p-2 bg-slate-100 text-left">Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cards as $card)
            <tr>
                <td class="border border-slate-300 p-2">{{ $card->name }}</td>
                <td class="border border-slate-300 p-2">
                    @if ($card->image_url)
                        <img src="{{ $card->image_url }}" loading="lazy" width="50" class="block mx-auto"> {{-- Añadido block y mx-auto para centrar si es necesario --}}
                    @else
                        No disponible
                    @endif
                </td>
                <td class="border border-slate-300 p-2">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('cards.edit', $card) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded text-center">Editar</a>
                        <form action="{{ route('cards.destroy', $card) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded w-full sm:w-auto" onclick="return confirm('¿Eliminar carta?')">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $cards->links() }}
</div>