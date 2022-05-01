<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Group;
use App\Models\Search;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\User;
use PHPUnit\TextUI\XmlConfiguration\Groups;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        #### GLOBAL SEEDS ####
        User::create([
            'alphanumeric_id' => '00000000',
            'pseudonym' => 'Global',
            'email' => 'global@stackmemento.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'image_url' => '654321.png',
            'remember_token' => Str::random(10),
            'deleted_at' => null,
        ]);
        File::makeDirectory('./storage/app/avatars/00000000');
        Thread::factory()->count(20)->forGlobal()->create();

        #### RANDOM USERS SEEDS ####
        User::factory()
            ->count(20)
            ->create();

        Friend::factory()
            ->count(100)
            ->create();

        Thread::factory()
            ->count(40)
            ->create();

        Bookmark::factory()
            ->count(200)
            ->create();

        Comment::factory()
            ->count(300)
            ->create();

        Group::factory()
            ->count(30)
            ->create();

        // Redirections
        for ($i = 0; $i < 50; $i++) {
            DB::table('redirections')->insert([
                'user_id' => rand(1, 20),
                'bookmark_id' => $i + 1,
                'count' => rand(10, 50)
            ]);
        }

        // Votes
        for ($i = 0; $i < 300; $i++) {
            DB::table('votes')->insert([
                'user_id' => rand(1, 20),
                'bookmark_id' => rand(1, 200),
            ]);
        }

        Tag::factory()
            ->count(100)
            ->create();

        Search::factory()
            ->count(100)
            ->create();

        // FriendThreads
        for ($i = 0; $i < 100; $i++) {
            DB::table('friend_thread')->insert([
                'friend_id' => 1,
                'thread_id' => rand(1, 40),
            ]);
        }

        // BookmarkThreads
        for ($i = 0; $i < 100; $i++) {
            DB::table('bookmark_thread')->insert([
                'bookmark_id' => rand(1, 200),
                'thread_id' => rand(1, 40),
            ]);
        }

        // BookmarkTags
        for ($i = 0; $i < 100; $i++) {
            DB::table('bookmark_tag')->insert([
                'bookmark_id' => rand(1, 200),
                'tag_id' => rand(1, 100),
            ]);
        }

        // GroupsUsers
        for ($i = 0; $i < 100; $i++) {
            DB::table('group_user')->insert([
                'group_id' => rand(1, 30),
                'user_id' => rand(1, 20),
                'sponsor_id' => 2,
            ]);
        }

        // GroupsThreads
        for ($i = 0; $i < 100; $i++) {
            DB::table('group_thread')->insert([
                'group_id' => rand(1, 30),
                'thread_id' => rand(1, 40),
            ]);
        }

        // PinnedThreads
        for ($i = 0; $i < 100; $i++) {
            DB::table('pinned_threads')->insert([
                'user_id' => rand(1, 20),
                'thread_id' => rand(1, 40),
            ]);
        }
        
        // RevokedGroupOwners
        for ($i = 0; $i < 20; $i++) {
            DB::table('revoked_group_owners')->insert([
                'user_id' => rand(1, 20),
                'group_id' => rand(1, 30),
            ]);
        }
        
    }
}
