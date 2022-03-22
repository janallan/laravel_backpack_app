<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccountRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AccountCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccountCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Account::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/account');
        CRUD::setEntityNameStrings('account', 'accounts');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('business_name');
        CRUD::column('sic')->label('SIC')->type('relationship')->entity('sic')->model(App\Models\Sic::class)->attribute('description');
        CRUD::column('created_at');
        CRUD::column('updated_at');

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
        CRUD::setValidation(AccountRequest::class);

        CRUD::field('business_name');
        CRUD::field('sics_id')->type('select')->entity('sic')->model('App\Models\Sic')->attribute('description');
        CRUD::field('owners')->type('repeatable')->fields([
            [
                'name' => 'owner_name',
                'type' => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'title',
                'type' => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'email',
                'type' => 'email',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name' => 'date_of_birth',
                'type' => 'date_picker',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ]
        ]);

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

    }

    protected function setupShowOperation()
    {

        CRUD::set('show.setFromDb', false);

        CRUD::column('business_name');
        CRUD::column('sic')->label('SIC')->type('relationship')->entity('sic')->model(App\Models\Sic::class)->attribute('description');
        CRUD::addColumn(['name' => 'owners', 'type' => 'multidimensional_array', 'label' => 'Owners', 'visible_key' => 'owner_name']);
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }


}
