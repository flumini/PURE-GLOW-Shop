# Shopware Upgrade Information
In this document you will find a changelog of the important changes related to the code base of Shopware.

## 4.3.0
* Removed `location` header in responses for all REST-API PUT routes (e.g. PUT /api/customers/{id}).
* Removed deprecated Zend Framework components:
    * `Zend_Amf`
    * `Zend_Application`
    * `Zend_Barcode`
    * `Zend_Cloud`
    * `Zend_CodeGenerator`
    * `Zend_Console`
    * `Zend_Gdata`
    * `Zend_Markup`
    * `Zend_Measure`
    * `Zend_Memory`
    * `Zend_Pdf`
    * `Zend_Reflection`
    * `Zend_Search`
    * `Zend_Serializer`
    * `Zend_Tag`
    * `Zend_Test`
    * `Zend_Tool`
    * `Zend_EventManager`
    * `Zend_Feed`
    * `Zend_Dojo`
    * `Zend_Mobile`
    * `Zend_Queue`
    * `Zend_Captcha`
    * `Zend_Service`
* Removed the following core classes deprecated and/or unused methods
    * `sArticles::sGetArticleAccessories`
    * `sArticles::sCreateTranslationTable`
    * `sArticles::sGetLiveShopping`
    * `sArticles::sGetArticleBundlesByArticleID`
    * `sArticles::sGetArticleBundleByID`
    * `sArticles::sGetBundleBasketDiscount`
    * `sSystem::sPreProcess`
    * `sSystem::sInitMailer`
    * `sSystem::sGetTranslation`
    * `sSystem::sInitAdo`
    * `sSystem::sTranslateConfig`
    * `sSystem::sInitConfig`
    * `sSystem::sInitSmarty`
    * `sSystem::sInitSession`
    * `sSystem::sCallHookPoint`
    * `sSystem::sLoadHookPoints`
    * `sSystem::sInitFactory`
    * `sSystem::sCheckLicense`
    * `sSystem::E_CORE_ERROR`
    * `sCms::sGetDynamicContentByGroup`
    * `sCms::sGetDynamicContentById`
    * `sCms::sGetDynamicGroupName`
    * `sAdmin::sGetDispatch`
    * `sAdmin::sGetDispatches`
    * `sAdmin::sGetShippingcosts`
    * `sAdmin::sCheckTaxID`
    * `sCore::sCustomRenderer`
    * `sBasket::sCountArticles`
    * `sBasket::sGetBasketWeight`
* Removed the following core classes deprecated and/or unused variables
    * `sSystem::sDB_HOST`
    * `sSystem::sDB_USER`
    * `sSystem::sDB_PASSWORD`
    * `sSystem::sDB_DATABASE`
    * `sSystem::sDB_CONNECTOR`
    * `sSystem::sDEBUG`
    * `sSystem::sBENCHRESULTS`
    * `sSystem::sBENCHMARK`
    * `sSystem::sPathMedia`
    * `sSystem::sBasePath`
    * `sSystem::sBasefile`
    * `sSystem::sLicenseData`
    * `sSystem::sCurrencyData`
    * `sSystem::sPathCmsFiles`
    * `sSystem::sPathCmsImg`
    * `sBasket::sBASKET`
* `sCore::sBuildLink()` second argument removed (dead code)
* `sCore` no longer returns `null` when calling not implemented functions
* `sNewsletter` core class removed
* `Shopware_Controllers_Frontend_Content` legacy controller removed
* `templates/_default/frontend/content` legacy template files removed
* `s_cms_content` legacy database table removed
* Removed functions `simpledom_load_file()` and `simpledom_load_string()`
* Removed class `SimpleDOM` and `Shopware_Components_Xml_SimpleXml`
* Add new product feed modifier `articleImages` and `property`
* Create a new product export cronjob to export all active product feeds
* Implement new article association for new seo categories. The seo categories can be assigned over the array key seoCategories in the article api resource.
* Access to GET, POST and COOKIES through sSystem is deprecated.
    * The current arrays have been replaced with wrappers objects to the global variables
    * This might introduce breaks in some scenarios (eg.: when using array functions like array_merge)
* Plugin configuration: Stores of `select` and `combo` elements can now be translated
* Dynamically injecting variables into sSystem is no longer supported
* Removed `Shopware\Models\Widget\View::label` variable, getter and setter, and correspondent `s_core_widget_views::label` database column
* Deprecated `Shopware\Models\Widget\Widget::label` variable, getter and setter, and correspondent `s_core_widgets::label` database column
* Removed deprecated widget settings from the config module. Active widgets and their positions will now be saved automatically.
* Removed desktop switcher from the `Shopware.container.Viewport` base component.

## 4.2.2

* Remove old payment dummy plugins out of the core: PaymentSofort and PigmbhRatePAYPayment
* The tell a friend feature is now disabled by default, due to legal requirements. This will affect new and existing installations. You can enable/re-enable it using a new configuration option in the backend settings menu.
* [REST API] Add thumbnail generation to article and variant create and update actions
* Deprecation: The Database Column impressions in s_articles_details in now deprecated. Please use the s_statistics_article_impression table.
* `Shopware_Components_Plugin_Bootstrap` now has a `addFormTranslations()` method to facilitate translations creation for forms.
* Removed view variables `sOrders` and `sNotes` from `/engine/Shopware/Controllers/Frontend/Account.php` index action
* The methods `sGetOpenOrderData` and `sGetDownloads` in `/engine/core/class/sAdmin.php` will now return a different array structure and will accept new optional parameters to provide a pager functionality
* Added X-Sendfile support for ESD downloads. `redirectDownload` configuration variable is now deprecated, `esdDownloadStrategy` should be used instead
* Deprecation: `/engine/Shopware/Models/Payment/Repository.php:` `getPaymentsQuery` and `getPaymentsQueryBuilder` use `getActivePaymentsQuery` and `getActivePaymentsQueryBuilder` instead.

