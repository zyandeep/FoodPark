<?php 

class Order
{
	private $im_id;
	private $im_name;
	private $price_per_item;
	private $quantity;
	private $amount;

	public function __construct($im_id, $im_name, $price_per_item, $quantity=0) {
		$this->im_id = $im_id;
		$this->im_name = $im_name;
		$this->price_per_item = $price_per_item;
		$this->quantity = $quantity;

		// Calculate the amount
		$this->amount = $this->price_per_item * $this->quantity;
	}

	public function setQuantity($quantity) {
		$this->quantity = $quantity;

		$this->calAmount();	
	}

	private function calAmount() {
		$this->amount = $this->price_per_item * $this->quantity;
	}

	public function getID() {
		return $this->im_id;
	}
	public function getName() {
		return $this->im_name;	
	}
	public function getPricePerItem() {
		return $this->price_per_item;	
	}
	public function getQuantity() {
		return $this->quantity;	
	}
	public function getAmount() {
		return $this->amount;	
	}	
}