<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use App\Models\Entities\Setting;
use ExcelTitleHelper;

class AnalysisExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithCharts, WithStrictNullComparison, WithCustomStartCell, WithTitle{
	use Exportable;
	protected $complaints;
	protected $startingRow;
	protected $chartDataRow;
	protected $chartViewStartRow;
	protected $chartViewEndRow;
	protected $filters;
	public function __construct($complaints, $filters) {
		$this->complaints = $complaints;
		$this->filters = $filters;
		$this->excelSetting = Setting::getExcelSettings();
		$this->startingRow = $this->excelSetting->STARTING_ROW;
		$this->chartDataRow = $this->excelSetting->CHART_DATA_ROW;
		$this->chartViewStartRow = $this->excelSetting->CHART_VIEW_START_ROW;
		$this->chartViewEndRow = $this->excelSetting->CHART_VIEW_END_ROW;
	}
	public function collection() {
		return collect ( $this->complaints ['tableDataSet']??[] );
	}
	
	public function headings(): array {
		return $this->complaints ['headerRow']??[];
	}
	
	public function startCell(): string
	{
		return 'A'.$this->startingRow;
	}
	
	public function title(): string
	{
		return $this->filters['code'];
	}
	
	/**
	 * Dummy chart to implement withChart abstract method.
	 * This will fix error thrown.
	 * @return Chart|Chart[]
	 */
  	public function charts() {
 		$label = [ new DataSeriesValues (DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$Z$1', null, 1 ) ];
 		$categories = [ new DataSeriesValues (DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$X$2:$X$3', null, 2 ) ];
 		$values = [ new DataSeriesValues ( DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$Z$2:$Z$3', null, 2 ) ];
		
		$series = new DataSeries ( DataSeries::TYPE_PIECHART, null, range ( 0, \count ( $values ) - 1 ), $label, $categories, $values );
		
		$layout = new Layout();
		$layout->setShowVal(true);
		$layout->setShowPercent(true);
		
		$plot = new PlotArea ( $layout, [$series] );
		
		$legend = new Legend (Legend::POSITION_RIGHT, null, false);
		$chart = new Chart ( 'chart name', new Title ( ExcelTitleHelper::getTitle($this->filters)), $legend, $plot );
		
		$chart->setTopLeftPosition ( 'Z1' );
		$chart->setBottomRightPosition ( 'Z1' );
		
		return $chart;
	}
	
	/**
	 *
	 * @return array
	 */
	public function registerEvents(): array {
		return [
				
				// Array callable, refering to a static method.
				BeforeWriting::class => [self::class, 'beforeWriting'],
				
				AfterSheet::class => function (AfterSheet $event) {
					/**
					 * Merge Cells.
					 */
					if(in_array($this->filters['code'], ['AWODA','BSLZR', 'RA', 'TFCR']) && isset($this->complaints ['mergeCells'])){
						foreach ($this->complaints ['mergeCells'] as $merge){
							$event->sheet->mergeCells($merge);
						}
					}
					
					$event->sheet->setCellValue('A1', ExcelTitleHelper::getTitle($this->filters));
					$event->sheet->getDelegate ()->mergeCells ( 'A1:' . $event->sheet->getDelegate ()->getHighestColumn () . '1' );
					$event->sheet->getDelegate ()->getStyle ( 'A1:' . $event->sheet->getDelegate ()->getHighestColumn () . '1' )->applyFromArray ( [
							'font' => [
									'name' => 'Calibri',
									'size' => 15,
									'bold' => true,
									'color' => [
											'argb' => '000000'
									]
							],
							'alignment' => [
									'horizontal' => Alignment::HORIZONTAL_LEFT
							]
					] );
					
					/**
					 * Header Row
					 */
					$event->sheet->getDelegate ()->getStyle ( 'A'.$this->startingRow.':' . $event->sheet->getDelegate ()->getHighestColumn () . $this->startingRow)->getFill ()->setFillType ( Fill::FILL_SOLID )->getStartColor ()->setARGB ($this->getHeaderRowColor($this->filters['code']));
					$event->sheet->getDelegate ()->getStyle ( 'A'.$this->startingRow.':' . $event->sheet->getDelegate ()->getHighestColumn () . $this->startingRow)->applyFromArray ( [
							'font' => [
									'bold' => true
							]
					] );
					/**
					 * Total Row
					 * RA total row is not the last row.
					 */
					if(in_array($this->filters['code'], ['RA'])){
					    $event->sheet->getDelegate ()->getStyle ( 'A'.$this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow (),3).':' . $event->sheet->getDelegate ()->getHighestColumn () . $this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow (),3))->getFill ()->setFillType ( Fill::FILL_SOLID )->getStartColor ()->setARGB ($this->getHeaderRowColor($this->filters['code']));
					    $event->sheet->getDelegate ()->getStyle ( 'A'.$this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow (),3).':' . $event->sheet->getDelegate ()->getHighestColumn () . $this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow (),3))->applyFromArray ( [
    					    'font' => [
    					        'bold' => true
    					    ]
    					] );
					}else{
					    $event->sheet->getDelegate ()->getStyle ( 'A'.$event->sheet->getDelegate ()->getHighestRow ().':' . $event->sheet->getDelegate ()->getHighestColumn () . $event->sheet->getDelegate ()->getHighestRow ())->getFill ()->setFillType ( Fill::FILL_SOLID )->getStartColor ()->setARGB ($this->getHeaderRowColor($this->filters['code']));
					    $event->sheet->getDelegate ()->getStyle ( 'A'.$event->sheet->getDelegate ()->getHighestRow ().':' . $event->sheet->getDelegate ()->getHighestColumn () . $event->sheet->getDelegate ()->getHighestRow ())->applyFromArray ( [
					        'font' => [
					            'bold' => true
					        ]
					    ] );
					}
					/**
					 * Set the border of all cells
					 */
					$event->sheet->getDelegate ()->getStyle ('A'.$this->startingRow.':'.$event->sheet->getDelegate ()->getHighestColumn () . $event->sheet->getDelegate ()->getHighestRow ())->applyFromArray ( [
					        'borders' => [
					            'allBorders' => [
					                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					                'color'       => ['argb' => '000000'],
					            ],
					        ]
					    ]);
					
					/**
					 * Set colors accordind to the chart type
					 */
					if(in_array($this->filters['code'], ['RA','TFCR']) && isset($this->complaints ['colorCells'])) {
					    foreach ($this->complaints ['colorCells'] as $colorSet){
					        if(is_array($colorSet)){
					            $range = $colorSet['range']; $color = $colorSet['color'];
					        }else if(is_object($colorSet)){
					            $range =  $colorSet->range; $color = $colorSet->color;
					        }
					        $event->sheet->getDelegate ()->getStyle ($range)->getFill ()->setFillType ( Fill::FILL_SOLID )->getStartColor ()->setARGB ($color);
					    }
					}
					
					/**
					 * Display Chart
					 * @var array $label
					 * hide chart for selected analyses and if no data.
					 */
					$sum = 0;
					if(is_array($this->complaints['chartDataSet'])){
						$sum = $this->complaints['chartDataSet']['data'];
					}else if(is_object($this->complaints['chartDataSet'])){
						$sum = $this->complaints['chartDataSet']->data;
					}
					if(!in_array($this->filters['code'], ['CRTTM','TFCR']) && array_sum($sum) > 0){
					    /**
					     * Custom Colors. Not working currently
					     */
					    foreach ($this->complaints ['chartColors'] as $color){
					        $colors[] = ltrim($color, '#');
					    }
					    
						$label = [ new DataSeriesValues ( DataSeriesValues::DATASERIES_TYPE_STRING, $this->filters['code'].'!$'.$event->sheet->getDelegate ()->getHighestColumn ().'$'.$this->startingRow, null, 1 ) ]; //Worksheet!$K$2 , null, 1
						$categories = [ new DataSeriesValues ( DataSeriesValues::DATASERIES_TYPE_STRING, $this->filters['code'].'!$A$'.($this->chartDataRow).':$A$'.$this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow ()), null, $this->getRowCount($this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow ())) ) ]; //Worksheet!$A$3:$A$6, null, 4
						$values = [ new DataSeriesValues ( DataSeriesValues::DATASERIES_TYPE_NUMBER, $this->filters['code'].'!$'.$event->sheet->getDelegate ()->getHighestColumn ().'$'.$this->chartDataRow.':$'.$event->sheet->getDelegate ()->getHighestColumn ().'$'.$this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow ()), null, $this->getRowCount($this->getHightestRowPrev($event->sheet->getDelegate ()->getHighestRow ())), [], null, $colors??[]) ]; //Worksheet!$K$3:$K$6, null, 4, [], null, $colors
						
						$series = new DataSeries ( DataSeries::TYPE_PIECHART, null, range ( 0, \count ( $values ) - 1 ), $label, $categories, $values );
						
						$layout = new Layout();
						$layout->setShowVal(false); // display value in the chart
						$layout->setShowPercent(true); //display percent in the chart
						$layout->setShowBubbleSize(false); //the bubble size should be shown in data labels
						$layout->setShowCatName(false); //Specifies that the category name should be shown in data labels
						$layout->setShowSerName(false); //Specifies that the series name should be shown in data labels
						$layout->setShowLegendKey(true); //Specifies that legend keys should be shown in data labels
						
						$plot = new PlotArea ( $layout, [$series] );
						
						$legend = new Legend (Legend::POSITION_RIGHT, null, false);
						$chart = new Chart ( 'chart name', new Title ( $this->filters['code']), $legend, $plot, true, DataSeries::EMPTY_AS_GAP, null, null);
						
						$chart->setTopLeftPosition ( $this->getHightColumnNext($event->sheet->getDelegate ()->getHighestColumn (),2).$this->chartViewStartRow);
						$chart->setBottomRightPosition ( $this->getHightColumnNext($event->sheet->getDelegate ()->getHighestColumn (),11).$this->chartViewEndRow);
						
						$event->sheet->getDelegate ()->addChart ( $chart );
					}
					/**
					 * End Display Chart
					 */
				} 
		];
	}
	
	public static function beforeWriting(BeforeWriting $event)
	{
		$event->writer->setIncludeCharts(true);
	}
	
	private function getHightColumnNext($column,$increasedBy){
		$alphas = range('A', 'Z');
		return $alphas[array_search($column,$alphas)+$increasedBy];
	}
	private function getHightColumnPrev($column,$descreasedBy){
	    $alphas = range('A', 'Z');
	    return $alphas[array_search($column,$alphas)-$descreasedBy];
	}
	private function getHightestRowNext($row,$increasedBy){
	    return $row+$increasedBy;
	}
	private function getHightestRowPrev($row,$descreasedBy=1){
	    return (int)($row-$descreasedBy);
	}
	private function getHeaderRowColor($code){
	    switch($code){
	       case 'RA'   : return 'CCCCCC';
	       case 'TFCR' : return 'CCCCCC';
	       default     : return 'b7def3';
	    }
	}
	
	/**
	 * A3:A7 (label range)
	 * @param unknown $to
	 * @return number
	 */
	private function getRowCount($to){
		return (int) $to - (int) $this->chartDataRow; 
	}
}
