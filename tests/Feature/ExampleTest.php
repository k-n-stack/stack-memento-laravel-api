<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;

it('has no welcome page')
    ->get('/')
    ->assertStatus(404);

$payload = [
    "email" => "test@gmail.com",
];

it('cannot login without password', function () use ($payload) {
    $response = $this->postJson('api/login', $payload);
    $response->assertStatus(401);
});

$payload = [
    "password" => "HiDdEn_PaSsWoRd",
];

it('cannot login without email', function () use ($payload) {
    $response = $this->postJson('api/login', $payload);
    $response->assertStatus(401);
});

$payload = [
    "email" => "random_email@gmail.com",
    "password" => "HiDdEn_PaSsWoRd",
];

it('cannot connect with invalid credentials', function () use ($payload) {
    $response = $this->postJson('api/login', $payload);
    $response->assertStatus(401);
});

$payload = [
    "email" => "global@stackmemento.com",
    "password" => "password",
];

it('cannot login with admin credentials', function () use ($payload) {
    $response = $this->postJson('api/login', $payload);
    $response->assertStatus(401);
});

$payload = [
    "email" => "hettie.heidenreich@example.net",
    "password" => "password",
];
$token = "";

it('can login with valid credentials', function () use ($payload, &$token) {
    $response = $this->postJson('api/login', $payload);
    $response->assertStatus(200);
});

$users = DB::table('users')->get();