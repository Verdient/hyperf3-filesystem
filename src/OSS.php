<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Filesystem;

use DateTime;
use Hyperf\Filesystem\FilesystemFactory;
use Iterator;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem;
use League\Uri\Uri;
use Override;
use Verdient\Hyperf3\Di\Container;

/**
 * OSS
 *
 * @author Verdient。
 */
class OSS implements AdapterInterface
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

        $this->filesystem = $factory->get('oss');
    }

    /**
     * 将选项转换为配置
     *
     * @param ?Options $options 选项
     *
     * @author Verdient。
     */
    protected function optionsToConfig(?Options $options): array
    {
        $result = [];

        if ($options) {
            $headers = [];

            if ($options->filename || $options->contentDispositionType !== null) {

                $headers['Content-Disposition'] = $options->contentDispositionType?->value ?: ContentDispositionType::INLINE->value;

                if ($options->filename) {
                    $encodedFilename = rawurlencode($options->filename);
                    $headers['Content-Disposition'] .= '; filename="' . $encodedFilename . '"; filename*=UTF-8\'\'' . $encodedFilename;
                }
            }

            if ($options->contentType) {
                $headers['Content-Type'] = $options->contentType;
            }

            if (!empty($headers)) {
                $result['headers'] = $headers;
            }
        }

        return $result;
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
        if (!$options) {
            $options = new Options();
        }

        if (!$options->contentType) {
            if ($contentType = ContentTypeResolver::resolveContent($content)) {
                $options = $options->merge(new Options(
                    contentType: $contentType,
                ));
            }
        }

        $this->filesystem->write($location, $content, $this->optionsToConfig($options));
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function writeFile(string $location, string $path, ?Options $options = null): void
    {
        $stream = fopen($path, 'r');

        try {
            $this->writeStream($location, $stream, $options);
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function writeStream(string $location, $stream, ?Options $options = null): void
    {
        $meta = stream_get_meta_data($stream);

        if (
            !empty($meta['uri'])
            && is_readable($meta['uri'])
        ) {

            $path = $meta['uri'];

            if (!$options) {
                $options = new Options();
            }

            if (!$options->filename) {
                $locationBaseName = pathinfo($location, PATHINFO_BASENAME);
                $pathBaseName = pathinfo($path, PATHINFO_BASENAME);
                if ($locationBaseName !== $pathBaseName) {
                    $options = $options->merge(new Options(
                        filename: $pathBaseName,
                    ));
                }
            }

            if (!$options->contentType) {
                if ($contentType = ContentTypeResolver::resolvePath($path)) {
                    $options = $options->merge(new Options(
                        contentType: $contentType,
                    ));
                }
            }
        }

        if ($this->exists($location)) {
            $this->filesystem->delete($location);
        }

        $this->filesystem->writeStream($location, $stream, static::optionsToConfig($options));
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
        $stream = $this->readStream($location);

        $dirname = dirname($path);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        file_put_contents($path, $stream);

        fclose($stream);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function moveToLocal(string $location, string $path): void
    {
        $this->copyToLocal($location, $path);
        $this->delete($location);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function publicUrl(string $location, ?Options $options = null): string
    {
        return $this->filesystem->publicUrl($location);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function temporaryUrl(string $location, int $expiredAt, ?Options $options = null): string
    {
        $config = [];

        if (!$options) {
            $options = new Options();
        }

        if ($options->filename || $options->contentDispositionType !== null) {

            $config['response-content-disposition'] = $options->contentDispositionType?->value ?: ContentDispositionType::INLINE->value;

            if ($options->filename) {
                $encodedFilename = rawurlencode($options->filename);
                $config['response-content-disposition'] .= '; filename="' . $encodedFilename . '"; filename*=UTF-8\'\'' . $encodedFilename;
            }
        }

        $uri2 = Uri::new($this->filesystem->publicUrl('/'));

        $dateTime = DateTime::createFromFormat('U', (string) $expiredAt);

        return Uri::new($this->filesystem->temporaryUrl($location, $dateTime, $config))
            ->withScheme($uri2->getScheme())
            ->withUserInfo($uri2->getUsername(), $uri2->getPassword())
            ->withHost($uri2->getHost())
            ->withPort($uri2->getPort())
            ->toString();
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function each(string $location): Iterator
    {
        foreach ($this->filesystem->listContents($location) as $item) {
            if ($item['type'] === 'dir') {
                yield new DirectoryAttributes($item['path']);
            } else if ($item['type'] === 'file') {
                yield new FileAttributes(
                    path: $item['path'],
                    fileSize: $item['size'],
                    lastModified: $item['timestamp']
                );
            }
        }
    }
}
