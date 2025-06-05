{{-- resources/views/components/app-header.blade.php --}}
@auth
<style>
    .header {
        background-color: var(--color-azul-oscuro);
    }
    .header * {
        color: white;
    }
    .headerRol {
        margin-left: 10px;
        text-transform: uppercase;
    }
    .secright .text-right {
        margin-right: 10px;
    }
    .headerDrop {
          background-color: #0e4068;
          color: white;
          font-size: 16px;
          overflow: hidden;
          margin-left: -30px;
    }
    .headerDrop a:hover, .headerDrop form:hover {
        background-color: #165780;
    }
    .leftsec{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        margin: 10px 0;
    }
</style>

<header class="bg-white shadow-sm header">
  <div class="mx-auto max-w-8xl px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
    {{-- Izquierda: logo de app + logo de empresa + rol --}}
    <div class="flex items-center space-x-4 leftsec">
      <img src="{{ asset('images/lhlight_logo.png') }}"
           alt="{{ config('app.name') }}"
           class="h-8 w-auto" />
      @php
        $empresa = auth()->user()->empresa;
        $logoUrl = $empresa && $empresa->logo
            ? Storage::url($empresa->logo)
            : asset('img/logo.png');
      @endphp
      <img src="{{ $logoUrl }}"
           alt="Logo de {{ $empresa?->nombre }}"
           class="h-8 w-auto object-contain drop-shadow-lg" />
      <span class="text-gray-700 font-medium uppercase headerRol">
        {{ auth()->user()->getRoleNames()->first() }}
      </span>
    </div>

    {{-- Derecha: nombre, cargo|sede y avatar con dropdown --}}
    <div class="flex items-center space-x-4 secright">
      <div class="text-right">
        <div class="text-gray-800 font-semibold">
          {{ auth()->user()->primer_nombre }} {{ auth()->user()->primer_apellido }}
        </div>
        <div class="text-gray-500 text-sm ">
          {{ auth()->user()->cargo?->nombre ?? '-' }} |
          {{ auth()->user()->sede?->nombre ?? '-' }}
        </div>
      </div>

      {{-- Dropdown --}}
      <div x-data="{ open: false }" class="relative">
        <button
          @click="open = !open"
          class="focus:outline-none"
        >
          @if(auth()->user()->profile_photo_path)
            <img
              src="{{ Storage::url(auth()->user()->profile_photo_path) }}"
              alt="Avatar de {{ auth()->user()->primer_nombre }}"
              class="h-10 w-10 rounded-full object-cover border-2 border-gray-200"
            />
          @else
            <img
              src="{{ asset('images/pp.png') }}"
              alt="Avatar de {{ auth()->user()->primer_nombre }}"
              class="h-10 w-10 rounded-full object-cover border-2 border-gray-200"
            />
          @endif
        </button>

        <div
          x-show="open"
          @click.outside="open = false"
          x-transition
          class="absolute mt-2 w-48 bg-white rounded-md shadow-lg z-50 headerDrop"
        >
          <a
            href="{{ url('/profile') }}"
            class="block px-4 py-2 text-gray-800 hover:bg-gray-100"
          >
            Perfil
          </a>
          <form method="POST" action="{{ url(config('filament.path') . '/logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full text-left block px-4 py-2 text-gray-800 "
            >
                Cerrar sesión
            </button>
        </form>
        </div>
      </div>
    </div>
  </div>
    <style>
