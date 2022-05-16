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

use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{

    
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        $this->feedFriendThread(); die;

        ####################
        ### GLOBAL SEEDS ###
        ####################

        User::create([
            'alphanumeric_id' => '00000000',
            'pseudonym' => 'Global',
            'email' => 'global@stackmemento.com',
            'password' => Hash::make('password'),
            'is_admin' => 1,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'deleted_at' => null,
        ]);

        $globalThreads = [
            "Javascript" => "ffff00",
            "React" => "61dafb", 
            "React Native" => "61dafb", 
            "Electron" => "2f3241", 
            "PHP" => "8892bf", 
            "Laravel" => "eb4432", 
            "Framer Motion" => "8859f5", 
            "Figma" => "ff7262",
        ];

        foreach ($globalThreads as $threadTitle => $color) {
            Thread::factory()
                ->count(1)
                ->forGlobal($threadTitle, $color)
                ->create();
        }

        // Create Global resource folder
        File::makeDirectory('./storage/app/avatars/00000000');



        ##########################
        ### RANDOM USERS SEEDS ###
        ##########################

        User::factory()
        ->count(8)
        ->create();
            
        Thread::factory()
        ->count(30)
        ->create();
        
        Bookmark::factory()
        ->count(60)
        ->create();
        
        Comment::factory()
        ->count(40)
        ->create();
        
        Group::factory()
        ->count(10)
        ->create();

        Tag::factory()
        ->count(30)
        ->create();

        Search::factory()
        ->count(30)
        ->create();
            
        // Redirections
        $this->feedFriends();
        $this->feedRedirections();
        $this->feedVotes();
        $this->feedFriendThread();


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

    public function feedFriends ($number = 20) {
        for ($i = $number; $i >=0; $i--) {
            $rand = $this->getRandomDistinctIds('users', [
                'user', 
                'friend',
            ], 2);
            while (DB::table('friends')
                ->where('user_id', $rand['user'])
                ->where('friend_id', $rand['friend'])
                ->exists()) 
            {
                echo('loop');
                $rand = $this->getRandomDistinctIds('users', [
                    'user',
                    'friend',
                ], 2);
            }
            $faker = Faker::create();
            DB::table('friends')->insert([
                'user_id' => $rand['user'],
                'friend_id' => $rand['friend'],
                'validated_at' => $faker->date(),
            ]);
        }
    }

    public function feedRedirections ($number = 30) {
        for ($i = $number; $i >= 0; $i--) {
            $rand = $this->getRandomDistinctMultipleIds(['users', 'bookmarks'], 2);
            while (DB::table('redirections')
                ->where('user_id', $rand['users'])
                ->where('bookmark_id', $rand['bookmarks'])
                ->exists())
            {
                echo('loop');
                $rand = $this->getRandomDistinctMultipleIds(['users', 'bookmarks'], 2);
            }
            DB::table('redirections')->insert([
                'user_id' => $rand['users'],
                'bookmark_id' => $rand['bookmarks'],
                'count' => rand(10, 30),
            ]);
        }
    }

    public function feedVotes ($number = 30) {
        for ($i = $number; $i >= 0; $i--) {
            $rand = $this->getRandomDistinctMultipleIds(['users', 'bookmarks'], 2);
            while (DB::table('votes')
                ->where('user_id', $rand['users'])
                ->where('bookmark_id', $rand['bookmarks'])
                ->exists())
            {
                echo('loop');
                $rand = $this->getRandomDistinctMultipleIds(['users', 'bookmarks'], 2);
            }
            DB::table('votes')->insert([
                'user_id' => $rand['users'],
                'bookmark_id' => $rand['bookmarks'],
            ]);
        }
    }

    public function feedFriendThread ($number = 20) {
        $friendsIds = DB::table('friends')->get()->map(function ($friend) {
            return array(
                'friend' => $friend->friend_id,
                'user' => $friend->user_id,
            );
        });
        error_log(print_r($friendsIds, 1));

        // for ($i = $number; $i >= 0; $i--) {
        //     DB::
        // }
    }

    public function getRandomDistinctIds ($table, $fields, $start = 1) {
        $randomIds = array_flip($fields);
        $tupleCounts = DB::table($table)->count();
        do {
            $randomIds = array_map(function ($field) use ($tupleCounts, $start) {
                return rand($start, $tupleCounts);
            }, $randomIds);
        } while (count(array_unique($randomIds)) != count($randomIds));

        return $randomIds;

    }

    public function getRandomDistinctMultipleIds ($tables, $start = 1) {
        $tupleCounts = array_flip($tables);
        foreach ($tupleCounts as $table => $count) {
            $tupleCounts[$table] = DB::table($table)->count();
        }
        do {
            $randomIds = array_map(function ($count) use ($start) {
                return rand($start, $count);
            }, $tupleCounts);
        } while (count(array_unique($randomIds)) != count($randomIds));

        return $randomIds;
    }
}
