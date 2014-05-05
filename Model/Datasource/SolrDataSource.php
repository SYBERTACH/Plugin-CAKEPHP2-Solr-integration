<?php
class SolrDataSource extends DataSource {


/**
 * An optional description of your datasource
 */
    public $solrResult;
    public $solrUrl;
    public $solrCollection;

    /**
 * Our default config options. These options will be customized in our
 * ``app/Config/database.php`` and will be merged in the ``__construct()``.
 */
    public $config = array(
        'apiKey' => '',
    );

/**
 * Create our HttpSocket and handle any config tweaks.
 */
    public function __construct($config) {
        parent::__construct($config);
        //load solr cofig file
        Configure::load('Solrdb.SolrConfig' , 'default' , false); 
        $solrConfig = Configure::read('solr'); //get solr config values from config file
        $this->solrUrl = $solrConfig['host'] . ':' . $solrConfig['port'] . '/' . $solrConfig['path'];
        $this->solrCollection = Configure::read('solrCollection');
    }

/**
 * Since datasources normally connect to a database there are a few things
 * we must change to get them to work without a database.
 */

/**
 * listSources() is for caching. You'll likely want to implement caching in
 * your own way with a custom datasource. So just ``return null``.
 */
    public function listSources($data = null) {
        return null;
    }


/**
 * calculate() is for determining how we will count the records and is
 * required to get ``update()`` and ``delete()`` to work.
 *
 * We don't count the records here but return a string to be passed to
 * ``read()`` which will do the actual counting. The easiest way is to just
 * return the string 'COUNT' and check for it in ``read()`` where
 * ``$data['fields'] === 'COUNT'``.
 */
    public function calculate(Model $model, $func, $params = array()) {
        return 'COUNT';
    }

/**
 * Implement the R in CRUD. Calls to ``Model::find()`` arrive here.
 */
    public function read(Model $model, $queryData = array(), $recursive = null) {


        /**
         * Here we do the actual count as instructed by our calculate()
         * method above. We could either check the remote source or some
         * other way to get the record count. Here we'll simply return 1 so
         * ``update()`` and ``delete()`` will assume the record exists.
         */
        if ($queryData['fields'] === 'COUNT') {
            return array(array(array('count' => 1)));
        }

        /**
         * Now we get, decode and return the remote data.
         */
        $queryData['conditions']['apiKey'] = $this->config['apiKey'];
        $this->params = $queryData;
        return $this->callApi()->formatResponse();
        
    }
    /*
     * Function : callApi
     * input: query Parameters
     * Description: call solr api to get result data
     */
    public function callApi(){
        $result = file_get_contents($this->solrUrl . $this->solrCollection . '/select?' . $this->params['conditions']['query'] . '&wt=json');
        $this->solrResult = $result;
        return $this;
    }

  /*
   * Function: formatResponse
   * Formate result output in to array.
   * output: json=>array
   */
  public function formatResponse(){
  
      return json_decode($this->solrResult);//decode json data
  }
  
 
}
