<?php

namespace Database\Seeders;

use App\Models\Pages;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = Pages::create([
            'title' => 'Nosotros',
            'description' => '<h2>Nuestra empresa</h2>  <p>Garage Tattoo Spa, nace el año 2020 bajo la necesidad de satisfacer las necesidades y acortar los tiempos de entrega a los clientes. Hemos desarrollado distintos procesos de trabajo para optimizar los recursos y tiempos de entregas de los productos.</p>  <p>Contamos con proveedores con estándares de calidad garantizado el uso para que nuestros clientes desarrollen de la mejor forma y con la mejor comodidad el arte del Tattoo.</p>  <p>Contamos con puntos importantes para la distribución de implementos para Tattoo, com por ejemplo:</p>  <ul> 	<li>Productos de alta calidad</li> 	<li>Distribución de marcas reconocidas en todo el mundo</li> 	<li>El mejor servicio de atención al cliente</li> 	<li>Garantía de devolución en 30 días</li> </ul>  <h2>Nuestro Equipo</h2>  <p>Nuestro equipo se compone de personal, en departamento de ventas y personal de bodega y embalaje, capacitado para que tus productos lleguen protegidos y sin problemas</p>',
            'active' => 1,
            'author' => 'Sistema'
        ]);

        $pages = Pages::create([
            'title' => 'Pago Seguro',
            'description' => '<h2>Nuestro pago seguro</h2>  <p>Con SSL: es la tecnología responsable de la autenticación y el cifrado de datos para las conexiones de Internet. ... Encripta los datos que se envían a través de Internet entre dos sistemas (comúnmente entre un servidor y un cliente) para que permanezcan privados.</p>  <h2>Utilizando Visa/Mastercard/Paypal</h2>  <p>Sobre este servicio</p>',
            'active' => 1,
            'author' => 'Sistema'
        ]);

        $pages = Pages::create([
            'title' => 'Política de Envíos',
            'description' => '<h2>Envío de tu compra</h2>  <p>Como norma general, los paquetes se envían dentro de las 24 horas siguientes a la recepción del pago, través de el transporte seleccionado por el cliente, con número de seguimiento. Si prefieres el envío express para entrega durante el dia, se aplicará un cargo adicional.Ponte en contacto con nosotros antes de solicitar esta opción. Sea cual sea la forma de envío que elijas, te proporcionaremos un enlace para que puedas seguir tu pedido en línea.</p>  <p>Los embalajes son personalizados y tus artículos estarán siempre bien protegidos.</p>  <p>Gracias por preferir Garage Tattoo Spa.</p>',
            'active' => 1,
            'author' => 'Sistema'
        ]);

        $pages = Pages::create([
            'title' => 'Devoluciones',
            'description' => '<h2>Forma de Devoluciones</h2>  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque rerum iusto laboriosam nulla assumenda unde magni! Inventore consequatur similique doloribus eum repellendus quos asperiores, voluptatum hic quae fugiat sit autem provident quam omnis et sapiente molestiae voluptates, recusandae aliquam? Quibusdam saepe a quis facere. Culpa voluptatem incidunt quae itaque magni.</p>  <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Magni, delectus saepe? Numquam reprehenderit molestiae nesciunt placeat enim, reiciendis odio dolore, mollitia sapiente officiis, dolores asperiores esse illum quo aut suscipit.</p>',
            'active' => 1,
            'author' => 'Sistema'
        ]);

        $pages = Pages::create([
            'title' => 'Términos y Condiciones',
            'description' => '<h2>1. General</h2>  <p>Es requisito esencial para comprar en el Sitio la aceptación de los Términos y Condiciones. Al aceptar estos Términos y Condiciones, los usuarios declaran haberse informado de manera clara, comprensible e inequívoca de los mismos, y que han tenido la posibilidad de almacenarlos e imprimirlos.</p>  <h2>2. Registro del Usuario</h2>  <p>El usuario que desee realizar compras a través del Sitio deberá registrarse señalando datos de carácter personal referidos a su dirección y número telefónico. El usuario garantiza la autenticidad, exactitud y vigencia de los datos registrados.</p>  <p>El registro del usuario en el Sitio implica el conocimiento y aceptación de estos Términos y Condiciones.</p>  <h2>3. Uso de Datos Personales</h2>  <p>La información entregada por el usuario será almacenada bajo altos estándares de seguridad, tratada en conformidad a la ley y con el exclusivo propósito de concretar las transacciones en el Sitio y mejorar la labor de información y comercialización de los productos ofrecidos.</p>  <p>Garagetattoo pone a disposición del usuario una dirección de correo electrónico para que se puedan modificar y/o corregir sus datos personales.</p>  <h2>4. Despacho de Productos</h2>  <p>Al realizar su pedido se consideran los siguientes tiempos de espera por sus productos:</p>  <p>Desde la III a la XII región: 2 a 3 días hábiles</p>  <p>Para la I y II región: 3 a 4 días hábiles</p>  <p>Los despachos se realizan de lunes a viernes (excepto festivos) se despachan los pedidos que cuenten con stock inmediato en tienda y pagados durante el día anterior hasta las 24 hrs. Ejemplo, si usted realiza y paga su pedido un día lunes antes de las 20:00 hrs., se despacha durante el día martes siempre y cuando se cuente con stock disponible en tienda, debe recibir el mismo si elige Entrega Express o al día siguiente del envio si elige transporte (Para RM, Santiago)</p>  <p>IMPORTANTE: Los despachos con transporte externo y entrega express tienen costos asociados, los retiros en nuestro local o bodega son sin costo.</p>  <p>Los números de seguimiento se envían al día siguiente de realizado el despacho, los pedidos se despachan al día hábil siguiente o subsiguiente dependiendo de la hora del pedido</p>  <p>En caso de despacho a domicilio, es responsabilidad del comprador, asegurarse que haya alguien en casa para recibir durante todo el día de 10:00 a 18:00 hrs. ya que en caso contrario el pedido es devuelto a remitente. Para realizar el despacho nuevamente, el comprador debe abonar por concepto de flete, un monto que va desde los $3.500 a $6.500 dependiendo de la localidad de despacho.</p>  <h2>5. Cambios y Devoluciones</h2>  <p>Los requisitos para cualquier cambio o devolución son los siguientes:</p>  <p>(a) Solicitarlo dentro de los 3 meses siguientes a la fecha en que se haya recibido el producto, en caso de deficiencias de fabricación, elaboración, materiales</p>  <p>(b) El producto debe estar sin uso, es decir armado, en perfectas condiciones y debe tener los embalajes originales.</p>  <h2>6. Garantía Falla de Fábrica Ley 19.496</h2>  <p>IMPORTANTE: La garantía del producto aplica a fallas de fabricación y no a una mala manipulación.</p>  <p>Si su producto presenta falla de fábrica dentro de los 3 meses de realizada la compra, debe presentar el producto completo (todas las piezas) junto con boleta de compra, para evaluación de falla, la cual tendrá una respuesta en 15 días hábiles. Si el producto cumple con falla de fabrica tendrá 3 opciones:</p>  <p>Será reparado gratuitamente.</p>  <p>Cambio de producto.</p>  <p>Devolución de dinero.</p>  <p><i>Garage Tattoo Spa, Casa Matriz Camino Apacible 592 Pudahuel, Móvil: +56 9 3020 8145, Mail:&nbsp;Ventas@garagetatto.cl&nbsp;&ndash;&nbsp;Contacto@garagetatto.cl, Web: www.garagetattoo.cl</i></i></p>',
            'active' => 1,
            'author' => 'Sistema'
        ]);
    }
}
