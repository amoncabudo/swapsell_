<x-mail::message>
{{-- Logo --}}


{{-- Salutació --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Ups!')
@else
# @lang('Hola!')
@endif
@endif

{{-- Línies d'introducció --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Botó d'acció --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
    {{ $actionText }}
</x-mail::button>
@endisset

{{-- Línies de tancament --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Comiat --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Salutacions,')<br>
{{ config('app.name') }}
@endif

{{-- Subcòpia --}}
@isset($actionText)
<x-slot:subcopy>
    @lang(
        "Si tens problemes per fer clic al botó \":actionText\", copia i enganxa l'URL següent\n".
        'al teu navegador web:',
        ['actionText' => $actionText]
    ) 
    <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
