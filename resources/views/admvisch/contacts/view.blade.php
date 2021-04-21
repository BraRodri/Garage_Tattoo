<x-app-layoutt>

    @section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

<ol class="breadcrumb bc-2">
    <li>
        <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
    </li>
    <li>
        <?php echo $title; ?>
    </li>
    <li class="active">
        <strong>Detalle de <?php echo $title; ?></strong>
    </li>
</ol>

<br class="hidden-print" />

<div class="invoice">

    <div class="row">
        <div class="col-sm-12 invoice-left">
            <h3>CONTACTO ID. #<?php echo $contact->id; ?></h3>
            <span class="badge badge-success"><?php echo Application\Helper::formatDateToCompleteDateUser($contact->created_at, true); ?></span>
        </div>
    </div>

    <hr class="margin" />

    <div class="row">

        <div class="col-md-12">
            <h4><i class="fa fa-chevron-right" aria-hidden="true"></i> Detalle de Contacto</h4>
            <table class="table table-bordered table-condensed">
                <tr>
                    <td width="20%" class="col-gris">Tipo:</td>
                    <td width="80%"><?php echo $contact->type; ?></td>
                </tr>
                <?php if(!empty($contact->offices_id)){ ?>
                    <tr>
                        <td class="col-gris">Sucursal:</td>
                        <td><?php echo $contact->office->title; ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td class="col-gris">Nombre:</td>
                    <td><?php echo $contact->name; ?></td>
                </tr>
                <tr>
                    <td class="col-gris">Email:</td>
                    <td><?php echo $contact->email; ?></td>
                </tr>
                <?php if(!empty($contact->phone)){ ?>
                <tr>
                    <td class="col-gris">Teléfono:</td>
                    <td><?php echo $contact->phone; ?></td>
                </tr>
                <?php } ?>
                <?php if(!empty($contact->mobile)){ ?>
                <tr>
                    <td class="col-gris">Celular:</td>
                    <td><?php echo $contact->mobile; ?></td>
                </tr>
                <?php } ?>
                <?php if(!empty($contact->city)){ ?>
                <tr>
                    <td class="col-gris">Ciudad:</td>
                    <td><?php echo $contact->city; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td valign="top" class="col-gris">Mensaje:</td>
                    <td valign="top"><?php echo $contact->message; ?></td>
                </tr>
            </table>
        </div>

    </div>

    <div class="margin"></div>

    <div class="row">

        <div class="col-sm-12">

            <div class="invoice-right">

                <a href="javascript:window.print();" class="btn btn-success btn-icon icon-left hidden-print">
                    Imprimir Contacto
                    <i class="entypo-doc-text"></i>
                </a>
            </div>

        </div>

    </div>

</div>



<x-slot name="js">
   
</x-slot>
</x-app-layoutt>