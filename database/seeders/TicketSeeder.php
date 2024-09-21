<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = File::get(base_path('.\database\seeders\ticketsDataToSeed.json'));
        $data = json_decode(json: $contents, associative: true);
        foreach ($data as $key => $value ) {
            Ticket::create([
                'ticket_id' => $value['ticket_id'],
                'title' => $value['title'],
                'description' => $value['description'],
                'status' => $value['status'],
                'priority' => $value['priority'],
                'satisfied' => $value['satisfied'],
                'user_id' => $value['user_id'],
                'assignee_id' => $value['assignee_id'],
                'created_at' => isset($value['created_at']) ? $value['created_at'] :$value['updated_at']  ,
                'updated_at' => $value['updated_at'],
            ]);
        }
    }
}
