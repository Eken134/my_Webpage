<?php
// GET: returnerar senaste posten som JSON { ts, data }
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

$STORE_FILE = __DIR__ . "/../_data/push.json";

if (!file_exists($STORE_FILE) || filesize($STORE_FILE) === 0) {
  echo json_encode(["ts"=>null, "data"=>null]); exit;
}

$raw = file_get_contents($STORE_FILE);
$j = json_decode($raw, true);
if (!$j) { echo json_encode(["ts"=>null, "data"=>null]); exit; }

echo json_encode([
  "ts"   => $j["ts"]   ?? null,
  "data" => $j["data"] ?? null
], JSON_UNESCAPED_UNICODE);
