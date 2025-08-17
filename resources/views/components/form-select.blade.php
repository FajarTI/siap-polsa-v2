@props([
    'name',
    'label' => null,
    'id' => null,
    'options' => [], // array key => label (atau Collection di-pluck)
    'placeholder' => null,
    'value' => null, // optional override
    'model' => null, // << NEW
])

@php
    // dukung name bertipe array: alamat[kecamatan] -> alamat.kecamatan
    $dotName = str_replace(['[', ']'], ['.', ''], $name);
    $id = $id ?? str_replace(['[', ']'], ['_', ''], $name);

    // prioritas: old() -> value prop -> data_get(model)
    $resolved = old($dotName, $value ?? data_get($model, $dotName));

    // samakan tipe untuk perbandingan
    $resolvedStr = is_array($resolved) ? collect($resolved)->map(fn($v) => (string) $v)->all() : (string) $resolved;

    $isMultiple = $attributes->has('multiple');
@endphp

<div class="form-group mb-2">
    @if ($label)
        <label for="{{ $id }}">
            {{ $label }}
            @if ($attributes->has('required'))
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => 'form-select form-select-sm' . ($errors->has($dotName) ? ' is-invalid' : '')]) }}>
        @if ($placeholder && !$isMultiple)
            <option value="" @selected($resolved === null || $resolved === '' || $resolved === []) disabled hidden>
                {{ $placeholder }}
            </option>
        @endif

        @foreach ($options as $optValue => $text)
            @php $optStr = (string) $optValue; @endphp

            @if ($isMultiple)
                <option value="{{ $optValue }}" @selected(is_array($resolvedStr) && in_array($optStr, $resolvedStr, true))>
                    {{ $text }}
                </option>
            @else
                <option value="{{ $optValue }}" @selected($optStr === $resolvedStr)>
                    {{ $text }}
                </option>
            @endif
        @endforeach
    </select>

    @error($dotName)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
