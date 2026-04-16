<?php
$req = Illuminate\Http\Request::create("/tum-etkinlikler", "GET", ["offset" => 15, "limit" => 15]);
$req->headers->set("X-Requested-With", "XMLHttpRequest");
$res = app()->make("App\Http\Controllers\HomeController")->tumEtkinlikler($req);
echo $res->getContent();

