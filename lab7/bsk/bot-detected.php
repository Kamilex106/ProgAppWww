<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once './utils/templates.php';

renderHeader("Moderato - Home");

class FakeDBResult {
    private $data;
    private $index;
    public $num_rows;

    public function __construct() {
        $fake_product = ['id' => 0, 'prodName' => 'Bot product', 'price' => 69.42];
        $fake_prod_list = [];
        for ($j = 0; $j < rand(3, 6); $j++) {
            $fake_prod_list = array_merge($fake_prod_list, [$fake_product]);
        }
        $this->data = $fake_prod_list;
        $this->index = 0;
        $this->num_rows = count($this->data);
    }

    public function fetch_assoc() {
        if ($this->index < count($this->data)) {
            return $this->data[$this->index++];
        }
        return null;
    }
}

for ($i=1; $i < 4; $i++) { 
    renderProductList("Bot category $i", new FakeDBResult());
}


renderFooter();