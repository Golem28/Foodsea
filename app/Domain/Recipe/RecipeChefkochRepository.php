<?php

interface RecipeChefkochRepository {
    public function get(int $id): Recipe;
}
