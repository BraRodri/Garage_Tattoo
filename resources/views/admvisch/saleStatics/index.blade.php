<x-app-layoutt>

    <?php
    if(isset($error)) {
        if ($error == 'failure') {
            echo '<div class="alert alert-danger"><strong>ERROR!</strong> No se ha encontrado cliente bajo búsqueda realizada, favor vuelva a intentarlo. Si el error persiste favor comunicarse al administrador.</div>';
        }
    }
    ?>
    
    <ol class="breadcrumb bc-2" >
        <li>
            <a href="<?php echo BASE_URL; ?>"><i class="entypo-home"></i>Home</a>
        </li>
        <li class="active">
            <strong><?php echo $title; ?></strong>
        </li>
    </ol>
    
    <h3><?php echo $title; ?></h3>
    <br />
    
    <form role="form" id="form1" method="post" action="{{route('saleStatistics')}}" enctype="multipart/form-data">
    
        <div class="row">
            <div class="col-md-12">
    
                <div class="panel panel-primary">
                    <div class="panel-heading container-blue">
                        <div class="panel-title"><i class="fa fa-search"></i> Filtros de Búsqueda</div>
    
                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="entypo-down-open"></i></a>
                        </div>
                    </div>
    
                    <div class="panel-body with-table">
    
                        <div class="row bs-callout bs-callout-danger">
    
                            <div class="col-md-3">
                                <span class="control-label">Fecha Desde</span><br>
                                <div class="col-md-6 no-padding">
                                    <div class="input-group">
                                        <input type="text" name="date_start_document" id="date_start_document" class="form-control datepicker" data-format="dd-mm-yyyy" value="<?php echo $_date_start_document; ?>">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="entypo-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-3">
                                <span class="control-label">Fecha Hasta</span><br>
                                <div class="col-md-6 no-padding">
                                    <div class="input-group">
                                        <input type="text" name="date_end_document" id="date_end_document" class="form-control datepicker" data-format="dd-mm-yyyy" value="<?php echo $_date_end_document; ?>">
                                        <div class="input-group-addon">
                                            <a href="#"><i class="entypo-calendar"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-md-3">
                                <span class="control-label">&nbsp;</span><br>
                                <div class="col-md-6 no-padding" style="margin:0 0 10px 0">
                                    <button type="submit" class="btn btn-blue"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
    
                        </div>
    
                    </div>
                </div>
    
            </div>
    
        </div>
    
        <script type="text/javascript" src={{asset('js/highcharts/js/highcharts.js')}}></script>
        <script type="text/javascript" src={{asset('js/highcharts/js/exporting.js')}}></script>
    
        <div class="clearfix"></div>
    
        <div class="row">
            <div class="col-md-12">
    
                <div class="panel panel-primary">
    
                    <div class="col-md-12">
                        <div id="container1"></div>
                        <script type="text/javascript"><?php echo $chart->render("chart1"); ?></script>
                    </div>
    
                    <div class="clearfix"></div>
    
                    <hr>
    
                    <div class="col-md-12">
                        <div id="container2"></div>
                        <script type="text/javascript"><?php echo $chart2->render("chart1"); ?></script>
                    </div>
    
                    <div class="clearfix"></div>
    
                    <hr>
    
                    <div class="col-md-6">
                        <div id="container3"></div>
                        <script type="text/javascript"><?php echo $chart3->render("chart1"); ?></script>
                    </div>
    
                    <div class="col-md-6">
                        <div id="container4"></div>
                        <script type="text/javascript"><?php echo $chart4->render("chart1"); ?></script>
                    </div>
    
                    <div class="clearfix"></div>
    
                </div>
    
            </div>
        </div>
    
    </form>
    
    <div class="clearfix"></div>
    
    <br />
    
    
    


    <x-slot name="js">

        <script type="text/javascript"> 


        </script>
    </x-slot>
</x-app-layoutt>