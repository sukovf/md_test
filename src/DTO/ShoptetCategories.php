<?php

namespace App\DTO;

use JMS\Serializer\Annotation\XmlList;

class ShoptetCategories
{
	/** @var string[] */
	#[XmlList(entry: 'CATEGORY', inline: true)]
	private array $categories = [];

	public function addCategories(self $categories): self
	{
		$this->categories = array_merge($this->categories, $categories->categories);
		$this->categories = array_unique($this->categories);

		return $this;
	}

	public function addCategory(string $category): self
	{
		$this->categories[] = $category;
		$this->categories = array_unique($this->categories);

		return $this;
	}
}