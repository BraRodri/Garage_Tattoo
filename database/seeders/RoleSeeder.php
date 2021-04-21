<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'orders.ver',
            'orders.editar',
            'saleStatistics.ver',
            'cotizaciones.ver',
            'modals.agregar',
            'modals.editar',
            'modals.eliminar',
            'sliders.agregar',
            'sliders.editar',
            'sliders.eliminar',
            'slidersClients.agregar',
            'slidersClients.editar',
            'slidersClients.eliminar',
            'slidersPartners.agregar',
            'slidersPartners.editar',
            'slidersPartners.eliminar',
            'publicities.agregar',
            'publicities.editar',
            'publicities.eliminar',
            'pages.agregar',
            'pages.editar',
            'pages.eliminar',
            'rcorreo.agregar',
            'rcorreo.editar',
            'rcorreo.eliminar',
            'responses.agregar',
            'responses.editar',
            'responses.eliminar',
            'offices.agregar',
            'offices.editar',
            'offices.eliminar',
            'clients.agregar',
            'clients.editar',
            'clients.eliminar',
            'clients.import',
            'clientsD.agregar',
            'clientsD.editar',
            'clientsD.eliminar',
            'brands.agregar',
            'brands.editar',
            'brands.eliminar',
            'categories.agregar',
            'categories.editar',
            'categories.eliminar',
            'products.agregar',
            'products.editar',
            'products.eliminar',
            'products.import',
            'products.importGallerie',
            'productsG.agregar',
            'productsG.editar',
            'productsG.eliminar',
            'discounts.agregar',
            'discounts.editar',
            'discounts.eliminar',
            'regions.agregar',
            'regions.editar',
            'regions.eliminar',
            'provinces.agregar',
            'provinces.editar',
            'provinces.eliminar',
            'locations.agregar',
            'locations.editar',
            'locations.eliminar',
            'couriers.agregar',
            'couriers.editar',
            'couriers.eliminar',
            'contacts.ver',
            'roles.agregar',
            'roles.editar',
            'roles.eliminar',
            'users.agregar',
            'users.editar',
            'users.eliminar',
            'configurations.editar',
            'metadata.editar',
            'faqs.ver',
            'faqs.editar',
            'faqs.eliminar'
         ];
      
         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         } 
    }
}
