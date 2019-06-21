<?php

namespace SilverStripe\AssetVersionedAdmin\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FormAction;
use SilverStripe\Versioned\Versioned;

class FileFormFactoryExtension extends Extension
{
    /**
     * @param array $actions
     * @param DataObject $record
     * @return void
     */
    public function updatePopoverActions(&$actions, $record)
    {
        if (!$record->hasExtension(Versioned::class)) {
            return;
        }

        $actions[] = FormAction::create('showHistory', 'Show history')->setIcon('clock');
    }
}
