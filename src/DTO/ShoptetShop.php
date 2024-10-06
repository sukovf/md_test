<?php

namespace App\DTO;

use JMS\Serializer\Annotation\XmlList;
use JMS\Serializer\Annotation\XmlRoot;

#[XmlRoot(name: 'SHOP')]
class ShoptetShop
{
	/** @var ShoptetItem[] */
	#[XmlList(entry: 'SHOPITEM', inline: true)]
	private array $items = [];

	public function addItem(ShoptetItem $newItem): self
	{
		foreach ($this->items as $item) {
			if ($item->getNo() === $newItem->getNo()) {
				$item->addCategories($newItem->getCategories());

				return $this;
			}
		}

		$this->items[] = $newItem;

		return $this;
	}
}