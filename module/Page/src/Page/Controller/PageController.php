<?php

namespace Page\Controller;

use CmsIr\File\Model\Gallery;
use CmsIr\Newsletter\Model\Subscriber;
use CmsIr\Post\Model\Post;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class PageController extends AbstractActionController
{
    public function homeAction()
    {
        $this->layout('layout/home');

        $slider = $this->getSliderService()->findOneBySlug('slider-glowny');
        $items = $slider->getItems();

        $page = 1;

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $allNews = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'news'), 'date_from DESC');
        $allNews->setCurrentPageNumber($page);
        $allNews->setItemCountPerPage(3);

        $allEvents = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'events'), 'date_from DESC');
        $allEvents->setCurrentPageNumber($page);
        $allEvents->setItemCountPerPage(3);

        $viewParams = array();
        $viewParams['slider'] = $items;
        $viewParams['news'] = $allNews;
        $viewParams['events'] = $allEvents;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function eventsAction()
    {
        $this->layout('layout/home');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();
        $page = $this->params()->fromRoute('number') ? (int) $this->params()->fromRoute('number') : 1;

        $allEvents = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'events'), 'date_from DESC');
        $allEvents->setCurrentPageNumber($page);
        $allEvents->setItemCountPerPage(5);

        $viewParams = array();
        $viewParams['paginator'] = $allEvents;
        $viewParams['posts'] = $allEvents;

        //sidebar vars
        $usefulLinks = $this->getMenuService()->getMenuByMachineName('usefull-links');
        $allNews = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'news'), 'date_from DESC');
        $allNews->setCurrentPageNumber(1);
        $allNews->setItemCountPerPage(3);

        $viewParams['usefulLinks'] = $usefulLinks;
        $viewParams['recentNews'] = $allNews;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function newsAction()
    {
        $this->layout('layout/home');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();
        $page = $this->params()->fromRoute('number') ? (int) $this->params()->fromRoute('number') : 1;

        $allNews = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'news'), 'date_from DESC');
        $allNews->setCurrentPageNumber($page);
        $allNews->setItemCountPerPage(5);

        $viewParams = array();
        $viewParams['paginator'] = $allNews;
        $viewParams['posts'] = $allNews;

        //sidebar vars
        $usefulLinks = $this->getMenuService()->getMenuByMachineName('usefull-links');
        $allEvents = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'events'), 'date_from DESC');
        $allEvents->setCurrentPageNumber(1);
        $allEvents->setItemCountPerPage(3);

        $viewParams['usefulLinks'] = $usefulLinks;
        $viewParams['upcomingEvents'] = $allEvents;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function galleryAction()
    {
        $this->layout('layout/home');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();
        $page = $this->params()->fromRoute('number') ? (int) $this->params()->fromRoute('number') : 1;

        $allGalleries = $this->getGalleryTable()->getWithPaginationBy(new Gallery(), array('status_id' => $activeStatusId));
        $allGalleries->setCurrentPageNumber($page);
        $allGalleries->setItemCountPerPage(5);

        $galleries = array();
        foreach($allGalleries as $gallery)
        {
            $galleryId = $gallery->getId();
            $files = $this->getFileTable()->getBy(array('entity_type' => 'gallery', 'entity_id' => $galleryId));
            $gallery->setFiles($files);

            $galleries[] = $gallery;
        }

        $viewParams = array();
        $viewParams['paginator'] = $allGalleries;
        $viewParams['galleries'] = $galleries;

        //sidebar vars
        $usefulLinks = $this->getMenuService()->getMenuByMachineName('usefull-links');
        $allNews = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'news'), 'date_from DESC');
        $allNews->setCurrentPageNumber(1);
        $allNews->setItemCountPerPage(3);

        $viewParams['usefulLinks'] = $usefulLinks;
        $viewParams['recentNews'] = $allNews;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function oneNewsAction()
    {
        $this->layout('layout/home');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $slug = $this->params('slug');
        $oneNews = $this->getPostTable()->getOneBy(array('status_id' => $activeStatusId, 'url' => $slug));
        $oneNewsId = $oneNews->getId();

        $newsFiles = $this->getFileTable()->getBy(array('entity_type' => 'Post', 'entity_id' => $oneNewsId));

        $viewParams = array();
        $viewParams['onePost'] = $oneNews;
        $viewParams['postFiles'] = $newsFiles;

        //sidebar vars
        $usefulLinks = $this->getMenuService()->getMenuByMachineName('usefull-links');
        $allEvents = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'events'), 'date_from DESC');
        $allEvents->setCurrentPageNumber(1);
        $allEvents->setItemCountPerPage(3);

        $viewParams['usefulLinks'] = $usefulLinks;
        $viewParams['upcomingEvents'] = $allEvents;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function oneEventAction()
    {
        $this->layout('layout/home');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();

        $slug = $this->params('slug');
        $oneEvent = $this->getPostTable()->getOneBy(array('status_id' => $activeStatusId, 'url' => $slug));
        $oneEventId = $oneEvent->getId();

        $eventFiles = $this->getFileTable()->getBy(array('entity_type' => 'Post', 'entity_id' => $oneEventId));

        $viewParams = array();
        $viewParams['onePost'] = $oneEvent;
        $viewParams['postFiles'] = $eventFiles;

        //sidebar vars
        $usefulLinks = $this->getMenuService()->getMenuByMachineName('usefull-links');
        $allNews = $this->getPostTable()->getWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'category' => 'news'), 'date_from DESC');
        $allNews->setCurrentPageNumber(1);
        $allNews->setItemCountPerPage(3);

        $viewParams['usefulLinks'] = $usefulLinks;
        $viewParams['recentNews'] = $allNews;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function oneGalleryAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('slug');
        $gallery = $this->getGalleryTable()->getOneBy(array('slug' => $slug));
        $galleryId = $gallery->getId();
        $galleryFiles = $this->getFileTable()->getBy(array('entity_type' => 'gallery', 'entity_id' => $galleryId));

        $gallery->setFiles($galleryFiles);

        $viewParams = array();
        $viewParams['gallery'] = $gallery;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;

    }

    public function viewPageAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('slug');
        $page = $this->getPageService()->findOneBySlug($slug);
        $pageId = $page->getId();
        $pageFiles = $this->getFileTable()->getBy(array('entity_type' => 'page', 'entity_id' => $pageId));

        if(empty($page)){
            $this->getResponse()->setStatusCode(404);
        }

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewParams['pageFiles'] = $pageFiles;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;

    }

    public function contactPageAction()
    {
        $this->layout('layout/home');

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $contactFormData = $request->getPost();
            $transport = $this->getServiceLocator()->get('mail.transport');
            $message = new Message();
            $content = '
                    <b>Imię i Nazwisko: </b>'.$contactFormData['data'][0]['value'].' <br>
                    <b>Email: </b>'.$contactFormData['data'][1]['value'].' <br>
                    <b>Telefon: </b>'.$contactFormData['data'][2]['value'].' <br>
                    <b>Temat: </b>'.$contactFormData['data'][3]['value'].' <br>
                    <b>Treść: </b>'.$contactFormData['data'][4]['value'].' <br>
                ';
            $html = new MimePart($content);
            $html->type = "text/html";

            $body = new MimeMessage();
            $body->setParts(array($html));

            $this->getRequest()->getServer();
            $message->addTo('biuro@web-ir.pl')
                ->addFrom('mailer@web-ir.pl')
                ->setEncoding('UTF-8')
                ->setSubject('Wiadomosc z formularza kontaktowego')
                ->setBody($body);
            $transport->send($message);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        $page = $this->getPageService()->findOneBySlug('kontakt');

        if(empty($page)){
            $this->getResponse()->setStatusCode(404);
        }

        $viewParams = array();
        $viewParams['page'] = $page;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;

    }

    public function saveSubscriberAjaxAction ()
    {
        $request = $this->getRequest();

        if ($request->isPost())
        {
            $uncofimerdStatus = $this->getStatusTable()->getOneBy(array('slug' => 'unconfirmed'));
            $uncofimerdStatusId = $uncofimerdStatus->getId();

            $email = $request->getPost('email');
            $confirmationCode = uniqid();
            $subscriber = new Subscriber();
            $subscriber->setEmail($email);
            $subscriber->setGroups(array());
            $subscriber->setConfirmationCode($confirmationCode);
            $subscriber->setStatusId($uncofimerdStatusId);

            $this->getSubscriberTable()->save($subscriber);
            $this->sendConfirmationEmail($email, $confirmationCode);

            $jsonObject = Json::encode($params['status'] = 'success', true);
            echo $jsonObject;
            return $this->response;
        }

        return array();
    }

    public function sendConfirmationEmail($email, $confirmationCode)
    {
        $transport = $this->getServiceLocator()->get('mail.transport');
        $message = new Message();
        $this->getRequest()->getServer();
        $message->addTo($email)
            ->addFrom('mailer@web-ir.pl')
            ->setSubject('Prosimy o potwierdzenie subskrypcji!')
            ->setBody("W celu potwierdzenia subskrypcji kliknij w link => " .
                $this->getRequest()->getServer('HTTP_ORIGIN') .
                $this->url()->fromRoute('newsletter-confirmation', array('code' => $confirmationCode)));
        $transport->send($message);
    }

    public function confirmationNewsletterAction()
    {
        $this->layout('layout/home');
        $request = $this->getRequest();
        $code = $this->params()->fromRoute('code');
        if (!$code) {
            return $this->redirect()->toRoute('home');
        }

        $viewParams = array();
        $viewModel = new ViewModel();

        $subscriber = $this->getSubscriberTable()->getOneBy(array('confirmation_code' => $code));

        $confirmedStatus = $this->getStatusTable()->getOneBy(array('slug' => 'confirmed'));
        $confirmedStatusId = $confirmedStatus->getId();

        if($subscriber == false)
        {
            $viewParams['message'] = 'Nie istnieje taki użytkownik';
            $viewModel->setVariables($viewParams);
            return $viewModel;
        }

        $subscriberStatus = $subscriber->getStatusId();

        if($subscriberStatus == $confirmedStatusId)
        {
            $viewParams['message'] = 'Użytkownik już potwierdził subskrypcję';
        }

        else
        {
            $viewParams['message'] = 'Subskrypcja została potwierdzona';
            $subscriberGroups = $subscriber->getGroups();
            $groups = unserialize($subscriberGroups);

            $subscriber->setStatusId($confirmedStatusId);
            $subscriber->setGroups($groups);
            $this->getSubscriberTable()->save($subscriber);
        }

        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function searchAction()
    {
        $this->layout('layout/home');

        $slug = $this->params('search');

        $activeStatus = $this->getStatusTable()->getOneBy(array('slug' => 'active'));
        $activeStatusId = $activeStatus->getId();
        $allPost = $this->getPostTable()->getSearchWithPaginationBy(new Post(), array('status_id' => $activeStatusId, 'slug' => $slug), 'date_from DESC');
        $page = $this->params()->fromRoute('number') ? (int) $this->params()->fromRoute('number') : 1;
        $allPost->setCurrentPageNumber($page);
        $allPost->setItemCountPerPage(5);

        $test = array();
        foreach($allPost as $post)
        {
            $test[] = $post;
        }

        $usefulLinks = $this->getMenuService()->getMenuByMachineName('usefull-links');

        $viewParams = array();
        $viewParams['paginator'] = $allPost;
        $viewParams['posts'] = $test;
        $viewParams['slug'] = $slug;

        //sidebar vars
        $viewParams['usefulLinks'] = $usefulLinks;

        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;

    }

    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService()
    {
        return $this->getServiceLocator()->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Slider\Service\SliderService
     */
    public function getSliderService()
    {
        return $this->getServiceLocator()->get('CmsIr\Slider\Service\SliderService');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService()
    {
        return $this->getServiceLocator()->get('CmsIr\Page\Service\PageService');
    }

    /**
     * @return \CmsIr\Post\Model\PostTable
     */
    public function getPostTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Post\Model\PostTable');
    }

    /**
     * @return \CmsIr\Newsletter\Model\SubscriberTable
     */
    public function getSubscriberTable()
    {
        return $this->getServiceLocator()->get('CmsIr\Newsletter\Model\SubscriberTable');
    }

    /**
     * @return \CmsIr\System\Model\StatusTable
     */
    public function getStatusTable()
    {
        return $this->getServiceLocator()->get('CmsIr\System\Model\StatusTable');
    }

    /**
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }

    /**
     * @return \CmsIr\File\Model\GalleryTable
     */
    public function getGalleryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\GalleryTable');
    }
}
