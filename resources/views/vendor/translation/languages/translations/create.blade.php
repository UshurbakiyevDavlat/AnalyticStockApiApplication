@extends('translation::layout')

@section('body')

    <div class="panel w-1/2">

        <div class="panel-header">

            {{ __('translation::translation.add_translation') }}

        </div>

        <form action="{{ route('languages.translations.store', $language) }}" method="POST">

            <fieldset>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="panel-body p-4">

                    @include(
                     'translation::forms.text', [
                         'options' => \App\Enums\LangStrEnum::getGroupsForTranslation(),
                         'params' => \App\Enums\LangStrEnum::getParamsForTranslation(),
                         'field' => 'key',
                         'group_field' => 'group',
                         'primary_field' => 'id',
                         'argument_field' => 'param',
                         'inputType' => 'select',
                         'group_label' => __('translation::translation.group_label'),
                         'key_label' => __('translation::translation.key_label'),
                         'placeholder' => __('translation::translation.key_placeholder'),
                         'primary_placeholder' => 'enter id',
                         'param_placeholder' => 'enter param',
                     ])

                    @include(
                     'translation::forms.text', [
                         'field' => 'value',
                         'label' => __('translation::translation.value_label'),
                         'placeholder' => __('translation::translation.value_placeholder')
                     ])

                    <div class="input-group">

                        <button v-on:click="toggleAdvancedOptions"
                                class="text-blue">{{ __('translation::translation.advanced_options') }}</button>

                    </div>

                    <div v-show="showAdvancedOptions">

                        @include('translation::forms.text', ['field' => 'namespace', 'label' => __('translation::translation.namespace_label'), 'placeholder' => __('translation::translation.namespace_placeholder')])

                    </div>


                </div>

            </fieldset>

            <div class="panel-footer flex flex-row-reverse">

                <button class="button button-blue">
                    {{ __('translation::translation.save') }}
                </button>

            </div>

        </form>

    </div>

@endsection