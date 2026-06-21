<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

use Hyperf\Flysystem\OSS\Adapter;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'annotations' => [
                'scan' => [
                    'class_map' => [
                        Adapter::class => __DIR__ . '/class_map/Adapter.php'
                    ]
                ],
            ]
        ];
    }
}
