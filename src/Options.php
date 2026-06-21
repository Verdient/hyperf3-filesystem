<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

/**
 * 选项
 *
 * @author Verdient。
 */
class Options
{
    /**
     * @param ?string $filename 文件名
     * @param ?ContentDispositionType $contentDispositionType 内容处置类型
     * @param ?string $contentType 内容类型
     *
     * @author Verdient。
     */
    public function __construct(
        public readonly ?string $filename = null,
        public readonly ?ContentDispositionType $contentDispositionType = null,
        public readonly ?string $contentType = null,
    ) {}

    /**
     * 合并选项
     *
     * @param Options $options 选项
     *
     * @author Verdient。
     */
    public function merge(Options $options): Options
    {
        return new Options(
            filename: $options->filename ?: $this->filename,
            contentDispositionType: $options->contentDispositionType ?: $this->contentDispositionType,
            contentType: $options->contentType ?: $this->contentType,
        );
    }
}
