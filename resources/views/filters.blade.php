@extends('layouts/app')

@section('title')
  Filtering
@endsection

@section('content')
  @component('components/card')
  <form action="{{ route('result') }}" method="GET">
    <label for="title">Rezepttitel</label>
    <input type="text" id="title" name="title" value="" placeholder="Rezepttitel suchen">

    @component('components/doubleslider',
      [
        'title' => 'Kochzeit',
        'name' => 'Kochzeit (in Minuten)',
        'minimum' => 0,
        'maximum' => 120,
        'min_name' => 'minTimeTotal',
        'max_name' => 'maxTimeTotal'
    ])
    @endcomponent

    <!-- Ratingslider from Bootstrap Starting at 0 -->
    <h3>Bewertung</h3>
    <div class="form-group">
      <input type="range" class="form-control-range" id="rating" name="rating" min="0" max="5" step="0.1" value="0">
    </div>

    <!-- Show the rating value -->
    <div class="form-group">
      <label for="rating">Mindestens: <span id="rating-value">0</span></label>
    </div>

    <!-- Script to show the rating value -->
    <script>
      var slider = document.getElementById("rating");
      var output = document.getElementById("rating-value");
      output.innerHTML = slider.value;

      slider.oninput = function () {
        output.innerHTML = this.value;
      }
    </script>

    @component('components/list', [
      'title' => 'Zutaten',
      'item' => 'ingredients',
      'input_name' => 'ingredient'
    ])
    @endcomponent

    @component('components/category',
    ['categories' => $categories])
    @endcomponent

    <button type="submit" class="btn btn-primary">Suchen</button>
  </form>
  @endcomponent
@endsection

@section('scripts')
@endsection