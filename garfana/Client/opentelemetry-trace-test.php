<?php
$tempoUrl = 'http://13.126.234.60:4318/v1/traces';

$tracePayload = [
    "resourceSpans" => [
        [
            "resource" => [
                "attributes" => [
                    [
                        "key" => "service.name",
                        "value" => ["stringValue" => "Event-Planner"]
                    ]
                ]
            ],
            "scopeSpans" => [   // Use scopeSpans instead of instrumentationLibrarySpans
                [
                    "spans" => [
                        [
                            "traceId" => bin2hex(random_bytes(16)),
                            "spanId" => bin2hex(random_bytes(8)),
                            "name" => "test-span",
                            "kind" => 1,
                            "startTimeUnixNano" => (int)(microtime(true) * 1e9),
                            "endTimeUnixNano" => (int)(microtime(true) * 1e9) + 50000000, // 50ms
                            "attributes" => [
                                [
                                    "key" => "event",
                                    "value" => ["stringValue" => "Test event from PHP"]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$jsonPayload = json_encode($tracePayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$ch = curl_init($tempoUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo "Trace sent successfully!\n";
} else {
    echo "Failed to send trace. HTTP code: $httpCode\n";
    echo "Response: $response\n";
}


