<?php

declare(strict_types=1);

namespace NotKinopoisk\Services;

use NotKinopoisk\Enums\ApiVersion;
use NotKinopoisk\Models\MediaPost;
use NotKinopoisk\Responses\DefaultResponse;
use NotKinopoisk\Responses\PaginatedResponse;

/**
 * Сервис для работы с медиа контентом в Kinopoisk API
 *
 * Предоставляет методы для получения медиа материалов, связанных с фильмами:
 * новости, статьи, интервью и другие публикации.
 *
 * Основные возможности:
 * - Получение медиа постов о фильмах
 * - Наследование функциональности от AbstractService
 * - Использование API версии v1 по умолчанию
 * - Доступ к HTTP-клиенту для выполнения запросов
 *
 * @package NotKinopoisk\Services
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\AbstractService
 * @see     \NotKinopoisk\Models\MediaPost
 *
 * @example
 * ```php
 * $client = new Client('api-key');
 * $mediaService = $client->media;
 *
 * // Получение медиа постов о фильме
 * $posts = $mediaService->getByFilmId(301);
 *
 * foreach ($posts as $post) {
 *     echo "Заголовок: {$post->title}\n";
 *     echo "URL: {$post->url}\n";
 * }
 * ```
 */
class MediaService extends AbstractService {

	/**
	 * Получает список медиа-постов с поддержкой пагинации
	 *
	 * Загружает страницу медиа-постов (новости, статьи, интервью и другой контент)
	 * связанных с текущим фильмом или сериалом. Поддерживает постраничную навигацию
	 * для обработки больших объемов медиа-контента.
	 *
	 * @api /api/v1/media_posts
	 *
	 * @see \NotKinopoisk\Models\MediaPost Структура объекта медиа-поста
	 * @see \NotKinopoisk\Responses\PaginatedResponse Детали пагинированного ответа
	 *
	 * @param   int  $page  Номер страницы для загрузки (начиная с 1, по умолчанию первая страница)
	 *
	 * @return PaginatedResponse Пагинированный ответ содержащий коллекцию медиа-постов с метаданными навигации
	 *
	 * @throws \NotKinopoisk\Exception\ApiException При общих ошибках API или проблемах сети
	 * @throws \NotKinopoisk\Exception\InvalidApiKeyException Если API ключ недействителен, заблокирован или отсутствует
	 * @throws \NotKinopoisk\Exception\KpValidationException При некорректном номере страницы или других параметрах валидации
	 * @throws \NotKinopoisk\Exception\RateLimitException При превышении лимитов запросов (дневных или общих)
	 * @throws \NotKinopoisk\Exception\ResourceNotFoundException Если запрашиваемая страница или ресурс не найден
	 *
	 * @example
	 * ```php
	 * // Получение первой страницы медиа-постов
	 * $posts = $mediaService->getPosts();
	 *
	 * foreach ($posts->items as $post) {
	 *     echo "Заголовок: {$post->title}\n";
	 *     echo "Описание: {$post->description}\n";
	 *     echo "URL: {$post->url}\n";
	 *     echo "Дата публикации: {$post->publishedAt}\n";
	 *     echo "Изображение: {$post->imageUrl}\n";
	 *     echo "---\n";
	 * }
	 *
	 * // Пагинация
	 * if ($posts->hasNextPage()) {
	 *     $nextPagePosts = $mediaService->getPosts($posts->getNextPage());
	 * }
	 *
	 * // Получение конкретной страницы
	 * $secondPagePosts = $mediaService->getPosts(2);
	 * echo "Страница {$secondPagePosts->currentPage} из {$secondPagePosts->totalPages}";
	 * ```
	 */
	public function getPosts(int $page = 1): PaginatedResponse {
		$data = $this->get($this->buildUri("media_posts"), ['page' => $page]);

		$response              = PaginatedResponse::fromArray($data, MediaPost::class);
		$response->currentPage = $page;

		return $response;
	}

}