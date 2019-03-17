<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Status::class, function (Faker $faker) {
	$date_time = $faker->date.' '.$faker->time;
    return [
        //生成微博数据
    	//运行数据库填充
    	'content' => $faker->text(),
    	'created_at' => $date_time,
    	'updated_at' => $date_time,
    ];
});
