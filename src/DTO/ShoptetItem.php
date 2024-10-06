<?php

namespace App\DTO;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;

#[XmlRoot(name: 'SHOPITEM')]
class ShoptetItem
{
	#[Type('string')]
	#[SerializedName('CODE')]
	private string $no = '';

	#[Type(ShoptetCategories::class)]
	#[SerializedName('CATEGORIES')]
	private ShoptetCategories $categories;

	#[Type('string')]
	#[SerializedName('NAME')]
	private string $name = '';

	#[Type('float')]
	#[SerializedName('PRICE')]
	private float $price = 0.0;

	public function __construct()
	{
		$this->categories = new ShoptetCategories();
	}

	public function getNo(): string
	{
		return $this->no;
	}

	public function setNo(string $no): self
	{
		$this->no = $no;
		return $this;
	}

	public function getCategories(): ShoptetCategories
	{
		return $this->categories;
	}

	public function addCategories(ShoptetCategories|string $categories): self
	{
		if (is_string($categories)) {
			$this->categories->addCategory($categories);
		} else {
			$this->categories->addCategories($categories);
		}

		return $this;
	}

	public function setName(string $name): self
	{
		$this->name = $name;
		return $this;
	}

	public function setPrice(float $price): self
	{
		$this->price = $price;
		return $this;
	}
}