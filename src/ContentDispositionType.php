<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

/**
 * 内容处置类型
 *
 * @author Verdient。
 */
enum ContentDispositionType: string
{
    /**
     * 附件
     *
     * @author Verdient。
     */
    case ATTACHMENT = 'attachment';

    /**
     * 内联
     *
     * @author Verdient。
     */
    case INLINE = 'inline';
}
