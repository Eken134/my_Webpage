<?php
// POST: tar emot JSON (kan vara siffra eller objekt) och skriver ÖVER senaste posten
header("Content-Type: application/json; charset=utf-8");

// Läs body
$raw = file_get_contents("php://input");
if ($raw === false || $raw === '') {
  http_response_code(400);
  echo json_encode(["status"=>"error","detail"=>"No body"]); exit;
}

// Dekoda JSON (tillåt även rena tal/bool/string som giltig JSON)
$data = json_decode($raw, true);
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
  http_response_code(400);
  echo json_encode(["status"=>"error","detail"=>"Invalid JSON"]); exit;
}

// Förbered lagring
$STORE_DIR  = __DIR__ . "/../_data";
$STORE_FILE = $STORE_DIR . "/push.json";   // OBS: .json (inte .jsonl)
if (!is_dir($STORE_DIR)) { @mkdir($STORE_DIR, 0755, true); }

// Bygg posten och skriv ÖVER filen (ingen historik)
$entry = [
  "ts"   => time(),
  "user" => $_SERVER['REMOTE_USER'] ?? "unknown",
  "data" => $data
];

$ok = file_put_contents($STORE_FILE, json_encode($entry, JSON_UNESCAPED_UNICODE), LOCK_EX);
if ($ok === false) { http_response_code(500); echo json_encode(["status"=>"error","detail"=>"Write failed"]); exit; }

echo json_encode(["status"=>"ok","received"=>true]);
