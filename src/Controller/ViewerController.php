<?php declare(strict_types=1);

namespace SilverStripe\AssetVersionedAdmin\Controller;

use Exception;
use SilverStripe\Forms\Form;
use SilverStripe\Assets\File;
use SilverStripe\Forms\FieldList;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Forms\HiddenField;
use SilverStripe\View\Requirements;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\VersionedAdmin\Forms\HistoryViewerField;

class ViewerController extends LeftAndMain
{
    /**
     * http://veterans.local/admin/asset-history/1
     * @var string
     */
    private static $url_segment = 'asset-history';

    /**
     * @var boolean
     */
    private static $ignore_menuitem = true;

    /**
     * @var array
     */
    private static $url_handlers = [
        '$FileID' => 'showHistory',
    ];

    /**
     * @var array
     */
    private static $allowed_actions = [
        'showHistory',
    ];

    /**
     * @return void
     */
    protected function init()
    {
        parent::init();

        // LOL Hacks
        Requirements::customCSS(
            '
                #Form_AssetsHistoryViewer fieldset {
                    height: 100%;
                }
            '
        );
    }

    /**
     * @param HTTPRequest $request
     * @return void
     */
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
        $form->addExtraClass('flexbox-area-grow');

        $fields->add(
            HistoryViewerField::create('AssetHistory')
                ->addExtraClass('history-viewer--standalone')
                ->setForm($form)
                ->setContextKey('asset-history')
        );

        $form->loadDataFrom($file);

        return [
            'Content' => $form->forTemplate()
        ];
    }
}
