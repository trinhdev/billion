<?php

use Illuminate\Support\Facades\Route;

Route::get('countries/{code}/states', 'CountryStateController@index')->name('countries.states.index');

Route::get('countries/city', 'CountryStateController@city')->name('countries.states.city');

Route::get('countries/{code}/district', 'CountryStateController@district')->name('countries.states.district');

Route::get('countries/{code}/ward', 'CountryStateController@ward')->name('countries.states.ward');

Route::get('manifest.json', 'ManifestController@json')->name('manifest.json');

Route::get('offline', 'ManifestController@offline')->name('offline');

Route::get('sitemap', 'SitemapController@index')->name('sitemap');
