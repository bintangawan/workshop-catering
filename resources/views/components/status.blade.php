@props(['status'])
@php($labels = ['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai'])
<span {{ $attributes->class('status status-'.$status) }}><span aria-hidden="true"></span>{{ $labels[$status] }}</span>
