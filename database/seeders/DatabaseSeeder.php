<?php

namespace Database\Seeders;
use App\Models\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        if ($this->command->confirm('Do you want to refresh the database?')){
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

      $this->call([UsersTableSeeder::class, 
                   BlogPostsTableSeeder::class, 
                   CommentsTableSeeder::class,
                   TagsTableSeeder::class,
                   BlogPostTagTableSeeder::class
                ]);
         

        
       
        
    }
}
