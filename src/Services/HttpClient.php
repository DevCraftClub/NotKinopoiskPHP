/**
 * HTTP клиент для работы с Kinopoisk API
 *
 * Предоставляет базовую функциональность для выполнения HTTP запросов
 * к Kinopoisk API. Обрабатывает аутентификацию, заголовки запросов
 * и базовые ошибки API.
 *
 * Основные возможности:
 * - Выполнение GET запросов к API
 * - Автоматическое добавление заголовков авторизации
 * - Обработка ошибок HTTP и API
 * - Поддержка различных типов ответов
 *
 * @package NotKinopoisk\Services
 * @since   1.0.0
 *
 * @author  Maxim Harder <dev@devcraft.club>
 * @version 1.0.0
 * @see     \NotKinopoisk\Services\AbstractService
 * @api     https://kinopoiskapiunofficial.tech
 * @link    https://kinopoiskapiunofficial.tech/documentation/api
 *
 * @example
 * ```php
 * $httpClient = new HttpClient('your-api-key');
 *
 * // Выполнение GET запроса
 * $response = $httpClient->get('/api/v1/persons');
 *
 * // Обработка ответа
 * if ($response->isSuccess()) {
 *     $data = $response->getData();
 * }
 * ```
 */ 