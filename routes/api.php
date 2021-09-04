<?php

Route::get('/', function(){
    return response()->json(['msg'=>'Hello World'], 200);
});