<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
Use \Maatwebsite\Excel\Sheet;
use \Maatwebsite\Excel\Writer;

class ExcelMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    	Writer::macro('setCreator', function (Writer $writer, string $creator) {
    		$writer->getDelegate()->getProperties()->setCreator($creator);
    	});
    	
    	Sheet::macro ('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
    		$sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
    	});
		Sheet::macro ( 'setOrientation', function (Sheet $sheet, $orientation) {
			$sheet->getDelegate ()->getPageSetup ()->setOrientation ( $orientation );
		} );
		
		Writer::macro('setIncludeCharts', function (Writer $writer, bool $includeChart) {
			$writer->setIncludeCharts($includeChart);
		});
    }
}
