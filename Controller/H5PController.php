<?php

namespace OpenLMS\H5PBundle\Controller;


use OpenLMS\H5PBundle\Core\H5PIntegration;
use OpenLMS\H5PBundle\Editor\Utilities;
use OpenLMS\H5PBundle\Entity\Content;
use OpenLMS\H5PBundle\Form\Type\H5pType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/h5p/")
 */
class H5PController extends Controller
{
    /**
     * @Route("list")
     */
    public function listAction(): ResponseAlias
    {
        $contents = $this->getDoctrine()->getRepository('H5PBundle:Content')->findAll();
        return $this->render('@H5P/list.html.twig', ['contents' => $contents]);
    }

    /**
     * @Route("show/{content}")
     * @param Content $content
     * @return ResponseAlias
     */
    public function showAction(Content $content)
    {
        $h5pIntegration = $this->get('openlms_h5p.integration')->getGenericH5PIntegrationSettings();
        $contentIdStr = 'cid-' . $content->getId();
        $h5pIntegration['contents'][$contentIdStr] = $this->get('openlms_h5p.integration')->getH5PContentIntegrationSettings($content);

        $preloaded_dependencies = $this->get('openlms_h5p.core')->loadContentDependencies($content->getId(), 'preloaded');

        $files = $this->get('openlms_h5p.core')->getDependenciesFiles($preloaded_dependencies, $this->get('openlms_h5p.options')->getRelativeH5PPath());

        if ($content->getLibrary()->isFrame()) {
            $jsFilePaths = array_map(function ($asset) {
                return $asset->path;
            }, $files['scripts']);
            $cssFilePaths = array_map(function ($asset) {
                return $asset->path;
            }, $files['styles']);
            $coreAssets = $this->get('openlms_h5p.integration')->getCoreAssets();

            $h5pIntegration['core']['scripts'] = $coreAssets['scripts'];
            $h5pIntegration['core']['styles'] = $coreAssets['styles'];
            $h5pIntegration['contents'][$contentIdStr]['scripts'] = $jsFilePaths;
            $h5pIntegration['contents'][$contentIdStr]['styles'] = $cssFilePaths;
        }

        return $this->render('@H5P/show.html.twig', ['contentId' => $content->getId(), 'isFrame' => $content->getLibrary()->isFrame(), 'h5pIntegration' => $h5pIntegration, 'files' => $files]);
    }

    /**
     * @Route("new")
     * @param Request $request
     * @param H5PIntegration $H5PIntegration
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|ResponseAlias
     */
    public function newAction(Request $request, H5PIntegration $H5PIntegration)
    {
        return $this->handleRequest($request, $H5PIntegration);
    }

    /**
     * @Route("edit/{content}")
     * @param Request $request
     * @param H5PIntegration $H5PIntegration
     * @param Content $content
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|ResponseAlias
     */
    public function editAction(Request $request, H5PIntegration $H5PIntegration, Content $content)
    {
        return $this->handleRequest($request, $H5PIntegration, $content);
    }

    /**
     * @param Request $request
     * @param H5PIntegration $H5PIntegration
     * @param Content|null $content
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|ResponseAlias
     */
    private function handleRequest(Request $request, H5PIntegration $H5PIntegration, Content $content = null)
    {
        $formData = null;
        if ($content) {
            $formData['parameters'] = $content->getParameters();
            $formData['library'] = (string)$content->getLibrary();
        }
        $form = $this->createForm(H5pType::class, $formData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $contentId = $this->get('openlms_h5p.library_storage')->storeLibraryData($data['library'], $data['parameters'], $content);

            return $this->redirectToRoute('openlms_h5p_h5p_show', ['content' => $contentId]);
        }
        $h5pIntegration = $H5PIntegration->getEditorIntegrationSettings($content ? $content->getId() : null);

        return $this->render('@H5P/edit.html.twig', ['form' => $form->createView(), 'h5pIntegration' => $h5pIntegration, 'h5pCoreTranslations' => $this->get('openlms_h5p.integration')->getTranslationFilePath()]);
    }

    /**
     * @Route("delete/{contentId}")
     * @param string $contentId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(string $contentId)
    {
        $this->get('openlms_h5p.storage')->deletePackage([
            'id' => $contentId,
            'slug' => 'interactive-content'
        ]);

        return $this->redirectToRoute('openlms_h5p_h5p_list');
    }
}
