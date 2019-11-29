<?php


class EsClient{

    private $es_host = '127.0.0.1';
    private $es_port = 9200;
    private $es_index = '';

    public function __construct($str_host,$str_port,$str_index){
        $this->url = $this->es_host.':'.$this->es_port ;
        $this->es_index = $str_index;
        $this->mapping = false;
        $this->esClient_conn = shell_exec('curl '.$this->url);
         if (!$this->esClient_conn) {
            $this->error =  'Error Connecting to Elastic Search Server.... Please check your configuration';
            self::showError(true);
        } else {
            $status = $this -> call('/'.$this->es_index.'/_stat');
            if(isset($status['error'])){
                $this->mapping = true;
                //import mapping template;
                require_once LIB.'elasticsearch/mapping.json.php';
                $this->mapping(json_decode($mapping));
            }
        }
        return false;
    }

    private function call($path, $method = 'GET', $data = null){
        $url  = $this->mapping? $this->url .'/'. $this->es_index . '/' : $this->url .'/' . $this->es_index . '/' . $path;
        $headers = array('Accept: application/json', 'Content-Type: application/json', );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        switch($method) {
            case 'GET' :
                break;
            case 'POST' :
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'PUT' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                break;
            case 'DELETE' :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
            }
        $response = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return json_decode($response, true);
    }

    public function query_wresultSize($type, $query, $from = 0, $size = 999,$aggs){   
        $str_query = array( "from" =>$from, "size"=> $size,"query" =>json_decode($query,true),"aggs" => json_decode($aggs,true));
        $data = (object)$this -> call($type . '/_search', 'POST', $str_query);
        if(!isset($data->error['type']) && $this->esClient_conn){
            $result = new \stdClass();
            $result->num_rows = $data->hits['total'] ?? null;
            $this->data = $data->hits['hits'];
            $result->aggregate = $data->aggregations;
            $assoc = array();
            foreach ($data->hits['hits'] as $key => $value) {
                array_push($assoc, $value['_source']);
            }
            $result->fetch_result = $assoc ?? null ;              
            return $result;  
        }
        else{
            echo $this->error;
            return false;
        }
    }

    public function query_all_wresultSize($query,$from = 0, $size = 999){
        return $this -> call('_search?' . http_build_query(array('q' => $query,'from'=> $from, 'size' => $size)));
    }

    private function mapping($data){
        return $this -> call( '/', 'PUT', $data);
    }

    public function status(){
        return $this -> call('_status');
    }


    public function count($type){
        return (object)$this -> call($type . '/_count?' . http_build_query(array(null => '{matchAll:{}}')));
    }


    public function add($type, $id, $data){
        return $this -> call($type . '/' . $id, 'PUT', $data);
    }


    public function delete($type, $id){
        return $this -> call($type . '/' . $id, 'DELETE');
    }


    public function query($type, $q){
        return $this -> call($type . '/_search?' . http_build_query(array('q' => $q)));
    }

    public function advancedquery($type, $query){
        return $this -> call($type . '/_search', 'POST', $query);
    }

    public function get($type, $id){
        return $this -> call($type . '/' . $id, 'GET');
    }

    public function query_all($query){
        return $this -> call('_search?' . http_build_query(array('q' => $query)));
    }

    public function suggest($query){
        return $this -> call('_suggest', 'POST', $query);
    }

    public function truncate(){
        return $this -> call($this->es_index, 'DELETE');
    }

    public function showError($bool){
        if($bool){
            return $this->error;
        }
    }

}
