<?php

use App\User;
use App\Profile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fruits = ['apple'=>'100円', 'orange'=>'80円', 'melon'=>'300円',
           'banana'=>'120円', 'pineapple'=>'350円'];
        $delivery_id = 1;
        for ($i = 1; $i <= 30; $i++) {
            $delivery_id++;

            DB::table('users')->insert([
                'id' => sprintf('%026d', $i),
                'delivery_id' => $delivery_id,
                'name' => 'buntsu'.$i,
                'nickname' => 'ブンツウ'.$i,
                'email' => 'buntsu'.$i.'@gmail.com',
                'password' => Hash::make('buntsu'.$i),
                'plan' => 'free',
                'official' => '0',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ]);

            $user = User::where('id', sprintf('%026d', $i))->first();
            $rand_key = array_rand($fruits, 1);

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->gender = mt_rand(1, 2);
            $profile->canSendGender = 9;
            $profile->status = true;
            $profile->profile = 'よろしく'.$rand_key[0];
            $profile->save();
        }
    }
}
