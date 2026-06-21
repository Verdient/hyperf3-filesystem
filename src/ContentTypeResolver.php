<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

/**
 * 内容类型解析器
 *
 * @author Verdient。
 */
class ContentTypeResolver
{
    /**
     * 通过路径解析内容类型
     *
     * @param string $path 路径
     *
     * @author Verdient。
     */
    public static function resolvePath(string $path): ?string
    {
        return mime_content_type($path);
    }

    /**
     * 通过内容解析内容类型
     *
     * @param string $content 内容
     *
     * @author Verdient。
     */
    public static function resolveContent(string &$content): ?string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        $result = finfo_buffer($finfo, substr($content, 0, 2048));

        finfo_close($finfo);

        return $result ?: null;
    }
}
