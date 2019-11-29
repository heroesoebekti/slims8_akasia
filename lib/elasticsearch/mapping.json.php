<?php
/**
 * es mapping template
 * Class for generating list of bibliographic records from MongoDB
 *
 * Copyright (C) 2013 Arie Nugraha (dicarve@yahoo.com)
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

$mapping ='  {
    "mappings": {
      "biblio_search": {
        "properties": {
          "authors": {
            "properties": {
              "author_name": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "authority_level": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "authority_type": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              }
            }
          },
          "biblio_id": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "call_number": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "classification": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "collation": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "content_type": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "edition": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "frequency": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "gmd_name": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "hash": {
            "properties": {
              "authors": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "biblio": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "classification": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "image": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "subjects": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              }
            }
          },
          "id": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "image": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "input_date": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "isbn_issn": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "items": {
            "properties": {
              "call_number": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "coll_type_name": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "input_date": {
                "type": "date",
                "format": "dateOptionalTime"
              },
              "inventory_code": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "invoice": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "invoice_date": {
                "type": "date",
                "format": "dateOptionalTime"
              },
              "item_code": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "item_id": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "item_status": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "last_update": {
                "type": "date",
                "format": "dateOptionalTime"
              },
              "location_name": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "order_date": {
                "type": "date",
                "format": "dateOptionalTime"
              },
              "order_no": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "price": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "price_currency": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "received_date": {
                "type": "date",
                "format": "dateOptionalTime"
              },
              "shelf_location": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "source": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "supplier_name": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "uid": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              }
            }
          },
          "language_name": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "last_update": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "notes": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "opac_hide": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "place": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "promoted": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "publish_year": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "publisher_name": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "series_title": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "sor": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "source": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "spec_detail_info": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "subjects": {
            "properties": {
              "topic": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "topic_level": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              },
              "topic_type": {
                "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
              }
            }
          },
          "title": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          },
          "uid": {
            "type": "string",
                "fields": {
                  "search": { 
                    "type":  "string",
                    "index": "not_analyzed"
                  }
                }
          }
        }
      }
    },
    "settings": {
      "index": {
        "max_result_window": "1000000",
        "analysis": {
          "analyzer": {
            "my_analyzer": {
              "tokenizer": "my_tokenizer"
            }
          },
          "tokenizer": {
            "my_tokenizer": {
              "pattern": " - ",
              "type": "pattern"
            }
          }
        }
      }
    }
  }
';
/*
$mapping ='
{
    "mappings": {
      "biblio_search": {
        "properties": {
          "author": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "analyzer": "my_analyzer"
              }
            } 
          },
          "biblio_id": {
            "type": "long"
          },
          "call_number": {
            "type": "string"
          },
          "carrier_type": {
            "type": "string"
          },
          "classification": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "collation": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "collection_types": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "content_type": {
            "type": "string"
          },
          "edition": {
            "type": "string"
          },
          "gmd": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "image": {
            "type": "string"
          },
          "input_date": {
            "type":   "date",
            "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
          },
          "isbn_issn": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "items": {
            "type": "string",
            "analyzer": "my_analyzer"
          },
          "labels": {
            "type": "string"
          },
          "language": {
            "type": "string"
          },
          "last_update": {
            "type":   "date",
            "format": "yyyy-MM-dd HH:mm:ss||yyyy-MM-dd||epoch_millis"
          },
          "location": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "media_type": {
            "type": "string"
          },
          "notes": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "opac_hide": {
            "type": "string"
          },
          "promoted": {
            "type": "string"
          },
          "publish_place": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "publish_year": {
            "type": "string"
          },
          "publisher": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "series_title": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "not_analyzed"
              }
            } 
          },
          "spec_detail_info": {
            "type": "string"
          },
          "title": {
            "type": "string",
       	    "fields": {
              "search": { 
                "type":  "string",
                "index": "analyzed"
              }
            }
          },
          "topic": {
            "type": "string",
            "fields": {
              "search": { 
                "type":  "string",
                "index": "analyzed"
              },
              "aggregate": { 
                "type":  "string",
                "analyzer": "my_analyzer"
              }
            }            
          }
        }
      }
    },
    "settings": {
      "index": {
        "max_result_window": "1000000",
        "analysis": {
          "analyzer": {
            "my_analyzer": {
              "tokenizer": "my_tokenizer"
            }
          },
          "tokenizer": {
            "my_tokenizer": {
              "pattern": " - ",
              "type": "pattern"
            }
          }
        }       
      }
    }
}';


*/