<?php

namespace Database\Seeders;

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
        User::factory()
            ->count(100)
            ->create();

        Friend::factory()
            ->count(100)
            ->create();

        Thread::factory()
            ->count(100)
            ->create();

        Bookmark::factory()
            ->count(100)
            ->create();

        Comment::factory()
            ->count(200)
            ->create();

        Group::factory()
            ->count(100)
            ->create();

        // Redirections
        for ($i = 0; $i < 100; $i++) {
            DB::table('redirections')->insert([
                'user_id' => rand(1, 99),
                'bookmark_id' => rand(1, 99),
            ]);
        }

        // Votes
        for ($i = 0; $i < 100; $i++) {
            DB::table('votes')->insert([
                'user_id' => rand(1, 99),
                'bookmark_id' => rand(1, 99),
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
                'friend_id' => 100,
                'thread_id' => rand(1, 99),
            ]);
        }

        // BookmarkThreads
        for ($i = 0; $i < 100; $i++) {
            DB::table('bookmark_thread')->insert([
                'bookmark_id' => rand(1, 99),
                'thread_id' => rand(1, 99),
            ]);
        }

        // BookmarkTags
        for ($i = 0; $i < 100; $i++) {
            DB::table('bookmark_tag')->insert([
                'bookmark_id' => rand(1, 99),
                'tag_id' => rand(1, 99),
            ]);
        }

        // GroupsUsers
        for ($i = 0; $i < 100; $i++) {
            DB::table('group_user')->insert([
                'group_id' => rand(1, 99),
                'user_id' => rand(1, 99),
                'sponsor_id' => rand(1, 99),
            ]);
        }

        // GroupsThreads
        for ($i = 0; $i < 100; $i++) {
            DB::table('group_thread')->insert([
                'group_id' => rand(1, 99),
                'thread_id' => rand(1, 99),
            ]);
        }

        // PinnedThreads
        for ($i = 0; $i < 100; $i++) {
            DB::table('pinned_threads')->insert([
                'user_id' => rand(1, 99),
                'thread_id' => rand(1, 99),
            ]);
        }
        
        // RevokedGroupOwners
        for ($i = 0; $i < 20; $i++) {
            DB::table('revoked_group_owners')->insert([
                'user_id' => rand(1, 99),
                'group_id' => rand(1, 99),
            ]);
        }
        
    }
}
