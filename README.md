Plugin-CAKEPHP2-Solr-integration
================================

Integrate solr with cakephp2.0, eassy to fetch solr data from cakephp application

## Requirements

- PHP5
- CakePHP >= 2.0

## Installation

this repository should be installed in the same way as any other plugin.


	
## Sample Code

To use this DB driver, install (obviously) and define a db source such as follows:

	public $solrdata = array(
            'datasource' => 'SolrDataSource',
            'apiKey'     => '',
        );

To use in Controller, for fatch data

   class SolrController extends AppController {

    	public $uses = array('Solrdb.Solrdb');
	
	public function getData(){
	
		$query = "q=*:*&fl=*&start=0&rows=10";
		$results = $this->Solrdb->find('all', array('conditions' => array('query' => $query)));
		$this->set('data',$results);
	}
 }

## Author

Ankesh Singh <ankeshgang@gmail.com>

