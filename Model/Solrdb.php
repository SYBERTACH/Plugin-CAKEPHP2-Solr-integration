<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Language
 *
 * @author Ankesh Singh
 */
class Solrdb extends AppModel {

    //use solr data source class
    public $useDbConfig = 'solrdata';
    public $query = '';

    /*
     * Function:beforeFind
     * Desc:get solr query 
     */

    public function beforeFind($query) {
        $this->query = $query['conditions']['query'];
    }

}

?>
