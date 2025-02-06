<x-mail::message>

<x-mail::panel>
# Hola,

Has rebut aquest correu electrònic perquè hem rebut una sol·licitud per restablir la contrasenya del teu compte.

{{-- Botó d'acció --}}
@isset($actionText)
<x-mail::panel>
    <x-mail::button :url="$actionUrl" color="primary">
        {{ $actionText }}
    </x-mail::button>
</x-mail::panel>
@endisset



Aquest enllaç de restabliment de contrasenya caducarà en **60 minuts**.
Si no has sol·licitat aquest restabliment, pots ignorar aquest missatge.

Gràcies per confiar en **SwapSell**! 

Salutacions,
**L’equip de SwapSell**
</x-mail::panel>
Aquest enllaç de restabliment de contrasenya caducarà en 60 minuts.

Si no has sol·licitat el restabliment de la contrasenya, no es necessita cap acció addicional.

</x-mail::message>

