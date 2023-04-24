<?php
use Illuminate\Database\Seeder;
use App\Models\Entities\User;

class UserTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         /**
//          * CR2
//          */
//         // 1 admin
//         $admin = User::create(array(

//             'first_name' => 'Admin',
//             'last_name' => 'SITS',
//             'emp_id' => '00001',
//             'email' => 'admin@sampath.lk',
//             'password' => Hash::make('Admin@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $admin->assignRole('admin');

//         // 2 - CCC admin
//         $user2 = User::create(array(

//             'first_name' => 'Admin',
//             'last_name' => 'CCC',
//             'emp_id' => '00002',
//             'email' => 'admin_ccc@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user2->assignRole('ccc');

//         // 3 - wellawatte
//         $user3 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'One Wellawatta',
//             'emp_id' => '00003',
//             'email' => 'user1w@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user3->assignRole('user');

//         // 4 - wellawatte
//         $user4 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'Two Wellawatta',
//             'emp_id' => '00004',
//             'email' => 'user2w@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user4->assignRole('user');

//         // 5 - gampaha
//         $user5 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'One Gampaha',
//             'emp_id' => '00005',
//             'email' => 'user1g@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));

//         $user5->assignRole('user');

//         // 6 - gampaha
//         $user6 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'Two Gampaha',
//             'emp_id' => '00006',
//             'email' => 'user2g@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user6->assignRole('user');

//         // 7 - negombo
//         $user7 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'One Negombo',
//             'emp_id' => '00007',
//             'email' => 'user1ne@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user7->assignRole('user');

//         // 8 - negombo
//         $user8 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'Two Negombo',
//             'emp_id' => '00008',
//             'email' => 'user2ne@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user8->assignRole('user');

//         // 9 - nugegoda
//         $user9 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'One Nugeggoda',
//             'emp_id' => '00009',
//             'email' => 'user1nu@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user9->assignRole('user');

//         // 10 - nugegoda
//         $user10 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'Two Nugeggoda',
//             'emp_id' => '00010',
//             'email' => 'user2nu@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user10->assignRole('user');

//         // 11 - Card centre
//         $user11 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'One Card Centre',
//             'emp_id' => '00011',
//             'email' => 'user1cc@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user11->assignRole('user');

//         // 12 - Card centre
//         $user12 = User::create(array(

//             'first_name' => 'User',
//             'last_name' => 'Two Card Centre',
//             'emp_id' => '00012',
//             'email' => 'user2cc@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user12->assignRole('user');
        
//         $user13 = User::create(array(
            
//             'first_name' => 'Mnager',
//             'last_name' => 'Western Zone',
//             'emp_id' => '00013',
//             'email' => 'zone1@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user13->assignRole('user');
        
//         $user14 = User::create(array(
            
//             'first_name' => 'Mnager',
//             'last_name' => 'Sabaragamuwa Zone',
//             'emp_id' => '00014',
//             'email' => 'zone2@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user14->assignRole('user');
        
//         $user15 = User::create(array(
            
//             'first_name' => 'Manager',
//             'last_name' => 'Colombo Region',
//             'emp_id' => '00015',
//             'email' => 'region1@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user15->assignRole('user');
        
//         $user16 = User::create(array(
            
//             'first_name' => 'Manager',
//             'last_name' => 'Gampaha Region',
//             'emp_id' => '00016',
//             'email' => 'region2@sampath.lk',
//             'password' => Hash::make('Abcd@123'), // hashes our password nicely for us,
//             'active' => 1
//         ));
//         $user16->assignRole('user');

        
        /**
         * Call center test details
         * 
         */
        $user1 = User::create(array(
            
            'first_name' => 'Madhavi',
            'last_name' => 'Bandaranayake',
            'email' => 'madhavib@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user1->assignRole('admin');
        
        $user2 = User::create(array(
            
            'first_name' => 'Customer',
            'last_name' => 'Relations',
            'email' => 'custrelations@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user2->assignRole('ccc');
        
        $user3 = User::create(array(
        
            'first_name' => 'Hasitha',
            'last_name' => 'Bandara',
            'emp_id' => '61719',
            'email' => 'custserv11@sb.sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user3->assignRole('user');
        $user4 = User::create(array(
            
            'first_name' => 'Vakini',
            'last_name' => 'Visakan',
            'emp_id' => '50067',
            'email' => 'custserv4@sb.sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user4->assignRole('user');
        $user5 = User::create(array(
            
            'first_name' => 'Dilakshi',
            'last_name' => 'Perera',
            'emp_id' => '49328',
            'email' => 'custserv13@sb.sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user5->assignRole('user');
        $user6 = User::create(array(
            
            'first_name' => 'Mahendra',
            'last_name' => 'Kamal',
            'emp_id' => '72370',
            'email' => 'pcu01@sb.sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user6->assignRole('user');
        $user7 = User::create(array(
            
            'first_name' => 'Piyumika',
            'last_name' => 'Bandara',
            'emp_id' => '70971',
            'email' => 'pcu@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user7->assignRole('user');
        $user8 = User::create(array(
            
            'first_name' => 'Pramanathan',
            'last_name' => 'Pathmaruban',
            'emp_id' => '63282',
            'email' => 'premiumcards@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user8->assignRole('user');
        
        /**
         * Dummy Users
         *
         */
        $user9 = User::create(array(

            'first_name' => 'Manager',
            'last_name' => 'Zone 1',
            'emp_id' => '00009',
            'email' => 'zone1@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user9->assignRole('user');

        $user10 = User::create(array(

            'first_name' => 'Manager',
            'last_name' => 'Zone 2',
            'emp_id' => '00010',
            'email' => 'zone2@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user10->assignRole('user');

        $user11 = User::create(array(

            'first_name' => 'Manager',
            'last_name' => 'Region 1',
            'emp_id' => '00011',
            'email' => 'region1@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user11->assignRole('user');

        $user12 = User::create(array(

            'first_name' => 'Manager',
            'last_name' => 'Region 2',
            'emp_id' => '00012',
            'email' => 'region2@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user12->assignRole('user');
        
        $user13 = User::create(array(
            
            'first_name' => 'Manager',
            'last_name' => 'Region 3',
            'emp_id' => '00013',
            'email' => 'region3@sampath.lk',
            'password' => Hash::make('123123'), // hashes our password nicely for us,
            'active' => 1
        ));
        $user13->assignRole('user');
    }
}
