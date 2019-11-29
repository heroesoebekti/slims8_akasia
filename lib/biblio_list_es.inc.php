<?php
/**
 * elastic search biblio_list class
 * Class for generating list of bibliographic records from Elastic Search
 *
 * Copyright (C) 2013 Arie Nugraha (dicarve@yahoo.com), 2019 Heru Subekti (heroe.soebekti@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

// be sure that this file not accessed directly
if (!defined('INDEX_AUTH')) {
    die("can not access this file directly");
} elseif (INDEX_AUTH != 1) {
    die("can not access this file directly");
}

class biblio_esClient_result {
  private $nosql_cursor = false;
  public function __construct(&$obj_nosql_cursor) {
    $this->nosql_cursor = $obj_nosql_cursor;
  }

  public function fetch_assoc() {
    return next($this->nosql_cursor);
  }

  public function free_result() {
    return reset($this->nosql_cursor);
  }
}


class biblio_list extends biblio_list_model
{

  protected $options = array();
	protected $Biblio = false;
	protected $offset = 0;
	protected $cursor = 0;
	public $current_page = 1;
	public $num2show = 1;
  protected $aggs_fields = array('author' =>'authors.author_name','subject' =>  'subjects.topic','publisher_name' =>'publisher_name','publishing_place' => 'place',
                                'publishing_year' => 'publish_year','gmd'=>'gmd_name','language' =>'language_name','location' =>'items.location_name','collection_type'=>'items.coll_type_name');
  protected $aggs_size = 10;
  public $aggs = array();

  /**
   * Class Constructor
   *
   * @param   object  $obj_db
   * @param   integer	$int_num_show
   */
  public function __construct($obj_db, $int_num_show) {
    parent::__construct($obj_db, $int_num_show);
	  if (!class_exists('EsClient')) {
	    throw new Exception('ElasticSearch library is not installed yet!');
	  } else {
	    // get page number from http get var
	    if (!isset($_GET['page']) OR $_GET['page'] < 1){ $_page = 1; } else {
	      $_page = (integer)$_GET['page'];
	    }
	    $this->current_page = $_page;
	    // count the row offset
	    if ($this->current_page <= 1) { $_offset = 0; } else {
	      $this->offset = ($this->current_page*$this->num2show) - $this->num2show;
	    }
	  }
    $this->enable_custom_frontpage = true;
  }


  /**
   * Method to print out document records
   *
   * @param   object  $obj_db
   * @param   integer $int_num2show
   * @param   boolean $bool_return_output
   * @return  string
   */
  public function getDocumentList($bool_return_output = true) { 
    global $esClient;
	  // start time
	  $_start = function_exists('microtime')?microtime(true):time();	  
    // execute query
    $this->cursor = $esClient->query_wresultSize('biblio_search', $this->criteria,$this->offset,$this->num2show,$this->aggs);
	  if($this->cursor){
    		$this->num_rows = $this->cursor->num_rows;
        $_SESSION['aggs'] = $this->cursor->aggregate;
        $this->aggregate = $this->cursor->aggregate;
    	  $biblio[] = array();
    	  foreach ($this->cursor->fetch_result as $key => $value) {
    	    $biblio[] = $value;
    	  }
    	  $this->biblio = $biblio;
    	  $this->resultset = new biblio_esClient_result($this->biblio);
        $this->resultset->free_result();
    		// end time
    		$_end = function_exists('microtime')?microtime(true):time();
    		$this->query_time = round($_end-$_start, 5);
    		if ($bool_return_output) {
    		  // return the html result
    		  return $this->makeOutput();
    		}
	   }
  }

  /**
   * Method to set search criteria
   *
   * @param   string  $str_criteria
   * @return  void
   */
  public function setSQLcriteria($str_criteria) {
      global $sysconf;
      $this->search_fields  =  array_merge(array('title'=>'title','isbn'=>'isbn','colltype'=>'coll_type_name'),$this->aggs_fields);
      $default_search = 'should';
      $multi_match = array();
      $cluster = array();
      $this->orig_query = $str_criteria;
      $this->aggs_size = $sysconf['es']['cluster']['size'];
      $_queries = simbio_tokenizeCQL($str_criteria, $this->searchable_fields, $this->stop_words, $this->queries_word_num_allowed);
      foreach ($_queries as $key => $value) {
        if(isset($value['q'])){     
          $this->words[$value['q']] = $value['q'];
          $multi_match[] = array("multi_match" => 
                array(
                    "query" => $value['q'] ,
                    "type"=> isset($value['is_phrase'])?'phrase':'phrase_prefix',
                    "fields"=> array($this->search_fields[$value['f']])
                  )
            );
        } 
      } 
      if(isset($_GET['field'])){
        foreach ($_GET['field'] as $key => $value) {
          if(isset($this->search_fields[$key])){
          $cluster[] = array('match' => array($this->search_fields[$key].'.search' => $value));
          }
        }
        array_merge($cluster);
      }
      if(empty($cluster)){        
        $cluster = array('match_all' => array( "boost" => 1.2));
      }    
      $filter = array('bool' => array('must'=>array($cluster,array('bool' => array($default_search => $multi_match)))));
      $this->criteria = json_encode($filter); 
      $aggs = array();
      foreach ($this->aggs_fields as $key => $value) {
        if($sysconf['es']['cluster']['field'][$key]){
          $aggs = array_merge($aggs, array($key => array('terms' => array('field'=> $value.'.search','size'=>$this->aggs_size))));
        }
      }
      $this->aggs = json_encode($aggs);
      $custom_frontpage_record_file = SB.$sysconf['template']['dir'].'/'.$sysconf['template']['theme'].'/custom_frontpage_record.inc.php';      
      if (file_exists($custom_frontpage_record_file)) {        
        include $custom_frontpage_record_file;       
        $this->enable_custom_frontpage = true;    
        $this->custom_fields = $custom_fields;
      }
     // echo '<pre>'.json_encode(json_decode($this->criteria,true)).'</pre>';
	  return $this->criteria;
  }

