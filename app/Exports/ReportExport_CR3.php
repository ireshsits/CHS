<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use \Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ReportExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithEvents, WithColumnFormatting, WithStrictNullComparison, WithCustomValueBinder{
	use Exportable;
	protected $complaints;
	protected $filters;
	protected $categoryLevels;
	public function __construct($complaints, $filters, $categoryLevels) {
		$this->complaints = $complaints;
		$this->filters = $filters;
		$this->categoryLevels = $categoryLevels;
	}
	public function view(): View {
        return view('dashboard.reports.export_table_component', [
            'complaints' => $this->complaints,
            'title' => 'CUSTOMER COMPLAINTS / COMPLIMENTS',
            'filters' => $this->filters,
            'category_levels' => $this->categoryLevels
        ]);
	}
	public function bindValue(Cell $cell, $value)
	{
		if (is_numeric($value)) {
			$cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
			
			return true;
		}
		
		// else return default behavior
		return parent::bindValue($cell, $value);
	}
	public function columnFormats(): array
	{
		return [
				'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
// 				'J' => NumberFormat::FORMAT_DATE_DDMMYYYY,
				'K' => NumberFormat::FORMAT_DATE_DDMMYYYY,
				'L' => NumberFormat::FORMAT_DATE_DDMMYYYY,
				'M' => NumberFormat::FORMAT_DATE_DDMMYYYY,
				'N' => NumberFormat::FORMAT_DATE_DDMMYYYY
		];
	}
	public function registerEvents(): array {
		return [ 
				BeforeExport::class => function (BeforeExport $event) {
					$event->writer->setCreator ( 'Sampath IT Solutions (PVT) Ltd' );
				},
				AfterSheet::class => function (AfterSheet $event) {
					$event->sheet->setOrientation ( PageSetup::ORIENTATION_LANDSCAPE );
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
					$event->sheet->getDelegate ()->getStyle ( 'A3:' . $event->sheet->getDelegate ()->getHighestColumn () . '3' )->applyFromArray ( [ 
							'font' => [ 
									'name' => 'Calibri',
									'size' => 12,
									'bold' => true,
									'color' => [ 
											'argb' => '000000' 
									] 
							] 
					] );
					$event->sheet->styleCells ( 'A6:' . $event->sheet->getDelegate ()->getHighestColumn () . $event->sheet->getDelegate ()->getHighestRow (), [ 
							'font' => [ 
									'name' => 'Calibri',
									'size' => 10,
									'color' => [ 
											'argb' => '000000' 
									] 
							] 
					] );
					$event->sheet->getDelegate ()->getStyle ( 'A6:' . $event->sheet->getDelegate ()->getHighestColumn () . '6' )->getFill ()->setFillType ( Fill::FILL_SOLID )->getStartColor ()->setARGB ( 'b7def3' );
					$event->sheet->getDelegate ()->getStyle ( 'A6:' . $event->sheet->getDelegate ()->getHighestColumn () . '6' )->applyFromArray ( [ 
							'font' => [ 
									'bold' => true 
							] 
					] );
					/**
					 * Set the border of all cells
					 */
					$event->sheet->getDelegate ()->getStyle ('A6:'.$event->sheet->getDelegate ()->getHighestColumn () . $event->sheet->getDelegate ()->getHighestRow ())->applyFromArray ( [
					    'borders' => [
					        'allBorders' => [
					            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					            'color'       => ['argb' => '000000'],
					        ],
					    ]
					]);
				} 
		];
	}
}
