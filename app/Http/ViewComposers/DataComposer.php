<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Traits\ImageService;
use App\Models\Entities\Setting;

class DataComposer
{
	use ImageService;
    /**
     * Create a movie composer.
     *
     * @return void
     */
    public function __construct()
    {
    	
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
    	$data = [
    			'notification_logo' => $this->getImage ( array (
    					'dir' => 'common',
    					'image_name' => 'sampath-bank-logo.png'
    			) ),
    	    'category_settings' => Setting::getCategorySettings()
    	];
    	$view->with($data);
    }
}