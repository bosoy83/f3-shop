<?php
namespace Shop\Site;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Routes extends \Dsc\Routes\Group
{

    /**
     * Initializes all routes for this group
     * NOTE: This method should be overriden by every group
     */
    public function initialize()
    {
        $this->setDefaults( array(
            'namespace' => '\Shop\Site\Controllers',
            'url_prefix' => '/shop' 
        ) );
        
        $this->add( '', 'GET', array(
            'controller' => 'Home',
            'action' => 'index' 
        ) );
        
        $this->add( '/page/@page', 'GET', array(
            'controller' => 'Home',
            'action' => 'index' 
        ) );
        
        $this->add( '/product/@slug', 'GET', array(
            'controller' => 'Product',
            'action' => 'read' 
        ) );
        
        $this->add( '/category/@slug', 'GET', array(
            'controller' => 'Category',
            'action' => 'index' 
        ) );
        
        $this->add( '/category/@slug/page/@page', 'GET', array(
            'controller' => 'Category',
            'action' => 'index' 
        ) );
        
        $this->add( '/collection/@slug', 'GET', array(
            'controller' => 'Collection',
            'action' => 'index' 
        ) );
        
        $this->add( '/collection/@slug/page/@page', 'GET', array(
            'controller' => 'Collection',
            'action' => 'index' 
        ) );
        
        $this->add( '/cart', 'GET', array(
            'controller' => 'Cart',
            'action' => 'read' 
        ) );
        
        $this->add( '/cart/add', 'POST', array(
            'controller' => 'Cart',
            'action' => 'add' 
        ) );
        
        $this->add( '/cart/remove/@cartitem_hash', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'remove' 
        ) );
        
        $this->add( '/cart/updateQuantities', 'POST', array(
            'controller' => 'Cart',
            'action' => 'updateQuantities' 
        ) );
        
        $this->add( '/checkout', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'index' 
        ) );
        
        $this->add( '/checkout/billing', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'billing' 
        ) );
        
        $this->add( '/checkout/shipping-methods [ajax]', 'GET', array(
            'controller' => 'Checkout',
            'action' => 'shippingMethods' 
        ) );
        
        $this->add( '/checkout/update', 'POST', array(
            'controller' => 'Checkout',
            'action' => 'update' 
        ) );
        
        $this->add( '/address/countries [ajax]', 'GET', array(
            'controller' => 'Address',
            'action' => 'countries' 
        ) );
        
        $this->add( '/address/regions/@country_isocode_2 [ajax]', 'GET', array(
            'controller' => 'Address',
            'action' => 'regions' 
        ) );
        
        $this->add( '/address/validate [ajax]', 'GET|POST', array(
            'controller' => 'Address',
            'action' => 'validate' 
        ) );
    }
}