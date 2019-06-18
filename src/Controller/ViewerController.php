<?php declare(strict_types=1);

namespace SilverStripe\AssetVersionedAdmin\Controller;

use Exception;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Assets\File;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\HiddenField;
use SilverStripe\VersionedAdmin\Forms\HistoryViewerField;

class ViewerController extends LeftAndMain
{
    private static $url_segment = 'asset-history';

    private static $url_handlers = [
        '$FileID' => 'showHistory',
    ];

    private static $allowed_actions = [
        'showHistory',
    ];

    public function showHistory(HTTPRequest $request)
    {
        $id = $request->param('FileID');
        $file = File::get()->byID($id);

        if (!$file) {
            throw new Exception(sprintf('Invalid file %s', $id));
        }

        $fields = FieldList::create(
            HiddenField::create('ID', null, $id)
        );

        $form = Form::create($this, 'AssetsHistoryViewer', $fields, FieldList::create());

        $fields->add(
            HistoryViewerField::create('AssetHistory')
                ->addExtraClass('history-viewer--standalone')
                ->setForm($form)
                ->setContextKey('asset-history')
        );

        return [
            'Content' => $form->forTemplate()
        ];
    }
}
