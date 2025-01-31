@props(['rect' => false, 'checked' => false, 'enabled' => true])
<label class="switch_on_off">
    <input type="checkbox" {!! $attributes !!} {{ $checked ? 'checked' : '' }} {{ $enabled ? '' : 'disabled' }} />
    <span class="slider {{ $rect ? 'rect' : '' }}">
      <span class="text on">Вкл</span>
      <span class="text off">Выкл</span>
    </span>
</label>

