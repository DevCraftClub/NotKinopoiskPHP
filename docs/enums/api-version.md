# ApiVersion

Перечисление версий API Kinopoisk Unofficial.

## Описание

`ApiVersion` определяет доступные версии API для работы с Kinopoisk Unofficial. Каждая версия API может иметь различные эндпоинты и возможности.

### Основные возможности

- Определение поддерживаемых версий API
- Получение всех доступных версий в виде массива
- Статическое кэширование для повышения производительности

## Значения enum

### Версии API

- `V1` - Версия 1.0 API
- `V2_1` - Версия 2.1 API
- `V2_2` - Версия 2.2 API

## Методы

### getApiVersions()

Получает массив всех доступных версий API.

```php
public static function getApiVersions(): array
```

#### Возвращаемое значение

- `ApiVersion[]` - Массив всех доступных версий API

#### Описание

Возвращает статически кэшированный массив всех версий API, определенных в данном перечислении. Использует ленивую инициализацию для оптимизации производительности при повторных вызовах.

#### Пример использования

```php
$versions = ApiVersion::getApiVersions();

// Вывод всех версий
foreach ($versions as $version) {
    echo "API версия: {$version->value}\n";
}

// Получение количества версий
$count = count(ApiVersion::getApiVersions());
echo "Доступно версий: {$count}";

// Проверка поддержки версии
$supportedVersions = ApiVersion::getApiVersions();
$isSupported = in_array(ApiVersion::V22, $supportedVersions, true);
```

## Полный пример использования

```php
<?php

require_once 'vendor/autoload.php';

use NotKinopoisk\Client;
use NotKinopoisk\Enums\ApiVersion;

// Создание клиента с определенной версией API
$client = new Client('your-api-key');

echo "=== Работа с версиями API ===\n";

// Вывод всех доступных версий
echo "Доступные версии API:\n";
foreach (ApiVersion::getApiVersions() as $version) {
    $icon = getApiVersionIcon($version);
    $description = getApiVersionDescription($version);
    echo "{$icon} {$version->value} - {$description}\n";
}

echo "\n";

// Сравнение версий
$v1 = ApiVersion::V1;
$v2_1 = ApiVersion::V2_1;
$v2_2 = ApiVersion::V2_2;

echo "Сравнение версий:\n";
echo "V1: {$v1->value}\n";
echo "V2.1: {$v2_1->value}\n";
echo "V2.2: {$v2_2->value}\n";

// Проверка поддержки версий
$supportedVersions = ApiVersion::getApiVersions();
echo "\nПоддерживаемые версии:\n";
foreach ($supportedVersions as $version) {
    $isLatest = isLatestVersion($version);
    $status = $isLatest ? ' (последняя)' : '';
    echo "• {$version->value}{$status}\n";
}
```

## Работа с версиями API

```php
// Функция для получения иконки версии API
function getApiVersionIcon(ApiVersion $version): string {
    return match ($version) {
        ApiVersion::V1 => '🔴',
        ApiVersion::V2_1 => '🟡',
        ApiVersion::V2_2 => '🟢'
    };
}

// Функция для получения описания версии API
function getApiVersionDescription(ApiVersion $version): string {
    return match ($version) {
        ApiVersion::V1 => 'Базовая версия API с основными функциями',
        ApiVersion::V2_1 => 'Улучшенная версия с дополнительными возможностями',
        ApiVersion::V2_2 => 'Последняя версия с полным функционалом'
    };
}

// Функция для получения цвета версии API
function getApiVersionColor(ApiVersion $version): string {
    return match ($version) {
        ApiVersion::V1 => '#dc3545',   // Красный
        ApiVersion::V2_1 => '#ffc107', // Желтый
        ApiVersion::V2_2 => '#28a745'  // Зеленый
    };
}

// Функция для проверки, является ли версия последней
function isLatestVersion(ApiVersion $version): bool {
    return $version === ApiVersion::V2_2;
}

// Функция для получения рекомендуемой версии
function getRecommendedVersion(): ApiVersion {
    return ApiVersion::V2_2;
}

// Функция для получения устаревших версий
function getDeprecatedVersions(): array {
    return [ApiVersion::V1];
}

// Функция для проверки, является ли версия устаревшей
function isDeprecatedVersion(ApiVersion $version): bool {
    return in_array($version, getDeprecatedVersions());
}

// Функция для получения стабильных версий
function getStableVersions(): array {
    return [ApiVersion::V2_1, ApiVersion::V2_2];
}

// Функция для проверки, является ли версия стабильной
function isStableVersion(ApiVersion $version): bool {
    return in_array($version, getStableVersions());
}

// Использование
$allVersions = ApiVersion::getApiVersions();

echo "Анализ версий API:\n";
foreach ($allVersions as $version) {
    $icon = getApiVersionIcon($version);
    $description = getApiVersionDescription($version);
    $color = getApiVersionColor($version);
    $isLatest = isLatestVersion($version) ? 'Да' : 'Нет';
    $isDeprecated = isDeprecatedVersion($version) ? 'Да' : 'Нет';
    $isStable = isStableVersion($version) ? 'Да' : 'Нет';

    echo "{$icon} {$version->value}\n";
    echo "   Описание: {$description}\n";
    echo "   Цвет: {$color}\n";
    echo "   Последняя: {$isLatest}\n";
    echo "   Устаревшая: {$isDeprecated}\n";
    echo "   Стабильная: {$isStable}\n\n";
}

// Рекомендации
$recommended = getRecommendedVersion();
echo "Рекомендуемая версия: {$recommended->value}\n";

$deprecated = getDeprecatedVersions();
if (!empty($deprecated)) {
    echo "Устаревшие версии:\n";
    foreach ($deprecated as $version) {
        echo "- {$version->value}\n";
    }
}
```

