<form action="{{ route('cart.addPuzzle', $puzzle->id) }}" method="POST">
    @csrf
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4 hover:bg-blue-700">
        Ajouter au panier
    </button>
</form>
