<?php

use Illuminate\Database\Seeder;



class SitesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sites')
        ->insert([
                ['username' => 'art_1001','sitecode' => '1001','province' => 'Kratie', 'name' => 'Kratie RH', 'site' => 'មន្ទីរពេទ្យខេត្តក្រចេះ'],
                ['username' => 'art_101','sitecode' => '101','province' => 'Banteay Meanchey', 'name' => 'Serey Sophon RH', 'site' => 'មន្ទីរពេទ្យបង្អែក សិរីសោភ័ណ'],
                ['username' => 'art_102','sitecode' => '102','province' => 'Banteay Meanchey', 'name' => 'Mongkul Borey PH', 'site' => 'មន្ទីរ​ពេទ្យ​បង្អែក​មិត្តភាព កម្ពុជា-ជប៉ុន មង្គលបូ...'],
                ['username' => 'art_103','sitecode' => '103','province' => 'Banteay Meanchey', 'name' => 'Poi Pet RH', 'site' => 'មន្ទីរពេទ្យបង្អែក ប៉ោយប៉ែត'],
                ['username' => 'art_104','sitecode' => '104','province' => 'Banteay Meanchey', 'name' => 'Thmor Pouk RH', 'site' => 'មន្ទីរពេទ្យបង្អែកថ្មពួក'],
                ['username' => 'art_105','sitecode' => '105','province' => 'Banteay Meanchey', 'name' => 'Preah Net Preah RH', 'site' => 'មន្ទីរ​ពេទ្យ​បង្អែក​ព្រះនេត្រព្រះ'],
                ['username' => 'art_1101','sitecode' => '1101','province' => 'Mondul Kiri', 'name' => 'Mondulkiri PH', 'site' => 'មន្ទីរ​ពេទ្យបង្អែកខេត្តមណ្ឌលគីរី'],
                ['username' => 'art_1201','sitecode' => '1201','province' => 'Phnom Penh', 'name' => 'Khmer Soviet Friendship Hospital', 'site' => 'មន្ទីរពេទ្យមិត្តភាពខ្មែរ-សូវៀត (ពេទ្យរុស្សី)'],
                ['username' => 'art_1202','sitecode' => '1202','province' => 'Phnom Penh', 'name' => 'Calmette Hospital', 'site' => 'មន្ទីរពេទ្យកាល់ម៉ែត'],
                ['username' => 'art_1203','sitecode' => '1203','province' => 'Phnom Penh', 'name' => 'Preah Kossamak Hospital', 'site' => 'មន្ទីរពេទ្យព្រះកុសុមៈ (ពេទ្យលោកសង្ឃ)'],
                ['username' => 'art_1204','sitecode' => '1204','province' => 'Phnom Penh', 'name' => 'National Pediatric Hospital', 'site' => 'មន្ទីរពេទ្យកុមារជាតិ'],
                ['username' => 'art_1205','sitecode' => '1205','province' => 'Phnom Penh', 'name' => 'Hope Center', 'site' => 'មន្ទីរ​ពេទ្យ​ព្រះសីហនុ មណ្ឌលនៃក្តីសង្ឃឹម'],
                ['username' => 'art_1207','sitecode' => '1207','province' => 'Phnom Penh', 'name' => 'Preah ketomealea Hospital', 'site' => 'មន្ទីរពេទ្យព្រះកេតុមាលា (ពេទ្យទាហាន​១៧៩)'],
                ['username' => 'art_1208','sitecode' => '1208','province' => 'Phnom Penh', 'name' => 'Social Health Clinic', 'site' => 'គ្លីនិកសុខភាពសង្គម'],
                ['username' => 'art_1209','sitecode' => '1209','province' => 'Phnom Penh', 'name' => 'Chhouk Sar Clinic', 'site' => 'សមាគមឈូកស​'],
                ['username' => 'art_1211','sitecode' => '1211','province' => 'Phnom Penh', 'name' => 'Meanchey RH', 'site' => 'មន្ទីរពេទ្យបង្អែកមានជ័យ'],
                ['username' => 'art_1212','sitecode' => '1212','province' => 'Phnom Penh', 'name' => 'Sam Dach Ov RH', 'site' => 'មន្ទីរពេទ្យបង្អែកសម្តេចឪសម្តេចម៉ែ'],
                ['username' => 'art_1213','sitecode' => '1213','province' => 'Phnom Penh', 'name' => 'Dangkoa RH', 'site' => 'មន្ទីរពេទ្យបង្អែកដង្កោ'],
                ['username' => 'art_1214','sitecode' => '1214','province' => 'Phnom Penh', 'name' => 'Pochin Tong RH', 'site' => 'មន្ទីរពេទ្យបង្អែកពោធិ៍ចិនតុង'],
                ['username' => 'art_1301','sitecode' => '1301','province' => 'Preah Vihear', 'name' => '16 Makara PH', 'site' => 'មន្ទីរ​ពេទ្យ​បង្អែក​ខេត្ត ១៦មករា'],
                ['username' => 'art_1401','sitecode' => '1401','province' => 'Prey Veng', 'name' => 'Neak Loeung RH', 'site' => 'មន្ទីរពេទ្យបង្អែកអ្នកលឿង'],
                ['username' => 'art_1402','sitecode' => '1402','province' => 'Prey Veng', 'name' => 'Prey Veng PH', 'site' => 'មន្ទីរពេទ្យខេត្តព្រៃវែង'],
                ['username' => 'art_1403','sitecode' => '1403','province' => 'Prey Veng', 'name' => 'Pearaing RH', 'site' => 'មន្ទីរពេទ្យបង្អែកពារាំង'],
                ['username' => 'art_1501','sitecode' => '1501','province' => 'Pursat', 'name' => 'Sampov Meas PH', 'site' => 'មន្ទីរពេទ្យបង្អែកសំពៅមាស'],
                ['username' => 'art_1502','sitecode' => '1502','province' => 'Pursat', 'name' => 'Phnom Kravanh RH', 'site' => 'មន្ទីរពេទ្យបង្អែកភ្នំក្រវ៉ាញ'],
                ['username' => 'art_1503','sitecode' => '1503','province' => 'Pursat', 'name' => 'Bakan RH', 'site' => 'មន្ទីរពេទ្យបង្អែកបាកាន'],
                ['username' => 'art_1601','sitecode' => '1601','province' => 'Ratanakiri', 'name' => 'Ratanakiri PH', 'site' => 'មន្ទីរពេទ្យបង្អែកខេត្តរតនៈគីរី'],
                ['username' => 'art_1702','sitecode' => '1702','province' => 'Siem Reap', 'name' => 'Siem Reap PH', 'site' => 'មន្ទីរពេទ្យបង្អែកសៀមរាប'],
                ['username' => 'art_1703','sitecode' => '1703','province' => 'Siem Reap', 'name' => 'Komar Angkor Hospital', 'site' => 'មន្ទីរពេទ្យកុមារអង្គរ'],
                ['username' => 'art_1704','sitecode' => '1704','province' => 'Siem Reap', 'name' => 'Sot Nikum RH', 'site' => 'មន្ទីរពេទ្យបង្អែកសូទ្រនិគម'],
                ['username' => 'art_1705','sitecode' => '1705','province' => 'Siem Reap', 'name' => 'Kralanh RH', 'site' => 'មន្ទីរពេទ្យបង្អែកក្រឡាញ់'],
                ['username' => 'art_1801','sitecode' => '1801','province' => 'Preah Sihanouk', 'name' => 'Preah Sihanouk PH', 'site' => 'មន្ទីរពេទ្យបង្អែកខេត្តព្រះសីហនុ'],
                ['username' => 'art_1901','sitecode' => '1901','province' => 'Stung Treng', 'name' => 'Stung Treng PH', 'site' => 'មន្ទីរពេទ្យបង្អែកខេត្តស្ទឹងត្រែង'],
                ['username' => 'art_2001','sitecode' => '2001','province' => 'Svay Rieng', 'name' => 'Svay Rieng PH', 'site' => 'មន្ទីរពេទ្យខេត្តស្វាយរៀង'],
                ['username' => 'art_2002','sitecode' => '2002','province' => 'Svay Rieng', 'name' => 'Romeas Hek RH', 'site' => 'មន្ទីរពេទ្យបង្អែករមាសហែក'],
                ['username' => 'art_201','sitecode' => '201','province' => 'Battambang', 'name' => 'Maung Russey RH', 'site' => 'មន្ទីរពេទ្យបង្អែកមោងឫស្សី'],
                ['username' => 'art_202','sitecode' => '202','province' => 'Battambang', 'name' => 'Battambang PH', 'site' => 'ន្ទីរ​ពេទ្យបង្អែកខេត្តបាត់ដំបង'],
                ['username' => 'art_203','sitecode' => '203','province' => 'Battambang', 'name' => 'Sampov Loun RH', 'site' => 'មន្ទីរពេទ្យបង្អែកសំពៅលូន'],
                ['username' => 'art_204','sitecode' => '204','province' => 'Battambang', 'name' => 'Thmor Kol RH', 'site' => 'មន្ទីរពេទ្យបង្អែកថ្មគោល'],
                ['username' => 'art_205','sitecode' => '205','province' => 'Battambang', 'name' => 'R5 Military Hospital', 'site' => 'មន្ទីរពេទ្យយោធិភាគទី5 (ពេទ្យព2)'],
                ['username' => 'art_206','sitecode' => '206','province' => 'Battambang', 'name' => 'Roka RH', 'site' => 'មន្ទីរពេទ្យបង្អែករកា'],
                ['username' => 'art_2101','sitecode' => '2101','province' => 'Takeo', 'name' => 'Takeo PH', 'site' => 'មន្ទីរពេទ្យខេត្តតាកែវ'],
                ['username' => 'art_2102','sitecode' => '2102','province' => 'Takeo', 'name' => 'Kirivong RH', 'site' => 'មន្ទីរពេទ្យបង្អែកគីរីវង់'],
                ['username' => 'art_2103','sitecode' => '2103','province' => 'Takeo', 'name' => 'Ang Roka RH', 'site' => 'មន្ទីរពេទ្យបង្អែកអង្គរកា'],
                ['username' => 'art_2104','sitecode' => '2104','province' => 'Takeo', 'name' => 'Prey Kabass RH', 'site' => 'មន្ទីរបង្អែកព្រៃកប្បាស'],
                ['username' => 'art_2201','sitecode' => '2201','province' => 'Oddar Meanchey', 'name' => 'Samraong RH', 'site' => 'មន្ទីរពេទ្យបង្អែកខេត្តឧត្តរមានជ័យ'],
                ['username' => 'art_2202','sitecode' => '2202','province' => 'Oddar Meanchey', 'name' => 'Anlong Veng RH', 'site' => 'មន្ទីរពេទ្យបង្អែកអន្លង់វែង'],
                ['username' => 'art_2301','sitecode' => '2301','province' => 'Kep', 'name' => 'Kep PH', 'site' => 'មន្ទីរពេទ្យ ខេត្តកែប'],
                ['username' => 'art_2401','sitecode' => '2401','province' => 'Pailin', 'name' => 'Pailin PH', 'site' => 'មន្ទីរពេទ្យបង្អែកខេត្តប៉ៃលិន'],
                ['username' => 'art_301','sitecode' => '301','province' => 'Kampong Cham', 'name' => 'Kampong Cham PH', 'site' => 'មន្ទីររពេទ្យបង្អែកខេត្តកំពង់ចាម'],
                ['username' => 'art_302','sitecode' => '302','province' => 'Tbong Khmum', 'name' => 'Memut RH', 'site' => 'មន្ទីរពេទ្យបង្អែកមេមត់'],
                ['username' => 'art_303','sitecode' => '303','province' => 'Tbong Khmum', 'name' => 'Tbong Khmum RH', 'site' => 'មន្ទីរពេទ្យបង្អែក ព្រះនរោត្តមសីហនុ ត្បូងឃ្មុំ'],
                ['username' => 'art_304','sitecode' => '304','province' => 'Kampong Cham', 'name' => 'Choeung Prey RH', 'site' => 'មន្ទីរពេទ្យបង្អែកជើងព្រៃ'],
                ['username' => 'art_305','sitecode' => '305','province' => 'Kampong Cham', 'name' => 'Srey Santhor RH', 'site' => 'មន្ទីរពេទ្យបង្អែកស្រីសន្ធរ'],
                ['username' => 'art_306','sitecode' => '306','province' => 'Kampong Cham', 'name' => 'Chamkar Leu RH', 'site' => 'មន្ទីរ​ពេទ្យ​បង្អែកចំការលើ'],
                ['username' => 'art_307','sitecode' => '307','province' => 'Kampong Cham', 'name' => 'Batheay RH', 'site' => 'មន្ទីរពេទ្យបង្អែកបាធាយ'],
                ['username' => 'art_401','sitecode' => '401','province' => 'Kampong Chhnang', 'name' => 'Kampong Chhnang PH', 'site' => 'មន្ទីររពេទ្យបង្អែកខេត្តកំពង់ឆ្នាំង'],
                ['username' => 'art_501','sitecode' => '501','province' => 'Kampong Speu', 'name' => 'Kampong Speu PH', 'site' => 'មន្ទីររពេទ្យបង្អែកខេត្តកំពង់ស្ពឺ'],
                ['username' => 'art_502','sitecode' => '502','province' => 'Kampong Speu', 'name' => 'Ou Dong RH', 'site' => 'មន្ទីរពេទ្យបង្អែកឧដុង្គ'],
                ['username' => 'art_503','sitecode' => '503','province' => 'Kampong Speu', 'name' => 'Kong Pisey RH', 'site' => 'មន្ទីរបង្អែកគងពិសី'],
                ['username' => 'art_601','sitecode' => '601','province' => 'Kampong Thom', 'name' => 'Kampong Thom PH', 'site' => 'មន្ទីរពេទ្យខេត្តកំពង់ធំ'],
                ['username' => 'art_602','sitecode' => '602','province' => 'Kampong Thom', 'name' => 'Baray Santuk RH', 'site' => 'មន្ទីរពេទ្យបង្អែកស្រុកបារាយហ៍-សន្ទុក'],
                ['username' => 'art_701','sitecode' => '701','province' => 'Kampot', 'name' => 'Kampot PH', 'site' => 'មន្ទីរពេទ្យបង្អែកខេត្តកំពត'],
                ['username' => 'art_703','sitecode' => '703','province' => 'Kampot', 'name' => 'Kampong Trach RH', 'site' => 'មន្ទីរពេទ្យបង្អែកកំពង់ត្រាច'],
                ['username' => 'art_801','sitecode' => '801','province' => 'Kandal', 'name' => 'Chey Chum Neah PH', 'site' => 'មន្ទីរពេទ្យបង្អែកជ័យជំនះ'],
                ['username' => 'art_802','sitecode' => '802','province' => 'Kandal', 'name' => 'Koh Thom RH', 'site' => 'មន្ទីរពេទ្យបង្អែកកោះធំ'],
                ['username' => 'art_803','sitecode' => '803','province' => 'Kandal', 'name' => 'Kean Svay RH', 'site' => 'មន្ទីរពេទ្យបង្អែកកៀនស្វាយ'],
                ['username' => 'art_901','sitecode' => '901','province' => 'Koh Kong', 'name' => 'Smach Meanchey PH', 'site' => 'មណ្ឌលសុខភាពស្មាច់មានជ័យ'],
                ['username' => 'art_902','sitecode' => '902','province' => 'Koh Kong', 'name' => 'Srae Ambel RH', 'site' => 'មន្ទីរពេទ្យបង្អែកស្រែអំបិល']
            
        ]
        );

                        
       
        
    }
}
