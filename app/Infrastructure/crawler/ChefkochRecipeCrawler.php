<?php

class ChefkochRecipeCrawler {
    public function get_all() {

    }

    public function get(int $id) {
        return HttpClient::sendRequest($id);
    }
}
