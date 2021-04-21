<?php
/**
 * Created by PhpStorm.
 * User: PC - Trabajo
 * Date: 04/12/2017
 * Time: 15:18
 */
namespace Application;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Border;
use PHPExcel_Style_NumberFormat;

class HelperExcel
{
    public static function applyCellProperties(PHPExcel $objPHPExcel, $columnStart, $columnEnd, $row, $bold = false, $color_text = "000000", $size = 11, $font_family = "Arial", $background_cells = false, $color_border = false)
    {
        $styleArray = array(
            'font' => array(
                'bold'  => $bold,
                'color' => array(
                    'rgb' => $color_text
                ),
                'size'  => $size,
                'name'  => $font_family
            )
        );

        if($background_cells !== false)
        {
            $styleArrayBackgroundCell = array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array(
                    'rgb' => $background_cells
                )
            );

            $styleArray['fill'] = $styleArrayBackgroundCell;
        }

        if($color_border !== false)
        {
            $styleArrayBorderCell = array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array(
                        'rgb' => $color_border
                    )
                )
            );

            $styleArray['borders'] = $styleArrayBorderCell;
        }

        for($col = $columnStart; $col != $columnEnd; $col++)
        {
            $objPHPExcel->getActiveSheet()->getStyleByColumnAndRow($col, $row)->applyFromArray($styleArray);
        }
    }

    public static function applyAutoAdjust(PHPExcel $objPHPExcel, $columnStart, $columnEnd = false)
    {
        if($columnEnd === false)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($columnStart)->setAutoSize(true);
        } else {
            for($col = $columnStart; $col != $columnEnd; $col++)
            {
                $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setAutoSize(true);
            }
        }
    }

    public static function applyCellCombinations(PHPExcel $objPHPExcel, $columnStart1, $rowStart1, $columnEnd2, $rowEnd2)
    {
        $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($columnStart1, $rowStart1, $columnEnd2, $rowEnd2);
    }

    public static function applyExcelFormatDates(PHPExcel $objPHPExcel, $date, $format = "YYYY-MM-DD")
    {
        return $displayDate = PHPExcel_Style_NumberFormat::toFormattedString($date, $format);
    }

    public static function applyBookProperties(PHPExcel $objPHPExcel, $titulo, $textoAlternativo = "")
    {
        $textoTituloConcatenado = (!empty($textoAlternativo))? $textoAlternativo . " " . $titulo : $titulo;
        $textoDescripcionConcatenado = (!empty($textoAlternativo))? "Excel descargado desde " . APP_SLOGAN . " con datos de " . $textoAlternativo . " " . $titulo : "Excel descargado desde " . APP_SLOGAN . " con datos de " . $titulo;

        $objPHPExcel->getSheet(0)->setTitle($textoTituloConcatenado);

        $objPHPExcel->getProperties()->setCreator('admin')
            ->setLastModifiedBy('admin')
            ->setTitle($textoTituloConcatenado)
            ->setSubject(APP_NAME)
            ->setDescription($textoDescripcionConcatenado);
    }

    public static function applyFixedRow(PHPExcel $objPHPExcel, $column, $row)
    {
        $objPHPExcel->setActiveSheetIndex()->freezePaneByColumnAndRow($column, $row);
    }

    public static function applyZoom(PHPExcel $objPHPExcel, $zoom)
    {
        $objPHPExcel->getActiveSheet()->getSheetView()->setZoomScale($zoom);
    }

    public static function applyExcelOutput(PHPExcel $objPHPExcel, $nombreArchivo)
    {
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$nombreArchivo.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        /*
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$nombreArchivo.'.xls"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        */
    }
}
?>