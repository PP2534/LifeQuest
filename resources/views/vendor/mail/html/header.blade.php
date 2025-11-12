@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Life Quest')
<img src="{{ asset('storage/root/logo.png') }}" class="logo" alt="Life Quest Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
