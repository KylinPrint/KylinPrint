<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Dcat\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use App\Admin\Extensions\WangEditor;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Repositories\Menu;

//Form::forget('map');
//Form::forget('editor');


//Dcat\Admin\Form::forget(['map', 'editor']);

Grid\Column::macro('center',function(){
    return $this->setHeaderAttributes(['class' => 'text-center']);
});

