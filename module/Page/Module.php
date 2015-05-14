<?php

namespace Page;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sm = $e->getApplication()->getServiceManager();

        $menu = $this->getMenuService($sm)->getMenuByMachineName('main-menu');
        $usefulLinks = $this->getMenuService($sm)->getMenuByMachineName('usefull-links');
        $latestGallery = $this->getGalleryTable($sm)->getOneBy(array(), 'id DESC');
        $latestGalleryFiles = array();
        if($latestGallery)
        {
            $latestGalleryId = $latestGallery->getId();
            $latestGalleryFiles = $this->getFileTable($sm)->getBy(array('entity_type' => 'gallery', 'entity_id' => $latestGalleryId));
        }

        $banners = $this->getBannerTable($sm)->getBy(array('status_id' => 1));
        $footerPage = $this->getPageService($sm)->findOneBySlug('stopka');

        $viewModel = $e->getViewModel();
        $viewModel->menu = $menu;
        $viewModel->usefullLinks = $usefulLinks;
        $viewModel->latestGalleryFiles = array_values($latestGalleryFiles);
        $viewModel->latestGallery = $latestGallery;
        $viewModel->banners = $banners;
        $viewModel->footerPage = $footerPage;

    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService($sm)
    {
        return$sm->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService($sm)
    {
        return$sm->get('CmsIr\Page\Service\PageService');
    }

    /**
     * @return \CmsIr\File\Model\GalleryTable
     */
    public function getGalleryTable($sm)
    {
        return$sm->get('CmsIr\File\Model\GalleryTable');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable($sm)
    {
        return$sm->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \CmsIr\Banner\Model\BannerTable
     */
    public function getBannerTable($sm)
    {
        return$sm->get('CmsIr\Banner\Model\BannerTable');
    }
}
