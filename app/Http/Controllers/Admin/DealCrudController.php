<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SalesStage;
use App\Http\Requests\DealRequest;
use App\Models\Iso;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DealCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DealCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Deal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/deal');
        CRUD::setEntityNameStrings('deal', 'deals');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $stages = SalesStage::getKeys();

        CRUD::column('submission_date');
        CRUD::column('account_id');
        CRUD::column('iso_id');
        CRUD::column('deal_name');
        CRUD::column('sales_stage')->label('Sale Stage')->type('select_from_array')->options($stages)->default(0)->allow_null(false);
        CRUD::column('created_at');


        CRUD::addFilter([
                'name' => 'submission',
                'type' => 'date_range',
                'label' => 'Submission Date'
            ], false,
            function($value) {
                $dates = json_decode($value);
                $this->crud->addClause('where', 'submission_date', '>=', $dates->from);
                $this->crud->addClause('where', 'submission_date', '<=', $dates->to);
        });
        CRUD::addFilter([
                'name' => 'iso',
                'type' => 'dropdown',
                'label' => 'ISO'
            ], function() {
                return Iso::all()->pluck('business_name', 'id')->toArray();
            }, function($value) {
                $this->crud->addClause('where', 'iso_id', $value);
        });

        CRUD::addFilter([
                'name' => 'stages',
                'type' => 'dropdown',
                'label' => 'Sales Stage'
            ],
            $stages,
            function($value) {
                $this->crud->addClause('where', 'sales_stage', $value);
        });
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(DealRequest::class);

        $stages = SalesStage::getKeys();
        CRUD::field('submission_date')->type('date_picker');
        CRUD::field('account_id')->type('select')->entity('account')->model('App\Models\Account')->attribute('business_name');
        CRUD::field('iso_id')->label('ISO')->type('select')->entity('iso')->model('App\Models\Iso')->attribute('business_name');
        CRUD::field('sales_stage')->label('Sale Stage')->type('select_from_array')->options($stages)->default(0)->allow_null(false);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
        $stages = SalesStage::getKeys();
        CRUD::field('iso_id')->label('ISO');
        CRUD::field('sales_stage')->label('Sale Stage')->type('select_from_array')->options($stages)->default(0)->allow_null(false);
        CRUD::removeField('account_id');
    }

    protected function setupShowOperation()
    {

        CRUD::set('show.setFromDb', false);
        $stages = SalesStage::getKeys();

        CRUD::column('account_id')->type('select')->entity('account')->model('App\Models\Account')->attribute('business_name');
        CRUD::column('iso_id')->label('ISO')->type('select')->entity('iso')->model('App\Models\Iso')->attribute('business_name');
        CRUD::column('sales_stage')->label('Sale Stage')->type('select_from_array')->options($stages)->default(0)->allow_null(false);
        CRUD::column('deal_name')->label('Deal Name');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }
}
