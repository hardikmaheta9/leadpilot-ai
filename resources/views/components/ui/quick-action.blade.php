@props([
'icon',
'title',
'link'=>'#'
])

<a href="{{ $link }}"
class="lp-quick-action">

<i class="{{ $icon }}"></i>

<span>

{{ $title }}

</span>

</a>