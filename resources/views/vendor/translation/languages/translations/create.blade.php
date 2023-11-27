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

                    <!--So this is for select fields only, such as model groups and params of those-->
                    @include(
                     'translation::forms.text', [
                         'options' => \App\Enums\LangStrEnum::getGroupsForTranslation(),
                         'params' => [],
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

                    <!--This is for text fields only-->
                    @include(
                     'translation::forms.text', [
                         'field' => 'value',
                         'label' => __('translation::translation.value_label'),
                         'placeholder' => __('translation::translation.value_placeholder')
                     ])

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