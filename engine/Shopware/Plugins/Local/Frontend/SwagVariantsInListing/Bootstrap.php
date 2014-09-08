<?php
/**
 * Shopware 4.0
 * Copyright © 2012 shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */
 
/**
 * Plugin bootstrapping class.
 *
 * @category Shopware
 * @package Shopware\Plugin\SwagAvailabilityCheck
 * @copyright Copyright (c) 2012, shopware AG (http://www.shopware.de)
 */
class Shopware_Plugins_Frontend_SwagVariantsInListing_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    /**
     * Returns the plugin label which displayed in the plugin information and
     * in the plugin manager.
     * @return string
     */
    public function getLabel() {
        return 'Varianten Übersicht im Listing';
    }
 
    /**
     * Returns the plugin information
     * @return array
     */
    public function getInfo() {
        return array(
            'label' => $this->getLabel(),
            'version' => $this->getVersion(),
            'link' => 'http://www.shopware.de/'
        );
    }
 
    /**
     * Returns the plugin version.
     *
     * @return string
     */
    public function getVersion() {
        return '1.0.0';
    }
 
    /**
     * Plugin install function which registers all required Shopware events.
     * @return bool
     */
    public function install() {
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Listing',
            'onFrontendPostDispatch'
        );
        
        
        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Frontend_Detail',
            'onPostDispatchDetail'
        );
      
 
        $this->subscribeEvent(
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_SwagVariantsInListing',
            'onGetFrontendController'
        );
        
		$this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch',
            'onPostDispatchFrontend'
		);
 	
 		$this->subscribeEvent(
      		'Enlight_Controller_Action_PostDispatch_Frontend_Search',
      		'onPostDispatchSearch'
   		);

        return true;
    }
    
    public function onPostDispatchSearch(Enlight_Event_EventArgs $arguments)
    {
    

    	/**@var $controller Shopware_Controllers_Frontend_Index */
        $controller = $arguments->getSubject();
        $view = $controller->View();
        $request = $controller->Request();
        
        
        if ($request->getControllerName() !== 'search'
                || !$view->hasTemplate()) {
                //echo "false";
            return;
        }
        $articles = $view->getAssign('sSearchResults');
        //print_r($articles);
        //foreach($articles['sArticles'] as &$article) {
        foreach($articles['sArticles'] as $key => &$article) {
        	//foreach ($article as $art){
        	//print_r($article);
        		//if (!$article['sConfigurator']) {
                //	continue;
            	//}

            	$article['swagVariantsInListing'] = $this->getArticleConfiguration($article['articleID']);
            	
            //}
            //print_r($art);
        }
        //Add our plugin template directory
        $view->addTemplateDir($this->Path() . 'Views/');
    	print_r($articles['sArticles']);

        $view->extendsTemplate('frontend/search/extension.tpl');
        $view->assign('sArticles', $articles['sArticles']);
    }
 
    /**
     * Post dispatch event of the frontend listing controller.
     *
     * @param Enlight_Event_EventArgs $arguments
     */
    public function onFrontendPostDispatch(Enlight_Event_EventArgs $arguments)
    {
        /**@var $controller Shopware_Controllers_Frontend_Index */
        $controller = $arguments->getSubject();
        $view = $controller->View();
        $request = $controller->Request();
        if ($request->getControllerName() !== 'listing'
                || $request->getModuleName() !== 'frontend'
                || !$view->hasTemplate()) {
                //echo "false";
            return;
        }
        $articles = $view->getAssign('sArticles');
        foreach($articles as &$article) {

        //if (!$article['sConfigurator']) {
             //   continue;
           // }
            
            $article['swagVariantsInListing'] = $this->getArticleConfiguration($article['articleID']);
        }
        //Add our plugin template directory
        $view->addTemplateDir($this->Path() . 'Views/');
        $view->extendsTemplate('frontend/listing/extension.tpl');
        $view->extendsTemplate('frontend/index/extension.tpl');
        $view->assign('sArticles', $articles);
    }
    

	public function onPostDispatchDetail(Enlight_Event_EventArgs $arguments)
    {
        /**@var $controller Shopware_Controllers_Frontend_Index */
        $controller = $arguments->getSubject();
        $view = $controller->View();
        //$request = $controller->Request();
        //if ($request->getControllerName() !== 'listing'
          //      || $request->getModuleName() !== 'frontend'
            //    || !$view->hasTemplate()) {
                //echo "false";
            //return;
        //}
        
        
    	$articles = $view->getAssign('sArticle');
    	$i = 0;
    	$bilder = array();
        foreach($articles['sRelatedArticles'] as &$article) {
        //if (!$article['sConfigurator']) {
            //    continue;
           // }
            //$bilder[$i]['swagVariantsInListing'] = $this->getArticleConfiguration($article['articleID']);
            $bilder[$i]['swagVariantsInListing'] = $this->getZubehoerPic($article['articleID'], $articles['ordernumber']);
            $i++;
        }
        $morearticles = $this->getMoreArticles($articles['supplierID'], $articles['supplierName'], $articles['articleID']);
        
        //Add our plugin template directory
        $view->addTemplateDir($this->Path() . 'Views/');
        $view->extendsTemplate('frontend/detail/extension.tpl');
        $view->assign('bilder', $bilder);
        $view->assign('morearticles', $morearticles);



    } 
 
 	public function onPostDispatchFrontend(Enlight_Event_EventArgs $arguments)
	{
 
        /**@var $controller Shopware_Controllers_Frontend_Index*/
        $controller = $arguments->getSubject();
 
        /**
         * @var $request Zend_Controller_Request_Http
         */
        $request = $controller->Request();
 
        /**
         * @var $response Zend_Controller_Response_Http
         */
        $response = $controller->Response();
 
        /**
         * @var $view Enlight_View_Default
         */
        $view = $controller->View();
 
        //Check if there is a template and if an exception has occured
        if(!$request->isDispatched()||$response->isException()||!$view->hasTemplate() || $request->getModuleName() != "frontend") {
            return;
        }
        
        $articles = $view->getAssign('sArticles');

 
        //Add our plugin template directory to load our slogan extension.
        //$view->addTemplateDir($this->Path() . 'Views/');
 
        //$view->extendsTemplate('frontend/plugins/slogan_of_the_day/index.tpl');
 
        //$view->assign('slogan', $this->getActiveSlogan());
		}
 
    /**
     * Event listener function which returns the controller path of the plugin frontend controller.
     *
     * @param Enlight_Event_EventArgs $arguments
     *
     * @return string
     */
    public function onGetFrontendController(Enlight_Event_EventArgs $arguments)
    {
        $this->Application()->Template()->addTemplateDir(
            $this->Path() . 'Views/'
        );
        return $this->Path() . 'Controllers/Frontend/SwagVariantsInListing.php';
    }
 
    /**
     * Helper function to get all possible configurator options for the passed
     * article id.
     *
     * @param $articleId
     *
     * @return array
     */
    private function getArticleConfiguration($articleId) {
       //creates an empty query builder object
   		$builder = Shopware()->Models()->createQueryBuilder();
 
   		//add the select and from path for the query
   		$builder->select(array('articles', 'images'))
         ->from('Shopware\Models\Article\Article', 'articles')
         ->leftJoin('articles.images', 'images')
         ->where('articles.id = :articleId')
         ->andWhere('articles.active = true')
         ->andWhere('images.main = 1');

        $builder->setParameters(array('articleId' => $articleId));
 
   		//get generated query object from the builder object
   		//$query = $builder->getQuery();
 
   		//set hydration mode to get the result as array data
   		//$query->setHydrationMode(
      	//	\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY
   		//);
 
   		//get paginator extension to get the query result
   		//$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
 
   		//get an array copy of the paginator result.
   		//$articles = $paginator->getIterator()->getArrayCopy();
 
   		//return $articles;
        //return the query result as array
        $mydata = $builder->getQuery()->getArrayResult();

        $return = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$mydata[0]['images'][0]['path']."_231x300.".$mydata[0]['images'][0]['extension'];
        $builder2 = Shopware()->Models()->createQueryBuilder();
 
   		//add the select and from path for the query
   		$builder2->select(array('articles', 'images'))
         ->from('Shopware\Models\Article\Article', 'articles')
         ->leftJoin('articles.images', 'images')
         ->where('articles.id = :articleId')
         ->andWhere('images.main != 1');

        $builder2->setParameters(array('articleId' => $articleId));
        
        $mydata2 = $builder2->getQuery()->getArrayResult();
        //$thisnumber = count($mydata2[0]['images']) - 1;

        $return2 = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$mydata2[0]['images'][0]['path']."_231x300.".$mydata2[0]['images'][0]['extension'];
        if (empty($mydata2[0]['images'][0]['path'])) $return2 = $return;
        return  array($return, $return2);
        //return $builder->getQuery()->getArrayResult();
        //return $articles;
    }
    
    
    /**
     * Helper function to get all possible configurator options for the passed
     * article id.
     *
     * @param $articleId
     *
     * @return array
     */
    private function getMoreArticles($id, $suppliername, $articleid) {
       //creates an empty query builder object
   		$builder = Shopware()->Models()->createQueryBuilder();
 
   		//add the select and from path for the query
   		$builder->select(array('articles', 'images'))
         ->from('Shopware\Models\Article\Article', 'articles')
         //->leftJoin('articles.details', 'details')
         ->leftJoin('articles.images', 'images')
         ->where('articles.supplierId = :id')
         ->andWhere('images.main = 1')
         ->andWhere('articles.active = true')
         ->orderBy('articles.id', DESC)
         ->setMaxResults(10);
        $builder->setParameters(array('id' => $id));
 
   		//get generated query object from the builder object
   		//$query = $builder->getQuery();
 
   		//set hydration mode to get the result as array data
   		//$query->setHydrationMode(
      	//	\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY
   		//);
 
   		//get paginator extension to get the query result
   		//$paginator = new \Doctrine\ORM\Tools\Pagination\Paginator($query);
 
   		//get an array copy of the paginator result.
   		//$articles = $paginator->getIterator()->getArrayCopy();
 
   		//return $articles;
        //return the query result as array
        $mydata = $builder->getQuery()->getArrayResult();

        //SELECT path FROM `s_core_rewrite_urls` WHERE main=1 AND subshopID=1 AND org_path='sViewport=detail&sArticle=5';
        $return = array();
        
        $counter = 0;
        $i = 0;
        foreach ($mydata as $data){
        	if (empty($data)) break; 
        	if ($data['id'] != $articleid && $counter < 4){
        		$return[$i]['images'] = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$data['images'][0]['path']."_140x140.".$data['images'][0]['extension'];
        		$return[$i]['title'] = $suppliername." ".$data['name'];
        		$return[$i]['link'] = "http://".Shopware()->Config()->BasePath."/?sViewport=detail&sArticle=".$data['id']; 
        	
        		$builder2 = Shopware()->Models()->createQueryBuilder();
 
   				//add the select and from path for the query
   				$builder2->select(array('articles', 'images'))
         		->from('Shopware\Models\Article\Article', 'articles')
         		->leftJoin('articles.images', 'images')
         		->where('articles.id = :id')
         		->andWhere('images.main != 1');
        		$builder2->setParameters(array('id' => $data['id']));
        
        		$mydata2 = $builder2->getQuery()->getArrayResult();
        		
        		if (empty($mydata2[0]['images'][0]['path'])) $return[$i]['images1'] = $return[$i]['images'];
        		
        		else $return[$i]['images1'] = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$mydata2[0]['images'][0]['path']."_140x140.".$mydata2[0]['images'][0]['extension'];
        		$i++;
        		$counter++;
        	} elseif ($counter >= 4){

				break;
			}
        	
        

        	
        }
		
			
        return  $return;
        //return $builder->getQuery()->getArrayResult();
        //return $articles;
    }
    
    /**
     * Helper function to get all possible configurator options for the passed
     * article id.
     *
     * @param $articleId
     *
     * @return array
     */
    private function getZubehoerPic($articleId, $ordernumber) {
       //creates an empty query builder object
   		$builder = Shopware()->Models()->createQueryBuilder();
 
   		//add the select and from path for the query
   		$builder->select(array('articles', 'images'))
         ->from('Shopware\Models\Article\Article', 'articles')
         ->leftJoin('articles.images', 'images')
         ->where('articles.id = :articleId')
         ->andWhere('articles.active = true')
         ->andWhere('images.description LIKE :orderNumber');

        $builder->setParameters(array('articleId' => $articleId, 'orderNumber' => "%".$ordernumber."%"));
 
        $mydata = $builder->getQuery()->getArrayResult();
        

        $return = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$mydata[0]['images'][0]['path']."_231x300.".$mydata[0]['images'][0]['extension'];
        
        $builder2 = Shopware()->Models()->createQueryBuilder();
 
   		//add the select and from path for the query
   		$builder2->select(array('articles', 'images'))
         ->from('Shopware\Models\Article\Article', 'articles')
         ->leftJoin('articles.images', 'images')
         ->where('articles.id = :articleId')
         ->andWhere('images.main = 1');

        $builder2->setParameters(array('articleId' => $articleId));
        
        $mydata2 = $builder2->getQuery()->getArrayResult();
        //$thisnumber = count($mydata2[0]['images']) - 1;

        $return2 = "http://".Shopware()->Config()->BasePath. '/media/image/thumbnail/'.$mydata2[0]['images'][0]['path']."_231x300.".$mydata2[0]['images'][0]['extension'];
        
        if (empty($mydata[0]['images'][0]['path'])) $return = $return2;

        return  array($return);
        //return $builder->getQuery()->getArrayResult();
        //return $articles;
    }
}