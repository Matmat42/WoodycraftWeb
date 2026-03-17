{{-- resources/views/commandes/facture.blade.php --}}
@php
    $fmt = fn($n) => number_format((float)$n, 2, ',', ' ');
    $date = optional($commande->created_at)->format('d/m/Y') ?? now()->format('d/m/Y');

    $liv   = $commande->adresseLivraison;
    $fact  = $commande->adresseFacturation;
    $lignes = $commande->lignes ?? collect();   // App\Models\Appartient
    $total = $lignes->sum(fn($l) => ($l->prix_unitaire ?? $l->puzzle->prix ?? 0) * ($l->quantite ?? 0));
@endphp
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $commande->id }}</title>
    <style>
        @page { margin: 28mm 20mm 25mm; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color:#222; }
        h1,h2,h3,p { margin: 0; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:18px; }
        .brand { font-weight:700; font-size:20px; color:#0a572e; }
        .company small { display:block; color:#555; }
        .title { margin: 10px 0 6px; font-size:18px; font-weight:700; }
        .muted { color:#666; }
        .grid { display:flex; gap:24px; }
        .card { border:1px solid #ddd; padding:12px; flex:1; }
        table { width:100%; border-collapse:collapse; margin-top:14px; }
        th, td { border:1px solid #ddd; padding:8px; }
        th { background:#f6f6f6; text-align:left; }
        tfoot td { border:none; padding:8px 0 0; font-weight:700; }
        .right { text-align:right; }
        .mt { margin-top: 18px; }
        .note { margin-top: 24px; font-size: 11px; }
    </style>
</head>
<body>

    {{-- En-tête --}}
    <div class="header">
        <div>
            <div class="brand">WoodyCraft</div>
            <div class="muted">contact@woodycraft.test</div>
            <div class="muted">SIRET 123 456 789 00012</div>
        </div>
        <div class="company">
            <div class="title">Facture n° {{ $commande->id }}</div>
            <small>Date {{ $date }}</small>
            @if($commande->user)
                <small>Client {{ $commande->user->name }} — {{ $commande->user->email }}</small>
            @endif
        </div>
    </div>

    {{-- Adresses --}}
    <div class="grid">
        <div class="card">
            <h3 class="muted">Adresse de livraison</h3>
            <p>
                {{ $liv->rue ?? '' }}<br>
                {{ $liv->code_postal ?? '' }} {{ $liv->ville ?? '' }}<br>
                {{ $liv->pays ?? '' }}
            </p>
        </div>
        <div class="card">
            <h3 class="muted">Adresse de facturation</h3>
            <p>
                {{ $fact->rue ?? '' }}<br>
                {{ $fact->code_postal ?? '' }} {{ $fact->ville ?? '' }}<br>
                {{ $fact->pays ?? '' }}
            </p>
        </div>
    </div>

    {{-- Lignes --}}
    <table>
        <thead>
        <tr>
            <th style="width:50%">Produit</th>
            <th class="right" style="width:15%">PU (€)</th>
            <th class="right" style="width:10%">Qté</th>
            <th class="right" style="width:25%">Total ligne (€)</th>
        </tr>
        </thead>
        <tbody>
        @forelse($lignes as $l)
            @php
                $pu = $l->prix_unitaire ?? ($l->puzzle->prix ?? 0);
                $q  = $l->quantite ?? 0;
                $tl = $pu * $q;
            @endphp
            <tr>
                <td>{{ $l->puzzle->nom ?? 'Article' }}</td>
                <td class="right">{{ $fmt($pu) }}</td>
                <td class="right">{{ $q }}</td>
                <td class="right">{{ $fmt($tl) }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="muted">Aucune ligne.</td></tr>
        @endforelse
        </tbody>
    </table>

    <table class="mt">
        <tr>
            <td class="right" style="width:75%; font-weight:700;">Total</td>
            <td class="right" style="width:25%; font-weight:700;">{{ $fmt($total) }} €</td>
        </tr>
    </table>

    {{-- Paiement --}}
    <div class="mt">
        <div class="muted">Mode de paiement : {{ ucfirst($commande->mode_paiement ?? 'chèque') }}</div>
        @if(($commande->mode_paiement ?? 'cheque') === 'cheque')
            <div class="note">
                À l’ordre de : <strong>WoodyCraft</strong><br>
                Adresse d’envoi : WoodyCraft – Service Comptabilité, 12 rue des Artisans, 75000 Paris (France)
            </div>
        @endif
        <div class="note">Merci pour votre commande !</div>
    </div>

</body>
</html>
