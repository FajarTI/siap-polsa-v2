<div class="form-group mb-2">
    @if($label)
        <label for="{{ $id }}">
            {{ $label }}
            @if($attributes->has('required'))
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="form-control form-control-sm @error($name) is-invalid @enderror"
        {{ $attributes }}
    >

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
