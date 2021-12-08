<?php

/*
 * This file is part of the PowerWeChatComposer.
 *
 * (c) amoydavid <liuw@liuw.net>
 * (c) mingyoung <mingyoungcheung@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PowerWeChatComposer;

class ManifestManager
{
    const PACKAGE_TYPE = 'powerwechat-extension';

    const EXTRA_OBSERVER = 'observers';

    /**
     * The vendor path.
     *
     * @var string
     */
    protected $vendorPath;

    /**
     * The manifest path.
     *
     * @var string
     */
    protected $manifestPath;

    /**
     * @param string $vendorPath
     * @param string $manifestPath
     */
    public function __construct(string $vendorPath, string $manifestPath)
    {
        $this->vendorPath = $vendorPath;
        $this->manifestPath = $manifestPath;
    }

    /**
     * Remove manifest file.
     *
     * @return $this
     */
    public function unlink()
    {
        if (file_exists($this->manifestPath)) {
            @unlink($this->manifestPath);
        }

        return $this;
    }

    /**
     * Build the manifest file.
     */
    public function build()
    {
        $packages = [];

        if (file_exists($installed = $this->vendorPath.'/composer/installed.json')) {
            $packages = json_decode(file_get_contents($installed), true);
        }

        $this->write($this->map($packages));
    }

    /**
     * @param array $packages
     *
     * @return array
     */
    protected function map(array $packages): array
    {
        $manifest = [];

        $packages = array_filter($packages, function ($package) {
            if(isset($package['type'])){
                return $package['type'] === self::PACKAGE_TYPE;
            }
        });

        foreach ($packages as $package) {
            $manifest[$package['name']] = [self::EXTRA_OBSERVER => $package['extra'][self::EXTRA_OBSERVER] ?? []];
        }

        return $manifest;
    }

    /**
     * Write the manifest array to a file.
     *
     * @param array $manifest
     */
    protected function write(array $manifest)
    {
        file_put_contents(
            $this->manifestPath, '<?php return '.var_export($manifest, true).';'
        );
        $this->opcacheInvalidate($this->manifestPath);
    }

    /**
     * Disable opcache.
     *
     * @param string $file
     */
    protected function opcacheInvalidate($file)
    {
        // invalidate opcache of extensions.php if exists
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
        }
    }
}
