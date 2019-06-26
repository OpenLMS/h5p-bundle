<?php

namespace OpenLMS\H5PBundle\Controller;

use OpenLMS\H5PBundle\Core\H5POptions;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/h5p/ajax")
 */
class AjaxController extends Controller
{
    /**
     * Callback that lists all h5p libraries.
     *
     * @Route("/libraries/")
     * @param Request $request
     * @return void
     */
    public function librariesCallback(Request $request)
    {
        if ($request->get('machineName')) {
            return $this->libraryCallback($request);
        }
        $editor = $this->get('openlms_h5p.editor');
        $editor->ajax->action(\H5PEditorEndpoints::LIBRARIES);

    }

    /**
     * Callback that returns the content type cache
     *
     * @Route("/content-type-cache/")
     */
    public function contentTypeCacheCallback()
    {
        $editor = $this->get('openlms_h5p.editor');
        $editor->ajax->action(\H5PEditorEndpoints::CONTENT_TYPE_CACHE);
    }

    /**
     * Callback Install library from external file
     *
     * @param Request $request
     * @Route("/library-install/")
     */
    public function libraryInstallCallback(Request $request)
    {
        $editor = $this->get('openlms_h5p.editor');
        $editor->ajax->action(\H5PEditorEndpoints::LIBRARY_INSTALL, $request->get('token', 1), $request->get('id'));
    }

    /**
     * Callback that returns data for a given library
     *
     * @param Request $request
     * @param H5POptions $H5POptions
     */
    private function libraryCallback(Request $request, H5POptions $H5POptions)
    {
        $editor = $this->get('openlms_h5p.editor');
        $editor->ajax->action(\H5PEditorEndpoints::SINGLE_LIBRARY, $request->get('machineName'),
            $request->get('majorVersion'), $request->get('minorVersion'), $request->getLocale(), $H5POptions->getOption('storage_dir')
        );
    }

    /**
     * Callback for file uploads.
     *
     * @param Request $request
     * @Route("/files/")
     */
    function filesCallback(Request $request)
    {
        $editor = $this->get('openlms_h5p.editor');
        $editor->ajax->action(\H5PEditorEndpoints::FILES, $request->get('token', 1), $request->get('id'));

    }

}
