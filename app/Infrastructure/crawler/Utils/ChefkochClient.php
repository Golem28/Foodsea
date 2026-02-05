<?php

/*
 * Searches a website content with curl
 */
class ChefkochClient {
    private const URL = 'https://api.chefkoch.de/v2/';

    private function sendRequest($url) {
        $curl = curl_init(self::URL . $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function getRecipe(int $id) {
        return $this->sendRequest(self::URL . 'recipes/' . $id);
    }
}
