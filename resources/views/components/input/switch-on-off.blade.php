@props(['rect' => false])
<label class="switch_on_off">
    <input type="checkbox" {!! $attributes !!} />
    <span class="slider {{ $rect ? 'rect' : '' }}">
      <span class="text on">Вкл</span>
      <span class="text off">Выкл</span>
    </span>
</label>

