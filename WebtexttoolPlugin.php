<?php

namespace Craft;

/**
 *
 * @author    Webtexttool <support@webtexttool.com>
 * @copyright Copyright (c) 2016, Webtexttool
 * @see       http://webtexttool.com
 * @package   webtexttool
 * @since     1.0
 */


class WebtexttoolPlugin extends BasePlugin
{
    public function getName()
    {
        return 'Webtexttool';
    }

    public function getDescription()
    {
        return 'Webtexttool is the easiest way to create SEO proof content to rank higher and get more traffic. Realtime optimization, keyword research and more.';
    }

    function getVersion()
    {
        return '1.0.0';
    }

    function getDeveloper()
    {
        return 'Webtexttool';
    }

    function getDeveloperUrl()
    {
        return 'http://webtexttool.com';
    }

    public function hasCpSection()
    {
        return true;
    }

    public function init()
    {
        craft()->templates->hook('cp.entries.edit.right-pane', [$this, 'renderCoreTemplate']);
        craft()->on('entries.onSaveEntry', [$this, 'handleEntrySave']);
    }

    public function renderCoreTemplate()
    {
        return craft()->templates->render('webtexttool/core');
    }

    /**
     * Applies the rules in case EntriesService::saveEntry() was used.
     *
     * @param Event $event
     */
    public function handleEntrySave(Event $event)
    {
        $entry = $event->params['entry'];

        $entryId = craft()->request->getPost('entryId');

        craft()->webtexttool->saveRecord($entryId);
    }

    public function getSettingsUrl()
    {
        return "webtexttool";
    }
}