<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

use Iterator;
use League\Flysystem\StorageAttributes;

/**
 * 适配器接口
 *
 * @author Verdient。
 */
interface AdapterInterface
{
    /**
     * 写入
     *
     * @param string $location 位置
     * @param string $content 内容
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    public function write(string $location, string $content, ?Options $options = null): void;

    /**
     * 写入文件
     *
     * @param string $location 位置
     * @param string $path 文件路径
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    public function writeFile(string $location, string $path, ?Options $options = null): void;

    /**
     * 写入流
     *
     * @param string $location 位置
     * @param resource $stream 流
     * @param Options $options 选项
     *
     * @author Verdient。
     */
    public function writeStream(string $location, $stream, ?Options $options = null): void;

    /**
     * 检查是否存在
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public function exists(string $location): bool;

    /**
     * 读取
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public function read(string $location): string;

    /**
     * 读取流
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public function readStream(string $location);

    /**
     * 删除
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public function delete(string $location): void;

    /**
     * 复制到本地
     *
     * @param string $location 位置
     * @param string $path 路径
     *
     * @author Verdient。
     */
    public function copyToLocal(string $location, string $path): void;

    /**
     * 移动到本地
     *
     * @param string $location 位置
     * @param string $path 路径
     *
     * @author Verdient。
     */
    public function moveToLocal(string $location, string $path): void;

    /**
     * 获取公共访问地址
     *
     * @param string $location 位置
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    public function publicUrl(string $location, ?Options $options = null): string;

    /**
     * 逐个迭代
     *
     * @param string $location 位置
     *
     * @return Iterator<StorageAttributes>
     * @author Verdient。
     */
    public function each(string $location): Iterator;
}
