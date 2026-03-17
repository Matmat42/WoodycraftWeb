<section class="w-full" style="background:var(--wc-accent)">
  <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
    <div>
      <h1 class="text-2xl md:text-3xl font-semibold text-[var(--wc-text)]">
        Construisez vos rêves en 3D avec <span class="inline-flex items-center">
          WoodyCraft
          <img src="/img/brand/logo-woodycraft.svg" alt="WoodyCraft" class="h-6 ml-2">
        </span>
      </h1>
      <p class="mt-3 text-sm text-gray-700">Des puzzles en bois détaillés, à assembler pas à pas.</p>
      <div class="mt-5 flex gap-3">
        <a href="{{ route('categories.show', 1) }}" class="inline-flex items-center px-5 py-3 rounded-md text-white"
           style="background:var(--wc-primary)">Découvrir</a>
        <a href="{{ route('panier.index') }}" class="inline-flex items-center px-5 py-3 rounded-md border"
           style="border-color:var(--wc-primary);color:var(--wc-primary)">Voir mon panier</a>
      </div>
    </div>
    <div class="flex justify-center md:justify-end">
      <div class="relative w-[520px] h-[220px] sm:h-[260px] md:h-[300px]">
        <img src="/img/hero/hero-eiffel.png" alt="Eiffel" class="absolute bottom-0 left-0 h-[85%] object-contain">
        <img src="/img/hero/hero-plane.png"  alt="Plane"  class="absolute -top-6 left-1/3 h-[70%] object-contain">
        <img src="/img/hero/hero-lion.png"   alt="Lion"   class="absolute bottom-0 right-0 h-[90%] object-contain">
      </div>
    </div>
  </div>
</section>
