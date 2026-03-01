<?php
namespace App\Http\Livewire;

use App\Application\Queries\SearchRecipesQuery;
use App\Domain\Recipe\ValueObject\RecipeFilter;
use Livewire\Component;

class Recipelist extends Component {
    public $categories;
    public $min_kochzeit;
    public $max_kochzeit;
    public $zutaten;
    public $error;
    public $printed = "";

    public $recepies = [];
    public $isLoading = true;

    public function __construct(
        $id = null,
    ) {
        parent::__construct($id);
    }

    public function render() {
        return view('livewire.recipelist');
    }

    public function getRecepies() {
        $searchRecipesQuery = app(SearchRecipesQuery::class);
        $recipeFilter = new RecipeFilter(
            [],
            $this->zutaten ?? [],
            "",
            $this->max_kochzeit,
            $this->min_kochzeit
        );
        $recipes = $searchRecipesQuery->execute($recipeFilter);

        if (empty($recipes)) {
            $this->error = "No recipes were found";
        } else {
            $this->recepies = $recipes;
        }

        $this->isLoading = false;
    }
}
