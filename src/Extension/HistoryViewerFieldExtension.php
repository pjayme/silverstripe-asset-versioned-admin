<?php

namespace SilverStripe\AssetVersionedAdmin\Extension;

use SilverStripe\Core\Extension;

class HistoryViewerFieldExtension extends Extension
{

    /**
     * @param boolean $previewEnabled
     * @param DataObject $record
     *
     * @return void
     */
    public function updatePreviewEnabled(&$previewEnabled, $record)
    {
        if ($this->owner->getContextKey() === 'asset-history') {
            $previewEnabled = false;
        }
    }
}
