<?php

namespace App\Infrastructure\Http;

use App\Domain\Common\Responses\FetchIdResponse;
use App\Domain\Recipe\{
    RecipeChefkochRepository,
};
use InvalidArgumentException;

class RecipeChefkochRepositoryImplementation implements RecipeChefkochRepository {
    private const ENDPOINT = 'https://api.chefkoch.de/v2';
    private RestClient $client;

    public function __construct() {
        $this->client = new RestClient(self::ENDPOINT);
    }

    public function getRecipe(int $id): array {
        $response = $this->client->get(self::ENDPOINT . '/recipes/' . $id);
        return $response->getJson();
    }

    public function searchRecipeIds(string $userQuery): FetchIdResponse {
        try {
            $ids = $this->getAllRecipesIds($userQuery);
            return new FetchIdResponse(null, ...$ids);
        } catch (RestError $e) {
            return new FetchIdResponse($e->getErrorMessage());
        }
    }

    public function getRecipeIds(): FetchIdResponse {
        try {
            $ids = $this->getAllRecipesIds();
            return new FetchIdResponse(null, ...$ids);
        } catch (RestError $e) {
            return new FetchIdResponse($e->getErrorMessage());
        }
    }

    private function getAllRecipesIds(?string $userQuery = null): array {
        $allRecipeIds = [];
        $offset = 0;
        $limit = 100;

        do {
            $response = $this->getMultipleRecipes($limit, $offset, $userQuery);
            if ($response->getStatusCode() !== 200) {
                throw new RestError($response->getStatusCode(), "Failed to fetch recipes: " . $response->getStatusCode());
            }

            $ids = $this->getIdsFromResponse($response);
            if (empty($ids)) {
                break;
            }

            $allRecipeIds = array_merge($allRecipeIds, $ids);
            $offset += count($ids);
        } while (count($ids) === $limit);

        return array_unique($allRecipeIds);
    }

    private function getIdsFromResponse(HttpResponse $response): array {
        $data = $response->getJson();
        $recipeIds = [];
        if (
            !isset($data['results'])
            || !is_array($data['results'])
        ) {
            throw new InvalidArgumentException("Invalid response format: 'results' key missing or not an array");
        }

        foreach ($data['results'] as $item) {
            if (!isset($item['recipe']['id'])) {
                throw new InvalidArgumentException("Invalid response format: 'recipe.id' key missing in one of the results");
            }

            $recipeIds[] = $item['recipe']['id'];
        }

        return $recipeIds;
    }

    private function getMultipleRecipes(
        int $limit,
        int $offset,
        ?string $userQuery = null,
        bool $descendCategories = true,
        int $orderBy = 2,
        bool $ascending = true
    ): HttpResponse {
        $params = [
            'limit' => $limit,
            'offset' => $offset,
            'descendCategories' => $descendCategories,
            'orderBy' => $orderBy,
            "order" => $ascending ? "0" : "1",
        ];
        if ($userQuery) {
            $params['query'] = $userQuery;
        }
        return $this->client->get(self::ENDPOINT . '/recipes', $params);
    }

    public function getCategories() {
        $response = $this->client->get(self::ENDPOINT . '/recipes/categories');
        return $response->getJson();
    }
}
