<?php
namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Settings
{

    protected $model;
    protected $field;
    protected $settings = [];

    public function __construct($settings = [], Model $model, $field = 'settings')
    {
        $this->settings = $settings;
        $this->model = $model;
        $this->field = $field;
    }

    public function all()
    {
        return $this->settings;
    }

    /* get method/methods */
    public function get($keys)
    {
    	if (is_array($keys))
    	{
    		$settings = [];
    		foreach ($keys as $key) {
    			if (array_key_exists($key, $this->settings))
    			{
    				echo $key;
    				$settings[$key] = array_get($this->settings, $key);
    			}
    		}
			return $settings;
    	} else {
            $value = $this->settings;
			return $value[$keys];
    	}
    }

    /* set methods, since PHP doesn't support override, there is magix meth ath the end of the file _call which defines which function is gonna be called */

    public function setOne($key, $value)
    {
        $this->settings[$key] = $value;
        $this->persist();
    }

    public function setMany($attributes)
    {
    	if (is_array($attributes))
    	{
	    	foreach ($attributes as $key => $value) {
		        $this->settings[$key] = $value;
	    	}
    	}
		$this->persist();
    }

    /* has check */

    public function has($key)
    {
        return array_key_exists($key, $this->settings);
    }

    /* merge only those which exists !!! */

    public function merge(array $attributes)
    {
        $this->settings = array_merge(
            $this->settings,
            array_only($attributes, array_keys($this->settings))
        );
        return $this->persist();
    }

    public function delete(array $attributes)
    {
		// $filtered = array_diff($this->settings, $attributes);
		foreach ($attributes as $attribute) {
			if (array_key_exists($attribute, $this->settings)) {
				unset($this->settings[$attribute]);
			}
		}
        return $this->persist();
    }

    protected function persist()
    {
        return $this->model->update([$this->field => $this->settings]);
    }

    /* vea add - magic meths */

    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }
        
        throw new Exception("The {$key} setting does not exist.");
    }

    public function __call($method, $arguments)
    {
      if($method == 'set') {
          if(count($arguments) == 2) {
             return call_user_func_array(array($this,'setOne'), $arguments);
          }
          else if(count($arguments) == 1) {
             return call_user_func_array(array($this,'setMany'), $arguments);
          }
      }
   }  
}
