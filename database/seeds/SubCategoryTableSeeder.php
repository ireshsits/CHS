<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\SubCategory;
class SubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subcategories=array(
        		array(
        				'category_id_fk'=> 1,
        				'name'=> 'Technical Error',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 1,
        				'name'=> 'Non availability of a machine',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 1,
        				'name'=> 'Non availability of function',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Efficiency',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Knowledge gap',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Staff Attitude Issues',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Facilities',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Email/Vishwa Requests Not Acknowledged',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Bank Charges',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Delay/Non attendance of customer requests',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 2,
        				'name'=> 'Staff Negligence',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Delay/Non attendance of customer requests',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Statments Delay',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'CIP/ESP Transactions',
        				'area_id_fk'=>1,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Duplicate transactions',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Card Application Process',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Card Sales',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Delaying/ Upgrading Balance transfers',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Card Delivery Related',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Rewards Points',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Offers and Promotional Campaign',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Bank Charges',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Billing Issue',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Unsuccessful POS Transaction',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Unsuccessful ECOM Transaction',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Staff Attitude Issues',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Dispute Transaction',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 3,
        				'name'=> 'Compliance Violation',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 4,
        				'name'=> 'Knowledge Gap',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 4,
        				'name'=> 'Staff Attitude Issues',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 4,
        				'name'=> 'Inability for Resolution',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 4,
        				'name'=> 'Connectivity',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 5,
        				'name'=> 'Compliance Violations',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 6,
        				'name'=> 'Interest Rates',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 6,
        				'name'=> 'Delay on processing',
        				'area_id_fk'=>1,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 6,
        				'name'=> 'Facilities Rejected',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 6,
        				'name'=> 'Bank Charges',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 7,
        				'name'=> 'Foreign Representative',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 7,
        				'name'=> 'Delay in Receipt',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 8,
        				'name'=> 'Pay App',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 8,
        				'name'=> 'Pay Easy',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 8,
        				'name'=> 'Ali Pay',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 8,
        				'name'=> 'Mobile cash',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 8,
        				'name'=> 'SVC',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 8,
        				'name'=> 'IPG',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 8,
        				'name'=> 'WePay',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 9,
        				'name'=> 'Employee Related',
        				'area_id_fk'=>2,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 9,
        				'name'=> 'Process Related',
        				'area_id_fk'=>1,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 12,
        				'name'=> 'unable to perform online transactions via Debit Card',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 13,
        				'name'=> 'Bank Charges',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 13,
        				'name'=> 'Process Related',
        				'area_id_fk'=>1,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 13,
        				'name'=> 'Vishwa unsuccesful Transactions',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 13,
        				'name'=> 'Vishwa Password Issues',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 13,
        				'name'=> 'Mobile App',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 13,
        				'name'=> 'OTP Issues',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 14,
        				'name'=> 'Others',
        				'area_id_fk'=>4,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 16,
        				'name'=> 'ATM Transaction Unsuccessful',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 16,
        				'name'=> 'KIOSK deposits Unsuccessful',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		),
        		array(
        				'category_id_fk'=> 16,
        				'name'=> 'Z reload Unsuccessful',
        				'area_id_fk'=>3,
        				'status' => 'ACT'
        		)	
        		
        );
        foreach ( $subcategories as $subcategory) {
        	$subcategory['color'] = ColorGenerator::generate();
        	SubCategory::create ( $subcategory);
        }
    }
}
