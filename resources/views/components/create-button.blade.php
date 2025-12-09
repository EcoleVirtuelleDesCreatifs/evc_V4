@props(['route', 'icon' => 'plus', 'text' => 'Créer', 'variant' => 'primary'])

@if(isset($canSubmitContent) && $canSubmitContent)
    <a href="{{ $route }}" {{ $attributes->merge(['class' => "btn btn-{$variant}"]) }}>
        <i class="fas fa-{{ $icon }} me-2"></i>{{ $text }}
    </a>
@else
    <button disabled {{ $attributes->merge(['class' => 'btn btn-secondary']) }} style="cursor: not-allowed; opacity: 0.6;">
        <i class="fas fa-lock me-2"></i>{{ $text }} (Compte expiré)
    </button>
@endif
