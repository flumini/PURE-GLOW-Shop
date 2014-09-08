<?php
class Shopware_Plugins_Frontend_SwagLightbox_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Installs the plugin
     *
     * Creates and subscribe the event
     * Creates the Backend Form
     *
     * @return bool
     */
	public function install()
	{
		$this->subscribeEvent(
			'Enlight_Controller_Action_PostDispatch_Frontend_Detail',
			'onDetailPostDispatch'
		);
		
		$form = $this->Form();
		$form->setElement('text', 'fadeTo', array('label'=>'Overlay Deckkraft', 'value'=>'0.8', 'scope'=>\Shopware\Models\Config\Element::SCOPE_SHOP));
		$form->setElement('text', 'fadeSpeed', array('label'=>'Fade-Geschwindigkeit (in ms)', 'value'=>'350', 'scope'=>\Shopware\Models\Config\Element::SCOPE_SHOP));
		$form->setElement('text', 'resizeSpeed', array('label'=>'Resize-Geschwindigkeit (in ms)', 'value'=>'600', 'scope'=>\Shopware\Models\Config\Element::SCOPE_SHOP));
	 	
	 	return true;
	}

    /**
     * @return array
     */
    public function getInfo()
    {
        return array(
            'version' => $this->getVersion(),
            'label' => $this->getLabel(),
            'description' => file_get_contents($this->Path() . 'info.txt'),
            'link' => 'http://www.shopware.de/',
        );
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Lightbox';
    }

    /**
     * @param string $version
     * @return bool
     */
    public function update($version)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getVersion()
	{
		return "1.0.4";
	}

    /**
     * @param Enlight_Event_EventArgs $args
     */
	public function onDetailPostDispatch(Enlight_Event_EventArgs $args)
	{	
		$request = $args->getSubject()->Request();
		$response = $args->getSubject()->Response();
		$view = $args->getSubject()->View();
		$config = $this->Config();
		
		if(!$request->isDispatched()||$response->isException()||$request->getModuleName()!='frontend') {
			return;
		}
		
		$view->assign('SwagLightbox', array(
			'fadeTo' => $config->fadeTo,
			'fadeSpeed' => $config->fadeSpeed,
			'resizeSpeed' => $config->resizeSpeed
		));
		$view->addTemplateDir($this->Path().'Views/');
		$view->extendsTemplate('frontend/plugins/swag_lightbox/index.tpl');
	}
}