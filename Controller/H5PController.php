<?php

namespace OpenLMS\H5PBundle\Controller;


use OpenLMS\H5PBundle\Editor\Utilities;
use OpenLMS\H5PBundle\Entity\Content;
use OpenLMS\H5PBundle\Form\Type\H5pType;
use Symfony\Component\HttpFoundation\Request;
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
    public function listAction()
    {
        $contents = $this->getDoctrine()->getRepository('H5PBundle:Content')->findAll();
        return $this->render('@EmmedyH5P/list.html.twig', ['contents' => $contents]);
    }

    /**
     * @Route("show/{content}")
     * @param Content $content
     * @return \Symfony\Component\HttpFoundation\Response
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

        return $this->render('@EmmedyH5P/show.html.twig', ['contentId' => $content->getId(), 'isFrame' => $content->getLibrary()->isFrame(), 'h5pIntegration' => $h5pIntegration, 'files' => $files]);
    }

    /**
     * @Route("new")
     */
    public function newAction(Request $request)
    {
        return $this->handleRequest($request);
    }

    /**
     * @Route("edit/{content}")
     * @param Request $request
     * @param Content $content
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Content $content)
    {
        return $this->handleRequest($request, $content);
    }

    private function handleRequest(Request $request, Content $content = null)
    {
        $formData = null;
        if ($content) {
            $formData['parameters'] = $content->getParameters();
            $formData['library'] = (string)$content->getLibrary();
        }
        $form = $this->createForm(H5pType::class, $formData);
        $form->handleRequest($request);
        if ($form->isValid()) {

            $data = $form->getData();
            $contentId = $this->get('openlms_h5p.library_storage')->storeLibraryData($data['library'], $data['parameters'], $content);

            return $this->redirectToRoute('openlms_h5p_h5p_show', ['content' => $contentId]);
        }
        $h5pIntegration = $this->get('openlms_h5p.integration')->getEditorIntegrationSettings($content ? $content->getId() : null);

        return $this->render('@EmmedyH5P/edit.html.twig', ['form' => $form->createView(), 'h5pIntegration' => $h5pIntegration, 'h5pCoreTranslations' => $this->get('openlms_h5p.integration')->getTranslationFilePath()]);
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
