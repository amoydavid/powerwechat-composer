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

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    protected $activated = true;

    /**
     * Apply plugin modifications to Composer.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }
    
    /**
     * Remove any hooks from Composer.
     *
     * This will be called when a plugin is deactivated before being
     * uninstalled, but also before it gets upgraded to a new version
     * so the old one can be deactivated and the new one activated.
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        //
    }

    /**
     * Prepare the plugin to be uninstalled.
     *
     * This will be called after deactivate.
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Listen events.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::PRE_PACKAGE_UNINSTALL => 'prePackageUninstall',
            ScriptEvents::POST_AUTOLOAD_DUMP => 'postAutoloadDump',
        ];
    }

    /**
     * @param \Composer\Installer\PackageEvent
     */
    public function prePackageUninstall(PackageEvent $event)
    {
        $this->activated = false;
    }

    /**
     * @param \Composer\Script\Event $event
     */
    public function postAutoloadDump(Event $event)
    {
        if (!$this->activated) {
            return;
        }
        $vendorPath = rtrim($event->getComposer()->getConfig()->get('vendor-dir'), '/');
        $manifest = new ManifestManager(
            $vendorPath, $vendorPath.'/amoydavid/powerwechat-composer/extensions.php'
        );

        $manifest->unlink()->build();
    }
}
