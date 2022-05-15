<?php

namespace Magelearn\CustomerDelete\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;

/**
 * Class Remove
 * @package Magelearn\CustomerDelete\Controller\Index
 */
class Remove extends Action
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * @var SessionFactory
     */
    protected $_sessionFactory;

    /**
     * @var ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;

    protected $_registry;

    /**
     * Remove constructor.
     * @param Context $context
     * @param SessionFactory $sessionFactory
     * @param PageFactory $pageFactory
     * @param ManagerInterface $messageManager
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        Context $context ,
        SessionFactory $sessionFactory ,
        PageFactory $pageFactory ,
        ManagerInterface $messageManager,
        CustomerRepositoryInterface $customerRepository,
        Registry $registry
    )
    {
        $this->_sessionFactory = $sessionFactory;
        $this->_pageFactory = $pageFactory;
        $this->_messageManager = $messageManager;
        $this->_customerRepository = $customerRepository;
        $this->_registry =$registry;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     * @throws \Exception
     */
    public function execute()
    {
        $customer = $this->_sessionFactory->create();

        if ($customer->isLoggedIn()) {
            $this->_registry->register('isSecureArea', true);
            $customerId = $customer->getId();
            if (!empty($customerId)) {
                try {
                    $this->_customerRepository->deleteById($customerId);
                    $this->_messageManager->addSuccessMessage(__('Customer has been deleted.'));
                } catch (\Exception $exception) {
                    $this->_messageManager->addErrorMessage($exception->getMessage());
                }
            }
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account/login');
        return $resultRedirect;

    }
}