/* Estilos existentes... */
.app-header {
  background-color: var(--color-azul-oscurotwo);
  padding: 1rem 40px;
}
.app-header__inner {

  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Grupo lateral */
.app-header__side-group {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}
.app-header__side-link {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  color: var(--color-blanco);
  font-size: 0.9rem;
  font-weight: 500;
  text-decoration: none;
  transition: opacity 0.2s;
}
.app-header__side-link:hover {
  opacity: 0.8;
}

/* Botón toggle (invisible en desktop) */
.app-header__toggle-btn {
  display: none;
  background: none;
  border: none;
  color: var(--color-blanco);
  font-size: 1.1rem;
  cursor: pointer;
}
.app-header__toggle-btn:hover {
  opacity: 0.8;
}

/* Navegación siempre flex por defecto */
.app-header__nav {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

/* Botones */
.app-header__btn {
  padding: 0 1rem;
  height: 2.75rem;
  line-height: 2.75rem;
  font-size: 0.9rem;
  font-weight: 600;
  text-align: center;
  text-decoration: none;
  border-radius: 0.375rem;
  transition: opacity 0.2s, transform 0.1s;
}
.app-header__btn:hover {
  opacity: 0.85;
  transform: translateY(-1px);
}
.btn--planear,
.btn--actuar {
  background-color: var(--color-blanco);
  color: var(--color-azul-oscuro);
}
.btn--hacer {
  background-color: var(--color-amarillo);
  color: var(--color-azul-oscuro);
}
.btn--verificar {
  background-color: var(--color-azul-medio);
  color: var(--color-blanco);
}

/* Responsive */
/* Desktop: nav siempre centrado, Inicio y Mi empresa a los extremos */
@media (min-width: 768px) {
  .app-header__inner {
    position: relative;    /* para contexto de posicionamiento */
  }

  /* Push Inicio y Mi empresa a los bordes */
  .app-header__side-group {
    display: flex;
    justify-content: space-between;
    width: 100%;
    z-index: 1;            /* asegurar que los enlaces laterales queden encima */
  }

  /* Centrar el nav encima de todo */
  .app-header__nav {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    /* quitamos cualquier wrap forzado */
    display: flex !important;
    flex-wrap: nowrap;
    z-index: 0;
  }
}

@media (max-width: 768px) {
    .app-header {
  padding: 0.5rem 1rem;
}
    .app-header__side-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }
  .app-header__inner {
    flex-direction: column;
    align-items: stretch;
    gap: 0.5rem;
  }

  /* Mostrar el toggle en móvil */
  .app-header__toggle-btn {
     display: flex;                 /* ya visible en móvil */
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
    background-color:var(--color-azul-oscuro) ;
    color: var(--color-blanco);
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    border: 2px solid var(--color-amarillo);
    position: relative;
    top:1.2rem;
    transition: transform 0.2s;
  }
    .app-header__toggle-btn:hover {
    transform: translateY(-2px);
  }
  .app-header__nav {
    flex-direction: column;
    gap: 0.5rem;
    width: 100%;
    margin-top: 1.5rem;        
    transform-origin: top center;
  }
  .app-header__side-link {
    flex: none;
  }
  .app-header__nav.overflow-hidden {
  /* Garantiza que no se vean bordes raros */
  will-change: transform, opacity;
}
  /* x-cloak oculta hasta que Alpine inicializa */
  [x-cloak] {
    display: none !important;
  }
}

    </style>
    <!-- resources/views/components/app-header.blade.php -->
<div
  x-data="{ navOpen: window.innerWidth >= 768 }"
  x-init="() => {
    window.addEventListener('resize', () => {
      navOpen = window.innerWidth >= 768;
    })
  }"
  class="app-header"
>
  <div class="app-header__inner">
    <!-- Grupo lateral -->
    <div class="app-header__side-group">
      <a href="#" class="app-header__side-link">
        <i class="fas fa-home"></i>
        Inicio
      </a>

      <!-- Toggle solo en móvil -->
      <button
        type="button"
        @click="navOpen = !navOpen"
        class="app-header__toggle-btn"
        aria-label="Mostrar/ocultar navegación"
      >
        <i x-show="!navOpen" class="fas fa-chevron-down"></i>
        <i x-show="navOpen"  class="fas fa-chevron-up"></i>
      </button>

      <a href="#" class="app-header__side-link">
        <i class="fas fa-building"></i>
        Mi empresa
      </a>
    </div>

    <!-- Navegación central -->
<nav
  x-show="navOpen"
  x-cloak
  x-transition:enter="transition ease-out duration-300"
  x-transition:enter-start="opacity-0 -translate-y-4"
  x-transition:enter-end="opacity-100 translate-y-0"
  x-transition:leave="transition ease-in duration-200"
  x-transition:leave-start="opacity-100 translate-y-0"
  x-transition:leave-end="opacity-0 -translate-y-4"
  class="app-header__nav overflow-hidden"
>
  <a href="#" class="app-header__btn btn--planear">Planear</a>
  <a href="#" class="app-header__btn btn--hacer">Hacer</a>
  <a href="#" class="app-header__btn btn--verificar">Verificar</a>
  <a href="#" class="app-header__btn btn--actuar">Actuar</a>
</nav>
  </div>
</div>
</header>
@endauth
