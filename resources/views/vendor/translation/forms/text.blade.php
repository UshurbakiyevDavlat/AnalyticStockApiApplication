<div class="input-group">
    @if(isset($inputType) && $inputType === 'select')
        <div class="input-group">
            <select
                class="@if($errors->has($group_field)) error @endif"
                name="{{ $group_field }}"
                id="{{ $group_field }}"
                {{ isset($required) ? 'required' : '' }}
            >
                @foreach ($options as $optionValue => $optionLabel)
                    <option
                        value="{{ strtolower($optionLabel) }}"
                        {{ old($field) == $optionLabel ? 'selected' : '' }}
                    >
                        {{ $optionLabel }}
                    </option>
                @endforeach
            </select>
        </div>

    <div class="input-group">
        <input
            class="@if($errors->has($primary_field)) error @endif"
            name="{{ $primary_field }}"
            id="{{ $primary_field }}"
            type="number"
            placeholder="{{ $primary_placeholder ?? '' }}"
            value="{{ old($primary_field) }}"
            {{ isset($required) ? 'required' : '' }}
            oninput="updateHiddenField()"
        >
    </div>

        <div class="input-group">
            <select
                class="@if($errors->has($argument_field)) error @endif"
                name="{{ $argument_field }}"
                id="{{ $argument_field }}"
                {{ isset($required) ? 'required' : '' }}
                onchange="updateHiddenField()"
            >
                <option value="" disabled selected>Select an option</option>
                {{-- Assume $options is an array of options --}}
                @foreach($params as $paramValue => $paramLabel)
                    <option
                        value="{{ $paramLabel }}"
                        {{ old($argument_field) == $paramLabel ? 'selected' : '' }}
                    >
                        {{ $paramLabel }}
                    </option>
                @endforeach
            </select>
        </div>

        <input
            name="{{ $field }}"
            id="{{ $field }}"
            type='hidden'
            value="{{ $primary_field . '.' . $argument_field}}"
        >
    @else
        <input
            class="@if($errors->has($field)) error @endif"
            name="{{ $field }}"
            id="{{ $field }}"
            type="text"
            placeholder="{{ $placeholder ?? '' }}"
            value="{{ old($field) }}"
            {{ isset($required) ? 'required' : '' }}
        >
    @endif

    @if($errors->has($field))
        @foreach($errors->get($field) as $error)
            <p class="error-text">{!! $error !!}</p>
        @endforeach
    @endif
</div>

@if(isset($inputType) && $inputType === 'select')
<script>
    function updateHiddenField() {
        // Update the value of the hidden input based on the values of $primary and $argument
        document.getElementById('{{ $field }}').value = document.getElementById(
            '{{ $primary_field }}').value
            + '.'
            + document.getElementById('{{ $argument_field }}').value;
    }
</script>
@endif