## Создание отчета по версиям API

```php
class ApiVersionReport {
    private array $versions;

    public function __construct() {
        $this->versions = ApiVersion::getApiVersions();
    }

    public function getVersions(): array {
        return $this->versions;
    }

    public function getLatestVersion(): ApiVersion {
        return ApiVersion::V2_2;
    }

    public function getDeprecatedVersions(): array {
        return getDeprecatedVersions();
    }

    public function getStableVersions(): array {
        return getStableVersions();
    }

    public function createDetailedReport(): string {
        $report = "=== ОТЧЕТ ПО ВЕРСИЯМ API ===\n\n";

        $latestVersion = $this->getLatestVersion();
        $deprecatedVersions = $this->getDeprecatedVersions();
        $stableVersions = $this->getStableVersions();

        // Общая информация
        $report .= "📊 ОБЩАЯ ИНФОРМАЦИЯ:\n";
        $report .= "Всего версий: " . count($this->versions) . "\n";
        $report .= "Последняя версия: {$latestVersion->value}\n";
        $report .= "Устаревших версий: " . count($deprecatedVersions) . "\n";
        $report .= "Стабильных версий: " . count($stableVersions) . "\n\n";

        // Детали по версиям
        $report .= "📋 ДЕТАЛИ ПО ВЕРСИЯМ:\n";
        foreach ($this->versions as $version) {
            $icon = getApiVersionIcon($version);
            $description = getApiVersionDescription($version);
            $isLatest = isLatestVersion($version) ? ' (последняя)' : '';
            $isDeprecated = isDeprecatedVersion($version) ? ' (устаревшая)' : '';
            $isStable = isStableVersion($version) ? ' (стабильная)' : '';

            $report .= "{$icon} {$version->value}{$isLatest}{$isDeprecated}{$isStable}\n";
            $report .= "   Описание: {$description}\n";
            $report .= "   Цвет: " . getApiVersionColor($version) . "\n\n";
        }

        // Рекомендации
        $report .= "💡 РЕКОМЕНДАЦИИ:\n";
        $report .= "• Рекомендуется использовать: {$latestVersion->value}\n";

        if (!empty($deprecatedVersions)) {
            $report .= "• Избегайте использования устаревших версий:\n";
            foreach ($deprecatedVersions as $version) {
                $report .= "  - {$version->value}\n";
            }
        }

        $report .= "• Стабильные версии для продакшена:\n";
        foreach ($stableVersions as $version) {
            $report .= "  - {$version->value}\n";
        }

        return $report;
    }

    public function createHtmlReport(string $title): string {
        $html = "<!DOCTYPE html>\n<html>\n<head>\n";
        $html .= "<title>{$title}</title>\n";
        $html .= "<style>\n";
        $html .= ".report { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }\n";
        $html .= ".section { margin-bottom: 30px; }\n";
        $html .= ".section-title { font-size: 20px; font-weight: bold; margin-bottom: 15px; padding: 10px; background-color: #f8f9fa; border-radius: 5px; }\n";
        $html .= ".version-item { margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }\n";
        $html .= ".version-title { font-weight: bold; font-size: 18px; margin-bottom: 10px; }\n";
        $html .= ".version-meta { font-size: 14px; color: #666; margin-bottom: 5px; }\n";
        $html .= ".stats { background-color: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }\n";
        $html .= ".version-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px; }\n";
        $html .= ".v1 { border-left: 5px solid #dc3545; }\n";
        $html .= ".v2_1 { border-left: 5px solid #ffc107; }\n";
        $html .= ".v2_2 { border-left: 5px solid #28a745; }\n";
        $html .= ".deprecated { opacity: 0.6; }\n";
        $html .= ".latest { border: 2px solid #28a745; }\n";
        $html .= "</style>\n</head>\n<body>\n";
        $html .= "<div class='report'>\n";
        $html .= "<h1>{$title}</h1>\n";

        $latestVersion = $this->getLatestVersion();
        $deprecatedVersions = $this->getDeprecatedVersions();
        $stableVersions = $this->getStableVersions();

        // Статистика
        $html .= "<div class='stats'>\n";
        $html .= "<h2>Общая информация</h2>\n";
        $html .= "<p><strong>Всего версий:</strong> " . count($this->versions) . "</p>\n";
        $html .= "<p><strong>Последняя версия:</strong> {$latestVersion->value}</p>\n";
        $html .= "<p><strong>Устаревших версий:</strong> " . count($deprecatedVersions) . "</p>\n";
        $html .= "<p><strong>Стабильных версий:</strong> " . count($stableVersions) . "</p>\n";
        $html .= "</div>\n";

        // Версии API
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>📋 Версии API</div>\n";
        $html .= "<div class='version-grid'>\n";

        foreach ($this->versions as $version) {
            $icon = getApiVersionIcon($version);
            $description = getApiVersionDescription($version);
            $color = getApiVersionColor($version);
            $isLatest = isLatestVersion($version);
            $isDeprecated = isDeprecatedVersion($version);
            $isStable = isStableVersion($version);

            $cssClass = 'v' . str_replace('.', '_', $version->value);
            if ($isDeprecated) $cssClass .= ' deprecated';
            if ($isLatest) $cssClass .= ' latest';

            $html .= "<div class='version-item {$cssClass}'>\n";
            $html .= "<div class='version-title'>{$icon} {$version->value}</div>\n";
            $html .= "<div class='version-meta'>{$description}</div>\n";
            $html .= "<div class='version-meta'>Цвет: {$color}</div>\n";

            if ($isLatest) {
                $html .= "<div class='version-meta'><strong>Последняя версия</strong></div>\n";
            }
            if ($isDeprecated) {
                $html .= "<div class='version-meta'><strong>Устаревшая версия</strong></div>\n";
            }
            if ($isStable) {
                $html .= "<div class='version-meta'><strong>Стабильная версия</strong></div>\n";
            }

            $html .= "</div>\n";
        }

        $html .= "</div>\n</div>\n";

        // Рекомендации
        $html .= "<div class='section'>\n";
        $html .= "<div class='section-title'>💡 Рекомендации</div>\n";
        $html .= "<p><strong>Рекомендуется использовать:</strong> {$latestVersion->value}</p>\n";

        if (!empty($deprecatedVersions)) {
            $html .= "<p><strong>Избегайте использования устаревших версий:</strong></p>\n";
            $html .= "<ul>\n";
            foreach ($deprecatedVersions as $version) {
                $html .= "<li>{$version->value}</li>\n";
            }
            $html .= "</ul>\n";
        }

        $html .= "<p><strong>Стабильные версии для продакшена:</strong></p>\n";
        $html .= "<ul>\n";
        foreach ($stableVersions as $version) {
            $html .= "<li>{$version->value}</li>\n";
        }
        $html .= "</ul>\n";
        $html .= "</div>\n";

        $html .= "</div>\n</body>\n</html>";

        return $html;
    }
}

// Использование
$report = new ApiVersionReport();

// Создание текстового отчета
$textReport = $report->createDetailedReport();
echo $textReport;

// Создание HTML отчета
$htmlReport = $report->createHtmlReport('Отчет по версиям API');
file_put_contents('api_versions_report.html', $htmlReport);
echo "\n✅ HTML отчет сохранен в api_versions_report.html\n";
```