public function aggs(){
  return $this->custom_fields;
}
  /**
   * Method to make an output of document records in simple XML format
   *
   * @return  string
   */
  public function XMLresult() {
    global $sysconf;
    $mods_version = '3.3';
    $_buffer = '';
    // loop data
    $xml = new XMLWriter();
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->startElement('modsCollection');
    $xml->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
    $xml->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $xml->writeAttribute('xmlns', 'http://www.loc.gov/mods/v3');
    $xml->writeAttribute('xmlns:slims', 'http://slims.web.id');
    $xml->writeAttribute('xsi:schemaLocation', 'http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-3.xsd');
    // $_buffer = '<modsCollection xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.loc.gov/mods/v3" xmlns:slims="http://slims.web.id" xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-3.xsd">'."\n";
    $xml->startElementNS('slims', 'resultInfo', null);
    $xml->startElementNS('slims', 'modsResultNum', null); $this->xmlWrite($xml, $this->num_rows); $xml->endElement();
    $xml->startElementNS('slims', 'modsResultPage', null); $this->xmlWrite($xml, $this->current_page); $xml->endElement();
    $xml->startElementNS('slims', 'modsResultShowed', null); $this->xmlWrite($xml, $this->num2show); $xml->endElement();
    $xml->endElement();

    while ($_biblio_d = $this->resultset->fetch_assoc()) {
      //echo json_encode($_biblio_d);
      $xml->startElement('mods');
      $xml->writeAttribute('version', $mods_version);
      $xml->writeAttribute('ID', $_biblio_d['biblio_id']);
      // parse title
      $_title_sub = '';
      if (stripos($_biblio_d['title'], ':') !== false) {
        $_title_main = trim(substr_replace($_biblio_d['title'], '', stripos($_biblio_d['title'], ':')+1));
        $_title_sub = trim(substr_replace($_biblio_d['title'], '', 0, stripos($_biblio_d['title'], ':')+1));
      } else {
        $_title_main = trim($_biblio_d['title']);
      }

      // parse title
      $_title_main = trim($_biblio_d['title']);
      $_title_sub = '';
      $_title_statement_resp = '';
      if (stripos($_biblio_d['title'], '/') !== false) {
          $_title_main = trim(substr_replace($_biblio_d['title'], '', stripos($_biblio_d['title'], '/')+1));
          $_title_statement_resp = trim(substr_replace($_biblio_d['title'], '', 0, stripos($_biblio_d['title'], '/')+1));
      }
      if (stripos($_biblio_d['title'], ':') !== false) {
          $_title_main = trim(substr_replace($_biblio_d['title'], '', stripos($_biblio_d['title'], ':')+1));
          $_title_sub = trim(substr_replace($_biblio_d['title'], '', 0, stripos($_biblio_d['title'], ':')+1));
      }

      $xml->startElement('titleInfo');
      $xml->startElement('title');
      $this->xmlWrite($xml, $_title_main);
      $xml->endElement();
      if ($_title_sub) {
          // $_xml_output .= '<subTitle><![CDATA['.$_title_sub.']]></subTitle>'."\n";
          $xml->startElement('subTitle');
          $this->xmlWrite($xml, $_title_sub);
          $xml->endElement();
      }
      // $_xml_output .= '</titleInfo>'."\n";
      $xml->endElement();

      /*
      foreach ($_biblio_d['authors'] as $key => $value) {
        $xml->startElement('name'); 
          $xml->writeAttribute('type', $key['authority_type']);
          $xml->writeAttribute('authority', "");
        $xml->startElement('namePart'); $this->xmlWrite($xml, $value['author_name']);
        $xml->endElement();
        $xml->startElement('role');
            $xml->startElement('roleTerm'); $xml->writeAttribute('type', 'text');
            $this->xmlWrite($xml, $value['authority_level']);
            $xml->endElement();
        $xml->endElement();
        $xml->endElement();

      }
      */
      // get the authors data
      $_biblio_authors_q = $this->obj_db->query('SELECT a.*,ba.level FROM mst_author AS a'
        .' LEFT JOIN biblio_author AS ba ON a.author_id=ba.author_id WHERE ba.biblio_id='.$_biblio_d['biblio_id']);
      while ($_auth_d = $_biblio_authors_q->fetch_assoc()) {
        // some rules to set name type in mods standard
        if ($sysconf['authority_type'][$_auth_d['authority_type']] == 'Personal Name') {
          $sysconf['authority_type'][$_auth_d['authority_type']] = 'personal';
        } elseif ($sysconf['authority_type'][$_auth_d['authority_type']] == 'Organizational Body') {
          $sysconf['authority_type'][$_auth_d['authority_type']] = 'corporate';
        } elseif ($sysconf['authority_type'][$_auth_d['authority_type']] == 'Conference') {
          $sysconf['authority_type'][$_auth_d['authority_type']] = 'conference';
        } else {
          $sysconf['authority_type'][$_auth_d['authority_type']] = 'personal';
        }
        $xml->startElement('name'); $xml->writeAttribute('type', $sysconf['authority_type'][$_auth_d['authority_type']]); $xml->writeAttribute('authority', $_auth_d['auth_list']);
        $xml->startElement('namePart'); $this->xmlWrite($xml, $_auth_d['author_name']); $xml->endElement();
        $xml->startElement('role');
            $xml->startElement('roleTerm'); $xml->writeAttribute('type', 'text');
            $this->xmlWrite($xml, $sysconf['authority_level'][$_auth_d['level']]);
            $xml->endElement();
        $xml->endElement();
        $xml->endElement();
      }

     // $_biblio_authors_q->free_result();
      $xml->startElement('typeOfResource'); $xml->writeAttribute('collection', 'yes'); $this->xmlWrite($xml, 'mixed material'); $xml->endElement();
      $xml->startElement('identifier'); $xml->writeAttribute('type', 'isbn'); $this->xmlWrite($xml, str_replace(array('-', ' '), '', $_biblio_d['isbn_issn'])); $xml->endElement();

      // imprint/publication data
      $xml->startElement('originInfo');
      $xml->startElement('place');
          $xml->startElement('placeTerm'); $xml->writeAttribute('type', 'text'); $this->xmlWrite($xml, $_biblio_d['place']); $xml->endElement();
          $xml->startElement('publisher'); $this->xmlWrite($xml, $_biblio_d['publisher_name']); $xml->endElement();
          $xml->startElement('dateIssued'); $this->xmlWrite($xml, $_biblio_d['publish_year']); $xml->endElement();
      $xml->endElement();
      $xml->endElement();

      // images
      $_image = '';
      if (!empty($_biblio_d['image'])) {
        $_image = urlencode($_biblio_d['image']);
  $xml->startElementNS('slims', 'image', null); $this->xmlWrite($xml, $_image); $xml->endElement();
      }

      $xml->endElement(); // MODS
    }
    // free resultset memory
    $this->resultset->free_result();

  $xml->endElement();
    $_buffer .= $xml->flush();
    // $_buffer .= '</modsCollection>';

    return $_buffer;
  }


  /**
   * Method to make an output of document records in JSON-LD format
   *
   * @return  string
   */
  public function JSONLDresult() {
    global $sysconf;
    $jsonld['@context'] = 'http://schema.org';
    $jsonld['@type'] = 'Book';

    // loop data
    $jsonld['total_rows'] = $this->num_rows;
    $jsonld['page'] = $this->current_page;
    $jsonld['records_each_page'] = $this->num2show;
    $jsonld['@graph'] = array();
  while ($_biblio_d = $this->resultset->fetch_assoc()) {
      $record = array();
      $record['@id'] = 'http://'.$_SERVER['SERVER_NAME'].SWB.'index.php?p=show_detail&id='.$_biblio_d['biblio_id'];
      $record['name'] = trim($_biblio_d['title']);

      // get the authors data
      $_biblio_authors_q = $this->obj_db->query('SELECT a.*,ba.level FROM mst_author AS a'
        .' LEFT JOIN biblio_author AS ba ON a.author_id=ba.author_id WHERE ba.biblio_id='.$_biblio_d['biblio_id']);
    $record['author'] = array();
      while ($_auth_d = $_biblio_authors_q->fetch_assoc()) {
    $record['author']['name'][] = trim($_auth_d['author_name']);
      }
      $_biblio_authors_q->free_result();

    // ISBN
    $record['isbn'] = $_biblio_d['isbn_issn'];

    // publisher
    $record['publisher'] = $_biblio_d['publisher_name'];

    // publish date
    $record['dateCreated'] = $_biblio_d['publish_year'];

      // doc images
      $_image = '';
      if (!empty($_biblio_d['image'])) {
        $_image = urlencode($_biblio_d['image']);
    $record['image'] = $_image;
      }

    $jsonld['@graph'][] = $record;
    }

    // free resultset memory
    $this->resultset->free_result();

    return str_ireplace('\/', '/', json_encode($jsonld));
  }



  /**
   * Method to make an output of document records in simple XML format
   *
   * @return  string
   */
  public function RSSresult() {
    global $sysconf;
    // loop data
    $_buffer = '<rss version="2.0">'."\n";
    $_buffer .= '<channel>'."\n";
    $_buffer .= '<title><![CDATA[Collection of '.$sysconf['library_name'].']]></title>'."\n";
    $_buffer .= '<link><![CDATA[http://'.$_SERVER['SERVER_NAME'].SWB.']]></link>'."\n";
    $_buffer .= '<description><![CDATA[New collection of '.$sysconf['library_name'].']]></description>'."\n";
    $_buffer .= "\n";

    while ($_biblio_d = $this->resultset->fetch_assoc()) {
      $_buffer .= '<item>'."\n";
      $_buffer .= ' <title><![CDATA['.trim($_biblio_d['title']).']]></title>'."\n";
      $_buffer .= ' <link><![CDATA[http://'.$_SERVER['SERVER_NAME'].SWB.'/index.php?p=show_detail&id='.$_biblio_d['biblio_id'].']]></link>'."\n";
      $_buffer .= ' <pubDate><![CDATA['.date('D, d F Y H:i:s', strtotime($_biblio_d['input_date'])).']]></pubDate>'."\n";

      // get the authors data
      $_authors = $this->getAuthors($this->obj_db, $_biblio_d['biblio_id']);
      // remove last comma
      $_buffer .= ' <author><![CDATA['.$_authors.']]></author>'."\n";

      $_buffer .= '<description><![CDATA[Author: '.$_authors.' ISBN: '.$_biblio_d['isbn_issn'].']]></description>'."\n";
      $_buffer .= '</item>'."\n";
    }
    $_buffer .= '</channel>';
    $_buffer .= '</rss>';

    // free resultset memory
    $this->resultset->free_result();

    return $_buffer;
  }

  private function xmlWrite(&$xmlwriter, $data, $mode = 'Text') {
  if ($mode == 'CData') {
    $xmlwriter->writeCData($data);
  } else {
    $xmlwriter->text($data);
  }
  }
}
