<div class="card mb-3" style="width: 100%; text-decoration: none;">
    <div class="row no-gutters">

        <div class="col-md-4">
            <div class="recipe-thumb">
                <a href="{{$recipe->originUrl}}" class="card mb-3" style="width: 100%; text-decoration: none;">
                    <img src="{{ str_replace('<format>', 'crop-240x300', $recipe->imageUrl) }}" alt="preview">
                </a>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title">{{ $recipe->title }}</h5>
                @if ($recipe->isFavourite)
                    <a class="btn btn-outline-primary" href="{{ route('favourites/delete', $recipe->id) }}">Remove from
                        Favorites</a>
                @else
                    <a class="btn btn-outline-primary" href="{{ route('favourites/create', $recipe->id) }}">Add to
                        Favorites</a>
                @endif
            </div>
        </div>
    </div>
</div>