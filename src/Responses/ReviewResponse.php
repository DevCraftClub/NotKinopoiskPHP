<?php

namespace NotKinopoisk\Responses;

class ReviewResponse extends PaginatedResponse {

	public function __construct(
		int        $total,
		array      $items,
		int        $currentPage,
		int        $totalPages,
		public int $totalPositiveReviews,
		public int $totalNegativeReviews,
		public int $totalNeutralReviews,
	) {
		$this->validatePaginationParameters($currentPage, $totalPages);
		parent::__construct($total, $items, $currentPage, $totalPages);
	}

	public static function fromArray(array $data, string $cls): self {
		parent::checkClass($cls);

		return new self(
			total               : (int) $data['total'],
			items               : array_map(
				static fn (array $itemData): object => $cls::fromArray($itemData),
				$data['items'] ?? [],
			),
			currentPage         : (int) ($data['current_page'] ?? $data['page'] ?? 1),
			totalPages          : (int) ($data['total_pages'] ?? 1),
			totalPositiveReviews: (int) $data['totalPositiveReviews'],
			totalNegativeReviews: (int) $data['totalNegativeReviews'],
			totalNeutralReviews : (int) $data['totalNeutralReviews'],
		);
	}

	public function toArray(): array {
		return [
			'total'                => $this->total,
			'items'                => $this->items,
			'current_page'         => $this->currentPage,
			'total_pages'          => $this->totalPages,
			'totalPositiveReviews' => $this->totalPositiveReviews,
			'totalNegativeReviews' => $this->totalNegativeReviews,
			'totalNeutralReviews'  => $this->totalNeutralReviews,
		];
	}

}