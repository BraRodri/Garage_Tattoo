<?php
function loadLanguage($language = 'ES'){

	switch($language){
		case 'ES':
			$loadLanguageEs = array(
					'loading' => 'Cargando...',
					'exportButtonTitle' => 'Exportar',
					'printButtonTitle' => 'Importar',
					'rangeSelectorFrom' => 'De',
					'rangeSelectorTo' => 'A',
					'rangeSelectorZoom' => 'Periodo',
					'downloadPNG' => 'Descargar gráfica PNG',
					'downloadJPEG' => 'Descargar gráfica JPEG',
					'downloadPDF' => 'Descargar gráfica PDF',
					'downloadSVG' => 'Descargar gráfica SVG',
					'printChart' => 'Imprimir Gráfica',
					'thousandsSep' => '.',
					'decimalPoint' => ',',
			);
			break;
	}

	return $loadLanguageEs;
}
?>