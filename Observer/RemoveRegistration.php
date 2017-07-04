<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Forgeonline\Mongoose\Observer;

use Magento\Framework\Event\ObserverInterface;

class RemoveRegistration implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    protected $_responseFactory;

	/**
     * @var \Magento\Framework\UrlInterface
     */

	protected $_url;

    /**
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
    }
	
    /**
     * Checking action to remove
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
		$redirect = false;
        $request = $observer->getEvent()->getData('request');
		$controllerAction = $observer->getEvent()->getData('controller_action');

		if($request->getControllerName()=='account'){
			if($request->getActionName()=='create'){
				$redirect = true;
			}
		}
		
		if($request->getControllerName()=='address'){
			if(
				$request->getActionName()=='create' ||
				$request->getActionName()=='edit'
			  ){
				$redirect = true;
			}
		}
		
		switch($request->getModuleName()){
			case 'downloadable':
					$redirect = true;
				break;
			case 'sales':
					$redirect = true;
				break;
			case 'checkout':
					$redirect = true;
				break;
			default:
				break;
		}

		
		if($redirect){
			$customerBeforeAuthUrl = $this->_url->getUrl('customer/account/login');
			$this->_responseFactory->create()->setRedirect($customerBeforeAuthUrl)->sendResponse();
		}
    }
	
	
}