## Анализ версий API

```php
function analyzeApiVersions(): array {
    $allVersions = ApiVersion::getApiVersions();

    $analysis = [
        'totalVersions' => count($allVersions),
        'latestVersion' => ApiVersion::V2_2,
        'deprecatedVersions' => getDeprecatedVersions(),
        'stableVersions' => getStableVersions(),
        'versionDetails' => []
    ];

    foreach ($allVersions as $version) {
        $analysis['versionDetails'][$version->value] = [
            'isLatest' => isLatestVersion($version),
            'isDeprecated' => isDeprecatedVersion($version),
            'isStable' => isStableVersion($version),
            'icon' => getApiVersionIcon($version),
            'color' => getApiVersionColor($version),
            'description' => getApiVersionDescription($version)
        ];
    }

    return $analysis;
}

// Использование
$analysis = analyzeApiVersions();

echo "=== Анализ версий API ===\n";
echo "Всего версий: {$analysis['totalVersions']}\n";
echo "Последняя версия: {$analysis['latestVersion']->value}\n";
echo "Устаревших версий: " . count($analysis['deprecatedVersions']) . "\n";
echo "Стабильных версий: " . count($analysis['stableVersions']) . "\n";

echo "\nДетали по версиям:\n";
foreach ($analysis['versionDetails'] as $versionValue => $details) {
    echo "• {$versionValue}:\n";
    echo "  - Последняя: " . ($details['isLatest'] ? 'Да' : 'Нет') . "\n";
    echo "  - Устаревшая: " . ($details['isDeprecated'] ? 'Да' : 'Нет') . "\n";
    echo "  - Стабильная: " . ($details['isStable'] ? 'Да' : 'Нет') . "\n";
    echo "  - Описание: {$details['description']}\n";
}
```

## Связанные классы

- [`Client`](../client.md) - Основной клиент для работы с API
- [`AbstractService`](../services/abstract-service.md) - Базовый класс для сервисов
