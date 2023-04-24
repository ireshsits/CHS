<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Analysis;

class AnalysesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $analyses = array(
            array(
                'name' => 'MODE WISE ANALYSIS',
                'analysis_category_id_fk' => 1,
                'code' => 'MWA'
            ),
            array(
                'name' => 'CATEGORY WISE ANALYSIS',
                'analysis_category_id_fk' => 1,
                'code' => 'CWA'
            ),
            array(
                'name' => 'AREA WISE ANALYSIS',
                'analysis_category_id_fk' => 1,
                'code' => 'AWA'
            ),
            array(
                'name' => 'DETAILED CATEGORY WISE ANALYSIS',
                'analysis_category_id_fk' => 2,
        	    'code' => 'DCWA'
            ),
            array(
                'name' => 'DETAILED SUB CATEGORY WISE ANALYSIS',
                'analysis_category_id_fk' => 2,
                'code' => 'DSCWA'
            ),
            array(
                'name' => 'IN DETAILED SUB CATEGORY WISE ANALYSIS',
                'analysis_category_id_fk' => 2,
                'code' => 'IDSCWA',
                'status' => 'INACT'
            ),
            array (
                'name' => 'DETAILED AREA WISE ANALYSIS',
                'analysis_category_id_fk' => 2,
                'code' => 'DAWA'
            ),
            array (
                'name' => 'RESOLUTION AUTHORITY',
                'analysis_category_id_fk' => 3,
                'code' => 'RA'
            ),
            array (
                'name' => 'TIME FRAME OF COMPLAINT RESOLUTION',
                'analysis_category_id_fk' => 3,
                'code' => 'TFCR'
            ),
            array (
                'name' => 'ZONAL WISE ANALYSIS',
                'analysis_category_id_fk' => 4,
                'code' => 'ZWA'
            ),
            array (
                'name' => 'REGIONAL WISE ANALYSIS',
                'analysis_category_id_fk' => 4,
                'code' => 'RWA'
            ),
            array (
                'name' => 'REGIONAL WISE DETAILED ANALYSIS',
                'analysis_category_id_fk' => 4,
                'code' => 'RWDA'
            ),
            array (
                'name' => 'REGIONAL WISE ANALYSIS WITH BRANCHES',
                'analysis_category_id_fk' => 4,
                'code' => 'RWAWB'
            ),
            array (
                'name' => 'BRANCH WISE ANALYSIS',
                'analysis_category_id_fk' => 4,
                'code' => 'BWA'
            ),
            array (
                'name' => 'MODE',
                'analysis_category_id_fk' => 5,
                'code' => 'MG'
            ),
            array (
                'name' => 'CATEGORY',
                'analysis_category_id_fk' => 5,
                'code' => 'CG'
            ),
            
//     			array (
//     					'name' => 'COMPLAINTS RECEIVED THROUGH TOP MANAGMENT',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'CRTTM'
//     			),
//     			array (
//     					'name' => 'COMPLAINTS RECEIVED THROUGH CARD CENTER HOT LINES - (Handle by CCC)',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'CRTCCHL'
//     			),
//     			array (
//     					'name' => 'DETAILED ANALYSIS OF COMPLAINTS RECEIVED THROUGH CARD CENTER HOT LINES',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'DACRTCCHL'
//     			),
//     			array (
//     					'name' => 'AREA-WISE OVERALL DETAILED ANALYSIS',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'AWODA'
//     			),
//     			array (
//     					'name' => 'BRANCH SERVICE LAPSES',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'BSL',
//     					'status' => 'INACT'
//     			),
//     			array (
//     					'name' => 'BRANCH SERVICE LAPSES - Zonal wise/ Region wise Monthly Comparison',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'BSLZR'
//     			),
//     			array (
//     					'name' => 'CARD CENTRE',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'CC',
//     					'status' => 'INACT'
//     			),
//     			array (
//     					'name' => 'ANALYSIS ON RESOLUTION',
//     					'analysis_category_id_fk' => 1,
//     					'code' => 'RA'
//     			),
//     			array (
//     					'name' => 'THROUGH EMAIL',
//     					'analysis_category_id_fk' => 2,
//     					'code' => 'TE'
//     			),
//     			array (
//     					'name' => 'THROUGH CSAT RATINGS',
//     					'analysis_category_id_fk' => 2,
//     					'code' => 'TCR'
//     			)
    	);
    	foreach ( $analyses as $analysis) {
    		Analysis::create($analysis);
    	}
    }
}
