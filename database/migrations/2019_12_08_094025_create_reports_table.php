<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('incident');
            $table->string('status');
            $table->double('longitude');
            $table->double('latitude');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('total_views');
            $table->timestamps();
        });


        # Set default timezone
        date_default_timezone_set('Africa/Lagos');

        # create default data
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.1335', '4.2538',  1,  8167809, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
        DB::insert('INSERT INTO reports (incident, status, longitude, latitude, user_id, total_views, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', ['According to the News Agency of Nigeria (NAN), the mother of three told the court that her husband has destroyed the windows and doors in their house.', 'pending', '8.0794', '4.2231', 1, 5747809,  date('Y-m-d H:i:s'), date('Y-m-d H:i:s')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
