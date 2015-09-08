# nardev
# vedran@nardev.org


# This class enables you to easly set/dalete/merge json nodes which could be saved in DB through your ORM

In this particular case i used it for Laravel project, you could place it in app/[YourClasses] an load it in composer.json through classmap

If the class is autoloaded in your Laravel project, create method in your model like this:


public function settings()
{
	return new Settings($this->settings,$this);
}


And use it like this:

Get the value/values. You can pass one key or array:
$model->settings()->get({keys});

Merge values, doesn't set new values which doesn't exists.
As you see, you can even pass whole request and it will be merged:
$model->settings()->merge($request->all());

Set/Create New value/node in json:
$model->settings()->set(['key' => 'value',...]);

Or single key:
$model->settings()->set('key','value')

$model->settings()->delete(['key1','key2',...]);


Also, once you create the method in your ORM model, you can get your values like this:

$model->settings()->value;



Etc...

bye

