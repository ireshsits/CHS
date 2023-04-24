<?php

use Illuminate\Database\Seeder;
use App\Models\Entities\Category;
// use ColorGenerator;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Category::truncate();
        $categories = array(
            /**
             * Catergory level 1
             */
            array(
                'name' => 'Service Efficiency',
                'parent' => null,
                'category_level' => 1,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Staff Related',
                'parent' => null,
                'category_level' => 1,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Facilities',
                'parent' => null,
                'category_level' => 1,
                'status' => 'ACT'
            ),

            array(
                'name' => 'Others',
                'parent' => null,
                'category_level' => 1,
                'status' => 'ACT'
            ),
            
           /**
            * Category level 2
            */
            array(
                'name' => 'Compliance Related Issue',
                'parent' => 'Staff Related',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Technical Error /ATM/CDM Failures',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Issues related to outsourced staff',
                'parent' => 'Staff Related',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Security',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Attitude Issues',
                'parent' => 'Staff Related',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Bank Charges',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Card Delivery related issue',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Complaints against Team Members',
                'parent' => 'Staff Related',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Staff Negligence',
                'parent' => 'Staff Related',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Delay in processing',
                'parent' => 'Service Efficiency',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Delay in delivering',
                'parent' => 'Service Efficiency',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Dispute Transaction',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Duplicate Transaction',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Duplicate Transaction',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array(
                'name' => 'Facilities provided at Branches/Dept',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => 'Foreign Representative related issues',
                'parent' => 'Staff Related',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => 'Knowledge gap',
                'parent' => 'Staff Related',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => 'Interest Rates',
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues in Bank's statements/advices",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Advance related issues",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues on CIP/ESP Transactions",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues related to Support Staff",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Mobile Apps related issues",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Non availability of a ATM/Deposit machine",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Non availability of a function in ATM/Deposit machine",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Offers and promotional campaign",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Others",
                'parent' => 'Others',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "OTP related issues",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Process related issues",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Policies",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Process related issues",
                'parent' => 'Service Efficiency',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Rewards points related issues",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Sampath Vishwa Corporate",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "SMS related issues",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Technical Error/Failures - others",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Connectivity",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unauthorized Transactions",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transactions",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            array (
                'name' => "Vishwa Password Issues",
                'parent' => 'Facilities',
                'category_level' => 2,
                'status' => 'ACT'
            ),
            /**
             * Category level 3
             */
            array (
                'name' => "Activating SMS Alertz facility to third party mobile",
                'parent' => 'Staff Related',
                'sub_parent' => 'Compliance Related Issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Activation of bank facilities without customer consent",
                'parent' => 'Staff Related',
                'sub_parent' => 'Compliance Related Issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Activation of bank facilities without customer consent",
                'parent' => 'Staff Related',
                'sub_parent' => 'Compliance Related Issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "ATM Card not functioning",
                'parent' => 'Facilities',
                'sub_parent' => 'Technical Error /ATM/CDM Failures',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "ATM Card Retained in the machine",
                'parent' => 'Facilities',
                'sub_parent' => 'Technical Error /ATM/CDM Failures',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "ATM not functioning",
                'parent' => 'Facilities',
                'sub_parent' => 'Technical Error /ATM/CDM Failures',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Attitude Issues - Outsourced Staff & others",
                'parent' => 'Staff Related',
                'sub_parent' => 'Issues related to outsourced staff',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Attitude Issues - Security",
                'parent' => 'Facilities',
                'sub_parent' => 'Security',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Attitude Issues - Staff",
                'parent' => 'Staff Related',
                'sub_parent' => 'Attitude Issues',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Bank Charges - Advances",
                'parent' => 'Facilities',
                'sub_parent' => 'Bank Charges',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Bank Charges - Branch Operations",
                'parent' => 'Facilities',
                'sub_parent' => 'Bank Charges',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Bank Charges - Credit Card",
                'parent' => 'Facilities',
                'sub_parent' => 'Bank Charges',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Cancellation of facility without informing customers",
                'parent' => 'Staff Related',
                'sub_parent' => 'Compliance Related Issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Card Delivery related issue",
                'parent' => 'Facilities',
                'sub_parent' => 'Card Delivery related issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "CDM/KIOSK not functioning",
                'parent' => 'Facilities',
                'sub_parent' => 'Technical Error /ATM/CDM Failures',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Cheque Dep Machine not functioning",
                'parent' => 'Facilities',
                'sub_parent' => 'Technical Error /ATM/CDM Failures',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Complaints against Team Members",
                'parent' => 'Staff Related',
                'sub_parent' => 'Complaints against Team Members',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Customer's requests not attended",
                'parent' => 'Staff Related',
                'sub_parent' => 'Staff Negligence',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay at Branch counters",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in processing',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay in delivering a Credit Card",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in delivering',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay in processing - Card Application",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in processing',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay in processing - Credit Facility",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in processing',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay in processing - Customer Request",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in processing',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay in processing - Inward remittance/EREM",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in processing',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay in recieving Bank statements",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in delivering',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Delay in recieving Cr Card statements",
                'parent' => 'Service Efficiency',
                'sub_parent' => 'Delay in delivering',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Dispute Transaction on Credit Cards",
                'parent' => 'Facilities',
                'sub_parent' => 'Dispute Transaction',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Dispute Transaction on Vishwa",
                'parent' => 'Facilities',
                'sub_parent' => 'Dispute Transaction',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Divuldging Customer Information to third parties",
                'parent' => 'Staff Related',
                'sub_parent' => 'Compliance Related Issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Duplicate Transaction - Online",
                'parent' => 'Facilities',
                'sub_parent' => 'Duplicate Transaction',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Duplicate Transaction - POS",
                'parent' => 'Facilities',
                'sub_parent' => 'Duplicate Transaction',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Duplicate Transaction - Vishwa",
                'parent' => 'Facilities',
                'sub_parent' => 'Duplicate Transaction',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Enetring erroneous data in system",
                'parent' => 'Staff Related',
                'sub_parent' => 'Staff Negligence',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Facilities at bank premises- AC/Seating",
                'parent' => 'Facilities',
                'sub_parent' => 'Facilities provided at Branches/Dept',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Failure to follow bank's standard proceduers",
                'parent' => 'Staff Related',
                'sub_parent' => 'Staff Negligence',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Failure to verify KYC/CDD/TDD",
                'parent' => 'Staff Related',
                'sub_parent' => 'Compliance Related Issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Foreign Representative related issues",
                'parent' => 'Staff Related',
                'sub_parent' => 'Foreign Representative related issues',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Foreign Representative related issues",
                'parent' => 'Staff Related',
                'sub_parent' => 'Foreign Representative related issues',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Fraudulent Cash withdrawal by a third party",
                'parent' => 'Staff Related',
                'sub_parent' => 'Compliance Related Issue',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Inability for Resolution",
                'parent' => 'Staff Related',
                'sub_parent' => 'Knowledge gap',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Interest Rate - Deposit Products",
                'parent' => 'Facilities',
                'sub_parent' => 'Interest Rates',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Interest Rate - Lending Products",
                'parent' => 'Facilities',
                'sub_parent' => 'Interest Rates',
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues in Credit Card Statements",
                'parent' => 'Facilities',
                'sub_parent' => "Issues in Bank's statements/advices",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues in recovery procedures",
                'parent' => 'Facilities',
                'sub_parent' => "Advance related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues on CIP/ESP Transactions",
                'parent' => 'Facilities',
                'sub_parent' => "Issues on CIP/ESP Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues related to Card Sales Team",
                'parent' => 'Staff Related',
                'sub_parent' => "Issues related to outsourced staff",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Issues related to Security personnels",
                'parent' => 'Facilities',
                'sub_parent' => "Issues related to Support Staff",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Knowledge Gap",
                'parent' => 'Staff Related',
                'sub_parent' => "Knowledge gap",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Mobile Apps not compatible",
                'parent' => 'Facilities',
                'sub_parent' => "Mobile Apps related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Mobile Apps not functioning",
                'parent' => 'Facilities',
                'sub_parent' => "Mobile Apps related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Non availability of a ATM/Deposit machine",
                'parent' => 'Facilities',
                'sub_parent' => "Non availability of a ATM/Deposit machine",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Non availability of a function in ATM/Deposit machine",
                'parent' => 'Facilities',
                'sub_parent' => "Non availability of a function in ATM/Deposit machine",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Non availability of competent staff Tamil fluency",
                'parent' => 'Staff Related',
                'sub_parent' => "Knowledge gap",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Non availability of Tamil fluency staff",
                'parent' => 'Staff Related',
                'sub_parent' => "Knowledge gap",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Offers and promotional campaign",
                'parent' => 'Facilities',
                'sub_parent' => "Offers and promotional campaign",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Others",
                'parent' => 'Others',
                'sub_parent' => "Others",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "OTP Credit Card related issues",
                'parent' => 'Facilities',
                'sub_parent' => "OTP related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "OTP Debit Card related issues",
                'parent' => 'Facilities',
                'sub_parent' => "OTP related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "OTP Vishwa related issues",
                'parent' => 'Facilities',
                'sub_parent' => "OTP related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Parking facility - issues",
                'parent' => 'Facilities',
                'sub_parent' => "Facilities provided at Branches/Dept",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Pawning related issues",
                'parent' => 'Facilities',
                'sub_parent' => "Process related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Pawning related issues",
                'parent' => 'Facilities',
                'sub_parent' => "Process related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Policies",
                'parent' => 'Facilities',
                'sub_parent' => "Policies",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Process related issues",
                'parent' => 'Service Efficiency',
                'sub_parent' => "Process related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Providing wrong information",
                'parent' => 'Staff Related',
                'sub_parent' => "Staff Negligence",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Recoveries related issues (Advances/Credit Cards)",
                'parent' => 'Service Efficiency',
                'sub_parent' => "Process related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Reward points - cancelled",
                'parent' => 'Facilities',
                'sub_parent' => "Rewards points related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Reward points - error in value",
                'parent' => 'Facilities',
                'sub_parent' => "Rewards points related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Reward points - unable to redeem",
                'parent' => 'Facilities',
                'sub_parent' => "Rewards points related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Reward points - vouchers not delivered",
                'parent' => 'Facilities',
                'sub_parent' => "Rewards points related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Sampath Vishwa Corporate",
                'parent' => 'Facilities',
                'sub_parent' => "Sampath Vishwa Corporate",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "SMS late delivery",
                'parent' => 'Facilities',
                'sub_parent' => "SMS related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "SMS on balances not received",
                'parent' => 'Facilities',
                'sub_parent' => "SMS related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "SMS on transactions not received",
                'parent' => 'Facilities',
                'sub_parent' => "SMS related issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Staff Negligence",
                'parent' => 'Staff Related',
                'sub_parent' => "Staff Negligence",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Technical Error/Failures",
                'parent' => 'Facilities',
                'sub_parent' => "Technical Error/Failures - others",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unable to contact Branch",
                'parent' => 'Facilities',
                'sub_parent' => "Connectivity",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unable to contact CCC",
                'parent' => 'Facilities',
                'sub_parent' => "Connectivity",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unable to contact Department",
                'parent' => 'Facilities',
                'sub_parent' => "Connectivity",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unauthorized Transaction - Credit Card",
                'parent' => 'Facilities',
                'sub_parent' => "Unauthorized Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unauthorized Transaction - Debit Card",
                'parent' => 'Facilities',
                'sub_parent' => "Unauthorized Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unauthorized Transaction - Mobile Apps",
                'parent' => 'Facilities',
                'sub_parent' => "Unauthorized Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unauthorized Transaction - Vishwa",
                'parent' => 'Facilities',
                'sub_parent' => "Unauthorized Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - Online",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - POS",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Cash Deposit in CDM",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - ATM",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - IPG",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - PayEasy",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - WePAy",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - Z-Reload",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Unsuccessful Transaction - Vishwa",
                'parent' => 'Facilities',
                'sub_parent' => "Unsuccessful Transactions",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Vishwa Password Issues",
                'parent' => 'Facilities',
                'sub_parent' => "Vishwa Password Issues",
                'category_level' => 3,
                'status' => 'ACT'
            ),
            array (
                'name' => "Wrong information provided",
                'parent' => 'Staff Related',
                'sub_parent' => "Knowledge gap",
                'category_level' => 3,
                'status' => 'ACT'
            )
        );
        
        foreach ($categories as $category){
            $category['color'] = ColorGenerator::generate();
            if(!is_null($category['parent'])){
                $parentCategory = Category::whereRaw('UPPER(name) like ? AND category_level = ?', array( 'name' => strtoupper($category['parent']), 'category_level' => 1))->first();
                if($parentCategory){
                    if(isset($category['sub_parent'])){
                        $subParentCategory = Category::whereRaw('UPPER(name) like ? AND category_level = ?', array( 'name' => strtoupper($category['sub_parent']), $category['category_level'] - 1))->first();
                        if($subParentCategory){
                            //Detailed sub category level
                            $newCategory = Category::whereRaw('UPPER(name) like ? AND category_level = ?', array( 'name' => strtoupper($category['name']), 'category_level' => $category['category_level']))->first();
                            if(!$newCategory){
                                $newCategory = new Category();
                            }
                            $newCategory->fill([
                                'name' => $category['name'],
                                'color' => $category['color'],
                                'parent_category_id' => $subParentCategory->category_id_pk,
                                'category_level' => $category['category_level'],
                                'status' => $category['status']
                            ])->save(); 
                        }
                    }else{
                        //Sub category level
                        $newCategory = Category::whereRaw('UPPER(name) like ? AND category_level = ?', array( 'name' => strtoupper($category['name']), 'category_level' => $category['category_level']))->first();
                        if(!$newCategory){
                            $newCategory = new Category();
                        }                            
                        $newCategory->fill([
                            'name' => $category['name'],
                            'color' => $category['color'],
                            'parent_category_id' => $parentCategory->category_id_pk,
                            'category_level' => $category['category_level'],
                            'status' => $category['status']
                        ])->save(); 
                    }
                }
            }else{
                //Category level
                Category::create([
                    'name' => $category['name'],
                    'color' => $category['color'],
                    'parent_category_id' => null,
                    'category_level' => $category['category_level'],
                    'status' => $category['status']
                ]);
            }
        }
    }
}
