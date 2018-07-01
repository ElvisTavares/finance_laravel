<?php

function format_money($value){
	$value = round($value, 2);
  return "<font class='".($value<0?'negative':'positive')."'>".number_format($value, 2 , __('config.decimal_point'), __('config.thousand_point'))."</font>";
}

function formatDate($date){
  return date( __('config.date_format'),strtotime($date));
}

function formatDateTime($date){
  return date( __('config.date_format'),strtotime($date));
}

function backButton(){
	return (object)[
		"btnClass" => "secondary",
		"iconClass" => "fa fa-arrow-left",
	];
}

function modeViewButton() {
	return (object)[
		"btnClass" => "secondary",
		"iconClass" => "fas fa-exchange-alt",
	];
}

function addButton(){
	return (object)[
		"btnClass" => "primary",
		"iconClass" => "fa fa-plus",
	];
}