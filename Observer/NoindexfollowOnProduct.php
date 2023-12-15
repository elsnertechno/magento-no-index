<?php
namespace Elsnertech\Noindex\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class NoindexfollowOnProduct implements ObserverInterface
{
    const Shippingcost = 'helloworld/general2/dynamic_field'; 
    
    protected $request;
    protected $layoutFactory;
    private $urlInterface;
    protected $_storeManager; 
    protected $serialize;
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager, 
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        \Magento\Framework\View\Page\Config $layoutFactory
        ) {
            $this->request = $request;
            $this->scopeConfig = $scopeConfig;
            $this->layoutFactory = $layoutFactory;
            $this->urlInterface = $urlInterface;
            $this->_storeManager = $storeManager;
            $this->serialize = $serialize;
    }
    public function execute(Observer $observer)
    {
        $productcostconfig = $this->scopeConfig->getValue(self:: Shippingcost);
        if($productcostconfig){
        $unserializedata = $this->serialize->unserialize($productcostconfig);
        $productcostarray = array();
        foreach($unserializedata as $key => $row)
	    {
            $productcostarray[] = $row['text_1'];
    	}
        foreach ($productcostarray as $url) {
            if ($this->getCurrentUrl() == $url || strpos($this->getCurrentUrl(), "/customer/account/login/referer")){
                $this->layoutFactory->setRobots('NOINDEX,NOFOLLOW');
            }
        }
        }
    }

    public function getCurrentUrl()
    {
        return $this->urlInterface->getCurrentUrl();
    }

    public function getStoreid()
	{
    	return $this->_storeManager->getStore()->getId();
	}
}