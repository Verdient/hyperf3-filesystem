<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

use Iterator;
use League\Flysystem\StorageAttributes;

/**
 * 文件系统
 *
 * @author Verdient。
 */
class Filesystem
{
    /**
     * 缓存的OSS实例
     *
     * @author Verdient。
     */
    protected static ?OSS $oss = null;

    /**
     * 缓存的本地实例
     *
     * @author Verdient。
     */
    protected static ?Local $local = null;

    /**
     * OSS存储
     *
     * @author Verdient。
     */
    public static function OSS(): AdapterInterface
    {
        if (static::$oss === null) {
            static::$oss = new OSS();
        }

        return static::$oss;
    }

    /**
     * 本地磁盘存储
     *
     * @author Verdient。
     */
    public static function local(): AdapterInterface
    {
        if (static::$local === null) {
            static::$local = new Local();
        }

        return static::$local;
    }

    /**
     * 默认存储
     *
     * @author Verdient。
     */
    public static function default(): AdapterInterface
    {
        return static::OSS();
    }

    /**
     * 写入
     *
     * @param string $location 位置
     * @param string $contents 内容
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    public static function write(
        string $location,
        string $contents,
        ?Options $options = null
    ): void {
        static::default()->write($location, $contents, $options);
    }

    /**
     * 写入文件
     *
     * @param string $location 位置
     * @param string $path 文件路径
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    public static function writeFile(string $location, string $path, ?Options $options = null): void
    {
        static::default()->writeFile($location, $path, $options);
    }

    /**
     * 写入流
     *
     * @param string $location 位置
     * @param resource $stream 流
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    public static function writeStream(string $location, $stream, ?Options $options = null): void
    {
        static::default()->writeStream($location, $stream, $options);
    }

    /**
     * 读取
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public static function read(string $location): string
    {
        return static::default()->read($location);
    }

    /**
     * 读取流
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public static function readStream(string $location)
    {
        return static::default()->readStream($location);
    }

    /**
     * 检查是否存在
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public static function exists(string $location): bool
    {
        return static::default()->exists($location);
    }

    /**
     * 删除
     *
     * @param string $location 位置
     *
     * @author Verdient。
     */
    public static function delete(string $location): void
    {
        static::default()->delete($location);
    }

    /**
     * 复制到本地
     *
     * @param string $location 位置
     * @param string $path 路径
     *
     * @author Verdient。
     */
    public static function copyToLocal(string $location, string $path): void
    {
        static::default()->copyToLocal($location, $path);
    }

    /**
     * 移动到本地
     *
     * @param string $location 位置
     * @param string $path 路径
     *
     * @author Verdient。
     */
    public static function moveToLocal(string $location, string $path): void
    {
        static::default()->moveToLocal($location, $path);
    }

    /**
     * 获取公共访问地址
     *
     * @param string $location 位置
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    public static function publicUrl(string $location, ?Options $options = null): string
    {
        return static::default()->publicUrl($location, $options);
    }

    /**
     * 逐个迭代
     *
     * @param string $location 位置
     *
     * @return Iterator<StorageAttributes>
     * @author Verdient。
     */
    public static function each(string $location): Iterator
    {
        return static::default()->each($location);
    }
}