## 4.2.0

* Add new metaTitle field to the Blog
* Add new metaTitle field to the Article
* Removed unused class `Services_JSON`, was located at `/engine/core/ajax/json.php`.
* The subquery in `$priceForBasePrice` used in `sArticles::sGetArticlesByCategory` has been removed.
* A userland implementaion of [`array_column()`](http://php.net/array_column) has been included.
* Deprecated class `sTicketSystem` has been removed.
* Doctrine has been updated to version 2.4. See: https://github.com/doctrine/doctrine2/blob/2.4/UPGRADE.md
* Break: `Doctrine\ORM\Query::setParamters()` has changed. See: https://github.com/doctrine/doctrine2/blob/2.4/UPGRADE.md
* `Shopware\Components\Model\QueryBuilder::setParameters()` provides old behavior.
* Break: `Shopware_Plugins_Frontend_RouterOld_Bootstrap::onAssemble` event and implementation removed
* Update Zend Framework to version 1.12.3 (latest stable)
* Deprecation: Several unused Zend Framework components and classes are now deprecated. Refer to the full upgrade guide for details
* Break: Custom article attributes of type `Time` are now always saved using the german format. Only affects values inserted in non-german backends
* Removed the sSetLastArticle in sArticles.php. Was deprecated through setLastArticleById in the Shopware_Plugins_Frontend_LastArticles_Bootstrap plugin.
* Implement new options in the article resource. "considerTaxInput" allows to get the variant prices considering the article tax. "language" allows to get a whole translated article array. The "language" parameter can contain a sub shop id or a language iso like en_GB.
* `s_core_debit` table is now deprecated. `s_core_payment_data` and `s_core_payment_instance` should be used instead.
* core payment classes were removed. Existing references in the core to those classes now use the core PaymentMethods module implementation. Refer to the module for details on how to implement payment method logic
* Break: PaymentMethods core plugin components and templates had their performance improved, resulting in potential breaks for extensions
* - getCurrentPaymentData() was removed and should be replaced with getCurrentPaymentDataAsArray(), which returns the same information but in an array format
* Break: some payment snippets had their namespaces changed to comply with recent changes in snippet handling
* Break: customer detail editing in the backend: field names and field container structured to add support for additional payment methods. As such, debit.js view and detail controller have some breaks
* Ext.editorLang variable is no longer used and is being deprecated.
* Deprecation (REST API): 'debit' info in /api/customers/{id} is deprecated. Use 'paymentData' instead
* Break: Removed the Shopware.apps.Analytics.view.table.Conversion, Shopware.apps.Analytics.view.toolbar.Source and Shopware.apps.Analytics.view.toolbar.Shop file which now defined in the analytics/view/main/toolbar.js file.
* Removed unused class `Shopware_Components_Subscriber`, was located at `/engine/Shopware/Components/Subscriber.php`.
* Deprecation: Enlight's assertArrayCount() and assertArrayNotCount() are deprecated. Use phpunit's assertCount() instead

## 4.1.4

* New method `\Shopware\Components\Model\ModelManager::createPaginator($query)`.
 * This method should be used instead of `new \Doctrine\ORM\Tools\Pagination\Paginator($query)`.
 * As of SW 4.2 `$paginator->setUseOutputWalkers(false)` will be set here.
* New methods for calculating the basepricedata have been integrated in `/engine/core/class/sArticles.php`
 * `calculateCheapestBasePriceData` | This methods returns always the basepricedata of the cheapest variant. This is used in the listing views.
 * `getCheapestVariant` | This method is used by the method `calculateCheapestBasePriceData` to get the purchaseunit and the referenceunit of the cheapest variant.
 * `calculateReferencePrice` | This method does the basic calculation to get the right referenceprice.
* New PaymentMethods core plugin including refactored Debit and new SEPA payment methods.
* New `Shopware\Models\Customer\PaymentData` model to store customer's payment information.
* New `Shopware\Models\Payment\PaymentInstance` model to store payments information for individual orders.

### Deprecations
* The subquery in `$priceForBasePrice` used in the method `sGetArticlesByCategory` of the class `/engine/core/class/sArticles.php` is marked deprecated, because the query variable `priceForBasePrice` is no longer in use. Please do not use it anymore.

## 4.1.3

* Add configuration `Always display item short descriptions in listing views`.
* `Shopware_Components_Plugin_Bootstrap::assertVersionGreaterThen()` is now an alias to  `Shopware_Components_Plugin_Bootstrap::assertMinimumVersion()` and returns always `true` if run on an development/git Version of Shopware
* Added a new method `getDefault()` in `engine/Shopware/Models/Shop/Repository.php` which returns just the default shop without calling `fixActiv()`.
* Removed the unused `downloadAction()` in `engine/Shopware/Controllers/Backend/Plugin.php`

### Deprecations
* `decompressFile()` in `/engine/Shopware/Controllers/Backend/Plugin.php`
* `decompressFile()` in `/engine/Shopware/Plugins/Default/Core/PluginManager/Controllers/Backend/PluginManager.php`

You should use the decompressFile method in the CommunityStore component instead


## 4.1.1 / 4.1.2

With Shopware 4.1.1 we have fixed a bug that appeared during certain constellations in the customer registration process.
Submitting the registration formular empty, from time to time a fatal error was displayed.

For further information have a look at the following wiki article:

- GER: <http://wiki.shopware.de/_detail_1342.html>
- ENG: <http://en.wiki.shopware.de/_detail_1398.html>
