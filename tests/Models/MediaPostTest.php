<?php

declare(strict_types=1);

namespace Tests\Models;

use NotKinopoisk\Models\MediaPost;
use PHPUnit\Framework\TestCase;

class MediaPostTest extends TestCase
{
    public function testConstructor(): void
    {
        $post = new MediaPost(
            kinopoiskId: 12345,
            imageUrl: 'https://example.com/image.jpg',
            title: 'Новости о фильме',
            description: 'Описание новости',
            url: 'https://example.com/post',
            publishedAt: '2024-01-01T12:00:00'
        );

        $this->assertEquals(12345, $post->kinopoiskId);
        $this->assertEquals('https://example.com/image.jpg', $post->imageUrl);
        $this->assertEquals('Новости о фильме', $post->title);
        $this->assertEquals('Описание новости', $post->description);
        $this->assertEquals('https://example.com/post', $post->url);
        $this->assertEquals('2024-01-01T12:00:00', $post->publishedAt);
    }

    public function testFromArray(): void
    {
        $data = [
            'kinopoiskId' => 12345,
            'imageUrl' => 'https://example.com/image.jpg',
            'title' => 'Новости о фильме',
            'description' => 'Описание новости',
            'url' => 'https://example.com/post',
            'publishedAt' => '2024-01-01T12:00:00'
        ];

        $post = MediaPost::fromArray($data);

        $this->assertEquals(12345, $post->kinopoiskId);
        $this->assertEquals('https://example.com/image.jpg', $post->imageUrl);
        $this->assertEquals('Новости о фильме', $post->title);
        $this->assertEquals('Описание новости', $post->description);
        $this->assertEquals('https://example.com/post', $post->url);
        $this->assertEquals('2024-01-01T12:00:00', $post->publishedAt);
    }

    public function testFromArrayWithEmptyStrings(): void
    {
        $data = [
            'kinopoiskId' => 12345,
            'imageUrl' => '',
            'title' => '',
            'description' => '',
            'url' => '',
            'publishedAt' => ''
        ];

        $post = MediaPost::fromArray($data);

        $this->assertEquals(12345, $post->kinopoiskId);
        $this->assertEquals('', $post->imageUrl);
        $this->assertEquals('', $post->title);
        $this->assertEquals('', $post->description);
        $this->assertEquals('', $post->url);
        $this->assertEquals('', $post->publishedAt);
    }
} 