<?php
/** 
 * 
 * @link http://www.authenticdesign.co.uk/extending-magento-rest-api-v2/
 * 
 * $consumerKey = 'c195916aadaf1f1cc1d7704ca8639d45';
$consumerSecret = '721477ba87aadbdec0f2221b76041f44';

//oauth token: b060eb809af1086caab5aaeab119a33a
//oauth token secreet: 89ddc47e8c895b9cbd8077011819e249
 */
class Custom_Restapi_Groups_Model_Api2_Group_Rest_Admin_V1 extends Mage_Api2_Model_Resource
{

    /**
     * Create a customer group
     * @return array
     */

    protected function _create() 
    {
        //Create Customer Group
        $requestData = $this->getRequest()->getBodyParams();
        $groupName = $requestData['name'];
        Mage::getSingleton('customer/group')->setData(
            array('customer_group_code' => $groupName,'tax_class_id' => 3))
            ->save();

        $targetGroup = Mage::getSingleton('customer/group');
        $groupId = $targetGroup->load($groupName, 'customer_group_code')->getId();

        if($groupId) {
            $json = array('id' => $groupId);
            echo json_encode($json);
            exit();
        }

    }

     /**
     * Retrieve a group name by ID
     * @return string
     */

    protected function _retrieve()
    {
        //retrieve a group name by ID
        $customerGroupId = $this->getRequest()->getParam('id');
        
        //getCustomerGroupCode = get data based on field name
        $groupname = Mage::getModel('customer/group')->load($customerGroupId)->getCustomerGroupCode();
        
        return $groupname;

    }

    /**
     * Retrieve list of reviews
     *
     * @return array
     */
    protected function _retrieveCollection() 
    {
        $collection = Mage::getModel('customer/group')->getCollection()->toArray();
        if(!empty($collection)) {
            return json_encode($collection);
        }
        return null;
    }
    
    protected function _retrieveCollectionResponse($products, $totalRecord) 
    {
    }

    /**
     * Update a customer group
     * @return array
     */

    
    protected function _update(array $data)
    {
        //Create Customer Group
        $requestData = $this->getRequest()->getBodyParams();
        $groupId = $requestData['id'];
        $groupName = $requestData['name'];
        Mage::getSingleton('customer/group')->setData(
            array(
                'entity_id' => $groupId, 
                'customer_group_code' => $groupName
            )
        )
        ->save();

        $targetGroup = Mage::getSingleton('customer/group');
        
        //load = load data based on field name and value
        $groupId = $targetGroup->load($groupName, 'customer_group_code')->getId();

        if($groupId) {
            $json = array('id' => $groupId);
            echo json_encode($json);
            exit();
        }

    }
    
    /**
     * Delete group
     */
    protected function _delete()
    {
        //Delete Customer Group
        
        $groupId = $this->getRequest()->getParam('id');
        $group = Mage::getModel('customer/group')->load($groupId);
        
        try {
            $group->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->_critical(self::RESOURCE_INTERNAL_ERROR);
        }
    }
    
}
?>