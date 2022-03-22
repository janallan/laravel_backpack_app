<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\IsoRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class IsoCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class IsoCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Iso::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/iso');
        CRUD::setEntityNameStrings('ISO', 'ISOs');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn(['name' => 'business_name', 'type' => 'text', 'label' => 'Business Name']);
        CRUD::addColumn(['name' => 'contact_name', 'type' => 'text', 'label' => 'Contact Name']);
        CRUD::addColumn(['name' => 'contact_number', 'type' => 'phone', 'label' => 'Contact Number']);

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
        CRUD::setValidation(IsoRequest::class);


        CRUD::addField(['name' => 'business_name', 'type' => 'text', 'label' => 'Business Name']);
        CRUD::addField(['name' => 'contact_name', 'type' => 'text', 'label' => 'Contact Name']);
        CRUD::addField(['name' => 'contact_number', 'type' => 'text', 'label' => 'Contact Number']);
        CRUD::addField(['name' => 'emails', 'type' => 'repeatable', 'label' => 'Email Addresses',
            'fields' => [
                    [
                        'name' => 'email',
                        'type' => 'email',
                        'label' => 'Email Address'
                    ]
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

        CRUD::addColumn(['name' => 'business_name', 'type' => 'text', 'label' => 'Business Name']);
        CRUD::addColumn(['name' => 'contact_name', 'type' => 'text', 'label' => 'Contact Name']);
        CRUD::addColumn(['name' => 'contact_number', 'type' => 'phone', 'label' => 'Contact Number']);
        CRUD::addColumn(['name' => 'emails', 'type' => 'multidimensional_array', 'label' => 'Email Addresses', 'visible_key' => 'email']);

    }
}
