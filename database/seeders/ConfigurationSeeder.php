<?php

namespace Database\Seeders;

use App\Models\Configurations;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configuration = Configurations::create([
            'address' => 'Av. Camino Apacible 592, Pudahuel, R.M.',
            'city' => 'CASA MATRIZ, Pudahuel',
            'address_2' => 'Nombre de la Calle 123, Providencia, R.M.',
            'city_2' => 'SUCURSAL, Providencia',
            'email' => 'contacto@garagetattoo.cl',
            'contact_email' => 'contacto@garagetattoo.cl',
            'sale_email' => '',
            'suscription_email' => '',
            'cotizacion_email' => '',
            'shipping_type' => '',
            'webpay_name_company' => '',
            'map_1' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3329.0621234756404!2d-70.84573568447408!3d-33.447687980774774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9662e9910c2db23b%3A0x7dc1761a41ac66c1!2sAv.%20Camino%20Apacible%20592%2C%20Pudahuel%2C%20Regi%C3%B3n%20Metropolitana!5e0!3m2!1ses-419!2scl!4v1616105739534!5m2!1ses-419!2scl',
            'map_2' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3329.0621234756404!2d-70.84573568447408!3d-33.447687980774774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9662e9910c2db23b%3A0x7dc1761a41ac66c1!2sAv.%20Camino%20Apacible%20592%2C%20Pudahuel%2C%20Regi%C3%B3n%20Metropolitana!5e0!3m2!1ses-419!2scl!4v1616105739534!5m2!1ses-419!2scl',
            'map_1_link' => 'https://goo.gl/maps/xnQWMANcimZYbPzW6',
            'map_2_link' => 'https://goo.gl/maps/xnQWMANcimZYbPzW6',
            'horary' => '<p><strong>Lunes a Viernes:</strong>&nbsp;10:00hrs - 14:00hrs y de 15:00hrs - 18:00hrs</p>
            <p><strong>Sábado:</strong>&nbsp;10:00 hrs - 14:00 hrs<br />
            <strong>Domingos y Festivos:</strong>&nbsp;Cerrado.</p>',
            'phone1' => '+562 9 3020 8115',
            'phone2' => '+5622 1234 5678',
            'phone3' => '+569 3020 8145',
            'social_facebook' => 'https://www.facebook.com/',
            'social_instagram' => 'https://www.instagram.com/',
            'author' => 'Sistema'
        ]);
    }
}
