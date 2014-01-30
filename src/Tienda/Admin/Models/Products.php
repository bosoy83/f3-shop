<?php 
namespace Tienda\Admin\Models;

class Products extends \Dsc\Models\Content 
{
    protected $collection = 'tienda.products';
    protected $type = 'tienda.products';
    protected $default_ordering_direction = '1';
    protected $default_ordering_field = 'metadata.title';
    
    public function __construct($config=array())
    {
        parent::__construct($config);
        
        $this->filter_fields = $this->filter_fields + array(
            'publication.start_date'
        );
    }
    
    public function prefab( $source=array(), $options=array() ) 
    {
        $prefab = new \Tienda\Prefabs\Product($source, $options);
        
        return $prefab;
    }
    
    protected function fetchFilters()
    {
        $this->filters = array();
    
        $filter_keyword = $this->getState('filter.keyword');
        if ($filter_keyword && is_string($filter_keyword))
        {
            $key =  new \MongoRegex('/'. $filter_keyword .'/i');
    
            $where = array();
            $where[] = array('metadata.title'=>$key);
            $where[] = array('details.copy'=>$key);
            $where[] = array('metadata.creator.name'=>$key);
    
            $this->filters['$or'] = $where;
        }
    
        $filter_id = $this->getState('filter.id');
        if (strlen($filter_id))
        {
            $this->filters['_id'] = new \MongoId((string) $filter_id);
        }
        
        $filter_copy_contains = $this->getState('filter.copy-contains');
        if (strlen($filter_copy_contains))
        {
            $key =  new \MongoRegex('/'. $filter_copy_contains .'/i');
            $this->filters['details.copy'] = $key;
        }
        
        $this->filters['metadata.type'] = $this->type;
    
        return $this->filters;
    }
    
    public function save( $values, $options=array(), $mapper=null )
    {
        if (empty($values['publication']['start'])) {
            $values['publication']['start'] = \Dsc\Mongo\Metastamp::getDate( $values['publication']['start_date'] . ' ' . $values['publication']['start_time'] );
        }
        
        if (empty($values['publication']['end']) && !empty($values['publication']['end_date'])) {
            $string = $values['publication']['end_date'];
            if (!empty($values['publication']['end_time'])) {
                $string .= ' ' . $values['publication']['end_time']; 
            }
            $values['publication']['end'] = \Dsc\Mongo\Metastamp::getDate( trim( $string ) );
        }
    
        // if no slug exists, generate it and make sure it's unique
        if (empty($values['metadata']['slug']))
        {
            $values['metadata']['slug'] = $this->generateSlug( $values, $mapper );
        }
        
        if (!empty($values['metadata']['tags']) && !is_array($values['metadata']['tags']))
        {
            $values['metadata']['tags'] = trim($values['metadata']['tags']);
            if (!empty($values['metadata']['tags'])) {
                $values['metadata']['tags'] = \Base::instance()->split( (string) $values['metadata']['tags'] );
            }
        }
        
        if (empty($values['metadata']['tags'])) {
            unset($values['metadata']['tags']);
        }

        // create an array of categories from the category_ids, if present
        if (isset($values['category_ids'])) 
        {
            $category_ids = $values['category_ids'];
            unset($values['category_ids']);
            
            $categories = array();
            $model = new \Blog\Admin\Models\Categories;
            if ($list = $model->setState('select.fields', array('title'))->setState('filter.ids', $category_ids)->getList()) {
                foreach ($list as $list_item) {
                    $cast = $list_item->cast();
                    $cat = array(
                        'id' => (string) $cast['_id'],
                        'title' => $cast['title']
                    );
                    unset($cast);
                    $categories[] = $cat;
                }
            }
            $values['metadata']['categories'] = $categories; 
        }
        
        if (!empty($values['attributes']) && is_array($values['attributes'])) {
            // Compress the attributes array to just the values, then sort them by sort order
            $values['attributes'] = array_values($values['attributes']);
            usort($values['attributes'], function($a, $b) {
                return $a['ordering'] - $b['ordering'];
            });
            array_walk($values['attributes'], function(&$item, $key){
            	if ($item['ordering'] != ($key+1)) {
            	    $item['ordering'] = $key+1;
            	}
            	
            	// then Loop through each attribute and do the same for each attribute's options
            	$item['options'] = array_values($item['options']);
            	usort($item['options'], function($a, $b) {
            	    return $a['ordering'] - $b['ordering'];
            	});            	
        	    array_walk($item['options'], function(&$item, $key){
        	        if ($item['ordering'] != ($key+1)) {
        	            $item['ordering'] = $key+1;
        	        }
        	    });            	       
            });
        }
        
        unset($values['parent']);
        unset($values['new_category_title']);
    
        return parent::save( $values, $options, $mapper );
    }
    
    /**
     * An alias for the save command, used only for creating a new object
     *
     * @param array $values
     * @param array $options
     */
    public function create( $values, $options=array() )
    {
        $values = $this->prefab( $values, $options )->cast();

        return $this->save( $values, $options );
    }
    
    /**
     * An alias for the save command, used only for updating an existing object
     *
     * @param unknown_type $mapper
     * @param array $values
     * @param array $options
     */
    public function update( $mapper, $values, $options=array() )
    {
        $values = $this->prefab( $mapper->cast(), $options )->bind( $values )->cast();
        
        return $this->save( $values, $options, $mapper );
    }
}