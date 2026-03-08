@foreach ($recipes as $recipe)
    @component('components/recipe', ['recipe' => $recipe])
    @endcomponent
@endforeach

@if (isset($error))
    <div class="alert alert-danger">
        <ul>
            <li>{{ $error }}</li>
        </ul>
    </div>
@endif