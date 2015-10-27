<?php
/**
 * Description of Mage_Catalog_Model_Api2_Review_Rest_Admin_V1
 *
 * @author Bilna Development Team <development@bilna.com>
 * 
 * @link http://www.authenticdesign.co.uk/extending-magento-rest-api-v2/
 * 
 * $consumerKey = 'c195916aadaf1f1cc1d7704ca8639d45';
$consumerSecret = '721477ba87aadbdec0f2221b76041f44';

//oauth token: b060eb809af1086caab5aaeab119a33a
//oauth token secreet: 89ddc47e8c895b9cbd8077011819e249
 * 
 */

class Bilna_Rest_Model_Api2_Review_Rest_Admin_V1 extends Bilna_Rest_Model_Api2_Review_Rest {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Retrieve review data
     *
     * @return array
     */
    protected function _retrieve() {
        echo json_encode(array('message' => 'retrieve ok'));
        die;
    }
    
    protected function _retrieveResponse() {
        echo json_encode(array('message' => 'retrieve ok'));
        die;
    }

    /**
     * Retrieve list of reviews
     *
     * @return array
     */
    protected function _retrieveCollection() {
    }
    
    protected function _retrieveCollectionResponse($products, $totalRecord) {
    }

    /**
     * Delete review by its ID
     *
     * @throws Mage_Api2_Exception
     */
    protected function _delete() {
    }

    /**
     * Create review
     *
     * @param array $data
     * @return string
     */
    protected function _create(array $data) {
    }

    /**
     * Update review by its ID
     *
     * @param array $data
     */
    protected function _update(array $data) {
    }
}
