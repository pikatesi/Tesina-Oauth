<?php

use Illuminate\Database\Seeder;

class oidc_data extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_users')->insert(array(
            'username' => "test",
            'password' => "test",
            'first_name' => "Test",
            'last_name' => "User",
        ));

        DB::table('oauth_clients')->insert(array(
            'client_id' => "testclient",
            'client_secret' => "testpass",
            'redirect_uri' => "http://localhost:8000/login/oidc/local/redirect/",
        ));
    }
}
