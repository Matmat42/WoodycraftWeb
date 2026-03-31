<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-16 flex items-center justify-between">

            {{-- LOGO --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('accueil') }}" class="flex items-center gap-2">
                    <span class="hidden sm:inline font-semibold text-black">WoodyCraft</span>
                </a>
            </div>

            {{-- ONGLETS DESKTOP --}}
            <div class="hidden md:flex items-center gap-2">

                <a href="{{ route('accueil') }}"
                   style="{{ request()->routeIs('accueil') ? 'background-color:#000;color:#fff;' : 'color:#000;' }} padding:0.5rem 1rem; border-radius:0.375rem; font-size:0.875rem; font-weight:600; text-decoration:none;">
                    ACCUEIL
                </a>

                <a href="{{ route('categories.index') }}"
                   style="{{ request()->routeIs('categories.*') ? 'background-color:#000;color:#fff;' : 'color:#000;' }} padding:0.5rem 1rem; border-radius:0.375rem; font-size:0.875rem; font-weight:600; text-decoration:none;">
                    CATÉGORIES
                </a>

                @auth
                <a href="{{ route('panier.index') }}"
                   style="{{ request()->routeIs('panier.*') ? 'background-color:#000;color:#fff;' : 'color:#000;' }} padding:0.5rem 1rem; border-radius:0.375rem; font-size:0.875rem; font-weight:600; text-decoration:none;">
                    PANIER
                </a>

                <a href="{{ route('commandes.index') }}"
                   style="{{ request()->routeIs('commandes.*') ? 'background-color:#000;color:#fff;' : 'color:#000;' }} padding:0.5rem 1rem; border-radius:0.375rem; font-size:0.875rem; font-weight:600; text-decoration:none;">
                    MES COMMANDES
                </a>
                @endauth

            </div>

            {{-- DROITE --}}
            <div class="hidden sm:flex items-center gap-3">

                @auth
                @php
                $cart = \App\Models\Panier::where('user_id', Auth::id())
                    ->where('statut', 0)
                    ->withCount('lignes')
                    ->first();
                $count = $cart->lignes_count ?? 0;
                @endphp

                <a href="{{ route('panier.index') }}"
                   style="position:relative; display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:9999px; border:1px solid #e5e7eb; color:#000; text-decoration:none;"
                   title="Mon panier">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 3h2l.4 2M7 13h10l3-8H6.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M10 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                    <span class="text-sm font-semibold">Panier</span>
                    @if($count > 0)
                    <span style="position:absolute; top:0; right:0; transform:translate(25%,-25%); background:#dc2626; color:#fff; font-size:11px; font-weight:700; border-radius:9999px; padding:0 4px; min-width:18px; text-align:center; line-height:1.25rem;">
                        {{ $count }}
                    </span>
                    @endif
                </a>
                @endauth

                @guest
                <a href="{{ route('login') }}"
                   style="background-color:#000; color:#fff; padding:0.5rem 1rem; border-radius:0.375rem; font-size:0.875rem; font-weight:600; text-decoration:none;"
                   onmouseover="this.style.backgroundColor='#374151'"
                   onmouseout="this.style.backgroundColor='#000'">
                    CONNEXION
                </a>
                <a href="{{ route('register') }}"
                   style="background-color:#fff; color:#000; padding:0.5rem 1rem; border-radius:0.375rem; font-size:0.875rem; font-weight:600; border:1px solid #000; text-decoration:none;"
                   onmouseover="this.style.backgroundColor='#f3f4f6'"
                   onmouseout="this.style.backgroundColor='#fff'">
                    INSCRIPTION
                </a>
                @endguest

                @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 0.75rem; border-radius:0.375rem; border:1px solid #e5e7eb; color:#000; background:#fff; cursor:pointer;">
                            <span class="text-sm font-semibold">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.08z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">Profil</x-dropdown-link>
                        <x-dropdown-link :href="route('commandes.index')">Mes commandes</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault(); this.closest('form').submit();">
                                Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                @endauth

            </div>

            {{-- BURGER MOBILE --}}
            <div class="md:hidden">
                <button @click="open = ! open"
                        style="display:inline-flex; align-items:center; justify-content:center; padding:0.5rem; border-radius:0.375rem; color:#000; background:transparent; border:none; cursor:pointer;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }"
                              class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- MENU MOBILE --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-gray-200">
        <div style="padding:0.75rem 1rem; background:#f9fafb;">

            <a href="{{ route('accueil') }}"
               style="{{ request()->routeIs('accueil') ? 'background-color:#000;color:#fff;' : 'color:#000;' }} display:block; padding:0.5rem 0.75rem; border-radius:0.375rem; font-weight:600; text-decoration:none; margin-bottom:0.5rem;">
                Accueil
            </a>

            <a href="{{ route('categories.index') }}"
               style="{{ request()->routeIs('categories.*') ? 'background-color:#000;color:#fff;' : 'color:#000;' }} display:block; padding:0.5rem 0.75rem; border-radius:0.375rem; font-weight:600; text-decoration:none;">
                Catégories
            </a>

        </div>
    </div>

</nav>
