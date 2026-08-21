<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(['email' => 'admin@greenorder.com'], [
            'username'  => 'admin',
            'name'      => 'GreenOrder Admin',
            'email'     => 'admin@greenorder.com',
            'birthdate' => '1990-01-01',
            'mobile'    => '09000000001',
            'role'      => 'admin',
            'password'  => Hash::make('Admin@12345'),
        ]);

        // Customer
        User::updateOrCreate(['email' => 'user0@gmail.com'], [
            'username'  => 'user0',
            'name'      => 'Sample Customer',
            'email'     => 'user0@gmail.com',
            'birthdate' => '1998-06-15',
            'mobile'    => '09111111111',
            'role'      => 'user',
            'password'  => Hash::make('User@12345'),
        ]);

        // Sample products
        $products = [
            ['name'=>'Adobo Rice Meal','description'=>'Classic Filipino pork adobo over steamed rice','price'=>85,'day_availability'=>'common','stock'=>24],
            ['name'=>'Sinigang na Baboy','description'=>'Sour tamarind soup with tender pork ribs','price'=>120,'day_availability'=>'common','stock'=>18],
            ['name'=>'Chicken Inasal','description'=>'Grilled Bacolod-style chicken with garlic rice','price'=>110,'day_availability'=>'monday','stock'=>30],
            ['name'=>'Laing','description'=>'Creamy taro leaves in coconut milk','price'=>75,'day_availability'=>'monday','stock'=>12],
            ['name'=>'Kare-Kare','description'=>'Oxtail stew in peanut sauce with bagoong','price'=>145,'day_availability'=>'tuesday','stock'=>9],
            ['name'=>'Beef Caldereta','description'=>'Tender beef in rich tomato sauce','price'=>130,'day_availability'=>'tuesday','stock'=>14],
            ['name'=>'Lechon Kawali','description'=>'Crispy pan-fried pork belly with liver sauce','price'=>125,'day_availability'=>'wednesday','stock'=>20],
            ['name'=>'Pancit Canton','description'=>'Stir-fried noodles with pork, shrimp and vegetables','price'=>90,'day_availability'=>'wednesday','stock'=>16],
            ['name'=>'Tinolang Manok','description'=>'Ginger chicken soup with green papaya','price'=>95,'day_availability'=>'thursday','stock'=>11],
            ['name'=>'Dinuguan','description'=>'Savory pork blood stew served with puto','price'=>85,'day_availability'=>'thursday','stock'=>8],
            ['name'=>'Pinakbet','description'=>'Vegetable medley with bagoong','price'=>80,'day_availability'=>'friday','stock'=>7],
            ['name'=>'Crispy Pata','description'=>'Deep-fried pork leg, crispy outside tender inside','price'=>180,'day_availability'=>'friday','stock'=>22],
            ['name'=>'Bicol Express','description'=>'Pork with chili peppers in coconut milk','price'=>95,'day_availability'=>'saturday','stock'=>15],
            ['name'=>'Bulalo','description'=>'Bone marrow beef soup with corn and cabbage','price'=>160,'day_availability'=>'saturday','stock'=>13],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
