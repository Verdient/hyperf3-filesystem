<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

use BadFunctionCallException;
use Hyperf\Filesystem\FilesystemFactory;
use Iterator;
use League\Flysystem\Filesystem;
use Override;
use Verdient\Hyperf3\Di\Container;

/**
 * Local
 *
 * @author Verdient。
 */
class Local implements AdapterInterface
{
    /**
     * 文件系统
     *
     * @author Verdient。
     */
    protected Filesystem $filesystem;

    /**
     * @author Verdient。
     */
    public function __construct()
    {
        $factory = Container::get(FilesystemFactory::class);

        $this->filesystem = $factory->get('local');
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function write(
        string $location,
        string $content,
        ?Options $options = null
    ): void {
        $this->filesystem->write($location, $content);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function writeFile(string $location, string $path, ?Options $options = null): void
    {
        $this->copyToLocal($location, $path);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function writeStream(string $location, $stream, ?Options $options = null): void
    {
        $this->filesystem->writeStream($location, $stream);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function exists(string $location): bool
    {
        return $this->filesystem->fileExists($location);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function delete(string $location): void
    {
        $this->filesystem->delete($location);
    }

    /**
     * @author Verdient。
     */
    public function read(string $location): string
    {
        return $this->filesystem->read($location);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function readStream(string $location)
    {
        return $this->filesystem->readStream($location);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function copyToLocal(string $location, string $path): void
    {
        if ($location !== $path) {

            $dirname = dirname($location);

            if (!is_dir($dirname)) {
                mkdir($dirname, 0755, true);
            }

            copy($path, $location);
        }
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function moveToLocal(string $location, string $path): void
    {
        if ($location !== $path) {
            rename($location, $path);
        }
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function publicUrl(string $location): string
    {
        throw new BadFunctionCallException('Local adapter does not support publicUrl method');
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function temporaryUrl(string $location, int $expiredAt, ?Options $options = null): string
    {
        throw new BadFunctionCallException('Local adapter does not support temporaryUrl method');
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function each(string $location): Iterator
    {
        foreach ($this->filesystem->listContents($location) as $item) {
            yield $item;
        }
    }
}
